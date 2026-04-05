<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
require_once '../../../utils/Encryption.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

try {

    if (empty($_POST['id']) || empty($_POST['key_id']) || empty($_POST['name'])) {
        throw new Exception("Datos incompletos");
    }

    $id = (int) $_POST['id'];
    $clinic_id = $_SESSION['clinic_id'];

    // DATOS
    $key_id     = trim($_POST['key_id']);
    $name       = trim($_POST['name']);
    $phone      = $_POST['phone'] ?? null;
    $cellphone  = $_POST['cellphone'] ?? null;
    $email      = $_POST['email'] ?? null;
    $birth_date = $_POST['birth_date'] ?: null;
    $address    = $_POST['address'] ?? null;
    $notes      = $_POST['notes'] ?? null;
    $status     = $_POST['status'] ?? 'active';

    // 🔐 ENCRIPTAR
    $name       = Encryption::encrypt($name);
    $phone      = $phone ? Encryption::encrypt($phone) : null;
    $cellphone  = $cellphone ? Encryption::encrypt($cellphone) : null;
    $email      = $email ? Encryption::encrypt($email) : null;
    $birth_date = $birth_date ? Encryption::encrypt($birth_date) : null;
    $address    = $address ? Encryption::encrypt($address) : null;
    $notes      = $notes ? Encryption::encrypt($notes) : null;

    // UPDATE
    $stmt = $pdo->prepare("
        UPDATE patients SET
            key_id = ?,
            name = ?,
            phone = ?,
            cellphone = ?,
            email = ?,
            birth_date = ?,
            address = ?,
            notes = ?,
            status = ?
        WHERE id = ? AND clinic_id = ?
    ");

    $stmt->execute([
        $key_id,
        $name,
        $phone,
        $cellphone,
        $email,
        $birth_date,
        $address,
        $notes,
        $status,
        $id,
        $clinic_id
    ]);

    header("Location: patient.php?id=$id&success=1");
    exit;

} catch (PDOException $e) {

    if ($e->getCode() == 23000) {
        header("Location: patient.php?id=$id&error=El KEY_ID ya existe");
    } else {
        header("Location: patient.php?id=$id&error=Error en base de datos");
    }
    exit;

} catch (Exception $e) {

    header("Location: patient.php?id=$id&error=" . urlencode($e->getMessage()));
    exit;
}