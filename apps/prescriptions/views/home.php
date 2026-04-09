<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Recetas</title>
    <?php include '../../../layouts/styles.php'; ?>
    <style>
        .badge-active {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid #10b981;
        }

        .badge-cancelled {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid #ef4444;
        }

        .table-hover tbody tr:hover {
            background-color: var(--med-bg);
            cursor: pointer;
            transition: 0.2s;
        }

        .search-input {
            border-radius: 20px 0 0 20px;
            border-right: none;
        }

        .search-btn {
            border-radius: 0 20px 20px 0;
        }

        .prescription-list-card {
            background: white;
            border-radius: 15px;
            border: 1px solid var(--med-border);
            overflow: hidden;
        }
    </style>
</head>

<body>

    <?php include '../../../layouts/navbar.php'; ?>


    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="text-color-title fw-bold m-0">Historial de Recetas</h3>
                <p class="text-muted small">Gestiona y consulta las recetas emitidas en la clínica.</p>
            </div>
            <a href="create_prescription.php" class="btn btn-outline-primary">
                <i class="bi bi-plus-lg"></i> Nueva Receta
            </a>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" id="searchInput" class="form-control search-input"
                        placeholder="Buscar por ID o diagnóstico...">
                    <button class="btn btn-primary text-white search-btn" onclick="fetchPrescriptions(1)">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="prescription-list-card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Fecha</th>
                            <th>Paciente</th>
                            <th>Diagnóstico</th>
                            <th>Estado</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="prescriptionTableBody">
                    </tbody>
                </table>
            </div>
        </div>

        <nav class="mt-4">
            <ul class="pagination justify-content-center" id="pagination"></ul>
        </nav>
    </div>


    <?php include '../../../layouts/scripts.php'; ?>
    <script>
        let currentPage = 1;

        async function fetchPrescriptions(page = 1) {
            currentPage = page;
            const search = document.getElementById('searchInput').value;
            const tbody = document.getElementById('prescriptionTableBody');

            // Feedback visual de carga
            tbody.innerHTML = `<tr><td colspan="6" class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></td></tr>`;

            try {
                const response = await fetch(`../../prescriptions/services/search_prescriptions.php?page=${page}&search=${encodeURIComponent(search)}`);
                const result = await response.json();

                if (result.data.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="6" class="text-center py-5 text-muted">No se encontraron recetas.</td></tr>`;
                    return;
                }

                renderTable(result.data);
                renderPagination(result.total_pages, result.page);

            } catch (error) {
                console.error('Error:', error);
                tbody.innerHTML = `<tr><td colspan="6" class="text-center py-5 text-danger">Error al cargar los datos.</td></tr>`;
            }
        }

        function renderTable(data) {
            const tbody = document.getElementById('prescriptionTableBody');
            tbody.innerHTML = '';

            data.forEach(item => {
                // Lógica de traducción de status
                const statusLabel = item.status === 'active' ? 'Activa' : 'Cancelada';
                const statusClass = item.status === 'active' ? 'badge-active' : 'badge-cancelled';

                // Formateo de fecha (asumiendo YYYY-MM-DD de la DB)
                const dateFormatted = new Date(item.issued_date).toLocaleDateString('es-MX', {
                    day: '2-digit', month: 'short', year: 'numeric'
                });

                const row = `
            <tr>
                <td class="ps-4 fw-bold text-secondary">#${item.id}</td>
                <td>${dateFormatted}</td>
                <td class="fw-bold">${item.patient_name || 'Desconocido'}</td>
                <td class="text-truncate" style="max-width: 250px;">
                    ${item.diagnosis || '<span class="text-muted italic">Sin diagnóstico</span>'}
                </td>
                <td><span class="badge ${statusClass}">${statusLabel}</span></td>
                <td class="text-end pe-4">
                    <a href="../../prescriptions/views/prescription_view.php?id=${item.id}" 
                       class="btn btn-sm btn-outline-primary border-0 shadow-none">
                        <i class="bi bi-eye-fill"></i> Ver detalles
                    </a>
                </td>
            </tr>
        `;
                tbody.insertAdjacentHTML('beforeend', row);
            });
        }

        function renderPagination(totalPages, activePage) {
            const nav = document.getElementById('pagination');
            nav.innerHTML = '';

            for (let i = 1; i <= totalPages; i++) {
                const li = document.createElement('li');
                li.className = `page-item ${i === activePage ? 'active' : ''}`;
                li.innerHTML = `<button class="page-link shadow-none" onclick="fetchPrescriptions(${i})">${i}</button>`;
                nav.appendChild(li);
            }
        }

        // Escuchar tecla enter en el buscador
        document.getElementById('searchInput').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') fetchPrescriptions(1);
        });

        // Carga inicial
        document.addEventListener('DOMContentLoaded', () => fetchPrescriptions(1));
    </script>
</body>

</html>