<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consultas Médicas</title>
    <?php include '../../../layouts/styles.php'; ?>
</head>

<body>

<?php include '../../../layouts/navbar.php'; ?>

<div class="container mt-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Consultas Médicas</h4>
        <button class="btn btn-primary" onclick="openCreateModal()">
            Nueva consulta
        </button>
    </div>

    <!-- TABLA -->
    <dynamic-table 
        link="../../consultations/services/search_consultations.php"
        columns="Paciente,Médico,Fecha,Estado"
        keys="patient_name,doctor_name,consultation_date,status">
    </dynamic-table>

</div>

<!-- MODAL CREAR -->
<div class="modal fade" id="createModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Nueva Consulta</h5>
            </div>

            <div class="modal-body">

                <label>Paciente</label>
                <dynamic-selector
                    id="patient"
                    link="../../patients/services/search_patients.php"
                    columns="Nombre,Email"
                    keys="name,email">
                </dynamic-selector>

                <label class="mt-3">Doctor</label>
                <input type="number" id="doctor_id" class="form-control">

            </div>

            <div class="modal-footer">
                <button class="btn btn-dark" onclick="createConsultation()">
                    Crear
                </button>
            </div>

        </div>
    </div>
</div>

<?php include '../../../layouts/scripts.php'; ?>

<script>
function openCreateModal(){
    window.location.href = "add_consultation.php";
    //new bootstrap.Modal(document.getElementById('createModal')).show();
}

function createConsultation(){

    const patient = document.getElementById('patient').value;
    const doctor  = document.getElementById('doctor_id').value;

    fetch('../../consultations/services/create_consultation.php', {
        method: 'POST',
        body: new URLSearchParams({
            patient_id: patient,
            doctor_id: doctor
        })
    })
    .then(res => res.json())
    .then(res => {

        if(res.success){
            Swal.fire('Éxito', 'Consulta creada', 'success')
            location.reload();
        } else {
            Swal.fire('Error', res.message, 'error')
        }

    });

}
</script>

</body>
</html>