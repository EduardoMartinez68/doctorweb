<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
require_once '../../../utils/Encryption.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];

$id        = (int)($_POST['id'] ?? 0);
$key_id    = trim($_POST['key_id'] ?? '');
$name      = trim($_POST['name'] ?? '');
$phone     = trim($_POST['phone'] ?? '');
$cellphone = trim($_POST['cellphone'] ?? '');
$email     = trim($_POST['email'] ?? '');
$birth_date= $_POST['birth_date'] ?? null;
$address   = trim($_POST['address'] ?? '');
$notes     = trim($_POST['notes'] ?? '');

if (!$id || empty($key_id) || empty($name)) {
    echo json_encode(['success' => false]);
    exit;
}

// 🔐 encrypt
$name_enc      = Encryption::encrypt($name);
$phone_enc     = Encryption::encrypt($phone);
$cellphone_enc = Encryption::encrypt($cellphone);
$email_enc     = Encryption::encrypt($email);
$address_enc   = Encryption::encrypt($address);
$notes_enc     = Encryption::encrypt($notes);

$sql = "UPDATE patients SET 
    key_id = ?, 
    name = ?, 
    phone = ?, 
    cellphone = ?, 
    email = ?, 
    birth_date = ?, 
    address = ?, 
    notes = ?
WHERE id = ? AND clinic_id = ? AND status = 'active'";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $key_id,
    $name_enc,
    $phone_enc,
    $cellphone_enc,
    $email_enc,
    $birth_date ?: null,
    $address_enc,
    $notes_enc,
    $id,
    $clinic_id
]);

echo json_encode([
    'success' => true,
    'message' => 'Paciente actualizado correctamente'
]);