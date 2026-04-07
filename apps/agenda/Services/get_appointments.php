<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];

// 🔥 INPUTS
$doctor_id = isset($_GET['doctor_id']) && $_GET['doctor_id'] !== ''
    ? (int)$_GET['doctor_id']
    : null;

$status = isset($_GET['status']) && $_GET['status'] !== ''
    ? $_GET['status']
    : null;

// 🔍 QUERY BASE
$params = [$clinic_id];
$where  = "WHERE a.clinic_id = ?";

// 👨‍⚕️ FILTRO POR DOCTOR
if ($doctor_id) {
    $where .= " AND a.user_id = ?";
    $params[] = $doctor_id;
}

// 📊 FILTRO POR STATUS
if ($status) {
    // 🔥 filtro exacto
    $where .= " AND a.status = ?";
    $params[] = $status;
} else {
    // 🔥 default: solo activas
    $where .= " AND a.status IN ('pending', 'confirmed', 'completed')";
}

// 🔥 QUERY FINAL
$sql = "
    SELECT 
        a.id,
        a.date,
        a.start_time,
        a.end_time,
        a.status,
        a.reason,
        p.name AS patient_name,
        u.name AS doctor_name
    FROM appointments a
    JOIN patients p ON p.id = a.patient_id
    JOIN users u ON u.id = a.user_id
    $where
    ORDER BY a.date ASC, a.start_time ASC
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$appointments = $stmt->fetchAll();

// 📦 FORMATO FULLCALENDAR
$data = array_map(function($a) {
    return [
        'id'    => $a['id'],
        'title' => ($a['reason'] ?: 'Cita') . ' - ' . $a['doctor_name'],
        'start' => $a['date'] . 'T' . $a['start_time'],
        'end'   => $a['date'] . 'T' . $a['end_time'],
        'extendedProps' => [
            'status' => $a['status'],
            'reason' => $a['reason'],
            'patient_name' => $a['patient_name']
        ]
    ];
}, $appointments);

// 🚀 RESPONSE
echo json_encode($data);