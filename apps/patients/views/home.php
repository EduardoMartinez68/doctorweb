<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
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
                        <h1 class="h3 fw-bold mb-1">Pacientes</h1>
                        <p class="text-muted small">
                            Tienes 0 pacientes registrados.
                        </p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-5">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Buscar Paciente..." id="input_search">
                            <div class="input-group-append">
                                <button class="btn btn-outline-primary btn-search" type="button">Buscar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4" id="patients-container">

                </div>
                <div class="d-flex justify-content-center mt-4" id="pagination"></div>
            </main>
        </div>
    </div>





    <?php
    include '../../../layouts/scripts.php';
    ?>
    <script>
        let currentPage = 1;
        let search = '';

        async function loadPatients(page = 1) {
            currentPage = page;

            const container = document.querySelector('#patients-container');
            container.innerHTML = '<p class="text-muted">Cargando...</p>';

            const res = await fetch(`../services/search_patients.php?page=${page}&search=${search}`);
            console.log(res)
            const data = await res.json();

            renderPatients(data.data);
            renderPagination(data.total_pages, page);

            // actualizar contador
            document.querySelector('.text-muted.small').innerText =
                `Tienes ${data.total || data.data.length} pacientes registrados.`;
        }

        function renderPatients(patients) {
            const container = document.querySelector('#patients-container');
            container.innerHTML = '';

            if (patients.length === 0) {
                container.innerHTML = `
            <div class="col-12">
                <div class="alert alert-info text-center">
                    No se encontraron pacientes.
                </div>
            </div>
        `;
                return;
            }

            patients.forEach(p => {
                const initials = p.name.substring(0, 2).toUpperCase();

                container.innerHTML += `
        <div class="col-12 col-md-6 col-xl-4">
            <div class="patient-card p-4 shadow-sm">

                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <h6 class="fw-bold">${p.name}</h6>
                        <small class="text-muted">ID: ${p.key_id}</small>
                    </div>
                    <span class="badge ${p.status === 'active' ? 'bg-success' : 'bg-danger'}">
                        ${p.status}
                    </span>
                </div>

                <div class="mb-2">
                    <small class="text-muted d-block">Teléfono</small>
                    <span>${p.cellphone || p.phone || 'N/A'}</span>
                </div>

                <div class="mb-2">
                    <small class="text-muted d-block">Email</small>
                    <span>${p.email || 'N/A'}</span>
                </div>

                <a href="patient.php?id=${p.id}" 
                   class="btn btn-outline-primary btn-sm mt-3 w-100 rounded-pill">
                    Ver Expediente
                </a>

            </div>
        </div>
        `;
            });
        }

        function renderPagination(totalPages, current) {
            const container = document.querySelector('#pagination');
            container.innerHTML = '';

            if (totalPages <= 1) return;

            for (let i = 1; i <= totalPages; i++) {
                container.innerHTML += `
            <button onclick="loadPatients(${i})" 
                class="btn mx-1 ${i === current ? 'btn-primary' : 'btn-outline-primary'}">
                ${i}
            </button>
        `;
            }
        }

        // 🔍 BUSQUEDA CON DEBOUNCE
        const input = document.getElementById('input_search');
        let timeout;

        input.addEventListener('input', (e) => {
            clearTimeout(timeout);

            timeout = setTimeout(() => {
                search = e.target.value; // 🔥 IMPORTANTE
                loadPatients(1);
            }, 300);
        });

        // 🚀 INIT
        document.addEventListener('DOMContentLoaded', () => {
            loadPatients();
        });    </script>
</body>

</html>