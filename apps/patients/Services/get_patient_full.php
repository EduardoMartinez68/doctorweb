<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
require_once '../../../utils/Encryption.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];
$id = (int)($_GET['id'] ?? 0);

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
    exit;
}

// 🔐 HELPERS
function decryptSafe($value) {
    try {
        return $value ? Encryption::decrypt($value) : null;
    } catch (Exception $e) {
        return null;
    }
}

function jsonSafeDecode($value){
    return $value ? json_decode($value, true) : [];
}

try {

    // 🧍 PATIENT
    $stmt = $pdo->prepare("
        SELECT id, key_id, name, phone, cellphone, email, birth_date, status
        FROM patients
        WHERE id = ? AND clinic_id = ?
        LIMIT 1
    ");

    $stmt->execute([$id, $clinic_id]);
    $patient = $stmt->fetch();

    if (!$patient) {
        echo json_encode(['success' => false, 'message' => 'Paciente no encontrado']);
        exit;
    }

    // 🏥 MEDICAL RECORD
    $stmt = $pdo->prepare("
        SELECT *
        FROM medical_records
        WHERE patient_id = ? AND clinic_id = ?
        LIMIT 1
    ");

    $stmt->execute([$id, $clinic_id]);
    $record = $stmt->fetch();

    // 🧠 SI NO EXISTE, CREAR EXPEDIENTE AUTOMÁTICAMENTE
    if (!$record) {

        $insert = $pdo->prepare("
            INSERT INTO medical_records (
                patient_id,
                clinic_id,
                marital_status,
                education_level,
                lifestyle_smoking,
                lifestyle_alcohol,
                lifestyle_drugs,
                lifestyle_activity,
                lifestyle_diet,
                menstrual_rhythm,
                pap_smear_done
            ) VALUES (?, ?, 'soltero', 'primaria', 'No', 'No', 'No', 'Nula', 'Balanceada', 'Regular', 'No')
        ");

        $insert->execute([$id, $clinic_id]);

        // 🔁 VOLVER A CONSULTAR
        $stmt = $pdo->prepare("
            SELECT *
            FROM medical_records
            WHERE patient_id = ? AND clinic_id = ?
            LIMIT 1
        ");

        $stmt->execute([$id, $clinic_id]);
        $record = $stmt->fetch();
    }

    // 🧠 FORMATEAR RESPUESTA
    $response = [
        'id'        => $patient['id'],
        'key_id'    => $patient['key_id'],
        'name'      => decryptSafe($patient['name']),
        'phone'     => decryptSafe($patient['phone']),
        'cellphone' => decryptSafe($patient['cellphone']),
        'email'     => decryptSafe($patient['email']),
        'birth_date'=> $patient['birth_date'],
        'status'    => $patient['status'],

        // 📂 RECORD
        'record' => $record ? [

            // COMPANY
            'company_date' => $record['company_date'],
            'company_name' => $record['company_name'],
            'company_center_type' => $record['company_center_type'],

            // ADDRESS
            'domicilio' => decryptSafe($record['street_address']),
            'colonia'   => decryptSafe($record['neighborhood']),
            'ciudad'    => decryptSafe($record['city']),
            'state'     => decryptSafe($record['state']),
            'num_ext'   => decryptSafe($record['ext_number']),
            'num_int'   => decryptSafe($record['int_number']),    
            'zip_code'  => decryptSafe($record['zip_code']),

            // PERSONAL
            'marital_status' => $record['marital_status'],
            'level_school'   => $record['education_level'],

            // JSON
            'family_history' => jsonSafeDecode($record['family_history']),
            'children_data'  => jsonSafeDecode($record['children']),
            'symptoms'       => jsonSafeDecode($record['symptoms']),

            // NOTES
            'note_laboratory'    => $record['note_laboratory'],
            'physical_exam_notes'=> $record['physical_exam_notes'],

            // LIFESTYLE
            'lifestyle_smoking'   => $record['lifestyle_smoking'],
            'lifestyle_alcohol'   => $record['lifestyle_alcohol'],
            'lifestyle_drugs'     => $record['lifestyle_drugs'],
            'lifestyle_drugs_type'=> $record['lifestyle_drugs_type'],
            'lifestyle_drugs_frequency'=> $record['lifestyle_drugs_frequency'],
            'lifestyle_activity'  => $record['lifestyle_activity'],
            'lifestyle_diet'      => $record['lifestyle_diet'],

            // 🧬 ANTECEDENTES PERSONALES
            'personal_chronic' => $record['personal_chronic'],
            'personal_chronic_comment' => $record['personal_chronic_comment'],

            'personal_trauma' => $record['personal_trauma'],
            'personal_trauma_comment' => $record['personal_trauma_comment'],

            'personal_surgery' => $record['personal_surgery'],
            'personal_surgery_comment' => $record['personal_surgery_comment'],

            'personal_allergy' => $record['personal_allergy'],
            'personal_allergy_comment' => $record['personal_allergy_comment'],

            'personal_transfusion' => $record['personal_transfusion'],
            'personal_transfusion_comment' => $record['personal_transfusion_comment'],
            'personal_transfusion_date' => $record['personal_transfusion_date'],
            'personal_transfusion_type' => $record['personal_transfusion_type'],

            // GINECO
            'menarche_age' => $record['menarche_age'],
            'sexual_onset_age' => $record['sexual_onset_age'],
            'sexual_partners_count' => $record['sexual_partners_count'],
            'contraceptive_method' => $record['contraceptive_method'],
            'last_menstrual_period' => $record['last_menstrual_period'],
            'menstrual_rhythm' => $record['menstrual_rhythm'],
            'pregnancies_count' => $record['pregnancies_count'],
            'births_count' => $record['births_count'],
            'c_sections_count' => $record['c_sections_count'],
            'abortions_count' => $record['abortions_count'],
            'living_children_count' => $record['living_children_count'],
            'pap_smear_done' => $record['pap_smear_done'],
            'pap_smear_result' => $record['pap_smear_result'],
            'ob_gyn_observations' => $record['ob_gyn_observations'],

            // 🏃 ACTIVIDAD FÍSICA
            'does_exercise' => (bool)$record['does_exercise'],
            'exercise_type' => $record['exercise_type'],
            'exercise_frequency' => $record['exercise_frequency'],
            'safety_shoe_impediment' => (bool)$record['safety_shoe_impediment'],
            'dominant_hand' => $record['dominant_hand'],

            'laboratory_data' => jsonSafeDecode($record['occupational_history'])

        ] : null
    ];

    echo json_encode([
        'success' => true,
        'data' => $response
    ]);

} catch (Exception $e) {
echo json_encode([
    'success' => false,
    'message' => $e->getMessage()
]);
}