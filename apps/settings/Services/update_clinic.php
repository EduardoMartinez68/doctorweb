<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];

$name      = $_POST['name'] ?? '';
$phone     = $_POST['phone'] ?? '';
$cellphone = $_POST['cellphone'] ?? '';
$email     = $_POST['email'] ?? '';
$address   = $_POST['address'] ?? '';
$currency  = $_POST['currency'] ?? 'MXN';
$timezone  = $_POST['timezone'] ?? 'America/Mexico_City';

try {

    $pdo->beginTransaction();

    // 🔍 OBTENER LOGO ACTUAL
    $stmt = $pdo->prepare("SELECT logo FROM clinic WHERE id = ?");
    $stmt->execute([$clinic_id]);
    $current = $stmt->fetch();

    $currentLogo = $current['logo'] ?? null;

    // 📂 NUEVO LOGO
    $logoPath = null;

    if (!empty($_FILES['logo']['name'])) {

        $folder = '../../../storage/public/logos/';
        if (!file_exists($folder)) mkdir($folder, 0777, true);

        $fileName = uniqid() . '_' . basename($_FILES['logo']['name']);
        $path = $folder . $fileName;

        // 📥 SUBIR NUEVO
        if (!move_uploaded_file($_FILES['logo']['tmp_name'], $path)) {
            throw new Exception('Error al subir el archivo');
        }

        $logoPath = 'storage/public/logos/' . $fileName;

        // 🗑️ ELIMINAR LOGO ANTERIOR
        if ($currentLogo) {

            $oldPath = '../../../' . $currentLogo;

            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }
    }

    // 🧾 UPDATE
    if ($logoPath) {
        $stmt = $pdo->prepare("
            UPDATE clinic SET
                name = ?, phone = ?, cellphone = ?, email = ?, address = ?,
                currency = ?, timezone = ?, logo = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $name, $phone, $cellphone, $email, $address,
            $currency, $timezone, $logoPath, $clinic_id
        ]);
    } else {
        $stmt = $pdo->prepare("
            UPDATE clinic SET
                name = ?, phone = ?, cellphone = ?, email = ?, address = ?,
                currency = ?, timezone = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $name, $phone, $cellphone, $email, $address,
            $currency, $timezone, $clinic_id
        ]);
    }

    $pdo->commit();

    echo json_encode(['success' => true]);

} catch (Exception $e) {

    $pdo->rollBack();

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}