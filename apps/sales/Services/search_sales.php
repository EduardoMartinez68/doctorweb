<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];

// 📥 INPUTS
$page           = max(1, (int)($_GET['page'] ?? 1));
$search         = trim($_GET['search'] ?? '');
$title          = trim($_GET['title'] ?? '');
$date_from      = $_GET['date_from'] ?? null;
$date_to        = $_GET['date_to'] ?? null;
$patient_id     = $_GET['patient_id'] ?? null;
$payment_method = $_GET['payment_method'] ?? null;
$status         = $_GET['status'] ?? null;

$limit  = 20;
$offset = ($page - 1) * $limit;

// 🔍 WHERE DINÁMICO
$params = [$clinic_id];
$where  = "WHERE s.clinic_id = ?";

// 🔎 Búsqueda general
if (!empty($search)) {
    $where .= " AND (
        s.id LIKE ? OR 
        s.payment_method LIKE ? OR
        s.title LIKE ?
    )";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// 🏷️ Filtro por título
if (!empty($title)) {
    $where .= " AND s.title LIKE ?";
    $params[] = "%$title%";
}

// 📅 Filtro por rango de fechas
if (!empty($date_from)) {
    $where .= " AND s.sale_date >= ?";
    $params[] = $date_from;
}

if (!empty($date_to)) {
    $where .= " AND s.sale_date <= ?";
    $params[] = $date_to;
}

// 👤 Filtro por paciente
if (!empty($patient_id)) {
    $where .= " AND s.patient_id = ?";
    $params[] = $patient_id;
}

// 💳 Filtro por método de pago
if (!empty($payment_method)) {
    $where .= " AND s.payment_method = ?";
    $params[] = $payment_method;
}

// 📦 Filtro por status
if (!empty($status)) {
    $where .= " AND s.status = ?";
    $params[] = $status;
}

// 📊 QUERY PRINCIPAL
$sql = "
    SELECT 
        s.id,
        s.sale_date,
        s.title,
        s.subtotal,
        s.discount,
        s.total,
        s.payment_method,
        s.status,
        s.created_at,
        
        u.name AS user_name,
        p.name AS patient_name

    FROM sales s
    LEFT JOIN users u ON u.id = s.create_by
    LEFT JOIN patients p ON p.id = s.patient_id
    
    $where
    ORDER BY s.id DESC
    LIMIT $limit OFFSET $offset
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$sales = $stmt->fetchAll();

// 📊 COUNT
$countSql = "
    SELECT COUNT(*) 
    FROM sales s
    $where
";

$countStmt = $pdo->prepare($countSql);
$countStmt->execute($params);
$total = (int)$countStmt->fetchColumn();

// 📦 FORMATEAR
$data = array_map(function($s) {
    return [
        'id'             => $s['id'],
        'sale_date'      => $s['sale_date'],
        'title'          => $s['title'],
        'subtotal'       => (float)$s['subtotal'],
        'discount'       => (float)$s['discount'],
        'total'          => (float)$s['total'],
        'payment_method' => $s['payment_method'],
        'status'         => $s['status'],
        'user_name'      => $s['user_name'],
        'patient_name'   => $s['patient_name'],
        'created_at'     => $s['created_at'],
    ];
}, $sales);

// 🚀 RESPUESTA
echo json_encode([
    'data'        => $data,
    'total'       => $total,
    'page'        => $page,
    'per_page'    => $limit,
    'total_pages' => ceil($total / $limit)
]);