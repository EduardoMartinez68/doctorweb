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

try {

    // VALIDAR EXISTENCIA
    $stmt = $pdo->prepare("
        SELECT status 
        FROM users 
        WHERE id = ? AND clinic_id = ?
        LIMIT 1
    ");
    $stmt->execute([$id, $clinic_id]);
    $user = $stmt->fetch();

    if (!$user) {
        throw new Exception("Usuario no encontrado");
    }

    if ($user['status'] === 'active') {
        throw new Exception("El usuario ya está activo");
    }

    // ACTIVAR
    $stmt = $pdo->prepare("
        UPDATE users 
        SET status = 'active'
        WHERE id = ? AND clinic_id = ?
    ");

    $stmt->execute([$id, $clinic_id]);

    echo json_encode([
        'success' => true,
        'message' => 'Usuario activado correctamente'
    ]);

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}