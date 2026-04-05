<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
require_once '../../../utils/Encryption.php';

// VALIDAR ID
if (!isset($_GET['id'])) {
    die('Paciente no encontrado');
}

$id = (int) $_GET['id'];
$clinic_id = $_SESSION['clinic_id'];

// 🔍 OBTENER PACIENTE
$stmt = $pdo->prepare("
    SELECT id, key_id, name, phone, cellphone, email, birth_date, address, notes, status
    FROM patients
    WHERE id = ? AND clinic_id = ?
    LIMIT 1
");

$stmt->execute([$id, $clinic_id]);
$patient = $stmt->fetch();

if (!$patient) {
    die('Paciente no encontrado');
}

// 🔐 DESENCRIPTAR
function decryptSafe($value) {
    try {
        return $value ? Encryption::decrypt($value) : '';
    } catch (Exception $e) {
        return '';
    }
}

$patient['name']       = decryptSafe($patient['name']);
$patient['phone']      = decryptSafe($patient['phone']);
$patient['cellphone']  = decryptSafe($patient['cellphone']);
$patient['email']      = decryptSafe($patient['email']);
$patient['birth_date'] = decryptSafe($patient['birth_date']);
$patient['address']    = decryptSafe($patient['address']);
$patient['notes']      = decryptSafe($patient['notes']);

// NOTIFICACIONES
$success = $_GET['success'] ?? null;
$error   = $_GET['error'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Paciente</title>
    <?php include '../../../layouts/styles.php'; ?>
</head>
<body>

<?php include '../../../layouts/navbar.php'; ?>

<div class="container mt-5">

    <h3 class="mb-4">Editar Paciente</h3>

    <?php if ($success): ?>
        <div class="alert alert-success">Paciente actualizado correctamente</div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="update.php" method="POST">

        <input type="hidden" name="id" value="<?= $patient['id'] ?>">

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>KEY_ID</label>
                <input type="text" name="key_id" class="form-control"
                       value="<?= htmlspecialchars($patient['key_id']) ?>" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Nombre</label>
                <input type="text" name="name" class="form-control"
                       value="<?= htmlspecialchars($patient['name']) ?>" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Teléfono</label>
                <input type="text" name="phone" class="form-control"
                       value="<?= htmlspecialchars($patient['phone']) ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Celular</label>
                <input type="text" name="cellphone" class="form-control"
                       value="<?= htmlspecialchars($patient['cellphone']) ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control"
                       value="<?= htmlspecialchars($patient['email']) ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Fecha de nacimiento</label>
                <input type="date" name="birth_date" class="form-control"
                       value="<?= htmlspecialchars($patient['birth_date']) ?>">
            </div>

            <div class="col-md-12 mb-3">
                <label>Dirección</label>
                <textarea name="address" class="form-control"><?= htmlspecialchars($patient['address']) ?></textarea>
            </div>

            <div class="col-md-12 mb-3">
                <label>Notas</label>
                <textarea name="notes" class="form-control"><?= htmlspecialchars($patient['notes']) ?></textarea>
            </div>

            <div class="col-md-6 mb-3">
                <label>Estado</label>
                <select name="status" class="form-control">
                    <option value="active" <?= $patient['status'] === 'active' ? 'selected' : '' ?>>Activo</option>
                    <option value="inactive" <?= $patient['status'] === 'inactive' ? 'selected' : '' ?>>Inactivo</option>
                </select>
            </div>
        </div>

        <button class="btn btn-primary">Actualizar Paciente</button>

    </form>

</div>

<?php include '../../../layouts/scripts.php'; ?>

</body>
</html>