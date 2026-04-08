<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];
$user_id   = $_SESSION['user_id'];

// 📥 INPUT
$input = json_decode(file_get_contents("php://input"), true);

$items          = $input['items'] ?? [];
$payment_method = $input['payment_method'] ?? 'cash';
$notes          = $input['notes'] ?? null;

if (empty($items)) {
    echo json_encode(['success' => false, 'message' => 'No hay items']);
    exit;
}

try {
    $pdo->beginTransaction();

    $subtotal = 0;

    foreach ($items as $item) {
        $subtotal += $item['total'];
    }

    $discount = 0;
    $total    = $subtotal - $discount;

    // 🧾 INSERT SALE
    $stmt = $pdo->prepare("
        INSERT INTO sales (create_by, clinic_id, sale_date, subtotal, discount, total, payment_method)
        VALUES (?, ?, CURDATE(), ?, ?, ?, ?)
    ");

    $stmt->execute([
        $user_id,
        $clinic_id,
        $subtotal,
        $discount,
        $total,
        $payment_method
    ]);

    $sale_id = $pdo->lastInsertId();

    // 📦 INSERT ITEMS
    $stmtItem = $pdo->prepare("
        INSERT INTO sale_items (sale_id, service_id, quantity, price, discount, total)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    foreach ($items as $item) {
        $stmtItem->execute([
            $sale_id,
            $item['service_id'],
            1,
            $item['price'],
            $item['discount'],
            $item['total']
        ]);
    }

    // 💰 PAYMENT (opcional básico)
    $stmtPayment = $pdo->prepare("
        INSERT INTO payments (sale_id, amount, payment_method)
        VALUES (?, ?, ?)
    ");

    $stmtPayment->execute([
        $sale_id,
        $total,
        $payment_method
    ]);

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'sale_id' => $sale_id
    ]);

} catch (Exception $e) {
    $pdo->rollBack();

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}