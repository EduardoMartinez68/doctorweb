<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];
$user_id   = $_SESSION['user_id'];

try {

    $pdo->beginTransaction();

    $patient_id = $_POST['patient_id'] ?? null;
    $diagnosis  = $_POST['diagnosis'] ?? null;
    $instructions = $_POST['general_instructions'] ?? null;
    $issued_date = $_POST['issued_date'] ?? date('Y-m-d');

    if (!$patient_id) {
        throw new Exception("Paciente requerido");
    }

    // INSERT PRESCRIPTION
    $stmt = $pdo->prepare("
        INSERT INTO prescriptions 
        (clinic_id, patient_id, create_by, diagnosis, general_instructions, issued_date)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $clinic_id,
        $patient_id,
        $user_id,
        $diagnosis,
        $instructions,
        $issued_date
    ]);

    $prescription_id = $pdo->lastInsertId();

    // ITEMS (medicamentos)
    $items = json_decode($_POST['items'], true);

    if ($items && is_array($items)) {

        $stmtItem = $pdo->prepare("
            INSERT INTO prescription_items 
            (prescription_id, medicine_name, dosage, frequency, duration, instructions)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        foreach ($items as $item) {
            $stmtItem->execute([
                $prescription_id,
                $item['medicine_name'] ?? null,
                $item['dosage'] ?? null,
                $item['frequency'] ?? null,
                $item['duration'] ?? null,
                $item['instructions'] ?? null
            ]);
        }
    }

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Receta creada correctamente'
    ]);

} catch (Exception $e) {

    $pdo->rollBack();

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}