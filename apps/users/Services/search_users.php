<?php
include '../../../middleware/authentication.php'; // 🔥 IMPORTANTE
include '../../../middleware/database.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];

// 🔥 INPUTS SEGUROS
$page   = max(1, (int)($_GET['page'] ?? 1));
$search = trim($_GET['search'] ?? '');
$role   = $_GET['role'] ?? ''; // opcional (doctor, admin, user)

$limit  = 20;
$offset = ($page - 1) * $limit;

// 🔍 QUERY DINÁMICA
$params = [$clinic_id];
$where  = "WHERE clinic_id = ?";

if (!empty($role)) {
    $where .= " AND role = ?";
    $params[] = $role;
}

if (!empty($search)) {
    $where .= " AND (name LIKE ? OR email LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// 🔥 SOLO COLUMNAS NECESARIAS
$sql = "
    SELECT id, name, email, phone, cellphone, role, created_at
    FROM users
    $where
    ORDER BY id DESC
    LIMIT $limit OFFSET $offset
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$users = $stmt->fetchAll();

// 🔢 COUNT
$countSql = "SELECT COUNT(*) FROM users $where";
$countStmt = $pdo->prepare($countSql);
$countStmt->execute($params);
$total = (int)$countStmt->fetchColumn();

// 📦 FORMATEAR RESPUESTA
$data = array_map(function($u) {
    return [
        'id'         => $u['id'],
        'name'       => $u['name'],
        'email'      => $u['email'],
        'phone'      => $u['phone'],
        'cellphone'  => $u['cellphone'],
        'role'       => $u['role'],
        'created_at' => $u['created_at'],
    ];
}, $users);

// 🚀 RESPUESTA FINAL
echo json_encode([
    'data'        => $data,
    'total'       => $total,
    'page'        => $page,
    'per_page'    => $limit,
    'total_pages' => ceil($total / $limit)
]);