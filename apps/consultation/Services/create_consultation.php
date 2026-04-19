<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];
$user_id   = $_SESSION['user_id'];

// INPUT
$patient_id = (int)($_POST['patient_id'] ?? 0);
$doctor_id  = (int)($_POST['doctor_id'] ?? 0);

if (!$patient_id || !$doctor_id) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

try {

    $sql = "
    INSERT INTO medical_consultation (
        doctor_id,
        create_by,
        clinic_id,
        patient_id,
        status
    ) VALUES (?, ?, ?, ?, 'draft')
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $doctor_id,
        $user_id,
        $clinic_id,
        $patient_id
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Consulta creada',
        'id' => $pdo->lastInsertId()
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al crear consulta'
    ]);
}