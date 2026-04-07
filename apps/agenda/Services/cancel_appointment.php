<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';

header('Content-Type: application/json');

try {

    $clinic_id = $_SESSION['clinic_id'];

    // 🔥 INPUT
    $appointment_id = (int)($_POST['appointment_id'] ?? 0);

    if (!$appointment_id) {
        throw new Exception("ID inválido");
    }

    // 🔥 TRANSACTION
    $pdo->beginTransaction();

    // 🔍 VALIDAR EXISTENCIA
    $stmt = $pdo->prepare("
        SELECT medical_consultation_id, status
        FROM appointments
        WHERE id = ? AND clinic_id = ?
        LIMIT 1
    ");
    $stmt->execute([$appointment_id, $clinic_id]);
    $appointment = $stmt->fetch();

    if (!$appointment) {
        throw new Exception("Cita no encontrada");
    }

    // 🔒 OPCIONAL: evitar cancelar si ya está completada
    if ($appointment['status'] === 'completed') {
        throw new Exception("No puedes cancelar una cita completada");
    }

    // 1️⃣ CANCELAR CITA
    $stmt = $pdo->prepare("
        UPDATE appointments
        SET status = 'cancelled'
        WHERE id = ? AND clinic_id = ?
    ");
    $stmt->execute([$appointment_id, $clinic_id]);

    // 2️⃣ CANCELAR CONSULTA RELACIONADA
    if (!empty($appointment['medical_consultation_id'])) {
        $stmt = $pdo->prepare("
            UPDATE medical_consultation
            SET status = 'cancelled'
            WHERE id = ?
        ");
        $stmt->execute([$appointment['medical_consultation_id']]);
    }

    $pdo->commit();

    echo json_encode([
        "success" => true,
        "message" => "Cita cancelada correctamente"
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