<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];
$user_id   = $_SESSION['user_id'];

$page   = max(1, (int)($_GET['page'] ?? 1));
$search = trim($_GET['search'] ?? '');

$limit  = 20;
$offset = ($page - 1) * $limit;

$params = [$clinic_id, $user_id];
$where  = "WHERE clinic_id = ? AND user_id = ?";

if (!empty($search)) {
    $where .= " AND file_name LIKE ?";
    $params[] = "%$search%";
}

$sql = "
    SELECT id, file_name, mime_type, created_at
    FROM medical_files
    $where
    ORDER BY id DESC
    LIMIT $limit OFFSET $offset
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$files = $stmt->fetchAll();

$countSql = "SELECT COUNT(*) FROM medical_files $where";
$countStmt = $pdo->prepare($countSql);
$countStmt->execute($params);
$total = (int)$countStmt->fetchColumn();

echo json_encode([
    'data'        => $files,
    'total'       => $total,
    'page'        => $page,
    'per_page'    => $limit,
    'total_pages' => ceil($total / $limit)
]);