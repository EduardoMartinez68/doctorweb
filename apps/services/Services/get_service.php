<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];
$id = (int)($_GET['id'] ?? 0);

if (!$id) {
    echo json_encode(['success' => false]);
    exit;
}

$sql = "SELECT id, name, description, price, duration_minutes, status, favorite
        FROM services
        WHERE id = ? AND clinic_id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$id, $clinic_id]);
$service = $stmt->fetch();

if (!$service) {
    echo json_encode(['success' => false]);
    exit;
}

echo json_encode([
    'success' => true,
    'data' => $service
]);