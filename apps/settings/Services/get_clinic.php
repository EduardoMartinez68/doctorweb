<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];

$stmt = $pdo->prepare("
    SELECT * FROM clinic WHERE id = ?
");
$stmt->execute([$clinic_id]);
$clinic = $stmt->fetch();

echo json_encode([
    'success' => true,
    'data' => $clinic
]);