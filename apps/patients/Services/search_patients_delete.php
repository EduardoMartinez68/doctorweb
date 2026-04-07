<?php
include '../../../middleware/authentication.php'; // 🔥 IMPORTANTE
include '../../../middleware/database.php';
require_once '../../../utils/Encryption.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];

// INPUTS SEGUROS
$page   = max(1, (int)($_GET['page'] ?? 1));
$search = trim($_GET['search'] ?? '');
$status = $_GET['status'] ?? 'inactive';
if (empty($status)) {
    $status = 'inactive';
}

$limit  = 20;
$offset = ($page - 1) * $limit;

// 🔍 QUERY DINÁMICA
$params = [$clinic_id, $status];
$where = "WHERE clinic_id = ? AND status = ?";

if (!empty($search)) {
    $where .= " AND key_id LIKE ?";
    $params[] = "%$search%";
}

// 🔥 SOLO COLUMNAS NECESARIAS
$sql = "
    SELECT id, key_id, name, phone, cellphone, email, status, birth_date
    FROM patients
    $where
    ORDER BY id DESC
    LIMIT $limit OFFSET $offset
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$patients = $stmt->fetchAll();

// COUNT
$countSql = "SELECT COUNT(*) FROM patients $where";
$countStmt = $pdo->prepare($countSql);
$countStmt->execute($params);
$total = (int)$countStmt->fetchColumn();

// 🔐 DESENCRIPTACIÓN SEGURA
function decryptSafe($value) {
    try {
        return $value ? Encryption::decrypt($value) : null;
    } catch (Exception $e) {
        return null; // evita romper la app
    }
}

// 📦 FORMATEAR RESPUESTA
$data = array_map(function($p) {
    return [
        'id'         => $p['id'],
        'key_id'     => $p['key_id'],
        'name'       => decryptSafe($p['name']),
        'phone'      => decryptSafe($p['phone']),
        'cellphone'  => decryptSafe($p['cellphone']),
        'email'      => decryptSafe($p['email']),
        'status'     => $p['status'],
        'birth_date' => decryptSafe($p['birth_date']),
    ];
}, $patients);

// 🚀 RESPUESTA FINAL
echo json_encode([
    'data'        => $data,
    'total'       => $total,
    'page'        => $page,
    'per_page'    => $limit,
    'total_pages' => ceil($total / $limit)
]);