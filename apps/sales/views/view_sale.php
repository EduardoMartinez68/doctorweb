<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Detalle de Venta | DentyCloud</title>
    <?php include '../../../layouts/styles.php'; ?>
    <style>
        :root {
            --med-primary: #004AAD;
            --med-bg: #F4F5FF;
            --med-border: #e2e8f0;
        }

        /* --- ESTILOS PARA PANTALLA --- */
        .sale-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: none;
            overflow: hidden;
        }

        .info-label {
            font-size: 0.75rem;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 700;
        }

        .info-value {
            font-weight: 500;
            color: var(--med-primary);
        }

        /* --- ESTILOS DE IMPRESIÓN (EL "CANVA" OCULTO) --- */
        #printArea {
            display: none;
        }

        @media print {
            @page {
                size: portrait;
                margin: 1cm;
            }

            body * {
                visibility: hidden;
                background: white !important;
            }

            #printArea {
                visibility: visible;
                display: block;
            }

            #printArea * {
                visibility: visible;
            }

            #printArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                font-family: 'Segoe UI', sans-serif;
                color: #333;
                background: white !important;
            }

            .no-print {
                display: none !important;
            }

            #printArea {
                position: relative;
                z-index: 1;
            }

            /* Leyendas Centrales (Marca de Agua) */
            .watermark {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) rotate(-45deg);
                font-size: 8rem;
                font-weight: 900;
                color: rgba(0, 0, 0, 0.05);
                white-space: nowrap;
                z-index: 0;
                opacity: 0.08;
                pointer-events: none;
                text-transform: uppercase;
            }
        }

        /* Estructura del Documento de Impresión */
        .print-header {
            border-bottom: 2px solid var(--med-primary);
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .print-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .print-table th,
        .print-table td {
            padding: 10px;
            border: 1px solid #e2e8f0;
            word-wrap: break-word;
        }

        /* Alinear columnas correctamente */
        .print-table th:nth-child(2),
        .print-table th:nth-child(3),
        .print-table th:nth-child(4),
        .print-table td:nth-child(2),
        .print-table td:nth-child(3),
        .print-table td:nth-child(4) {
            text-align: right;
        }

        .print-table th:first-child,
        .print-table td:first-child {
            width: 40%;
        }

        .print-table th:nth-child(2),
        .print-table td:nth-child(2) {
            width: 20%;
        }

        .print-table th:nth-child(3),
        .print-table td:nth-child(3) {
            width: 20%;
        }

        .print-table th:nth-child(4),
        .print-table td:nth-child(4) {
            width: 20%;
        }

        .signature-box {
            margin-top: 100px;
            display: flex;
            justify-content: space-around;
            text-align: center;
        }

        .line-signature {
            width: 200px;
            border-top: 1px solid #000;
            margin-bottom: 5px;
        }

        .logo-print {
            height: 100px;
            width: auto;
            object-fit: contain;
            border: none !important;
            box-shadow: none !important;
            background: transparent !important;
            mix-blend-mode: normal;
        }

        @media print {
            img {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                background: transparent !important;
                box-shadow: none !important;
            }
        }

        .print-header img {
            background: transparent !important;
        }
    </style>
</head>

<body>

    <?php include '../../../layouts/navbar.php'; ?>

    <div class="container py-5 no-print">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-dark">Detalle de la Transacción</h3>
            <div class="d-flex gap-2">
                <button class="btn btn-light border-0 px-4 py-2" style="border-radius: 10px;" id="btnCancelSaleForm">
                    <i class="bi bi-x-circle me-2 text-danger"></i> Cancelar Venta
                </button>
                <button class="btn btn-primary px-4 py-2" style="border-radius: 10px; background: var(--med-primary);"
                    id="btnPrint">
                    <i class="bi bi-printer me-2"></i> Imprimir Comprobante
                </button>
            </div>
        </div>

        <div class="sale-card p-4">
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="info-label">ID Venta</div>
                    <div class="info-value" id="view-id">---</div>
                </div>
                <div class="col-md-3">
                    <div class="info-label">Fecha</div>
                    <div class="info-value" id="view-date">---</div>
                </div>
                <div class="col-md-3">
                    <div class="info-label">Paciente</div>
                    <div class="info-value" id="view-patient">---</div>
                </div>
                <div class="col-md-3 text-end">
                    <div class="info-label">Estado</div>
                    <div id="view-status-badge">---</div>
                </div>
            </div>

            <table class="table align-middle" id="salesTable">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0">Servicio</th>
                        <th class="border-0">Precio</th>
                        <th class="border-0">Descuento</th>
                        <th class="border-0 text-end">Total</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

            <div class="d-flex justify-content-end mt-4">
                <div class="text-end">
                    <span class="info-label d-block">Total Pagado</span>
                    <h2 class="fw-bold text-dark" id="grandTotal">$0.00</h2>
                </div>
            </div>
        </div>
    </div>

    <div id="printArea">
        <div id="printWatermark" class="watermark"></div>

        <div class="print-header d-flex justify-content-between align-items-center">
            <div>
                <img id="logoPreview" class="logo-print">
                <h2 style="color: var(--med-primary); margin: 0;" id="clinicNameTitle"></h2>
            </div>
            <div class="text-end">
                <h4 class="mb-0">RECIBO DE VENTA</h4>
                <p class="mb-0">ID: <span id="p-id"></span></p>
                <p class="mb-0">Fecha: <span id="p-date"></span></p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-6">
                <p class="mb-1 fw-bold">Clínica Emisora:</p>
                <p class="small" id="infoClinic"></p>
            </div>
            <div class="col-6 text-end">
                <p class="mb-1 fw-bold">Información del Paciente:</p>
                <p id="p-patient" class="mb-0">---</p>
                <p class="small text-muted">Método de pago: <span id="p-method"></span></p>
            </div>
        </div>

        <table class="print-table">
            <thead>
                <tr>
                    <th>Descripción del Servicio</th>
                    <th>Precio</th>
                    <th>Descuento</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody id="p-tbody"></tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end fw-bold">TOTAL:</td>
                    <td class="fw-bold" id="p-total" style="font-size: 1.2rem; color: var(--med-primary);"></td>
                </tr>
            </tfoot>
        </table>

        <div class="signature-box">
            <div>
                <div class="line-signature"></div>
                <p class="small">Firma del Doctor Responsable<br><small id="p-doctor"></small></p>
            </div>
            <div>
                <div class="line-signature"></div>
                <p class="small">Firma del Paciente<br><small>Acepto de conformidad</small></p>
            </div>
        </div>

        <div style="margin-top: 50px; text-align: center;" class="text-muted small">
            <p>Gracias por confiar su salud con nosotros.<br>Este documento es un comprobante de servicio.</p>
        </div>
    </div>

    <?php include '../../../layouts/scripts.php'; ?>

    <script>
        const params = new URLSearchParams(window.location.search);
        const saleId = params.get('id');

        async function loadClinic() {

            const res = await fetch('../../settings/services/get_clinic.php');
            const data = await res.json();

            const clinic = data.data;
            document.getElementById('clinicNameTitle').textContent = clinic.name;
            document.getElementById('infoClinic').innerHTML = `${clinic.name} <br> Tel. ${clinic.phone} Cel. ${clinic.cellphone}. <br> ${clinic.address}`;

            if (clinic.logo) {
                document.getElementById('logoPreview').src = '../../../' + clinic.logo;
            } else {

            }
        }

        async function loadSale() {
            const res = await fetch(`../../sales/services/get_sale.php?id=${saleId}`);
            const data = await res.json();
            if (!data.success) {
                Swal.fire('Error', data.message, 'error');
                return;
            }
            renderSale(data.sale, data.items);

            await loadClinic();
        }

        function renderSale(sale, items) {
            const tbody = document.querySelector('#salesTable tbody');
            const ptbody = document.querySelector('#p-tbody');
            tbody.innerHTML = '';
            ptbody.innerHTML = '';

            items.forEach(item => {
                // Render en pantalla
                tbody.innerHTML += `
            <tr>
                <td><div class="fw-bold">${item.name}</div><small class="text-muted">${item.description}</small></td>
                <td>$${item.price.toFixed(2)}</td>
                <td class="text-danger">-$${item.discount.toFixed(2)}</td>
                <td class="text-end fw-bold">$${item.total.toFixed(2)}</td>
            </tr>
        `;
                // Render en Impresión
                ptbody.innerHTML += `
            <tr>
                <td>${item.name}</td>
                <td>$${item.price.toFixed(2)}</td>
                <td>$${item.discount.toFixed(2)}</td>
                <td>$${item.total.toFixed(2)}</td>
            </tr>
        `;
            });

            // Actualizar labels de pantalla
            document.getElementById('view-id').innerText = '#' + sale.title;
            document.getElementById('view-date').innerText = sale.sale_date;
            document.getElementById('view-patient').innerText = sale.patient_name || 'Paciente General';
            document.getElementById('grandTotal').innerText = '$' + sale.total.toFixed(2);

            // Badge de estado en pantalla
            const badge = document.getElementById('view-status-badge');
            badge.className = 'badge rounded-pill p-2 px-3 ';
            badge.className += (sale.status === 'completed' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger');
            badge.innerText = sale.status.toUpperCase();

            // Llenar datos de impresión (CANVA)
            let payment_method = 'TARJETA'
            if (sale.payment_method == 'cash') {
                payment_method = 'EFECTIVO'
            }

            document.getElementById('p-id').innerText = sale.title;
            document.getElementById('p-date').innerText = sale.sale_date;
            document.getElementById('p-patient').innerText = sale.patient_name || 'Publico en General';
            document.getElementById('p-method').innerText = payment_method;
            document.getElementById('p-total').innerText = '$' + sale.total.toFixed(2);
            document.getElementById('p-doctor').innerText = sale.user_name;

            // Lógica de Marca de Agua
            const watermark = document.getElementById('printWatermark');
            if (sale.status === 'cancelled') {
                watermark.innerText = 'CANCELADO';
                watermark.style.color = 'rgba(255, 0, 0, 0.08)';
            } else if (sale.status === 'quote') {
                watermark.innerText = 'COTIZACIÓN';
            } else {
                watermark.innerText = '';
            }

            const title = document.querySelector('.print-header h4');
            if (sale.status === 'quote') {
                title.innerText = 'COTIZACIÓN';
            } else if (sale.status === 'cancelled') {
                title.innerText = 'RECIBO CANCELADO';
            } else {
                title.innerText = 'RECIBO DE VENTA';
            }

        }

        document.getElementById('btnPrint').onclick = () => window.print();

        document.getElementById('btnCancelSaleForm').onclick = async () => {
            const confirm = await Swal.fire({
                title: '¿Confirmar cancelación?',
                text: 'Esto invalidará el recibo actual',
                confirmButtonColor: '#004AAD',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Sí, cancelar',
                cancelButtonText: 'Cancelar',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33'
            });
            if (confirm.isConfirmed) {
                const res = await fetch(`../../sales/services/cancel_sale.php?id=${saleId}`);
                const data = await res.json();
                if (data.success) {
                    Swal.fire('Éxito', 'Venta cancelada', 'success');
                    loadSale();
                }else{
                    Swal.fire('Error al cancelar la venta', data.message, 'error');
                }
            }
        };

        loadSale();
    </script>
</body>

</html>