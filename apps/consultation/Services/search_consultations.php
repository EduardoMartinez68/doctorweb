<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];

// INPUTS
$page   = max(1, (int)($_GET['page'] ?? 1));
$search = trim($_GET['search'] ?? '');

$limit  = 20;
$offset = ($page - 1) * $limit;

// QUERY
$params = [$clinic_id];
$where  = "WHERE mc.clinic_id = ?";

if (!empty($search)) {
    $where .= " AND p.name LIKE ?";
    $params[] = "%$search%";
}

$sql = "
SELECT 
    mc.id,
    mc.consultation_date,
    mc.status,
    p.name AS patient_name,
    u.name AS doctor_name
FROM medical_consultation mc
LEFT JOIN patients p ON mc.patient_id = p.id
LEFT JOIN users u ON mc.doctor_id = u.id
$where
ORDER BY mc.id DESC
LIMIT $limit OFFSET $offset
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll();

// COUNT
$countSql = "
SELECT COUNT(*) 
FROM medical_consultation mc
LEFT JOIN patients p ON mc.patient_id = p.id
$where
";

$countStmt = $pdo->prepare($countSql);
$countStmt->execute($params);
$total = (int)$countStmt->fetchColumn();

echo json_encode([
    'data'        => $data,
    'total'       => $total,
    'page'        => $page,
    'per_page'    => $limit,
    'total_pages' => ceil($total / $limit)
]);