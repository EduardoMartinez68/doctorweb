<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];
$id = (int)($_POST['id'] ?? 0);

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
    exit;
}

// 🔍 Obtener estado actual
$stmt = $pdo->prepare("SELECT status FROM services WHERE id = ? AND clinic_id = ?");
$stmt->execute([$id, $clinic_id]);
$service = $stmt->fetch();

if (!$service) {
    echo json_encode(['success' => false, 'message' => 'Servicio no encontrado']);
    exit;
}

$newStatus = $service['status'] === 'active' ? 'inactive' : 'active';

try {

    $stmt = $pdo->prepare("UPDATE services SET status = ? WHERE id = ? AND clinic_id = ?");
    $stmt->execute([$newStatus, $id, $clinic_id]);

    echo json_encode([
        'success' => true,
        'message' => $newStatus === 'inactive' ? 'Servicio eliminado' : 'Servicio restaurado',
        'status'  => $newStatus
    ]);

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'message' => 'Error al actualizar estado'
    ]);
}