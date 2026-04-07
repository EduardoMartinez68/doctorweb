<?php
include '../../../middleware/authentication.php'; // 🔥 IMPORTANTE
include '../../../middleware/database.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];

// 🔐 INPUTS SEGUROS
$page   = max(1, (int)($_GET['page'] ?? 1));
$search = trim($_GET['search'] ?? '');
$status = $_GET['status'] ?? 'active'; // por defecto activos

$limit  = 20;
$offset = ($page - 1) * $limit;

// 🔍 QUERY DINÁMICA
$params = [$clinic_id];
$where  = "WHERE clinic_id = ?";

// FILTRO STATUS
if (in_array($status, ['active', 'inactive'])) {
    $where .= " AND status = ?";
    $params[] = $status;
}

// FILTRO BÚSQUEDA
if (!empty($search)) {
    $where .= " AND (name LIKE ? OR description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// 🔥 QUERY PRINCIPAL
$sql = "
    SELECT id, name, description, price, duration_minutes, status, favorite
    FROM services
    $where
    ORDER BY favorite DESC, name ASC
    LIMIT $limit OFFSET $offset
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$services = $stmt->fetchAll();

// 🔢 TOTAL
$countSql = "SELECT COUNT(*) FROM services $where";
$countStmt = $pdo->prepare($countSql);
$countStmt->execute($params);
$total = (int)$countStmt->fetchColumn();


// 📦 FORMATEAR RESPUESTA
$data = array_map(function($s) {
    return [
        'id'               => $s['id'],
        'name'             => $s['name'],
        'description'      => $s['description'],
        'price'            => (float)$s['price'],
        'duration_minutes' => $s['duration_minutes'],
        'status'           => $s['status'],
        'favorite'         => (bool)$s['favorite'],
    ];
}, $services);

// 🚀 RESPUESTA FINAL
echo json_encode([
    'data'        => $data,
    'total'       => $total,
    'page'        => $page,
    'per_page'    => $limit,
    'total_pages' => ceil($total / $limit)
]);