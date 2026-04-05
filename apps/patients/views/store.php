<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
require_once '../../../utils/Encryption.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: create.php');
    exit;
}

try {

    // VALIDACIÓN
    if (empty($_POST['key_id']) || empty($_POST['name'])) {
        throw new Exception("KEY_ID y Nombre son obligatorios");
    }

    $clinic_id = $_SESSION['clinic_id'];

    // DATOS (SIN encriptar aún)
    $key_id     = trim($_POST['key_id']);
    $name       = trim($_POST['name']);
    $phone      = $_POST['phone'] ?? null;
    $cellphone  = $_POST['cellphone'] ?? null;
    $email      = $_POST['email'] ?? null;
    $birth_date = $_POST['birth_date'] ?: null;
    $address    = $_POST['address'] ?? null;
    $notes      = $_POST['notes'] ?? null;
    $status     = $_POST['status'] ?? 'active';

    // 🔐 ENCRIPTAR TODO MENOS key_id
    $name       = Encryption::encrypt($name);
    $phone      = $phone ? Encryption::encrypt($phone) : null;
    $cellphone  = $cellphone ? Encryption::encrypt($cellphone) : null;
    $email      = $email ? Encryption::encrypt($email) : null;
    $address    = $address ? Encryption::encrypt($address) : null;
    $notes      = $notes ? Encryption::encrypt($notes) : null;

    // IMPORTANTE: birth_date también debería ir encriptado si sigues tu regla
    $birth_date = $birth_date ? Encryption::encrypt($birth_date) : null;

    // INSERT
    $stmt = $pdo->prepare("
        INSERT INTO patients 
        (clinic_id, key_id, name, phone, cellphone, email, birth_date, address, notes, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $clinic_id,
        $key_id,
        $name,
        $phone,
        $cellphone,
        $email,
        $birth_date,
        $address,
        $notes,
        $status
    ]);

    header("Location: create.php?success=1");
    exit;

} catch (PDOException $e) {

    if ($e->getCode() == 23000) {
        header("Location: create.php?error=El KEY_ID ya existe");
    } else {
        header("Location: create.php?error=Error en la base de datos");
    }
    exit;

} catch (Exception $e) {

    header("Location: create.php?error=" . urlencode($e->getMessage()));
    exit;
}