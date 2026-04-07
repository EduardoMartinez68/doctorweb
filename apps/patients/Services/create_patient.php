<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
require_once '../../../utils/Encryption.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];

// 📥 INPUTS
$key_id    = trim($_POST['key_id'] ?? '');
$name      = trim($_POST['name'] ?? '');
$phone     = trim($_POST['phone'] ?? '');
$cellphone = trim($_POST['cellphone'] ?? '');
$email     = trim($_POST['email'] ?? '');
$birth_date= $_POST['birth_date'] ?? null;
$address   = trim($_POST['address'] ?? '');
$notes     = trim($_POST['notes'] ?? '');

// 🛑 VALIDACIONES BÁSICAS
if (empty($key_id) || empty($name)) {
    echo json_encode([
        'success' => false,
        'message' => 'Key ID y Nombre son obligatorios'
    ]);
    exit;
}

try {
    // 🔐 ENCRIPTAR
    $name_enc      = Encryption::encrypt($name);
    $phone_enc     = Encryption::encrypt($phone);
    $cellphone_enc = Encryption::encrypt($cellphone);
    $email_enc     = Encryption::encrypt($email);
    $address_enc   = Encryption::encrypt($address);
    $notes_enc     = Encryption::encrypt($notes);

    // 💾 INSERT
    $sql = "INSERT INTO patients 
        (clinic_id, key_id, name, phone, cellphone, email, birth_date, address, notes) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $clinic_id,
        $key_id,
        $name_enc,
        $phone_enc,
        $cellphone_enc,
        $email_enc,
        $birth_date ?: null,
        $address_enc,
        $notes_enc
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Paciente creado correctamente'
    ]);

} catch (PDOException $e) {

    // ⚠️ ERROR POR DUPLICADO
    if ($e->getCode() == 23000) {
        echo json_encode([
            'success' => false,
            'message' => 'El Key ID ya existe'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error al crear paciente'
        ]);
    }
}