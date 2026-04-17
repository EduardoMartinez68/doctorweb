<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <?php include '../../../layouts/styles.php'; ?>
    <style>
        .filter-card {
            background: #fff;
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        }
        .user-table-container {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
        }
        .table thead th {
            background-color: #f8f9fa;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            font-weight: 700;
            padding: 15px;
            border-bottom: 2px solid #ebeef2;
        }
        .table tbody td {
            padding: 15px;
            vertical-align: middle;
        }
        .badge-active { background-color: #e6fcf5; color: #0ca678; border-radius: 6px; padding: 5px 10px; font-weight: 600; }
        .badge-deactivated { background-color: #fff5f5; color: #f03e3e; border-radius: 6px; padding: 5px 10px; font-weight: 600; }
        .search-icon { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #adb5bd; }
        .search-input { padding-left: 40px !important; }
    </style>
</head>
<body class="bg-light">

<?php include '../../../layouts/navbar.php'; ?>

<div class="container py-5">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold m-0">Personal de Clínica</h2>
            <p class="text-muted">Gestiona roles, accesos y estados de tus empleados.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <button class="btn btn-primary px-4 shadow-sm" onclick="add_user()">
                <i class="bi bi-arrow-primary"></i> Agregar Usuario
            </button>
        </div>
    </div>

    <div class="card filter-card p-3 mb-4">
        <div class="row g-3">
            <div class="col-md-4 position-relative">
                <i class="bi bi-search search-icon"></i>
                <input type="text" id="searchInput" class="form-control search-input" placeholder="Buscar por nombre, email o tel...">
            </div>
            <div class="col-md-3">
                <select id="roleFilter" class="form-select">
                    <option value="doctor">Doctores</option>
                    <option value="user">Usuarios Estándar</option>
                    <option value="admin">Administradores</option>
                    <option value="">Todos los roles</option>
                </select>
            </div>
            <div class="col-md-3">
                <select id="statusFilter" class="form-select">
                    <option value="active">Activos</option>
                    <option value="deactivated">Inactivos</option>
                    <option value="">Todos los estados</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-primary w-100 py-2" onclick="location.reload()">Actualizar</button>
            </div>
        </div>
    </div>

    <div class="user-table-container">
        <div class="table-responsive">
            <table class="table table-hover m-0">
                <thead>
                    <tr>
                        <th>Empleado</th>
                        <th>Contacto</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                    </tbody>
            </table>
        </div>
        
        <div class="p-3 border-top d-flex justify-content-between align-items-center bg-white">
            <small id="paginationInfo" class="text-muted"></small>
            <nav>
                <ul class="pagination pagination-sm m-0" id="paginationControls"></ul>
            </nav>
        </div>
    </div>
</div>

<?php include '../../../layouts/scripts.php'; ?>

<script>
let currentPage = 1;
let searchTimeout;

// 📡 FETCH PRINCIPAL
async function fetchUsers(page = 1) {
    currentPage = page;
    const search = document.getElementById('searchInput').value;
    const role = document.getElementById('roleFilter').value;
    const status = document.getElementById('statusFilter').value;

    const query = new URLSearchParams({
        page: page,
        search: search,
        role: role,
        status: status
    });

    const res = await fetch(`../../users/services/search_users.php?${query}`);
    const result = await res.json();

    renderTable(result.data);
    renderPagination(result);
}

// 🖌 RENDERIZAR TABLA
function renderTable(users) {
    const tbody = document.getElementById('userTableBody');
    if (users.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5" class="text-center py-5 text-muted">No se encontraron empleados con estos filtros.</td></tr>`;
        return;
    }

    tbody.innerHTML = users.map(u => `
        <tr>
            <td>
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <span class="fw-bold text-dark">${u.name.charAt(0)}</span>
                    </div>
                    <div>
                        <div class="fw-bold">${u.name}</div>
                        <small class="text-muted">${u.email}</small>
                    </div>
                </div>
            </td>
            <td>
                <div><i class="bi bi-phone me-1"></i> ${u.cellphone || u.phone || 'N/A'}</div>
            </td>
            <td>
                <span class="text-capitalize small fw-medium text-secondary">
                    <i class="bi ${u.role === 'doctor' ? 'bi-heart-pulse' : 'bi-person'}"></i> ${u.role}
                </span>
            </td>
            <td>
                <span class="${u.status === 'active' ? 'badge-active' : 'badge-deactivated'} small">
                    ${u.status === 'active' ? 'Activo' : 'Inactivo'}
                </span>
            </td>
            <td class="text-center">
                <a href="../../users/views/user_edit.php?id=${u.id}" class="btn btn-sm btn-light border shadow-sm px-3">
                    <i class="bi bi-pencil-square"></i> Editar
                </a>
            </td>
        </tr>
    `).join('');
}

// 🔢 PAGINACIÓN
function renderPagination(result) {
    const controls = document.getElementById('paginationControls');
    const info = document.getElementById('paginationInfo');
    
    info.innerText = `Página ${result.page} de ${result.total_pages} (${result.total} empleados)`;
    
    let html = '';
    for(let i = 1; i <= result.total_pages; i++) {
        html += `<li class="page-item ${i === result.page ? 'active' : ''}">
            <a class="page-link" href="#" onclick="fetchUsers(${i})">${i}</a>
        </li>`;
    }
    controls.innerHTML = html;
}

// 🔍 DEBOUNCE & EVENTS
document.getElementById('searchInput').addEventListener('input', () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => fetchUsers(1), 400);
});

document.getElementById('roleFilter').addEventListener('change', () => fetchUsers(1));
document.getElementById('statusFilter').addEventListener('change', () => fetchUsers(1));

function add_user() {
    //document.getElementById('searchInput').value = '';
    //document.getElementById('roleFilter').value = 'doctor';
    //document.getElementById('statusFilter').value = 'active';
    //fetchUsers(1);
    window.location.href = "user_create.php";
}

// Carga inicial
document.addEventListener('DOMContentLoaded', () => fetchUsers(1));
</script>

</body>
</html>