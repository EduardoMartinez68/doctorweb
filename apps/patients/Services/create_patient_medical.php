<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
require_once '../../../utils/Encryption.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];
$input = json_decode(file_get_contents("php://input"), true);

if (!$input) {
    echo json_encode(['success' => false, 'message' => 'JSON inválido']);
    exit;
}

// HELPERS
function enc($v){ return $v ? Encryption::encrypt($v) : null; }

function jsonSafe($v){
    return isset($v) ? json_encode($v) : null;
}

function cleanDate($v){
    return (!empty($v) && $v !== '') ? $v : null;
}

function yesNoToEnum($value){
    return ($value && $value !== '') ? 'Pos' : 'Neg';
}

function mapEnum($value, $allowed){
    $value = strtolower(trim($value));
    return in_array($value, $allowed) ? $value : null;
}

try {

    $pdo->beginTransaction();

    $key_id = uniqid('PAC-');

    // PATIENT
    $stmt = $pdo->prepare("
        INSERT INTO patients (clinic_id, key_id, name, phone, cellphone, email)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $clinic_id,
        $key_id,
        enc($input['name']),
        enc($input['phone']),
        enc($input['cellphone']),
        enc($input['email'])
    ]);

    $patient_id = $pdo->lastInsertId();

    // MEDICAL RECORD
    $stmt = $pdo->prepare("
        INSERT INTO medical_records (
            patient_id, clinic_id,
            company_date, company_name, company_center_type,

            street_address, ext_number, int_number,
            neighborhood, city, state, zip_code,

            marital_status, education_level,

            family_history, children, symptoms,

            note_laboratory, physical_exam_notes,

            personal_chronic, personal_chronic_comment,
            personal_trauma, personal_trauma_comment,
            personal_surgery, personal_surgery_comment,
            personal_allergy, personal_allergy_comment,
            personal_transfusion, personal_transfusion_comment,
            personal_transfusion_date, personal_transfusion_type,

            lifestyle_smoking, lifestyle_alcohol, lifestyle_drugs,
            lifestyle_drugs_type, lifestyle_drugs_frequency,
            lifestyle_activity, lifestyle_diet,

            menarche_age, sexual_onset_age, sexual_partners_count,
            contraceptive_method, last_menstrual_period,
            menstrual_rhythm, pregnancies_count, births_count,
            c_sections_count, abortions_count, living_children_count,
            pap_smear_done, pap_smear_result, ob_gyn_observations,

            exercise_frequency
        )
        VALUES (
            ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?, ?,
            ?, ?,
            ?, ?, ?,
            ?, ?,
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )
    ");

    $stmt->execute([
        $patient_id,
        $clinic_id,

        cleanDate($input['company_date']),
        $input['company_name'],
        $input['company_center_type'],

        enc($input['domicilio']),
        enc($input['num_ext']),
        enc($input['num_int']),
        enc($input['colonia']),
        enc($input['ciudad']),
        enc($input['state']),
        enc($input['zip_code']),

        mapEnum($input['marital_status'], ['soltero','casado','separado','divorciado','union_libre','viudo']),
        mapEnum($input['level_school'], ['primaria','secundaria','bachillerato','tecnica','licenciatura','posgrado']),

        jsonSafe($input['family_history']),
        jsonSafe($input['children_data']),
        jsonSafe($input['symptoms']),

        $input['note_laboratory'],
        $input['physical_exam_notes'],

        yesNoToEnum($input['personal_chronic_comment']),
        $input['personal_chronic_comment'],

        yesNoToEnum($input['personal_trauma_comment']),
        $input['personal_trauma_comment'],

        yesNoToEnum($input['personal_surgery_comment']),
        $input['personal_surgery_comment'],

        yesNoToEnum($input['personal_allergy_comment']),
        $input['personal_allergy_comment'],

        yesNoToEnum($input['personal_transfusion_comment']),
        $input['personal_transfusion_comment'],
        cleanDate($input['personal_transfusion_date']),
        $input['personal_transfusion_type'],

        $input['lifestyle_smoking'],
        $input['lifestyle_alcohol'],
        $input['lifestyle_drugs'],
        $input['lifestyle_drugs_type'],
        $input['lifestyle_drugs_frequency'],
        $input['lifestyle_activity'],
        $input['lifestyle_diet'],

        $input['menarche_age'] ?: null,
        $input['sexual_onset_age'] ?: null,
        $input['sexual_partners_count'] ?: null,
        $input['contraceptive_method'],
        cleanDate($input['last_menstrual_period']),
        $input['menstrual_rhythm'],
        $input['pregnancies_count'] ?: null,
        $input['births_count'] ?: null,
        $input['c_sections_count'] ?: null,
        $input['abortions_count'] ?: null,
        $input['living_children_count'] ?: null,
        $input['pap_smear_done'],
        $input['pap_smear_result'],
        $input['ob_gyn_observations'],

        $input['exercise_frequency']
    ]);

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'patient_id' => $patient_id
    ]);

} catch (Exception $e) {

    $pdo->rollBack();

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}