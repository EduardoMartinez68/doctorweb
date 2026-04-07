<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];

// 🔥 INPUT
$id = (int)($_GET['id'] ?? 0);

if (!$id) {
    echo json_encode([
        "success" => false,
        "message" => "ID inválido"
    ]);
    exit;
}

// 🔍 QUERY
$sql = "
    SELECT 
        a.id,
        a.user_id,
        a.patient_id,
        a.date,
        a.start_time,
        a.end_time,
        a.reason,
        a.notes,
        a.status,
        a.medical_consultation_id,

        p.name AS patient_name,
        u.name AS doctor_name

    FROM appointments a
    JOIN patients p ON p.id = a.patient_id
    JOIN users u ON u.id = a.user_id

    WHERE a.id = ? AND a.clinic_id = ?
    LIMIT 1
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$id, $clinic_id]);

$appointment = $stmt->fetch();

if (!$appointment) {
    echo json_encode([
        "success" => false,
        "message" => "Cita no encontrada"
    ]);
    exit;
}

// 🚀 RESPONSE
echo json_encode([
    "success" => true,
    "data" => $appointment
]);