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
    include '../layouts/menu.php';
    ?>
    <div class="container py-5">
        <h2>Formulario Médico</h2>

        <?php
        include '../layouts/medicalRecord/form.php';
        include '../layouts/medicalRecord/data-pacient.php';
        include '../layouts/medicalRecord/son-table.php';
        include '../layouts/medicalRecord/medical-history.php';
        include '../layouts/medicalRecord/family-history.php';
        include '../layouts/medicalRecord/personal-background.php';
        include '../layouts/medicalRecord/gynecological-and-obstetrical.php';
        ?>

        <!-- Botón -->
        <div class="mt-5 d-grid">
            <button class="btn btn-medical shadow-sm">Guardar Registro Clínico</button>
        </div>

    </div>


    <?php
    include '../../../layouts/scripts.php';
    ?>
</body>

</html>