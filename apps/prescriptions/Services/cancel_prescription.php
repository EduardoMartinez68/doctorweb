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

    // VALIDAR ESTADO
    $stmt = $pdo->prepare("
        SELECT status 
        FROM prescriptions 
        WHERE id = ? AND clinic_id = ?
        LIMIT 1
    ");
    $stmt->execute([$id, $clinic_id]);
    $prescription = $stmt->fetch();

    if (!$prescription) {
        throw new Exception("Receta no encontrada");
    }

    if ($prescription['status'] !== 'active') {
        throw new Exception("Solo se pueden cancelar recetas activas");
    }

    // ACTUALIZAR
    $stmt = $pdo->prepare("
        UPDATE prescriptions 
        SET status = 'cancelled' 
        WHERE id = ? AND clinic_id = ?
    ");

    $stmt->execute([$id, $clinic_id]);

    echo json_encode([
        'success' => true,
        'message' => 'Receta cancelada correctamente'
    ]);

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}