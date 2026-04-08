<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Venta</title>
    <?php include '../../../layouts/styles.php'; ?>
    <style>
        /* Contenedor Principal con Estilo Card */
        .main-card {
            background: white;
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        /* Encabezado */
        .page-header {
            padding: 1.5rem 2rem;
            background: white;
            border-bottom: 1px solid var(--med-border);
        }

        .header-icon {
            width: 45px;
            height: 45px;
            background: var(--med-bg);
            color: var(--med-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-size: 1.2rem;
        }

        /* Panel Lateral de Resumen */
        .summary-panel {
            background: #f8fafc;
            border-left: 1px solid var(--med-border);
            padding: 2rem;
            height: 100%;
        }

        /* Inputs Estilizados */
        .form-label-custom {
            font-size: 0.8rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
            display: block;
        }

        .minimal-input {
            border: 1px solid var(--med-border);
            border-radius: 12px;
            padding: 0.8rem;
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        .minimal-input:focus {
            border-color: var(--text-title);
            box-shadow: 0 0 0 4px rgba(56, 182, 255, 0.1);
            outline: none;
        }

        /* Items de la Tabla */
        .service-row {
            transition: all 0.2s;
            border-bottom: 1px solid var(--med-bg);
        }

        .service-row:hover {
            background: #fafbff;
        }

        .total-amount {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--med-primary);
            letter-spacing: -1px;
        }

        /* Botones Especiales */
        .btn-create-sale {
            background: var(--med-primary);
            color: white;
            border: none;
            border-radius: 15px;
            padding: 1rem;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
        }

        .btn-create-sale:hover {
            background: #003a8a;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 74, 173, 0.2);
        }

        .delete-btn {
            color: #ef4444;
            background: #fee2e2;
            border: none;
            padding: 5px 10px;
            border-radius: 8px;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>

<?php include '../../../layouts/navbar.php'; ?>

<div class="container-fluid mt-4 px-4">
    <div class="main-card">
        <div class="row g-0">
            <div class="col-lg-8">
                <div class="page-header d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="header-icon"><i class="bi bi-cart-plus"></i></div>
                        <div>
                            <h4 class="mb-0 fw-bold">Nueva Venta</h4>
                            <span class="text-muted small">Selecciona los servicios prestados</span>
                        </div>
                    </div>
                </div>

                <div class="p-4">
                    <div class="row">
                        <div class="col-4">
                            <div class="mb-4">
                                <label class="form-label-custom">Buscar Servicio o Procedimiento</label>
                                <dynamic-selector
                                    id="serviceSelector"
                                    link="../../services/services/search_services.php"
                                    columns="Nombre,Precio"
                                    keys="name,price">
                                </dynamic-selector>
                            </div>

                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle" id="salesTable">
                            <thead class="text-muted small">
                                <tr>
                                    <th>SERVICIO</th>
                                    <th width="150">PRECIO</th>
                                    <th width="150">DESC.</th>
                                    <th width="120">TOTAL</th>
                                    <th width="50"></th>
                                </tr>
                            </thead>
                            <tbody>
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="summary-panel">
                    <h5 class="fw-bold mb-4">Detalles de Venta</h5>
                    
                    <div class="mb-4">
                        <label class="form-label-custom">Título / Concepto</label>
                        <input type="text" id="saleTitle" class="form-control minimal-input" placeholder="Ej: Limpieza dental completa" value="Nueva Venta">
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">Método de Pago</label>
                        <select id="paymentMethod" class="form-select minimal-input">
                            <option value="cash">Efectivo</option>
                            <option value="card">Tarjeta de Crédito/Débito</option>
                            <option value="transfer">Transferencia</option>
                        </select>
                    </div>

                    <hr class="my-4" style="opacity: 0.1;">

                    <div class="d-flex justify-content-between align-items-center mb-2 text-muted">
                        <span>Subtotal</span>
                        <span id="subtotalDisplay">$0.00</span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fw-bold text-dark">Total a Cobrar</span>
                        <div class="total-amount">$<span id="grandTotal">0.00</span></div>
                    </div>

                    <button class="btn-create-sale mb-3" id="btnCreate">
                        <i class="bi bi-check2-circle me-2"></i> Confirmar y Guardar
                    </button>

                    <button class="btn btn-outline-primary w-100 d-none" id="btnView" style="border-radius: 12px; padding: 0.8rem;">
                        <i class="bi bi-printer me-2"></i> Ver Recibo
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../../layouts/scripts.php'; ?>

<script>
let items = [];
let saleCreated = false;

document.getElementById('serviceSelector').addEventListener('item-selected', function(e) {
    const service = e.detail;
    const item = {
        service_id: service.id,
        name: service.name,
        price: parseFloat(service.price),
        discount: 0,
        total: parseFloat(service.price)
    };

    items.push(item);
    renderTable();
});

function renderTable() {
    const tbody = document.querySelector('#salesTable tbody');
    tbody.innerHTML = '';

    items.forEach((item, index) => {
        const tr = document.createElement('tr');
        tr.className = 'service-row';
        tr.innerHTML = `
            <td>
                <div class="fw-bold text-dark">${item.name}</div>
            </td>
            <td>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-transparent border-0">$</span>
                    <input type="number" class="form-control border-0 bg-light price" value="${item.price}" ${saleCreated ? 'disabled':''} style="border-radius: 8px;">
                </div>
            </td>
            <td>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-transparent border-0">$</span>
                    <input type="number" class="form-control border-0 bg-light discount" value="${item.discount}" ${saleCreated ? 'disabled':''} style="border-radius: 8px;">
                </div>
            </td>
            <td class="fw-bold text-dark">$${item.total.toFixed(2)}</td>
            <td class="text-end">
                ${saleCreated ? '' : `<button class="delete-btn delete"><i class="bi bi-trash"></i></button>`}
            </td>
        `;

        if (!saleCreated) {
            tr.querySelector('.price').addEventListener('input', (e) => {
                item.price = parseFloat(e.target.value) || 0;
                updateItem(item);
            });
            tr.querySelector('.discount').addEventListener('input', (e) => {
                item.discount = parseFloat(e.target.value) || 0;
                updateItem(item);
            });
            tr.querySelector('.delete').addEventListener('click', () => {
                items.splice(index, 1);
                renderTable();
            });
        }
        tbody.appendChild(tr);
    });

    calculateTotal();
}

function updateItem(item) {
    item.total = item.price - item.discount;
    calculateTotal();
    // Renderizado suave solo de los totales para evitar perder el focus
    renderTable(); 
}

function calculateTotal() {
    let total = items.reduce((sum, i) => sum + i.total, 0);
    document.getElementById('grandTotal').innerText = total.toFixed(2);
    document.getElementById('subtotalDisplay').innerText = '$' + total.toFixed(2);
}

document.getElementById('btnCreate').addEventListener('click', async () => {
    const title = document.getElementById('saleTitle').value;
    const payment_method = document.getElementById('paymentMethod').value;

    if (items.length === 0) {
        Swal.fire('Atención', 'Por favor agrega al menos un servicio', 'warning');
        return;
    }

    if (!title.trim()) {
        Swal.fire({
            icon: 'warning',
            title: 'Campo requerido',
            text: 'Debes agregar un nombre a la venta'
        });
        title.focus();
        return;
    }



    const res = await fetch('../../sales/services/create_sale.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ 
            title: title,
            items: items,
            payment_method: payment_method
        })
    });

    const data = await res.json();

    if (data.success) {
        saleCreated = true;
        Swal.fire({
            icon: 'success',
            title: 'Venta Registrada',
            text: 'El comprobante se ha generado con éxito',
            confirmButtonColor: '#004AAD'
        });

        document.getElementById('btnCreate').classList.add('d-none');
        document.getElementById('btnView').classList.remove('d-none');


        document.getElementById('btnView').addEventListener('click', () => {
            window.location.href = `../../sales/views/view_sale.php?id=${data.sale_id}`;
        });

        renderTable();
    } else {
        Swal.fire('Error', data.message, 'error');
    }
});

</script>

</body>
</html>