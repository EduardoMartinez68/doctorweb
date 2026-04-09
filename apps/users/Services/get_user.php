<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];
$id = (int)($_GET['id'] ?? 0);

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID requerido']);
    exit;
}

$stmt = $pdo->prepare("
    SELECT id, name, email, phone, cellphone, role, status
    FROM users
    WHERE id = ? AND clinic_id = ?
    LIMIT 1
");

$stmt->execute([$id, $clinic_id]);
$user = $stmt->fetch();

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
    exit;
}

echo json_encode([
    'success' => true,
    'data' => $user
]);