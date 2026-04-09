<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
require_once '../../../utils/Encryption.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];

$id = (int)($_GET['id'] ?? 0);

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID requerido']);
    exit;
}

try {

    // 🔍 RECETA
    $stmt = $pdo->prepare("
        SELECT 
            p.*,
            pa.name as patient_name
        FROM prescriptions p
        INNER JOIN patients pa ON pa.id = p.patient_id
        WHERE p.id = ? AND p.clinic_id = ?
        LIMIT 1
    ");

    $stmt->execute([$id, $clinic_id]);
    $prescription = $stmt->fetch();

    if (!$prescription) {
        echo json_encode(['success' => false, 'message' => 'Receta no encontrada']);
        exit;
    }

    // 🔍 ITEMS
    $stmtItems = $pdo->prepare("
        SELECT *
        FROM prescription_items
        WHERE prescription_id = ?
    ");
    $stmtItems->execute([$id]);
    $items = $stmtItems->fetchAll();

    // 🔐 DESENCRIPTAR
    function decryptSafe($value) {
        try {
            return $value ? Encryption::decrypt($value) : null;
        } catch (Exception $e) {
            return null;
        }
    }

    // 📦 FORMATEAR ITEMS (adaptado a tu frontend)
    $itemsFormatted = array_map(function($i){
        return [
            'medicine_name' => $i['medicine_name'],
            'dosage_qty'    => $i['dosage'],      // adaptamos naming
            'presentation'  => '',                // puedes mejorar esto luego
            'freq_val'      => $i['frequency'],
            'freq_unit'     => '',
            'dur_val'       => $i['duration'],
            'dur_unit'      => '',
            'note'          => $i['instructions']
        ];
    }, $items);

    echo json_encode([
        'success' => true,
        'data' => [
            'id'            => $prescription['id'],
            'patient'       => decryptSafe($prescription['patient_name']),
            'diagnosis'     => $prescription['diagnosis'],
            'instructions'  => $prescription['general_instructions'],
            'date'          => $prescription['issued_date'],
            'status'        => $prescription['status'],
            'items'         => $itemsFormatted
        ]
    ]);

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}