<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
require_once '../../../utils/Encryption.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];
$input = json_decode(file_get_contents("php://input"), true);

$patient_id = (int)($input['id'] ?? 0);

if (!$patient_id) {
    echo json_encode(['success'=>false]);
    exit;
}

function enc($v){ return $v ? Encryption::encrypt($v) : null; }
function jsonSafe($v){ return !empty($v) ? json_encode($v) : null; }

try {

    $pdo->beginTransaction();

    // 🧍 UPDATE PATIENT
    $stmt = $pdo->prepare("
        UPDATE patients SET
            name = ?,
            phone = ?,
            cellphone = ?,
            email = ?
        WHERE id = ? AND clinic_id = ?
    ");

    $stmt->execute([
        enc($input['name']),
        enc($input['phone']),
        enc($input['cellphone']),
        enc($input['email']),
        $patient_id,
        $clinic_id
    ]);

    // 🏥 UPDATE MEDICAL RECORD
    $stmt = $pdo->prepare("
        UPDATE medical_records SET
            street_address = ?,
            city = ?,
            state = ?,
            zip_code = ?,
            family_history = ?,
            children = ?,
            symptoms = ?,
            physical_exam_notes = ?
        WHERE patient_id = ? AND clinic_id = ?
    ");

    $stmt->execute([
        enc($input['domicilio']),
        enc($input['ciudad']),
        enc($input['state']),
        enc($input['zip_code']),
        jsonSafe($input['family_history']),
        jsonSafe($input['children_data']),
        jsonSafe($input['symptoms']),
        $input['physical_exam_notes'],
        $patient_id,
        $clinic_id
    ]);

    $pdo->commit();

    echo json_encode(['success'=>true]);

} catch (Exception $e){

    $pdo->rollBack();
    echo json_encode(['success'=>false]);
}