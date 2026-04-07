
<div class="container mt-4">

    <h4>Paciente</h4>

    <form id="patientFormUpdate">
        <input type="hidden" name="id" id="id">

        <div class="row">
            <div class="col-md-6 mb-2">
                <input type="text" name="key_id" id="key_id" class="form-control" required>
            </div>

            <div class="col-md-6 mb-2">
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="col-md-6 mb-2">
                <input type="text" name="phone" id="phone" class="form-control">
            </div>

            <div class="col-md-6 mb-2">
                <input type="text" name="cellphone" id="cellphone" class="form-control">
            </div>

            <div class="col-md-6 mb-2">
                <input type="email" name="email" id="email" class="form-control">
            </div>

            <div class="col-md-6 mb-2">
                <input type="date" name="birth_date" id="birth_date" class="form-control">
            </div>

            <div class="col-md-12 mb-2">
                <input type="text" name="address" id="address" class="form-control">
            </div>

            <div class="col-md-12 mb-2">
                <textarea name="notes" id="notes" class="form-control"></textarea>
            </div>
        </div>

        <button type="button" class="btn btn-success" id="btnUpdate">Actualizar</button>
        <button type="button" class="btn btn-danger" id="btnDelete">Eliminar</button>
        <button type="button" class="btn btn-warning d-none" id="btnRestore">Restaurar</button>

    </form>
</div>


<script>
let id = new URLSearchParams(window.location.search).get('id');

async function update_data_patient_pop_flash(patient_id){
    id=patient_id;
    await loadPatient();
    openPop('pop_view_patient_flash');
}


// 🔄 Cargar paciente
async function loadPatient() {
    const res = await fetch(`../../patients/services/get_patient.php?id=${id}`);
    const data = await res.json();

    if (!data.success) return;

    const p = data.data;

    for (let key in p) {
        if (document.getElementById(key)) {
            document.getElementById(key).value = p[key] ?? '';
        }
    }

    // 🎯 estado UI
    if (p.status === 'inactive') {
        document.querySelectorAll('#patientForm input, #patientForm textarea').forEach(el => el.disabled = true);
        btnUpdate.classList.add('d-none');
        btnDelete.classList.add('d-none');
        btnRestore.classList.remove('d-none');
    } else {
        btnRestore.classList.add('d-none');
    }
}

// 💾 actualizar

btnUpdate.addEventListener('click', async () => {
    const patientForm=document.getElementById('patientFormUpdate')
    const formData = new FormData(patientForm);

    const res = await fetch('../../patients/services/update_patient.php', {
        method: 'POST',
        body: formData
    });

    const data = await res.json();

    closePop('pop_view_patient_flash'); //close the pop if the user is create a patient from other view 
    if (data.success) {
        Swal.fire({
            icon: 'success',
            title: 'Actualizado',
            text: data.message,
            timer: 2000,
            showConfirmButton: false
        });
    }
});

// 🗑 eliminar
btnDelete.addEventListener('click', async () => {

    const confirm = await Swal.fire({
        title: '¿Eliminar paciente?',
        icon: 'warning',
        showCancelButton: true
    });

    if (!confirm.isConfirmed) return;

    const formData = new FormData();
    formData.append('id', id);
    formData.append('action', 'delete');

    const res = await fetch('../../patients/services/toggle_patient_status.php', {
        method: 'POST',
        body: formData
    });

    const data = await res.json();

    if (data.success) {
        Swal.fire('Eliminado', '', 'success');
        loadPatient(); // 🔄 recargar UI
    }
});

// ♻ restaurar
btnRestore.addEventListener('click', async () => {

    const formData = new FormData();
    formData.append('id', id);
    formData.append('action', 'restore');

    const res = await fetch('../../patients/services/toggle_patient_status.php', {
        method: 'POST',
        body: formData
    });

    const data = await res.json();

    if (data.success) {
        Swal.fire('Restaurado', '', 'success');
        location.reload();
    }
});

loadPatient();
</script>