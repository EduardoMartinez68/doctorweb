<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];

// 📥 INPUTS
$name        = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$price       = (float)($_POST['price'] ?? 0);
$duration    = (int)($_POST['duration_minutes'] ?? 0);

// 🛑 VALIDACIONES BÁSICAS
if (empty($name) || $price <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Nombre y precio son obligatorios'
    ]);
    exit;
}

try {

    $sql = "INSERT INTO services 
        (clinic_id, name, description, price, duration_minutes) 
        VALUES (?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $clinic_id,
        $name,
        $description,
        $price,
        $duration
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Servicio creado correctamente'
    ]);

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'message' => 'Error al crear servicio'
    ]);

}