<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];

// 📥 INPUTS
$id          = (int)($_POST['id'] ?? 0);
$name        = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$price       = (float)($_POST['price'] ?? 0);
$duration    = (int)($_POST['duration_minutes'] ?? 0);

// 🛑 VALIDACIÓN
if ($id <= 0 || empty($name) || $price <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Datos inválidos'
    ]);
    exit;
}

// 🔍 Verificar estado actual
$stmt = $pdo->prepare("SELECT status FROM services WHERE id = ? AND clinic_id = ?");
$stmt->execute([$id, $clinic_id]);
$service = $stmt->fetch();

if (!$service) {
    echo json_encode([
        'success' => false,
        'message' => 'Servicio no encontrado'
    ]);
    exit;
}

// 🚫 No editar si está inactivo
if ($service['status'] === 'inactive') {
    echo json_encode([
        'success' => false,
        'message' => 'No puedes editar un servicio inactivo'
    ]);
    exit;
}

try {

    $sql = "UPDATE services 
            SET name = ?, description = ?, price = ?, duration_minutes = ?
            WHERE id = ? AND clinic_id = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $name,
        $description,
        $price,
        $duration,
        $id,
        $clinic_id
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Servicio actualizado correctamente'
    ]);

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'message' => 'Error al actualizar'
    ]);
}