<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];

// 📥 INPUT
$sale_id = (int)($_GET['id'] ?? 0);

if (!$sale_id) {
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
    exit;
}

try {

    // 🧾 OBTENER VENTA
    $stmt = $pdo->prepare("
        SELECT 
            s.*,
            u.name AS user_name,
            p.name AS patient_name

        FROM sales s
        LEFT JOIN users u ON u.id = s.create_by
        LEFT JOIN patients p ON p.id = s.patient_id

        WHERE s.id = ? AND s.clinic_id = ?
        LIMIT 1
    ");

    $stmt->execute([$sale_id, $clinic_id]);
    $sale = $stmt->fetch();

    if (!$sale) {
        echo json_encode(['success' => false, 'message' => 'Venta no encontrada']);
        exit;
    }

    // 📦 OBTENER ITEMS
    $stmtItems = $pdo->prepare("
        SELECT 
            si.id,
            si.service_id,
            si.quantity,
            si.price,
            si.discount,
            si.total,

            sv.name,
            sv.description

        FROM sale_items si
        INNER JOIN services sv ON sv.id = si.service_id
        WHERE si.sale_id = ?
    ");

    $stmtItems->execute([$sale_id]);
    $items = $stmtItems->fetchAll();

    // 📦 FORMATEAR ITEMS
    $itemsFormatted = array_map(function($i) {
        return [
            'id'          => $i['id'],
            'service_id'  => $i['service_id'],
            'name'        => $i['name'],
            'description' => $i['description'],
            'quantity'    => (int)$i['quantity'],
            'price'       => (float)$i['price'],
            'discount'    => (float)$i['discount'],
            'total'       => (float)$i['total'],
        ];
    }, $items);

    // 📦 RESPUESTA FINAL
    echo json_encode([
        'success' => true,
        'sale' => [
            'id'             => $sale['id'],
            'title'      => $sale['title'],
            'sale_date'      => $sale['sale_date'],
            'subtotal'       => (float)$sale['subtotal'],
            'discount'       => (float)$sale['discount'],
            'total'          => (float)$sale['total'],
            'payment_method' => $sale['payment_method'],
            'status'         => $sale['status'],
            'notes'          => $sale['notes'],

            'user_name'      => $sale['user_name'],
            'patient_name'   => $sale['patient_name'],
            'created_at'     => $sale['created_at'],
        ],
        'items' => $itemsFormatted
    ]);

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}