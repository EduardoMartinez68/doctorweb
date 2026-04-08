<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mis Archivos</title>
    <?php include '../../../layouts/styles.php'; ?>
</head>
<body>

<?php include '../../../layouts/navbar.php'; ?>

<div class="container mt-4">

    <h4>Mis Archivos</h4>

    <!-- 🔍 BUSCADOR -->
     <div class="row">
        <div class="col">
            <input type="text" id="search" class="form-control mb-3" placeholder="Buscar archivo...">
        </div>
        <div class="col-3">
            <button class="btn btn-success" onclick="go_to_upload_file()">Subir archivo</button>
        </div>
     </div>


    <!-- 📂 TABLA -->
    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="filesTable"></tbody>
    </table>

    <!-- 📄 PAGINACIÓN -->
    <div class="d-flex justify-content-between">
        <button class="btn btn-outline-secondary" id="prevBtn">Anterior</button>
        <span id="pageInfo"></span>
        <button class="btn btn-outline-secondary" id="nextBtn">Siguiente</button>
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

function renderTable(files) {

    const tbody = document.getElementById('filesTable');
    tbody.innerHTML = '';

    files.forEach(file => {

        const isImage = file.mime_type.startsWith('image/');

        const preview = isImage
            ? `<img src="../../files/services/view_image.php?id=${file.id}" 
                    style="width:50px;height:50px;object-fit:cover;border-radius:6px;">`
            : `<span class="badge bg-secondary">Archivo</span>`;

        const tr = document.createElement('tr');

        tr.innerHTML = `
            <td>${preview} ${file.file_name}</td>
            <td>${file.mime_type}</td>
            <td>${file.created_at}</td>
            <td>
                <button class="btn btn-sm btn-primary view">Ver</button>
                <button class="btn btn-sm btn-success download">Descargar</button>
                <button class="btn btn-sm btn-danger delete">Eliminar</button>
            </td>
        `;

        // 👁️ VER
        tr.querySelector('.view').onclick = () => {
            window.open(`../../files/services/view_file.php?id=${file.id}`, '_blank');
        };

        // ⬇️ DESCARGAR
        tr.querySelector('.download').onclick = () => {
            const link = document.createElement('a');
            link.href = `../../files/services/view_file.php?id=${file.id}`;
            link.download = file.file_name;
            link.click();
        };

        // ❌ ELIMINAR
        tr.querySelector('.delete').onclick = async () => {

            const confirm = await Swal.fire({
                title: '¿Eliminar archivo?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar'
            });

            if (!confirm.isConfirmed) return;

            const res = await fetch(`../../files/services/delete_file.php?id=${file.id}`);
            const data = await res.json();

            if (data.success) {
                Swal.fire('Eliminado', data.message, 'success');
                loadFiles(); // 🔄 recargar tabla
            } else {
                Swal.fire('Error', data.message, 'error');
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