<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];

$id = (int)($_POST['id'] ?? 0);
$action = $_POST['action'] ?? ''; // delete | restore

if (!$id || !in_array($action, ['delete','restore'])) {
    echo json_encode(['success' => false]);
    exit;
}

$status = $action === 'delete' ? 'inactive' : 'active';

$sql = "UPDATE patients SET status = ? WHERE id = ? AND clinic_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$status, $id, $clinic_id]);

echo json_encode([
    'success' => true,
    'status' => $status
]);