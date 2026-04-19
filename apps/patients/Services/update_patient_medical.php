<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
require_once '../../../utils/Encryption.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];
$input = json_decode(file_get_contents("php://input"), true);

$patient_id = (int)($input['id'] ?? 0);

if (!$patient_id) {
    echo json_encode(['success'=>false, 'message'=>'ID inválido']);
    exit;
}

// HELPERS
function enc($v){ return $v ? Encryption::encrypt($v) : null; }
function jsonSafe($v){ return !empty($v) ? json_encode($v) : null; }

function toNull($v){
    return ($v === '' || $v === null) ? null : $v;
}

function boolValf($v){
    return ($v === 'on' || $v === 1 || $v === true) ? 1 : 0;
}

// ENUM MAPS
function mapMarital($v){
    $map = [
        'soltero'=>'soltero',
        'casado'=>'casado',
        'divorciado'=>'divorciado',
        'viudo'=>'viudo',
        'union_libre'=>'union_libre',
        'separado'=>'separado'
    ];
    return $map[strtolower($v)] ?? null;
}

function mapSchool($v){
    $map = [
        'primaria'=>'primaria',
        'secundaria'=>'secundaria',
        'bachillerato'=>'bachillerato',
        'tecnica'=>'tecnica',
        'licenciatura'=>'licenciatura',
        'posgrado'=>'posgrado'
    ];
    return $map[strtolower($v)] ?? null;
}

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

    // 🏥 UPDATE MEDICAL RECORD COMPLETO
    $stmt = $pdo->prepare("
        UPDATE medical_records SET

            company_date = ?,
            company_name = ?,
            company_center_type = ?,

            street_address = ?,
            ext_number = ?,
            int_number = ?,
            neighborhood = ?,
            city = ?,
            state = ?,
            zip_code = ?,

            marital_status = ?,
            education_level = ?,

            family_history = ?,
            children = ?,
            occupational_history = ?,
            symptoms = ?,

            note_laboratory = ?,
            physical_exam_notes = ?,

            lifestyle_smoking = ?,
            lifestyle_alcohol = ?,
            lifestyle_drugs = ?,
            lifestyle_drugs_type = ?,
            lifestyle_drugs_frequency = ?,
            lifestyle_activity = ?,
            lifestyle_diet = ?,

            menarche_age = ?,
            sexual_onset_age = ?,
            sexual_partners_count = ?,
            contraceptive_method = ?,
            last_menstrual_period = ?,
            menstrual_rhythm = ?,
            pregnancies_count = ?,
            births_count = ?,
            c_sections_count = ?,
            abortions_count = ?,
            living_children_count = ?,
            pap_smear_done = ?,
            pap_smear_result = ?,
            ob_gyn_observations = ?,

            personal_chronic = ?,
            personal_chronic_comment = ?,

            personal_trauma = ?,
            personal_trauma_comment = ?,

            personal_surgery = ?,
            personal_surgery_comment = ?,

            personal_allergy = ?,
            personal_allergy_comment = ?,

            personal_transfusion = ?,
            personal_transfusion_comment = ?,
            personal_transfusion_date = ?,
            personal_transfusion_type = ?,

            does_exercise = ?,
            exercise_type = ?,
            exercise_frequency = ?,
            safety_shoe_impediment = ?,
            dominant_hand = ?

        WHERE patient_id = ? AND clinic_id = ?
    ");

    $stmt->execute([

        toNull($input['company_date']),
        $input['company_name'] ?? null,
        $input['company_center_type'] ?? null,

        enc($input['domicilio']),
        enc($input['num_ext']),
        enc($input['num_int']),
        enc($input['colonia']),
        enc($input['ciudad']),
        enc($input['state']),
        enc($input['zip_code']),

        mapMarital($input['marital_status']),
        mapSchool($input['level_school']),

        jsonSafe($input['family_history']),
        jsonSafe($input['children_data']),
        jsonSafe($input['laboratory_data']),
        jsonSafe($input['symptoms']),

        $input['note_laboratory'] ?? null,
        $input['physical_exam_notes'] ?? null,

        $input['lifestyle_smoking'] ?? 'No',
        $input['lifestyle_alcohol'] ?? 'No',
        $input['lifestyle_drugs'] ?? 'No',
        $input['lifestyle_drugs_type'] ?? null,
        $input['lifestyle_drugs_frequency'] ?? null,
        $input['lifestyle_activity'] ?? 'Nula',
        $input['lifestyle_diet'] ?? 'Balanceada',

        toNull($input['menarche_age']),
        toNull($input['sexual_onset_age']),
        toNull($input['sexual_partners_count']),
        $input['contraceptive_method'] ?? null,
        toNull($input['last_menstrual_period']),
        $input['menstrual_rhythm'] ?? 'Regular',
        toNull($input['pregnancies_count']),
        toNull($input['births_count']),
        toNull($input['c_sections_count']),
        toNull($input['abortions_count']),
        toNull($input['living_children_count']),
        $input['pap_smear_done'] ?? 'No',
        $input['pap_smear_result'] ?? null,
        $input['ob_gyn_observations'] ?? null,

        $input['personal_chronic'] ?? 'Neg',
        $input['personal_chronic_comment'] ?? null,

        $input['personal_trauma'] ?? 'Neg',
        $input['personal_trauma_comment'] ?? null,

        $input['personal_surgery'] ?? 'Neg',
        $input['personal_surgery_comment'] ?? null,

        $input['personal_allergy'] ?? 'Neg',
        $input['personal_allergy_comment'] ?? null,

        $input['personal_transfusion'] ?? 'Neg',
        $input['personal_transfusion_comment'] ?? null,
        toNull($input['personal_transfusion_date']),
        $input['personal_transfusion_type'] ?? null,

        boolValf($input['does_exercise'] ?? null),
        $input['exercise_type'] ?? null,
        $input['exercise_frequency'] ?? null,
        boolValf($input['safety_shoe_impediment'] ?? null),
        $input['dominant_hand'] ?? null,

        $patient_id,
        $clinic_id
    ]);

    $pdo->commit();

    echo json_encode([
        'success'=>true,
        'message'=>'Expediente actualizado correctamente'
    ]);

} catch (Exception $e){

    $pdo->rollBack();

    echo json_encode([
        'success'=>false,
        'message'=>$e->getMessage()
    ]);
}