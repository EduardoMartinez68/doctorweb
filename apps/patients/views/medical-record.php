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
    <div class="container py-5">
        <h2>Formulario Médico</h2>

        <form id="form-medical-record">
            <?php
            include '../partials/medicalRecord/form.php';
            include '../partials/medicalRecord/data-pacient.php';
            include '../partials/medicalRecord/son-table.php';
            include '../partials/medicalRecord/medical-history.php';
            include '../partials/medicalRecord/family-history.php';
            include '../partials/medicalRecord/personal-background.php';
            include '../partials/medicalRecord/gynecological-and-obstetrical.php';
            include '../partials/medicalRecord/medical-examination.php';
            ?>

            <!-- Botón -->
            <div class="mt-5 d-grid">
                <button class="btn btn-medical shadow-sm" onclick="send_form_medical_record()" type="button">Guardar Registro Clínico</button>
            </div>
        </form>
    </div>


    <?php
    include '../../../layouts/scripts.php';
    ?>


    <script>
        async function send_form_medical_record(){
            //here we will to get all the information of the form basic
            const form=document.getElementById('form-medical-record');
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            //here we will to get the information of the table of the form for after send to the server
            data.family_history=get_data_table_family();
            data.children_data=get_children_data();
            data.laboratory_data=get_ccupational_data();
            data.symptoms=get_medical_examination_data();

            console.log(JSON.stringify(data, null, 2));
        }


    </script>
</body>

</html>