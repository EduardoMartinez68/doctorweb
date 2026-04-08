<style>
    :root {
        --med-primary: #004AAD;
        --med-bg-card: #ffffff;
        --med-border: #e2e8f0;
        --med-soft-blue: #F4F5FF;
        --med-text: #334155;
        --med-danger: #ef4444;
        --med-warning: #f59e0b;
        --med-success: #1053b9;
    }

    .edit-patient-card {
        background: var(--med-bg-card);
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        padding: 2.5rem;
    }

    /* Estilo de Secciones */
    .section-divider {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--med-primary);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin: 1.5rem 0 1.2rem 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-divider::after {
        content: "";
        flex: 1;
        height: 1px;
        background: var(--med-border);
    }

    /* Inputs Estilizados */
    .form-group-edit {
        margin-bottom: 1rem;
    }

    .label-edit {
        font-size: 0.8rem;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 0.4rem;
        display: block;
    }

    .input-edit {
        background-color: var(--med-soft-blue);
        border: 1px solid transparent;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        color: var(--med-text);
        transition: all 0.25s ease;
        font-size: 0.95rem;
    }

    .input-edit:focus {
        background-color: #fff;
        border-color: #38B6FF;
        box-shadow: 0 0 0 4px rgba(56, 182, 255, 0.1);
        outline: none;
    }

    /* Botones de Acción */
    .action-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--med-border);
    }

    .btn-action {
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
        border: none;
    }

    #btnUpdate {
        background: var(--med-success);
        color: white;
        width: 100%;
        text-align: center;
        padding: 1rem 0;
    }

    #btnUpdate:hover {
        background: #054496;
        box-shadow: 0 4px 12px rgba(16, 27, 185, 0.2);
    }

    #btnDelete {
        background: #fee2e2;
        color: var(--med-danger);
    }

    #btnDelete:hover {
        background: var(--med-danger);
        color: white;
    }

    #btnRestore {
        background: #fef3c7;
        color: var(--med-warning);
    }


/* --- MEJORAS EN LA BARRA DE ACCIÓN --- */
    .action-bar {
        display: flex;
        flex-wrap: wrap; /* Permite que los botones bajen si no hay espacio */
        gap: 15px; /* Espacio entre botones */
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--med-border);
        align-items: center;
    }

    .btn-action {
        border-radius: 12px;
        padding: 0.8rem 1.5rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center; /* CENTRADO DE TEXTO E ICONO */
        gap: 10px;
        transition: all 0.3s;
        border: none;
        white-space: nowrap;
    }

    /* El botón de actualizar toma el espacio principal */
    #btnUpdate {
        background: var(--med-success);
        color: white;
        flex: 2; /* Ocupa 2 partes del espacio */
        min-height: 55px; /* Lo hace notar más grande */
        font-size: 1.1rem;
    }

    #btnUpdate:hover {
        background: #054496;
        box-shadow: 0 5px 15px rgba(16, 83, 185, 0.3);
    }

    /* El botón de eliminar es más discreto */
    #btnDelete {
        background: #fee2e2;
        color: var(--med-danger);
        flex: 0.6; /* Ocupa mucho menos espacio que actualizar */
        min-height: 55px;
    }

    #btnDelete:hover {
        background: var(--med-danger);
        color: white;
    }

    /* Botón de Restaurar: Ocupará todo el ancho si se activa solo */
    #btnRestore {
        background: #fef3c7;
        color: var(--med-warning);
        flex: 1 1 100%; /* Fuerza a ocupar el 100% del ancho disponible */
        min-height: 50px;
        margin-top: 5px;
    }

    #btnRestore:hover {
        background: var(--med-warning);
        color: white;
    }

    /* Contenedor de botones secundarios para asegurar el flex */
    .secondary-actions {
        display: flex;
        flex: 0.6; 
        gap: 10px;
    }
</style>

<div class="container mt-5">
    <div class="edit-patient-card mx-auto" style="max-width: 850px;">
        
        <div class="d-flex align-items-center gap-3 mb-4">
            <div style="background: var(--med-soft-blue); padding: 10px; border-radius: 12px;">
                <i class="bi bi-person-gear fs-4 text-primary"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0">Expediente del Paciente</h4>
                <p class="text-muted small mb-0">Actualice la información o gestione el estado del paciente.</p>
            </div>
        </div>

        <form id="patientFormUpdate">
            <input type="hidden" name="id" id="id">

            <div class="section-divider">Datos de Identificación</div>
            <div class="row">
                <div class="col-md-6 form-group-edit">
                    <label class="label-edit">Clave (Key ID)</label>
                    <input type="text" name="key_id" id="key_id" class="form-control input-edit" required>
                </div>

                <div class="col-md-6 form-group-edit">
                    <label class="label-edit">Nombre Completo</label>
                    <input type="text" name="name" id="name" class="form-control input-edit" required>
                </div>
            </div>

            <div class="section-divider">Contacto y Fecha</div>
            <div class="row">
                <div class="col-md-4 form-group-edit">
                    <label class="label-edit">Teléfono Fijo</label>
                    <input type="text" name="phone" id="phone" class="form-control input-edit">
                </div>

                <div class="col-md-4 form-group-edit">
                    <label class="label-edit">Celular</label>
                    <input type="text" name="cellphone" id="cellphone" class="form-control input-edit">
                </div>

                <div class="col-md-4 form-group-edit">
                    <label class="label-edit">F. de Nacimiento</label>
                    <input type="date" name="birth_date" id="birth_date" class="form-control input-edit">
                </div>

                <div class="col-md-12 form-group-edit mt-2">
                    <label class="label-edit">Correo Electrónico</label>
                    <input type="email" name="email" id="email" class="form-control input-edit">
                </div>
            </div>

            <div class="section-divider">Ubicación y Notas</div>
            <div class="row">
                <div class="col-md-12 form-group-edit">
                    <label class="label-edit">Dirección Completa</label>
                    <input type="text" name="address" id="address" class="form-control input-edit">
                </div>

                <div class="col-md-12 form-group-edit">
                    <label class="label-edit">Notas del Médico / Observaciones</label>
                    <textarea name="notes" id="notes" class="form-control input-edit" rows="3"></textarea>
                </div>
            </div>

            <div class="action-bar">
                <div>
                    <button type="button" class="btn-action" id="btnDelete">
                        <i class="bi bi-trash3"></i> Eliminar
                    </button>
                    <button type="button" class="btn-action d-none" id="btnRestore">
                        <i class="bi bi-arrow-counterclockwise"></i> Restaurar
                    </button>
                </div>
                
                <button type="button" class="btn-action" id="btnUpdate">
                    <i class="bi bi-cloud-arrow-up"></i> Actualizar Información
                </button>
            </div>
        </form>
    </div>
</div>


<script>
let patient_id = new URLSearchParams(window.location.search).get('id');

async function update_data_patient_pop_flash(id){
    patient_id=id;
    await loadPatient();
    openPop('pop_view_patient_flash');
}


// 🔄 Cargar paciente
async function loadPatient() {
    const res = await fetch(`../../patients/services/get_patient.php?id=${patient_id}`);
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
    if (data.success) {
        closePop('pop_view_patient_flash'); //close the pop if the user is create a patient from other view 
        Swal.fire({
            icon: 'success',
            title: 'Actualizado',
            text: data.message || '',text: data.message,
            timer: 2000,
            showConfirmButton: false
        });
    }else{
        Swal.fire({
            icon: 'error',
            title: 'No se pudo crear el paciente',
            text: data.message || '',
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
    formData.append('id', patient_id);
    formData.append('action', 'delete');

    const res = await fetch('../../patients/services/toggle_patient_status.php', {
        method: 'POST',
        body: formData
    });

    const data = await res.json();

    if (data.success) {
        Swal.fire('Eliminado', '', 'success');
        loadPatient(); // 🔄 recargar UI
    }else{
        Swal.fire({
            icon: 'error',
            title: 'No se pudo eliminar el paciente',
            text: data.message || '',
            timer: 2000,
            showConfirmButton: false
        });
    }
});

// ♻ restaurar
btnRestore.addEventListener('click', async () => {

    const formData = new FormData();
    formData.append('id', patient_id);
    formData.append('action', 'restore');

    const res = await fetch('../../patients/services/toggle_patient_status.php', {
        method: 'POST',
        body: formData
    });

    const data = await res.json();

    if (data.success) {
        Swal.fire('Restaurado', '', 'success');
        location.reload();
    }else{
        Swal.fire({
            icon: 'error',
            title: 'No se pudo restaurar el paciente',
            text: data.message || '',
            timer: 2000,
            showConfirmButton: false
        });
    }
});

loadPatient();
</script>