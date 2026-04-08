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

    $pdo->beginTransaction();

    // 🔍 VALIDAR VENTA
    $stmt = $pdo->prepare("
        SELECT id, status 
        FROM sales 
        WHERE id = ? AND clinic_id = ?
        LIMIT 1
    ");
    $stmt->execute([$sale_id, $clinic_id]);
    $sale = $stmt->fetch();

    if (!$sale) {
        throw new Exception('Venta no encontrada');
    }

    if ($sale['status'] === 'cancelled') {
        throw new Exception('La venta ya está cancelada');
    }

    // ❌ CANCELAR VENTA
    $stmt = $pdo->prepare("
        UPDATE sales 
        SET status = 'cancelled'
        WHERE id = ?
    ");
    $stmt->execute([$sale_id]);

    // 💰 ACTUALIZAR PAGOS
    $stmt = $pdo->prepare("
        UPDATE payments
        SET status = 'failed'
        WHERE sale_id = ?
    ");
    $stmt->execute([$sale_id]);

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Venta cancelada correctamente'
    ]);

} catch (Exception $e) {

    $pdo->rollBack();

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}