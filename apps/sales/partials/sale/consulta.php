<style>
    :root {
        --med-primary: #004AAD;
        --med-primary-hover: #02377e;
        --text-color-title: #38B6FF;
        --med-bg: #F4F5FF;
        --med-text: #334155;
        --med-border: #e2e8f0;
        --doctor-brand-color: #004AAD;
        --med-primary-online: #004bad9a;
    }

    body {
        background-color: var(--med-bg);
        color: var(--med-text);
        font-family: 'Inter', sans-serif;
    }

    /* Cabecera de Información Básica */
    .header-consultation {
        background: white;
        border-bottom: 2px solid var(--med-border);
        padding: 20px 0;
        margin-bottom: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
    }

    .card-clinical {
        background: white;
        border: 1px solid var(--med-border);
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    }

    /* Estilo de Pestañas (Tabs) */
    .nav-pills .nav-link {
        color: var(--med-text);
        font-weight: 500;
        border-radius: 8px;
        margin-right: 10px;
        padding: 10px 20px;
        transition: all 0.3s ease;
    }

    .nav-pills .nav-link.active {
        background-color: var(--med-primary);
        color: white;
        border: 1px solid var(--med-primary);
    }

    .nav-pills .nav-link:hover:not(.active) {
        background-color: white;
        color: var(--med-primary);
        border: 1px solid var(--med-primary);
    }

    .form-label {
        font-weight: 600;
        font-size: 0.9rem;
        color: #64748b;
    }

    .form-control,
    .form-select {
        border-radius: 8px;
        border: 1px solid var(--med-border);
    }

    .form-control:focus {
        border-color: var(--text-color-title);
        box-shadow: none;
    }

    .section-title {
        color: var(--med-primary);
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
</style>

<main class="container">
    <br>
    <div class="d-flex align-items-center mb-4">
        <div class="rounded-circle me-3" style="width: 15px; height: 15px; background-color: var(--med-primary);">
        </div>
        <h2 class="text-title m-0">Nueva Consulta Médica</h2>
    </div>
    <div class="card card-custom p-4 mb-4">
        <h5 class="text-muted mb-3 fs-6 text-uppercase fw-bold">Información General</h5>
        <div class="row g-3">
            <div class="col-md-2">
                <label class="form-label">Fecha Cita</label>
                <input type="date" class="form-control border-0 bg-light fw-bold">
            </div>
            <div class="col-md-6">
                <label class="form-label">Hora de la cita</label>
                <input type="time" class="form-control border-0 bg-light fw-bold">
            </div>
        </div>
        <br>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold">Médico Tratante</label>
                <dynamic-selector title="Seleccionar Paciente" link="../../patients/services/search_patients.php"
                    columns="ID,Nombre,email,Teléfono" keys="key_id,name,email,cellphone" name="patients_id">
                </dynamic-selector>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Paciente</label>
                <dynamic-selector title="Seleccionar Paciente" link="../../patients/services/search_patients.php"
                    columns="ID,Nombre,email,Teléfono" keys="key_id,name,email,cellphone" name="patients_id">
                </dynamic-selector>
            </div>
        </div>
    </div>

    <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-consultation-tab" data-bs-toggle="pill"
                data-bs-target="#pills-consultation" type="button" role="tab">
                <i class="bi bi-stethoscope me-2"></i>Consulta Médica
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-prescription-tab" data-bs-toggle="pill"
                data-bs-target="#pills-prescription" type="button" role="tab">
                <i class="bi bi-capsule me-2"></i>Receta Médica
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-sales-tab" data-bs-toggle="pill" data-bs-target="#pills-sales"
                type="button" role="tab">
                <i class="bi bi-credit-card me-2"></i>Venta y Cobro
            </button>
        </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">

        <div class="tab-pane fade show active" id="pills-consultation" role="tabpanel">
            <div class="card-clinical">
                <div class="section-title"><i class="bi bi-journal-medical"></i> Nota de Evolución</div>

                <div class="p-4 mb-4">
                    <h5 class="form-label">Signos Vitales</h5>
                    <div class="row g-3">
                        <div class="col-6 col-md-4 col-lg-2">
                            <label class="form-label text-secondary small">Peso (kg)</label>
                            <input type="number" step="0.1" class="form-control" placeholder="0.0">
                        </div>
                        <div class="col-6 col-md-4 col-lg-2">
                            <label class="form-label text-secondary small">Estatura (cm)</label>
                            <input type="number" class="form-control" placeholder="0">
                        </div>
                        <div class="col-6 col-md-4 col-lg-2">
                            <label class="form-label text-secondary small">Temp. (°C)</label>
                            <input type="number" step="0.1" class="form-control" placeholder="36.5">
                        </div>
                        <div class="col-6 col-md-4 col-lg-2">
                            <label class="form-label text-secondary small">Presión Art.</label>
                            <input type="text" class="form-control" placeholder="120/80">
                        </div>
                        <div class="col-6 col-md-4 col-lg-2">
                            <label class="form-label text-secondary small">F. Cardíaca</label>
                            <input type="number" class="form-control" placeholder="LPM">
                        </div>
                        <div class="col-6 col-md-4 col-lg-2">
                            <label class="form-label text-secondary small">SpO2 (%)</label>
                            <input type="number" class="form-control" placeholder="98">
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-12">
                        <label class="form-label">Motivo de Consulta y Síntomas</label>
                        <textarea class="form-control" rows="5"
                            placeholder="Escribe el motivo o síntomas referidos..."></textarea>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Diagnóstico Clínico</label>
                        <textarea class="form-control" rows="3" placeholder="Impresión diagnóstica final..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="pills-prescription" role="tabpanel">
            <div class="card-clinical">
                <div class="section-title"><i class="bi bi-printer"></i> Prescripción (Receta)</div>
                <div class="alert alert-info py-2"
                    style="background-color: var(--med-bg); border-color: var(--text-color-title);">
                    <small class="text-primary"><i class="bi bi-info-circle me-1"></i> Esta información se imprimirá
                        en el formato de receta para el paciente.</small>
                </div>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Indicaciones Terapéuticas</label>
                        <textarea class="form-control" rows="8"
                            placeholder="Ej: 1. Amoxicilina 500mg cada 8 horas..."></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Vigencia de la Receta (Días)</label>
                        <input type="number" class="form-control" value="30">
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="pills-sales" role="tabpanel">
            <div class="card-clinical">
                <div class="section-title"><i class="bi bi-cash-stack"></i> Detalle de Venta</div>
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">Vincular a Cita (ID)</label>
                        <select class="form-select">
                            <option value="001">Cita #001 - 10:00 AM</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Folio de Venta Relacionado</label>
                        <select class="form-select">
                            <option value="v-500">Venta #V-500 (Pendiente de pago)</option>
                            <option value="v-501">Nueva Venta...</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <div class="p-3 border rounded bg-light">
                            <p class="mb-1 fw-bold">Resumen de Cargos:</p>
                            <div class="d-flex justify-content-between">
                                <span>Consulta General</span>
                                <span class="fw-bold">$500.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="d-flex justify-content-end gap-3 mt-5 mb-5">
        <button class="btn btn-outline-secondary px-4">Cancelar</button>
        <button class="btn btn-primary px-5" style="background-color: var(--med-primary); border: none;">
            <i class="bi bi-check2-circle me-2"></i>Guardar Consulta
        </button>
    </div>
</main>