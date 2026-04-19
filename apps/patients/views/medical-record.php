<?php
include '../../../middleware/authentication.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Médico</title>
    <?php
    include '../../../layouts/styles.php';
    ?>
</head>

<body>
    <?php
    include '../../../layouts/navbar.php';
    include '../partials/menu.php';
    ?>
    <?php
    include '../partials/medicalRecord/form_complete.php';
    ?>
    <!-- Botón -->
     <div class="container">
        <div class="mt-5 d-grid">
            <button class="btn btn-medical shadow-sm" onclick="send_form_medical_record()" type="button">Guardar
                Registro Clínico</button>
        </div>
    </div>

    <?php
    include '../../../layouts/scripts.php';
    ?>
</body>

</html>