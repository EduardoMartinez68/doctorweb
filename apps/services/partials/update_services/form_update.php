<div class="container mt-4">

    <h4>Servicio</h4>

    <form id="serviceForm">
        <input type="hidden" name="id" id="id">

        <div class="row">
            <div class="col-md-6 mb-2">
                <input type="text" name="name" id="name" class="form-control" placeholder="Nombre" required>
            </div>

            <div class="col-md-6 mb-2">
                <input type="number" step="0.01" name="price" id="price" class="form-control" placeholder="Precio" required>
            </div>

            <div class="col-md-6 mb-2">
                <input type="number" name="duration_minutes" id="duration_minutes" class="form-control" placeholder="Duración (min)">
            </div>

            <div class="col-md-6 mb-2 d-flex align-items-center">
                <input type="checkbox" id="favorite" name="favorite" class="form-check-input me-2">
                <label for="favorite">Favorito</label>
            </div>

            <div class="col-md-12 mb-2">
                <textarea name="description" id="description" class="form-control" placeholder="Descripción"></textarea>
            </div>
        </div>

        <button type="submit" class="btn btn-success" id="btnUpdate">Actualizar</button>
        <button type="button" class="btn btn-danger" id="btnDelete">Eliminar</button>
        <button type="button" class="btn btn-warning d-none" id="btnRestore">Restaurar</button>

    </form>
</div>
<script>
let service_id = new URLSearchParams(window.location.search).get('id');

async function pop_services_view(id){
    service_id=id;
    await loadService();
    openPop('pop_services_view');
}


// 🔄 cargar servicio
async function loadService() {
    const res = await fetch(`../../services/services/get_service.php?id=${service_id}`);
    const data = await res.json();

    if (!data.success) return;

    const s = data.data;
    console.log(s)
    document.getElementById('id').value = s.id;
    document.getElementById('name').value = s.name;
    document.getElementById('price').value = s.price;
    document.getElementById('duration_minutes').value = s.duration_minutes ?? '';
    document.getElementById('description').value = s.description ?? '';
    document.getElementById('favorite').checked = s.favorite == 1;

    // 🎯 estado UI
    if (s.status === 'inactive') {
        document.querySelectorAll('#serviceForm input, #serviceForm textarea').forEach(el => el.disabled = true);

        btnUpdate.classList.add('d-none');
        btnDelete.classList.add('d-none');
        btnRestore.classList.remove('d-none');
    } else {
        btnRestore.classList.add('d-none');
    }
}

// 💾 actualizar
serviceForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(serviceForm);

    // checkbox fix
    formData.set('favorite', document.getElementById('favorite').checked ? 1 : 0);

    const res = await fetch('../../services/services/update_service.php', {
        method: 'POST',
        body: formData
    });

    const data = await res.json();
    closePop('pop_services_view');
    if (data.success) {
        Swal.fire({
            icon: 'success',
            title: 'Actualizado',
            text: 'Servicio actualizado correctamente',
            timer: 2000,
            showConfirmButton: false
        });
    }
});

// 🗑 eliminar
btnDelete.addEventListener('click', async () => {

    const confirm = await Swal.fire({
        title: '¿Eliminar servicio?',
        icon: 'warning',
        showCancelButton: true
    });

    if (!confirm.isConfirmed) return;

    const formData = new FormData();
    formData.append('id', service_id);
    formData.append('action', 'delete');

    const res = await fetch('../../services/services/toggle_service_status.php', {
        method: 'POST',
        body: formData
    });

    const data = await res.json();

    if (data.success) {
        Swal.fire('Eliminado', '', 'success');
        loadService();
    }
});

// ♻ restaurar
btnRestore.addEventListener('click', async () => {

    const formData = new FormData();
    formData.append('id', service_id);
    formData.append('action', 'restore');

    const res = await fetch('../../services/services/toggle_service_status.php', {
        method: 'POST',
        body: formData
    });

    const data = await res.json();

    if (data.success) {
        Swal.fire('Restaurado', '', 'success');
        location.reload();
    }
});

loadService();
</script>
