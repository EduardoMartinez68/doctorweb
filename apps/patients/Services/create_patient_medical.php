<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
require_once '../../../utils/Encryption.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];

// 📦 INPUT JSON
$input = json_decode(file_get_contents("php://input"), true);

if (!$input) {
    echo json_encode(['success' => false, 'message' => 'JSON inválido']);
    exit;
}

// 🔐 HELPERS
function enc($value){
    return $value ? Encryption::encrypt($value) : null;
}

function jsonSafe($value){
    return !empty($value) ? json_encode($value) : null;
}

function toEnum($value, $map){
    return $map[strtolower($value)] ?? null;
}

// ENUM MAPS
$maritalMap = [
    'soltero'=>'soltero',
    'casado'=>'casado',
    'divorciado'=>'divorciado',
    'viudo'=>'viudo'
];

$schoolMap = [
    'primaria'=>'primaria',
    'secundaria'=>'secundaria',
    'bachillerato'=>'bachillerato',
    'licenciatura'=>'licenciatura'
];

try {

    $pdo->beginTransaction();

    // 🧾 GENERAR KEY_ID
    $key_id = uniqid('PAC-');

    // 🧍 INSERT PATIENT
    $stmt = $pdo->prepare("
        INSERT INTO patients (
            clinic_id, key_id, name, phone, cellphone, email
        ) VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $clinic_id,
        $key_id,
        enc($input['name'] ?? ''),
        enc($input['phone'] ?? ''),
        enc($input['cellphone'] ?? ''),
        enc($input['email'] ?? '')
    ]);

    $patient_id = $pdo->lastInsertId();

    // 🏥 INSERT MEDICAL RECORD
    $stmt = $pdo->prepare("
        INSERT INTO medical_records (
            patient_id,
            clinic_id,
            company_date,
            company_name,
            company_center_type,

            street_address,
            ext_number,
            int_number,
            neighborhood,
            city,
            state,
            zip_code,

            marital_status,
            education_level,

            family_history,
            children,
            symptoms,

            note_laboratory,
            physical_exam_notes,

            lifestyle_smoking,
            lifestyle_alcohol,
            lifestyle_drugs,
            lifestyle_drugs_type,
            lifestyle_drugs_frequency,
            lifestyle_activity,
            lifestyle_diet,

            menarche_age,
            sexual_onset_age,
            sexual_partners_count,
            contraceptive_method,
            last_menstrual_period,
            menstrual_rhythm,
            pregnancies_count,
            births_count,
            c_sections_count,
            abortions_count,
            living_children_count,
            pap_smear_done,
            pap_smear_result,
            ob_gyn_observations
        )
        VALUES (
            ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?, ?,
            ?, ?,
            ?, ?, ?,
            ?, ?,
            ?, ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )
    ");

    $stmt->execute([
        $patient_id,
        $clinic_id,
        $input['company_date'] ?: null,
        $input['company_name'] ?? null,
        $input['company_center_type'] ?? null,

        enc($input['domicilio'] ?? ''),
        enc($input['num_ext'] ?? ''),
        enc($input['num_int'] ?? ''),
        enc($input['colonia'] ?? ''),
        enc($input['ciudad'] ?? ''),
        enc($input['state'] ?? ''),
        enc($input['zip_code'] ?? ''),

        toEnum($input['marital_status'], $maritalMap),
        toEnum($input['level_school'], $schoolMap),

        jsonSafe($input['family_history']),
        jsonSafe($input['children_data']),
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

        $input['menarche_age'] ?: null,
        $input['sexual_onset_age'] ?: null,
        $input['sexual_partners_count'] ?: null,
        $input['contraceptive_method'] ?? null,
        $input['last_menstrual_period'] ?: null,
        $input['menstrual_rhythm'] ?? 'Regular',
        $input['pregnancies_count'] ?: null,
        $input['births_count'] ?: null,
        $input['c_sections_count'] ?: null,
        $input['abortions_count'] ?: null,
        $input['living_children_count'] ?: null,
        $input['pap_smear_done'] ?? 'No',
        $input['pap_smear_result'] ?? null,
        $input['ob_gyn_observations'] ?? null
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
        'message' => 'Error al crear paciente'
    ]);
}