<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];
$user_id   = $_SESSION['user_id'];

$file_id = (int)($_GET['id'] ?? 0);

if (!$file_id) {
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
    exit;
}

try {

    $pdo->beginTransaction();

    // 🔍 BUSCAR ARCHIVO
    $stmt = $pdo->prepare("
        SELECT file_path 
        FROM medical_files
        WHERE id = ? AND clinic_id = ? AND user_id = ?
        LIMIT 1
    ");
    $stmt->execute([$file_id, $clinic_id, $user_id]);
    $file = $stmt->fetch();

    if (!$file) {
        throw new Exception('Archivo no encontrado o sin permisos');
    }

    // 📂 ELIMINAR ARCHIVO FÍSICO
    $path = '../../../storage/private/' . $file['file_path'];

    if (file_exists($path)) {
        unlink($path);
    }

    // 🧾 ELIMINAR REGISTRO
    $stmt = $pdo->prepare("
        DELETE FROM medical_files
        WHERE id = ?
    ");
    $stmt->execute([$file_id]);

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Archivo eliminado correctamente'
    ]);

} catch (Exception $e) {

    $pdo->rollBack();

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}