<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
require_once '../../../utils/Encryption.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];

// INPUTS SEGUROS
$page   = max(1, (int)($_GET['page'] ?? 1));
$search = trim($_GET['search'] ?? '');

$limit  = 20;
$offset = ($page - 1) * $limit;

// 🔍 QUERY BASE
$params = [$clinic_id];
$where  = "WHERE p.clinic_id = ?";

// ⚠️ IMPORTANTE: no buscamos por name (está encriptado)
if (!empty($search)) {
    $where .= " AND (p.id LIKE ? OR p.diagnosis LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// 🔥 SOLO COLUMNAS NECESARIAS
$sql = "
SELECT 
    p.id,
    p.diagnosis,
    p.issued_date,
    p.status,
    pa.name as patient_name
FROM prescriptions p
INNER JOIN patients pa ON pa.id = p.patient_id
$where
ORDER BY p.id DESC
LIMIT $limit OFFSET $offset
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();

// COUNT
$countSql = "
SELECT COUNT(*) 
FROM prescriptions p
INNER JOIN patients pa ON pa.id = p.patient_id
$where
";
$countStmt = $pdo->prepare($countSql);
$countStmt->execute($params);
$total = (int)$countStmt->fetchColumn();

// 🔐 DESENCRIPTACIÓN SEGURA
function decryptSafe($value) {
    try {
        return $value ? Encryption::decrypt($value) : null;
    } catch (Exception $e) {
        return null;
    }
}

// 📦 FORMATEAR RESPUESTA
$data = array_map(function($r) {
    return [
        'id'            => $r['id'],
        'diagnosis'     => $r['diagnosis'],
        'issued_date'   => $r['issued_date'],
        'status'        => $r['status'],
        'patient_name'  => decryptSafe($r['patient_name']),
    ];
}, $rows);

// 🚀 RESPUESTA FINAL
echo json_encode([
    'data'        => $data,
    'total'       => $total,
    'page'        => $page,
    'per_page'    => $limit,
    'total_pages' => ceil($total / $limit)
]);