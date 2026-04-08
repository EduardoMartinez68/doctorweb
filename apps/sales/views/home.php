<?php
include '../../../middleware/authentication.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas</title>
    <?php
    include '../../../layouts/styles.php';
    ?>
    <style>
        /* Estilo Minimalista de Tarjetas */
        .card-sales {
            background: #ffffff;
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 74, 173, 0.05);
        }

        .header-title {
            color: var(--med-primary);
            font-weight: 600;
            letter-spacing: -0.5px;
        }

        /* Inputs y Selects Elegantes */
        .form-control,
        .form-select {
            border: 1px solid var(--med-border);
            border-radius: 10px;
            padding: 0.6rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--text-color-title);
            box-shadow: 0 0 0 4px rgba(56, 182, 255, 0.1);
        }

        /* Botones */
        .btn-primary-med {
            background-color: var(--med-primary);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 0.6rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-primary-med:hover {
            background-color: var(--med-primary-hover);
            transform: translateY(-1px);
        }

        /* Tabla Estilizada */
        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: transparent;
            border-bottom: 2px solid var(--med-bg);
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 1.2rem;
        }

        .table tbody td {
            padding: 1.2rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--med-bg);
        }

        .badge-status {
            padding: 0.5em 1em;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .status-completed {
            background: #dcfce7;
            color: #15803d;
        }

        .status-pending {
            background: #fef9c3;
            color: #854d0e;
        }

        .status-cancelled {
            background: #fec3c3;
            color: #850e0e;
        }

        .btn-action {
            width: 36px;
            height: 36px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: var(--med-bg);
            color: var(--med-primary);
            border: none;
            transition: 0.2s;
        }

        .btn-action:hover {
            background: var(--med-primary);
            color: white;
        }
    </style>
</head>

<body>
    <?php
    include '../../../layouts/navbar.php';
    ?>



    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="header-title mb-1">Historial de Ventas</h2>
                <p class="text-muted small">Gestiona y consulta las transacciones de la clínica</p>
            </div>
            <a href="../../sales/views/create_sale.php" class="btn btn-primary-med">
                <i class="bi bi-plus-lg me-2"></i> Crear Venta
            </a>
        </div>

        <div class="card card-sales mb-4">
            <div class="card-body p-4">
                <div class="row g-3">


                    <!-- 🏷️ Título -->
                    <div class="col-md-4">
                        <label for="">Filtrar por Titulo</label>
                        <input type="text" id="titleFilter" class="form-control" placeholder="Filtrar por título">
                    </div>

                    <!-- 👤 Paciente -->
                    <div class="col-md-4">
                        <label for="">Filtrar por Paciente</label>
                        <dynamic-selector title="Seleccionar Paciente" link="../../patients/services/search_patients.php"
                            columns="ID,Nombre,email,Teléfono" keys="key_id,name,email,cellphone" id="patients_id" name="patients_id">
                        </dynamic-selector>
                    </div>

                    <!-- 📅 Fecha inicio -->
                    <div class="col-md-3">
                        <label for="">Fecha de inicio</label>
                        <input type="date" id="dateFrom" class="form-control">
                    </div>

                    <!-- 📅 Fecha fin -->
                    <div class="col-md-3">
                        <label for="">Fecha Final</label>
                        <input type="date" id="dateTo" class="form-control">
                    </div>

                    <!-- 💳 Método -->
                    <div class="col-md-3">
                        <label for="">Metodos de Pagos</label>
                        <select id="methodFilter" class="form-select">
                            <option value="">Todos los métodos</option>
                            <option value="cash">Efectivo</option>
                            <option value="card">Tarjeta</option>
                            <option value="transfer">Transferencia</option>
                        </select>
                    </div>

                    <!-- 📦 Status -->
                    <div class="col-md-3">
                        <label for="">Estado de la Venta</label>
                        <select id="statusFilter" class="form-select">
                            <option value="">Todos los estados</option>
                            <option value="completed">Completado</option>
                            <option value="cancelled">Cancelado</option>
                        </select>
                    </div>

                    <!-- 🔘 Botones -->
                    <div class="col-md-6">
                        <button class="btn btn-primary-med w-100" onclick="fetchSales(1)">
                            Filtrar
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                            Limpiar filtros
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-sales">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Paciente</th>
                            <th>Total</th>
                            <th>Método</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="salesTableBody">
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <span class="text-muted small" id="recordCount">Cargando datos...</span>
                <nav>
                    <ul class="pagination pagination-sm mb-0" id="pagination">
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <?php
    include '../../../layouts/scripts.php';
    ?>
    <script>
        let currentPage = 1;

        async function fetchSales(page = 1) {
            currentPage = page;

            const params = new URLSearchParams();

            params.append('page', page);

            const search = '';
            const title = document.getElementById('titleFilter').value;
            const patient = document.getElementById('patients_id').getValue();
            const dateFrom = document.getElementById('dateFrom').value;
            const dateTo = document.getElementById('dateTo').value;
            const method = document.getElementById('methodFilter').value;
            const status = document.getElementById('statusFilter').value;

            // 🔥 SOLO ENVÍA SI EXISTEN
            if (search) params.append('search', search);
            if (title) params.append('title', title);
            if (patient) params.append('patient_id', patient);
            if (dateFrom) params.append('date_from', dateFrom);
            if (dateTo) params.append('date_to', dateTo);
            if (method) params.append('payment_method', method);
            if (status) params.append('status', status);

            const url = `../../sales/services/search_sales.php?${params.toString()}`;

            try {
                const response = await fetch(url);
                const res = await response.json();

                renderTable(res.data);
                renderPagination(res);

                document.getElementById('recordCount').innerText =
                    `Mostrando página ${res.page} de ${res.total_pages} (${res.total} registros)`;

            } catch (error) {
                console.error("Error cargando ventas:", error);
            }
        }

        function resetFilters() {
            document.getElementById('titleFilter').value = '';
            document.getElementById('dateFrom').value = '';
            document.getElementById('dateTo').value = '';
            document.getElementById('methodFilter').value = '';
            document.getElementById('statusFilter').value = '';

            document.getElementById('patients_id').setValue(null, 'Seleccionar');
            fetchSales(1);
        }

        function renderTable(data) {
            const tbody = document.getElementById('salesTableBody');
            tbody.innerHTML = '';

            if (data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" class="text-center py-5 text-muted">No se encontraron ventas</td></tr>`;
                return;
            }

            data.forEach(sale => {
                let statusClass = 'status-pending';
                let status = 'Pendiente'
                if (sale.status == 'completed') {
                    statusClass = 'status-completed'
                    status = 'Completado'
                } else if (sale.status == 'cancelled') {
                    statusClass = 'status-cancelled'
                    status = 'Cancelado'
                }

                let payment_method = 'Tarjeta';
                if (sale.payment_method == 'cash') {
                    payment_method = 'Efectivo';
                }


                tbody.innerHTML += `
                <tr>
                    <td>${sale.title || 'Sin Titulo'}</td>
                    <td>
                        <div class="small">${new Date(sale.sale_date).toLocaleDateString()}</div>
                        <div class="text-muted" style="font-size: 0.7rem;">${sale.created_at}</div>
                    </td>
                    <td>
                        <div class="fw-medium">${sale.patient_name || 'Publico en General'}</div>
                        <div class="text-muted small">Atendido por: ${sale.user_name}</div>
                    </td>
                    <td class="fw-bold text-dark">$${sale.total.toFixed(2)}</td>
                    <td><span class="text-muted small">${payment_method}</span></td>
                    <td><span class="badge-status ${statusClass}">${status}</span></td>
                    <td class="text-end">
                        <a href="../../sales/views/view_sale.php?id=${sale.id}" class="btn btn-action" title="Ver detalle">
                            <i class="bi bi-eye-fill"></i>
                        </a>
                    </td>
                </tr>
            `;
            });
        }

        function renderPagination(res) {
            const pag = document.getElementById('pagination');
            pag.innerHTML = '';

            for (let i = 1; i <= res.total_pages; i++) {
                pag.innerHTML += `
                <li class="page-item ${i === res.page ? 'active' : ''}">
                    <button class="page-link" onclick="fetchSales(${i})">${i}</button>
                </li>
            `;
            }
        }

        // Escuchar búsqueda en tiempo real (opcional)
        document.getElementById('titleFilter').addEventListener('keyup', (e) => {
            if (e.key === 'Enter') fetchSales(1);
        });

        // Carga inicial
        document.addEventListener('DOMContentLoaded', () => fetchSales(1));
    </script>
</body>

</html>