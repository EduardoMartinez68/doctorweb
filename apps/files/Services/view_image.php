<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
require_once '../../../utils/FileEncryption.php';

$clinic_id = $_SESSION['clinic_id'];
$user_id   = $_SESSION['user_id'];

$file_id = (int)($_GET['id'] ?? 0);

// 🔍 VALIDAR
$stmt = $pdo->prepare("
    SELECT * FROM medical_files
    WHERE id = ? AND clinic_id = ? AND user_id = ?
");
$stmt->execute([$file_id, $clinic_id, $user_id]);
$file = $stmt->fetch();

if (!$file) {
    http_response_code(403);
    exit;
}

// ⚠️ SOLO IMÁGENES
if (strpos($file['mime_type'], 'image/') !== 0) {
    http_response_code(400);
    exit;
}

// 📂 LEER ARCHIVO
$path = '../../../storage/private/' . $file['file_path'];
$encrypted = file_get_contents($path);

// 🔓 DESENCRIPTAR
$data = FileEncryption::decrypt($encrypted);

// 📤 OUTPUT
header('Content-Type: ' . $file['mime_type']);
echo $data;