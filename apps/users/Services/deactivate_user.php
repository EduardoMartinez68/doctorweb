<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];

$id = (int)($_POST['id'] ?? 0);

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID requerido']);
    exit;
}

$stmt = $pdo->prepare("
    UPDATE users 
    SET status = 'inactive'
    WHERE id = ? AND clinic_id = ?
");

$stmt->execute([$id, $clinic_id]);

echo json_encode([
    'success' => true,
    'message' => 'Usuario desactivado'
]);