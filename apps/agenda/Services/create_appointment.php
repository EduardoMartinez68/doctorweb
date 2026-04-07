<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';

header('Content-Type: application/json');

try {

    $clinic_id = $_SESSION['clinic_id'];
    $create_by = $_SESSION['user_id'];

    // 🔥 INPUTS
    $patient_id = (int)($_POST['patient_id'] ?? 0);
    $doctor_id  = (int)($_POST['doctor_id'] ?? 0);
    $date       = $_POST['date'] ?? null;
    $start_time = $_POST['start_time'] ?? null;
    $end_time   = $_POST['end_time'] ?? null;
    $reason     = trim($_POST['reason'] ?? '');

    if (!$patient_id || !$doctor_id || !$date || !$start_time || !$end_time) {
        throw new Exception("Datos incompletos");
    }

    // 🔥 TRANSACTION
    $pdo->beginTransaction();

    // 1️⃣ CREAR CONSULTA (draft)
    $sqlConsultation = "
        INSERT INTO medical_consultation 
        (doctor_id, create_by, clinic_id, patient_id, status)
        VALUES (?, ?, ?, ?, 'draft')
    ";

    $stmt = $pdo->prepare($sqlConsultation);
    $stmt->execute([$doctor_id, $create_by, $clinic_id, $patient_id]);

    $medical_consultation_id = $pdo->lastInsertId();

    // 2️⃣ CREAR CITA
    $sqlAppointment = "
        INSERT INTO appointments
        (user_id, create_by, patient_id, clinic_id, service_id, medical_consultation_id, date, start_time, end_time, reason)
        VALUES (?, ?, ?, ?, 1, ?, ?, ?, ?, ?)
    ";

    $stmt = $pdo->prepare($sqlAppointment);
    $stmt->execute([
        $doctor_id,
        $create_by,
        $patient_id,
        $clinic_id,
        $medical_consultation_id,
        $date,
        $start_time,
        $end_time,
        $reason
    ]);

    $pdo->commit();

    echo json_encode([
        "success" => true,
        "message" => "Cita creada correctamente"
    ]);

} catch (Exception $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}