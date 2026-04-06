<style>
    /* Estilo de Barra Superior Odoo */
    .odoo-header {
        background-color: #ffffff;
        border-bottom: 1px solid #dee2e6;
        padding: 10px 20px;
        margin-bottom: 20px;
    }

    .breadcrumb-item+.breadcrumb-item::before {
        content: "/";
    }

    /* Tarjeta tipo "Hoja de Papel" */
    .odoo-sheet {
        background: #ffffff;
        border: 1px solid #d9d9d9;
        border-radius: 2px;
        padding: 40px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        margin: 0 auto 40px auto;
        height: 100vh;
    }

    .odoo-status-bar {
        border-bottom: 1px solid #dee2e6;
        margin: -40px -40px 30px -40px;
        padding: 10px 40px;
        background: #f8f9fa;
        display: flex;
        justify-content: flex-end;
        gap: 5px;
    }

    .status-badge {
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        background: #e2e7f0;
    }

    /* Inputs minimalistas */
/* Estilo Base: Minimalismo total */
.form-control-plus,
.form-select-plus {
    border: none;
    border-bottom: 1px solid #e2e2e2; /* Línea muy suave por defecto */
    border-radius: 0;
    padding: 0.375rem 0; /* Ajuste de padding para alinear el texto a la izquierda */
    background-color: transparent;
    transition: border-color 0.2s ease-in-out, background-color 0.2s ease-in-out;
}

/* Efecto Hover: Indica que el campo es editable */
.form-control-plus:hover,
.form-select-plus:hover {
    background-color: #f8f9fa; /* Un toque grisáceo muy leve */
    border-bottom: 1px solid #666; /* La línea se oscurece un poco */
}

/* Efecto Focus (Edición activa): La línea "cobra vida" */
.form-control-plus:focus,
.form-select-plus:focus {
    outline: none;
    box-shadow: none;
    background-color: transparent;
    border-bottom: 2px solid var(--med-primary); /* Línea más gruesa y con tu color corporativo */
    margin-bottom: -1px; /* Evita que el layout "salte" por el cambio de 1px a 2px */
}

/* Ajuste para Selects: Quitar la flecha azul de Windows/Chrome si es necesario */
.form-select-plus {
    background-position: right 0 center;
    padding-right: 1.5rem;
}

    .odoo-label {
        font-weight: 600;
        color: #000;
        font-size: 0.9rem;
    }

    /* Tabla de items */
    .table thead th {
        border-top: none;
        text-transform: uppercase;
        font-size: 0.75rem;
        color: #666;
        letter-spacing: 1px;
    }

    .btn-odoo-primary {
        background-color: var(--med-primary);
        border-color: var(--med-primary);
        color: white;
    }

    .btn-odoo-primary:hover {
        background-color: var(--med-primary-hover);
        color: white;
    }

    #signature-canvas {
        border: 1px dashed #ccc;
        background: #fcfcfc;
    }
</style>

<div class="">
    <div class="odoo-sheet">
        <div class="odoo-status-bar">
            <span class="status-badge text-primary" style="background: #e0e7ff;">COTIZACIÓN</span>
            <span class="status-badge">ORDEN DE VENTA</span>
            <span class="status-badge">PAGADO</span>
        </div>

        <div class="row mb-5">
            <div class="col-md-6">
                <h1 class="h3 mb-4">Nueva Venta</h1>

                <div class="mb-3 row">
                    <div class="col">
                        <label class="fw-bold text-secondary">Paciente seleccionado:</label>
                        <div class="">
                            <patient-selector name="patients_id"></patient-selector>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 border-start ps-4">
                <div class="row mb-3">
                    <label class="col-sm-4 odoo-label">Fecha de Venta</label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control" value="2024-05-22">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 odoo-label">Clínica</label>
                    <div class="col-sm-8">
                        <select class="form-select">
                            <option>Sede Central</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active text-dark fw-bold" data-bs-toggle="tab" data-bs-target="#lineas">Líneas
                    de la orden</button>
            </li>
            <li class="nav-item">
                <button class="nav-link text-muted" data-bs-toggle="tab" data-bs-target="#pagos">Información de
                    Pago</button>
            </li>
            <li class="nav-item">
                <button class="nav-link text-muted" data-bs-toggle="tab" data-bs-target="#receta">Receta</button>
            </li>
            <li class="nav-item">
                <button class="nav-link text-muted" data-bs-toggle="tab" data-bs-target="#notas">Notas</button>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="lineas">
                <?php
                include '../partials/sale/sale.php';
                ?>
            </div>

            <div class="tab-pane fade" id="pagos">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="odoo-label mb-2">Método de Pago</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="pay" id="cash" checked>
                                    <label class="form-check-label" for="cash">Efectivo</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="pay" id="card">
                                    <label class="form-check-label" for="card">Tarjeta</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="odoo-label">Recibido por:</label>
                            <input type="text" class="form-control" placeholder="Nombre del cajero">
                        </div>
                    </div>
                    <div class="col-md-6 text-center">
                        <label class="odoo-label d-block mb-2">Firma del Paciente (JSON Data)</label>
                        <canvas id="signature-canvas" class="signature-pad w-100 h-75"></canvas>
                        <small class="text-muted">Dibuje su firma arriba</small>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="receta">
                <?php
                include '../partials/sale/prescriptions.php';
                ?>
            </div>

            <div class="tab-pane fade" id="notas">
                <textarea class="form-control border" rows="4"
                    placeholder="Notas internas o términos y condiciones..."></textarea>
            </div>

        </div>

        <div class="row mt-5">
            <div class="col-md-7"></div>
            <div class="col-md-5">
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span class="odoo-label">Subtotal</span>
                    <span id="subtotalGlobal">$ 0.00</span>
                </div>
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span class="odoo-label">Descuento</span>
                    <span id="descuentoGlobal">$ 0.00</span>
                </div>
                <div class="d-flex justify-content-between py-2 mb-3">
                    <span class="h4 odoo-label">Total</span>
                    <span id="totalGlobal">$ 0.00</span>
                </div>
                <button class="btn btn-odoo-primary w-100 py-2 fw-bold">CONFIRMAR VENTA Y PAGO</button>
            </div>
        </div>
    </div>
</div>

