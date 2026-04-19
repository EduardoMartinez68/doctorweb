<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];
$id = (int)($_POST['id'] ?? 0);

if (!$id) {
    echo json_encode(['success' => false]);
    exit;
}

$sql = "
UPDATE medical_consultation 
SET status = 'cancelled' 
WHERE id = ? AND clinic_id = ?
";

$stmt = $pdo->prepare($sql);
$success = $stmt->execute([$id, $clinic_id]);

echo json_encode(['success' => $success]);