<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
require_once '../../../utils/Encryption.php';

function decryptSafe($value) {
    try {
        return $value ? Encryption::decrypt($value) : null;
    } catch (Exception $e) {
        return null;
    }
}

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];

// 📅 FECHAS CLAVE
$today = date('Y-m-d');
$startWeek = date('Y-m-d', strtotime('monday this week'));
$endWeek   = date('Y-m-d', strtotime('sunday this week'));

$startMonth = date('Y-m-01');
$endMonth   = date('Y-m-t');

$startLastMonth = date('Y-m-01', strtotime('-1 month'));
$endLastMonth   = date('Y-m-t', strtotime('-1 month'));

// 🔥 FUNCION PARA SUMAR VENTAS
function getTotal($pdo, $clinic_id, $start, $end) {
    $stmt = $pdo->prepare("
        SELECT SUM(total) 
        FROM sales 
        WHERE clinic_id = ? 
        AND status = 'completed'
        AND sale_date BETWEEN ? AND ?
    ");
    $stmt->execute([$clinic_id, $start, $end]);
    return (float) $stmt->fetchColumn();
}

// 📊 RESUMEN
$todaySales     = getTotal($pdo, $clinic_id, $today, $today);
$weekSales      = getTotal($pdo, $clinic_id, $startWeek, $endWeek);
$monthSales     = getTotal($pdo, $clinic_id, $startMonth, $endMonth);
$lastMonthSales = getTotal($pdo, $clinic_id, $startLastMonth, $endLastMonth);

// 📈 CONTEOS
$stmt = $pdo->prepare("
    SELECT 
        COUNT(*) as total_sales,
        SUM(status = 'cancelled') as cancelled_sales
    FROM sales
    WHERE clinic_id = ?
");
$stmt->execute([$clinic_id]);
$counts = $stmt->fetch();

// 💰 DINERO
$stmt = $pdo->prepare("
    SELECT 
        SUM(CASE WHEN status = 'completed' THEN total ELSE 0 END) as income,
        SUM(CASE WHEN status = 'cancelled' THEN total ELSE 0 END) as outcome
    FROM sales
    WHERE clinic_id = ?
    AND sale_date BETWEEN ? AND ?
");
$stmt->execute([$clinic_id, $startMonth, $endMonth]);
$money = $stmt->fetch();

// 📊 GRAFICA: VENTAS POR DIA DEL MES
$stmt = $pdo->prepare("
    SELECT 
        DAY(sale_date) as day,
        SUM(total) as total
    FROM sales
    WHERE clinic_id = ?
    AND status = 'completed'
    AND sale_date BETWEEN ? AND ?
    GROUP BY DAY(sale_date)
    ORDER BY day ASC
");
$stmt->execute([$clinic_id, $startMonth, $endMonth]);

$chartData = [];
foreach ($stmt->fetchAll() as $row) {
    $chartData[] = [
        'day' => (int)$row['day'],
        'total' => (float)$row['total']
    ];
}


// 👥 PACIENTES - RESUMEN
$stmt = $pdo->prepare("
    SELECT 
        COUNT(*) as total_patients,
        SUM(status = 'active') as active_patients,
        SUM(status = 'inactive') as inactive_patients
    FROM patients
    WHERE clinic_id = ?
");
$stmt->execute([$clinic_id]);
$patients = $stmt->fetch();

// 🆕 PACIENTES NUEVOS ESTE MES
$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM patients
    WHERE clinic_id = ?
    AND created_at BETWEEN ? AND ?
");
$stmt->execute([$clinic_id, $startMonth, $endMonth]);
$newPatients = (int)$stmt->fetchColumn();

// 📊 GRAFICA: PACIENTES POR DIA (mes actual)
$stmt = $pdo->prepare("
    SELECT 
        DAY(created_at) as day,
        COUNT(*) as total
    FROM patients
    WHERE clinic_id = ?
    AND created_at BETWEEN ? AND ?
    GROUP BY DAY(created_at)
    ORDER BY day ASC
");
$stmt->execute([$clinic_id, $startMonth, $endMonth]);

$patientsChart = [];
foreach ($stmt->fetchAll() as $row) {
    $patientsChart[] = [
        'day' => (int)$row['day'],
        'total' => (int)$row['total']
    ];
}


// 🥇 TOP SERVICIOS MÁS VENDIDOS
$stmt = $pdo->prepare("
    SELECT 
        si.service_id,
        SUM(si.quantity) as total_sold,
        SUM(si.total) as total_money
    FROM sale_items si
    INNER JOIN sales s ON s.id = si.sale_id
    WHERE s.clinic_id = ?
    AND s.status = 'completed'
    AND s.sale_date BETWEEN ? AND ?
    GROUP BY si.service_id
    ORDER BY total_sold DESC
    LIMIT 5
");
$stmt->execute([$clinic_id, $startMonth, $endMonth]);

$services = $stmt->fetchAll();


$serviceIds = array_column($services, 'service_id');

$names = [];

if (!empty($serviceIds)) {
    $in = str_repeat('?,', count($serviceIds) - 1) . '?';

    $stmt = $pdo->prepare("
        SELECT id, name 
        FROM services 
        WHERE id IN ($in)
    ");
    $stmt->execute($serviceIds);

    foreach ($stmt->fetchAll() as $s) {
        $names[$s['id']] = $s['name'];
    }
}

$servicesData = array_map(function($s) use ($names) {
    return [
        'service_id' => $s['service_id'],
        'name'       => $names[$s['service_id']] ?? 'Servicio #' . $s['service_id'],
        'total_sold' => (int)$s['total_sold'],
        'total'      => (float)$s['total_money']
    ];
}, $services);



// 📅 CITAS - RESUMEN GENERAL
$stmt = $pdo->prepare("
    SELECT 
        COUNT(CASE WHEN date BETWEEN ? AND ? THEN 1 END) as month_total,
        COUNT(CASE WHEN date BETWEEN ? AND ? THEN 1 END) as last_month_total,
        
        SUM(CASE 
            WHEN status = 'cancelled' 
            AND date BETWEEN ? AND ? 
            THEN 1 ELSE 0 
        END) as cancelled_month,

        SUM(CASE 
            WHEN status = 'completed' 
            AND date BETWEEN ? AND ? 
            THEN 1 ELSE 0 
        END) as completed_month

    FROM appointments
    WHERE clinic_id = ?
");
$stmt->execute([
    $startMonth, $endMonth,
    $startLastMonth, $endLastMonth,
    $startMonth, $endMonth,
    $startMonth, $endMonth,
    $clinic_id
]);

$appointments = $stmt->fetch();


$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM appointments
    WHERE clinic_id = ?
    AND date = ?
    AND status != 'cancelled'
");
$stmt->execute([$clinic_id, $today]);

$todayAppointments = (int)$stmt->fetchColumn();


$stmt = $pdo->prepare("
    SELECT 
        a.id,
        a.date,
        a.start_time,
        a.status,
        a.reason,
        p.name as patient_name
    FROM appointments a
    INNER JOIN patients p ON p.id = a.patient_id
    WHERE a.clinic_id = ?
    AND a.date >= ?
    AND a.status != 'cancelled'
    ORDER BY a.date ASC, a.start_time ASC
    LIMIT 5
");
$stmt->execute([$clinic_id, $today]);

$nextAppointments = $stmt->fetchAll();

$nextAppointments = array_map(function($a) {
    return [
        'id' => $a['id'],
        'date' => $a['date'],
        'time' => $a['start_time'],
        'reason' => $a['reason'],
        'status' => $a['status'],
        'patient' => decryptSafe($a['patient_name'])
    ];
}, $nextAppointments);




// 🚀 RESPUESTA FINAL
echo json_encode([
    'patients' => [
        'total'     => (int)$patients['total_patients'],
        'active'    => (int)$patients['active_patients'],
        'inactive'  => (int)$patients['inactive_patients'],
        'new_month' => $newPatients
    ],

    'patients_chart' => $patientsChart,
    'summary' => [
        'today'       => $todaySales,
        'week'        => $weekSales,
        'month'       => $monthSales,
        'last_month'  => $lastMonthSales,
    ],
    'counts' => [
        'total_sales'     => (int)$counts['total_sales'],
        'cancelled_sales' => (int)$counts['cancelled_sales'],
    ],
    'money' => [
        'income'  => (float)$money['income'],
        'outcome' => (float)$money['outcome'],
    ],
    'chart' => $chartData,
    'top_services' => $servicesData,
    'appointments' => [
        'month_total'       => (int)$appointments['month_total'],
        'last_month_total'  => (int)$appointments['last_month_total'],
        'cancelled_month'   => (int)$appointments['cancelled_month'],
        'completed_month'   => (int)$appointments['completed_month'],
        'today'             => $todayAppointments
    ],

    'next_appointments' => $nextAppointments
]);