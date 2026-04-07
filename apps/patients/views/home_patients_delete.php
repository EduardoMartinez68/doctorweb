<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Ventas</title>
    <?php
    include '../../../layouts/styles.php';
    ?>
    <style>
        .sidebar {
            background: #fff;
            min-height: 100vh;
            border-right: 1px solid #e2e8f0;
        }

        .patient-card {
            border: none;
            border-radius: 16px;
            transition: transform 0.2s, box-shadow 0.2s;
            background: #fff;
        }

        .patient-card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body>
    <?php
    include '../../../layouts/navbar.php';
    include '../partials/menu.php';
    ?>

    <br>
    <div class="container">
        <dynamic-table 
            link="../../patients/services/search_patients_delete.php"
            columns="Nombre,Email,Teléfono"
            keys="name,email,phone"
            edit="../../patients/views/view_patient.php?id="
            add="../../patients/views/add_patient_flash.php">
        </dynamic-table>
    </div>
    

    <?php
    include '../../../layouts/scripts.php';
    ?>
</body>

</html>