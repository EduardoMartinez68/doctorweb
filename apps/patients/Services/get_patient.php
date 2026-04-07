<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
require_once '../../../utils/Encryption.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];
$id = (int)($_GET['id'] ?? 0);

if (!$id) {
    echo json_encode(['success' => false]);
    exit;
}

$sql = "SELECT * FROM patients WHERE id = ? AND clinic_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id, $clinic_id]);
$patient = $stmt->fetch();

if (!$patient) {
    echo json_encode(['success' => false]);
    exit;
}

// 🔐 decrypt seguro
function decryptSafe($value) {
    try {
        return $value ? Encryption::decrypt($value) : null;
    } catch (Exception $e) {
        return null;
    }
}

$data = [
    'id'         => $patient['id'],
    'key_id'     => $patient['key_id'],
    'name'       => decryptSafe($patient['name']),
    'phone'      => decryptSafe($patient['phone']),
    'cellphone'  => decryptSafe($patient['cellphone']),
    'email'      => decryptSafe($patient['email']),
    'birth_date' => $patient['birth_date'],
    'address'    => decryptSafe($patient['address']),
    'notes'      => decryptSafe($patient['notes']),
    'status'     => $patient['status']
];

echo json_encode([
    'success' => true,
    'data' => $data
]);