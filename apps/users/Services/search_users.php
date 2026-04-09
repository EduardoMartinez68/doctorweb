<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];

// 🔥 INPUTS SEGUROS
$page   = max(1, (int)($_GET['page'] ?? 1));
$search = trim($_GET['search'] ?? '');

// 🎯 NUEVOS FILTROS
$role   = $_GET['role'] ?? 'doctor';     // 🔥 default doctor
$status = $_GET['status'] ?? 'active';   // 🔥 default active

$limit  = 20;
$offset = ($page - 1) * $limit;

// 🔍 QUERY DINÁMICA
$params = [$clinic_id];
$where  = "WHERE clinic_id = ?";

// 👨‍⚕️ FILTRO POR ROL
if (!empty($role)) {
    $where .= " AND role = ?";
    $params[] = $role;
}

// 🟢 FILTRO POR STATUS
if (!empty($status)) {
    $where .= " AND status = ?";
    $params[] = $status;
}

// 🔍 BUSCADOR
if (!empty($search)) {
    $where .= " AND (name LIKE ? OR email LIKE ? OR phone LIKE ? OR cellphone LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// 🔥 SOLO COLUMNAS NECESARIAS
$sql = "
    SELECT id, name, email, phone, cellphone, role, status, created_at
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
        'status'     => $u['status'], // 🔥 importante para UI
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