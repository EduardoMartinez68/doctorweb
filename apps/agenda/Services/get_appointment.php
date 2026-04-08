<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
// 1. Importar la utilidad de encriptación
require_once '../../../utils/Encryption.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];
$id = (int)($_GET['id'] ?? 0);

if (!$id) {
    echo json_encode([
        "success" => false,
        "message" => "ID inválido"
    ]);
    exit;
}

// 2. Definir la función de ayuda para desencriptar de forma segura
function decryptSafe($value) {
    try {
        return $value ? Encryption::decrypt($value) : null;
    } catch (Exception $e) {
        return "Error al desencriptar"; // O null si prefieres
    }
}

// 🔍 QUERY (Se mantiene igual)
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

// 3. 🔐 PROCESAR LOS DATOS ANTES DEL RESPONSE
// Desencriptamos el nombre del paciente
$appointment['patient_name'] = decryptSafe($appointment['patient_name']);

// Si las notas de la cita también estuvieran encriptadas, podrías hacerlo aquí:
// $appointment['notes'] = decryptSafe($appointment['notes']);

// 🚀 RESPONSE
echo json_encode([
    "success" => true,
    "data" => $appointment
]);