<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Archivos | Gestión Médica</title>
    <?php include '../../../layouts/styles.php'; ?>
    <style>
        :root {
            --med-primary: #004AAD;
            --med-primary-hover: #02377e;
            --text-color-title: #38B6FF;
            --med-secondary: #38B6FF;
            --med-bg: #F4F5FF;
            --med-text: #334155;
            --med-border: #e2e8f0;
        }

        body {
            background-color: var(--med-bg);
            color: var(--med-text);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .main-card {
            background: white;
            border-radius: 16px;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 74, 173, 0.05);
            padding: 2rem;
        }

        .header-title {
            color: var(--med-primary);
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        /* Buscador estilizado */
        .search-container .form-control {
            border-radius: 10px;
            border: 1px solid var(--med-border);
            padding: 0.6rem 1rem;
            transition: all 0.3s ease;
        }

        .search-container .form-control:focus {
            box-shadow: 0 0 0 4px rgba(56, 182, 255, 0.1);
            border-color: var(--med-secondary);
        }

        .btn-upload {
            background-color: var(--med-primary);
            color: white;
            border-radius: 10px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-upload:hover {
            background-color: var(--med-primary-hover);
            color: white;
            transform: translateY(-1px);
        }

        /* Tabla minimalista */
        .table-modern {
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        .table-modern thead th {
            border: none;
            color: #94a3b8;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
            padding: 1rem;
        }

        .table-modern tbody tr {
            background-color: white;
            transition: transform 0.2s ease;
        }

        .table-modern tbody tr td {
            padding: 1.2rem 1rem;
            vertical-align: middle;
            border-top: 1px solid var(--med-border);
            border-bottom: 1px solid var(--med-border);
        }

        .table-modern tbody tr td:first-child {
            border-left: 1px solid var(--med-border);
            border-radius: 12px 0 0 12px;
        }

        .table-modern tbody tr td:last-child {
            border-right: 1px solid var(--med-border);
            border-radius: 0 12px 12px 0;
        }

        .file-preview {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 10px;
            margin-right: 12px;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #f1f5f9;
        }

        .btn-action {
            width: 35px;
            height: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            margin: 0 2px;
            border: none;
            transition: 0.2s;
        }

        .btn-view { background: #e0f2fe; color: #0369a1; }
        .btn-download { background: #dcfce7; color: #15803d; }
        .btn-delete { background: #fee2e2; color: #b91c1c; }

        .btn-action:hover { opacity: 0.8; transform: scale(1.05); }

        /* Paginación */
        .pagination-info {
            font-size: 0.9rem;
            color: #64748b;
            font-weight: 500;
        }
    </style>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/4.0.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/4.0.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/4.0.0/uicons-thin-chubby/css/uicons-thin-chubby.css'>
</head>
<body>

<?php include '../../../layouts/navbar.php'; ?>

<div class="container py-5">
    <div class="main-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="header-title m-0">Mis Archivos</h2>
            <button class="btn btn-upload shadow-sm" onclick="go_to_upload_file()">
                <i class="fas fa-plus me-2"></i> Nuevo Archivo
            </button>
        </div>

        <div class="row mb-4 search-container">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" id="search" class="form-control" placeholder="Buscar por nombre de archivo o paciente...">
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>Documento</th>
                        <th>Tipo de Archivo</th>
                        <th>Fecha de Carga</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody id="filesTable">
                    </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
            <button class="btn btn-light border" id="prevBtn">
                <i class="fas fa-chevron-left"></i> Anterior
            </button>
            <span id="pageInfo" class="pagination-info"></span>
            <button class="btn btn-light border" id="nextBtn">
                Siguiente <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</div>

<?php include '../../../layouts/scripts.php'; ?>


<script>
let page = 1;
let totalPages = 1;
let search = '';

function go_to_upload_file(){
    window.location.href = "upload_file.php";
}

async function loadFiles() {

    const res = await fetch(`../../files/services/search_files.php?page=${page}&search=${search}`);
    const data = await res.json();

    totalPages = data.total_pages;

    renderTable(data.data);
    updatePagination();
}
// Mantén tu lógica de JS igual, solo actualizamos renderTable para la nueva UI
function renderTable(files) {
    const tbody = document.getElementById('filesTable');
    tbody.innerHTML = '';

    files.forEach(file => {
        const isImage = file.mime_type.startsWith('image/');
        const preview = isImage
            ? `<img src="../../files/services/view_image.php?id=${file.id}" class="file-preview">`
            : `<div class="file-preview"><i class="fas fa-file-alt text-muted"></i></div>`;

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <div class="d-flex align-items-center">
                    ${preview}
                    <div>
                        <div class="fw-bold text-dark">${file.file_name}</div>
                        <small class="text-muted">ID: #${file.id}</small>
                    </div>
                </div>
            </td>
            <td><span class="badge bg-light text-dark border">${file.mime_type}</span></td>
            <td><span class="text-muted">${file.created_at}</span></td>
            <td class="text-end">
                <button class="btn-action btn-view view" title="Ver"><i class="fi fi-rr-eye"></i></button>
                <button class="btn-action btn-download download" title="Descargar"><i class="fi fi-rr-download"></i></button>
                <button class="btn-action btn-delete delete" title="Eliminar"><i class="fi fi-sr-trash"></i></button>
            </td>
        `;

        // Los eventos onclick se mantienen igual...
        tr.querySelector('.view').onclick = () => window.open(`../../files/services/view_file.php?id=${file.id}`, '_blank');
        
        tr.querySelector('.download').onclick = () => {
            const link = document.createElement('a');
            link.href = `../../files/services/view_file.php?id=${file.id}`;
            link.download = file.file_name;
            link.click();
        };

        tr.querySelector('.delete').onclick = async () => {
            const confirm = await Swal.fire({
                title: '¿Eliminar archivo?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#004AAD',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            });

            if (!confirm.isConfirmed) return;

            const res = await fetch(`../../files/services/delete_file.php?id=${file.id}`);
            const data = await res.json();

            if (data.success) {
                Swal.fire('Eliminado', data.message, 'success');
                loadFiles();
            }
        };

        tbody.appendChild(tr);
    });
}

function updatePagination() {
    document.getElementById('pageInfo').innerText = `Página ${page} de ${totalPages}`;
    document.getElementById('prevBtn').disabled = page <= 1;
    document.getElementById('nextBtn').disabled = page >= totalPages;
}

// 🔍 BUSCAR
document.getElementById('search').addEventListener('input', (e) => {
    search = e.target.value;
    page = 1;
    loadFiles();
});

// 📄 PAGINACIÓN
document.getElementById('prevBtn').onclick = () => {
    page--;
    loadFiles();
};

document.getElementById('nextBtn').onclick = () => {
    page++;
    loadFiles();
};

// 🚀 INIT
loadFiles();
</script>


</body>
</html>



