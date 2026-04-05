<?php
include '../../../middleware/authentication.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda</title>
    <?php
    include '../../../layouts/styles.php';
    ?>

    <link rel="stylesheet" href="../public/css/agenda.css?v=1.0.3">
</head>

<body>
    <?php
    include '../../../layouts/navbar.php';
    ?>
    <div class="container">
        <div id="calendar"></div>
    </div>

    <div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Detalle de la Cita</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createEventModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 p-4" style="border-radius: 16px;">
                <div class="modal-header border-0 p-0 mb-4 justify-content-between align-items-start">
                    <div class="w-100">
                        <input type="text" id="newPaciente" class="form-control fw-bold border-0 p-0 fs-3 mb-1"
                            placeholder="Nombre del Paciente" style="color: #1e293b; text-decoration: underline;">
                        <input type="text" id="newTitle" class="form-control border-0 p-0 fs-5 text-muted fw-medium"
                            placeholder="Escribe el motivo de la cita (ej. Revisión General)">
                    </div>
                    <button type="button" class="btn-close ms-2" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-0">
                    <form id="appointmentForm">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="me-3 opacity-50">📱</span>
                                    <div class="flex-grow-1">
                                        <label class="small text-muted mb-1 d-block">Teléfono de contacto</label>
                                        <input type="tel" id="newPhone" class="form-control border-bottom-only"
                                            placeholder="+569 1234 5678">
                                    </div>
                                </div>

                                <div class="d-flex align-items-center mb-3">
                                    <span class="me-3 opacity-50">✉️</span>
                                    <div class="flex-grow-1">
                                        <label class="small text-muted mb-1 d-block">Correo electrónico</label>
                                        <input type="email" id="newEmail" class="form-control border-bottom-only"
                                            placeholder="paciente@email.com">
                                    </div>
                                </div>

                                <div class="d-flex align-items-center mb-3">
                                    <span class="me-3 opacity-50">🩺</span>
                                    <div class="flex-grow-1">
                                        <label class="small text-muted mb-1 d-block">Doctor asignado</label>
                                        <input type="text" id="newDoctor" class="form-control border-bottom-only"
                                            placeholder="Nombre del profesional">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="me-3 opacity-50">📅</span>
                                    <div class="flex-grow-1">
                                        <label class="small text-muted mb-1 d-block">Fecha de la cita</label>
                                        <input type="date" id="newDate" class="form-control border-bottom-only">
                                    </div>
                                </div>

                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="me-3 opacity-50">⏰</span>
                                            <div class="flex-grow-1">
                                                <label class="small text-muted mb-1 d-block">Hora Inicio</label>
                                                <input type="time" id="startTime"
                                                    class="form-control border-bottom-only">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="flex-grow-1">
                                                <label class="small text-muted mb-1 d-block">Hora Fin</label>
                                                <input type="time" id="endTime" class="form-control border-bottom-only">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center mb-3">
                                    <span class="me-3 opacity-50">📌</span>
                                    <div class="flex-grow-1">
                                        <label class="small text-muted mb-1 d-block">Estado de reserva</label>
                                        <select class="form-select border-0 bg-light-subtle small shadow-none">
                                            <option>Reservado</option>
                                            <option>Confirmado</option>
                                            <option>En sala de espera</option>
                                            <option>Atendido</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-4 pt-3 border-top">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <label>Etiqueta:</label>
                                        <br>
                                        <div class="d-flex gap-2">
                                            <div class="color-dot bg-primary active" data-color="#0d6efd"></div>
                                            <div class="color-dot bg-success" data-color="#198754"></div>
                                            <div class="color-dot bg-danger" data-color="#dc3545"></div>
                                            <div class="color-dot bg-warning" data-color="#ffc107"></div>
                                            <div class="color-dot bg-info" data-color="#0dcaf0"></div>
                                            <input type="color" id="colorPicker"
                                                class="form-control-color border-0 p-0 bg-transparent"
                                                style="width: 20px; height: 20px;" style="display: none;">
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-outline-primary rounded-pill px-4">Ver
                                            pago</button>
                                        <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold"
                                            style="background-color: #004AAD; border: none;">Guardar Cita</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <?php
    include '../../../layouts/scripts.php';
    ?>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <script src="../public/js/agenda.js?v=1.0.2" defer></script>
</body>

</html>