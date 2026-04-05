<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';

$success = $_GET['success'] ?? null;
$error = $_GET['error'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Paciente</title>
    <?php include '../../../layouts/styles.php'; ?>
</head>
<body>

<?php include '../../../layouts/navbar.php'; ?>

<div class="container mt-5 form-container">

    <h3 class="mb-4">Crear Paciente</h3>

    <!-- NOTIFICACIONES -->
    <?php if ($success): ?>
        <div class="alert alert-success">Paciente creado correctamente</div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="store.php" method="POST">

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Identificador (KEY_ID)</label>
                <input type="text" name="key_id" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Nombre</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Teléfono</label>
                <input type="text" name="phone" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Celular</label>
                <input type="text" name="cellphone" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Fecha de nacimiento</label>
                <input type="date" name="birth_date" class="form-control">
            </div>

            <div class="col-md-12 mb-3">
                <label>Dirección</label>
                <textarea name="address" class="form-control"></textarea>
            </div>

            <div class="col-md-12 mb-3">
                <label>Notas</label>
                <textarea name="notes" class="form-control"></textarea>
            </div>

            <div class="col-md-6 mb-3">
                <label>Estado</label>
                <select name="status" class="form-control">
                    <option value="active">Activo</option>
                    <option value="inactive">Inactivo</option>
                </select>
            </div>
        </div>

        <button class="btn btn-primary">Guardar Paciente</button>

    </form>

</div>

<?php include '../../../layouts/scripts.php'; ?>

</body>
</html>