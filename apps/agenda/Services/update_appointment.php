<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';

header('Content-Type: application/json');

try {

    $clinic_id = $_SESSION['clinic_id'];
    $user_id   = $_SESSION['user_id'];

    // 🔥 INPUTS
    $appointment_id = (int)($_POST['appointment_id'] ?? 0);
    $patient_id     = (int)($_POST['patient_id'] ?? 0);
    $doctor_id      = (int)($_POST['doctor_id'] ?? 0);
    $date           = $_POST['date'] ?? null;
    $start_time     = $_POST['start_time'] ?? null;
    $end_time       = $_POST['end_time'] ?? null;
    $reason         = trim($_POST['reason'] ?? '');
    $notes          = trim($_POST['notes'] ?? '');
    $status         = $_POST['status'] ?? 'pending';

    if (!$appointment_id || !$patient_id || !$doctor_id || !$date || !$start_time || !$end_time) {
        throw new Exception("Datos incompletos");
    }

    // 🔥 TRANSACTION
    $pdo->beginTransaction();

    // 🔍 VALIDAR QUE EXISTE Y PERTENECE A LA CLÍNICA
    $stmt = $pdo->prepare("
        SELECT medical_consultation_id 
        FROM appointments 
        WHERE id = ? AND clinic_id = ?
        LIMIT 1
    ");
    $stmt->execute([$appointment_id, $clinic_id]);
    $appointment = $stmt->fetch();

    if (!$appointment) {
        throw new Exception("Cita no encontrada");
    }

    $medical_consultation_id = $appointment['medical_consultation_id'];

    // 1️⃣ ACTUALIZAR CITA
    $sqlUpdateAppointment = "
        UPDATE appointments SET
            user_id = ?,
            patient_id = ?,
            date = ?,
            start_time = ?,
            end_time = ?,
            reason = ?,
            notes = ?,
            status = ?
        WHERE id = ? AND clinic_id = ?
    ";

    $stmt = $pdo->prepare($sqlUpdateAppointment);
    $stmt->execute([
        $doctor_id,
        $patient_id,
        $date,
        $start_time,
        $end_time,
        $reason,
        $notes,
        $status,
        $appointment_id,
        $clinic_id
    ]);

    // 2️⃣ ACTUALIZAR CONSULTA (sin tocar contenido médico)
    $sqlUpdateConsultation = "
        UPDATE medical_consultation SET
            doctor_id = ?,
            patient_id = ?
        WHERE id = ?
    ";

    $stmt = $pdo->prepare($sqlUpdateConsultation);
    $stmt->execute([
        $doctor_id,
        $patient_id,
        $medical_consultation_id
    ]);

    $pdo->commit();

    echo json_encode([
        "success" => true,
        "message" => "Cita actualizada correctamente"
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