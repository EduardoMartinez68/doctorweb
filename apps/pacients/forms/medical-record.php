<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Médico</title>
    <?php
    include '../../../main/styles.php';
    ?>
</head>

<body>
    <?php
    include '../../../main/navbar.php';
    ?>
    <div class="container py-5">
        <h2>Formulario Médico</h2>
        <div class="form-container">
            <div class="row">
                <div class="col">
                    <div class="section-header">Información de Empresa</div>
                    <div class="mb-4">
                        <label class="form-label">Fecha</label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Tipo de Centro</label>
                            <select class="form-select">
                                <option>Empleado</option>
                                <option>Outsourcing</option>
                                <option>Sindicalizado</option>
                                <option>Paciente</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Empresa</label>
                            <input type="text" class="form-control" placeholder="Nombre de la empresa">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        include '../medicalRecord/data-pacient.php';
        include '../medicalRecord/son-table.php';
        include '../medicalRecord/medical-history.php';
        include '../medicalRecord/family-history.php';
        include '../medicalRecord/personal-background.php';
        include '../medicalRecord/gynecological-and-obstetrical.php';
        ?>

        <!-- Botón -->
        <div class="mt-5 d-grid">
            <button class="btn btn-medical shadow-sm">Guardar Registro Clínico</button>
        </div>

    </div>


    <?php
    include '../../../main/scripts.php';
    ?>
</body>

</html>