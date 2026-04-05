<?php
include '../../../middleware/authentication.php';
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

    <div class="container-fluid">
        <div class="row">
            <main class="col ms-sm-auto px-md-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 fw-bold mb-1">Directorio de Pacientes</h1>
                        <p class="text-muted small">Tienes 24 pacientes programados para esta semana.</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-5">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Buscar Paciente...">
                            <div class="input-group-append">
                                <button class="btn btn-outline-primary btn-search" type="button">Buscar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="patient-card p-4 shadow-sm">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-placeholder me-3 text-primary" style="background: #e0e7ff;">JS
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">Javier Sánchez</h6>
                                        <span class="text-muted small">ID: #4492-A</span>
                                    </div>
                                </div>
                                <span class="badge rounded-pill bg-light text-dark border"><i
                                        class="bi bi-clock me-1"></i> 10:30 AM</span>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">Último Diagnóstico:</small>
                                <span class="fw-medium text-secondary">Hipertensión Grado 1</span>
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-outline-primary btn-sm rounded-pill">Ver Expediente</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="patient-card p-4 shadow-sm">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-placeholder me-3 text-success" style="background: #dcfce7;">ML
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">María López</h6>
                                        <span class="text-muted small">ID: #3120-C</span>
                                    </div>
                                </div>
                                <span class="badge rounded-pill bg-light text-dark border">Mañana</span>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">Último Diagnóstico:</small>
                                <span class="fw-medium text-secondary">Control Prenatal</span>
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-outline-primary btn-sm rounded-pill">Ver Expediente</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="patient-card p-4 shadow-sm border-start border-4 border-warning">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-placeholder me-3 text-warning" style="background: #fef9c3;">RC
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">Ricardo Castillo</h6>
                                        <span class="text-muted small">ID: #8821-K</span>
                                    </div>
                                </div>
                                <span
                                    class="badge rounded-pill bg-warning-subtle text-warning-emphasis border border-warning-subtle">Urgente</span>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">Último Diagnóstico:</small>
                                <span class="fw-medium text-secondary">Traumatismo Leve</span>
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-outline-primary btn-sm rounded-pill">Ver Expediente</button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>





    <?php
    include '../../../layouts/scripts.php';
    ?>
</body>

</html>