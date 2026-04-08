<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
require_once '../../../utils/FileEncryption.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];
$user_id   = $_SESSION['user_id'];

if (!isset($_FILES['file'])) {
    echo json_encode(['success' => false, 'message' => 'No file']);
    exit;
}

$file = $_FILES['file'];

if ($file['error'] !== 0) {
    echo json_encode(['success' => false, 'message' => 'Error upload']);
    exit;
}

// 📦 LEER CONTENIDO
$content = file_get_contents($file['tmp_name']);

// 🔐 ENCRIPTAR
$encrypted = FileEncryption::encrypt($content);

// 📁 RUTA SEGURA (FUERA DE PUBLIC)
$folder = '../../../storage/private/';
if (!file_exists($folder)) mkdir($folder, 0777, true);

$fileName = uniqid() . '.bin';
$path = $folder . $fileName;

// 💾 GUARDAR
file_put_contents($path, $encrypted);

// 🧾 DB
$stmt = $pdo->prepare("
    INSERT INTO medical_files (clinic_id, user_id, file_name, file_path, mime_type)
    VALUES (?, ?, ?, ?, ?)
");

$stmt->execute([
    $clinic_id,
    $user_id,
    $file['name'],
    $fileName,
    $file['type']
]);

echo json_encode(['success' => true]);