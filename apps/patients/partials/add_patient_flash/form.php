<style>
    :root {
        --med-primary: #004AAD;
        --med-bg-card: #ffffff;
        --med-border: #e2e8f0;
        --med-soft-blue: #F4F5FF;
        --med-text: #334155;
        --med-danger: #ef4444;
        --med-warning: #f59e0b;
        --med-success: #10b981;
    }

    .edit-patient-card {
        background: var(--med-bg-card);
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        padding: 2.5rem;
    }

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

    /* --- CORRECCIÓN DEL BOTÓN --- */
    .action-bar {
        display: flex;
        justify-content: center; /* Centra el botón dentro de la barra */
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--med-border);
    }

    .btn-action {
        border-radius: 12px;
        padding: 0.8rem 2rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center; /* CENTRA EL CONTENIDO INTERNO (Icono + Texto) */
        gap: 10px;
        transition: all 0.3s;
        border: none;
        width: 100%; /* Ocupa el ancho disponible */
        width: 100%;
    }


</style>

<div class="container mt-5">
    <div class="edit-patient-card mx-auto" style="max-width: 850px;">
        
        <div class="d-flex align-items-center gap-3 mb-4">
            <div style="background: var(--med-soft-blue); padding: 10px; border-radius: 12px;">
                <i class="bi bi-person-gear fs-4 text-primary"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0">Agregar Nuevo Paciente</h4>
                <p class="text-muted small mb-0">Agrega la información del paciente.</p>
            </div>
        </div>

        <form id="patientForm">
            <div class="section-divider">Datos de Identificación</div>
            <div class="row">
                <div class="col-md-6 form-group-edit">
                    <label class="label-edit">Clave (Key ID)</label>
                    <input type="text" name="key_id" id="key_id" class="form-control input-edit" placeholder="Ej: PAC-001" required>
                </div>

                <div class="col-md-6 form-group-edit">
                    <label class="label-edit">Nombre Completo</label>
                    <input type="text" name="name" id="name" class="form-control input-edit" placeholder="Ingrese nombre y apellidos" required>
                </div>
            </div>

            <div class="section-divider">Contacto y Fecha</div>
            <div class="row">
                <div class="col-md-4 form-group-edit">
                    <label class="label-edit">Teléfono Fijo</label>
                    <input type="text" name="phone" id="phone" class="form-control input-edit" placeholder="Ej: 55 1234 5678">
                </div>

                <div class="col-md-4 form-group-edit">
                    <label class="label-edit">Celular</label>
                    <input type="text" name="cellphone" id="cellphone" class="form-control input-edit" placeholder="Ej: 55 8765 4321">
                </div>

                <div class="col-md-4 form-group-edit">
                    <label class="label-edit">F. de Nacimiento</label>
                    <input type="date" name="birth_date" id="birth_date" class="form-control input-edit">
                </div>

                <div class="col-md-12 form-group-edit mt-2">
                    <label class="label-edit">Correo Electrónico</label>
                    <input type="email" name="email" id="email" class="form-control input-edit" placeholder="correo@ejemplo.com">
                </div>
            </div>

            <div class="section-divider">Ubicación y Notas</div>
            <div class="row">
                <div class="col-md-12 form-group-edit">
                    <label class="label-edit">Dirección Completa</label>
                    <input type="text" name="address" id="address" class="form-control input-edit" placeholder="Calle, número, colonia...">
                </div>

                <div class="col-md-12 form-group-edit">
                    <label class="label-edit">Notas del Médico / Observaciones</label>
                    <textarea name="notes" id="notes" class="form-control input-edit" rows="3" placeholder="Alergias, padecimientos previos, etc."></textarea>
                </div>
            </div>

            <div class="action-bar">
                <button type="submit" class="btn-action btn btn-success" id="btnUpdate">
                    <i class="bi bi-check-circle-fill"></i> Registrar Paciente
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('patientForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    try {
        const res = await fetch('../../patients/services/create_patient.php', {
            method: 'POST',
            body: formData
        });

        const data = await res.json();

        if (data.success) {
            // ✅ LIMPIAR FORMULARIO
            form.reset();
            closePop('pop_add_patient_flash'); //close the pop if the user is create a patient from other view 

            // 🔔 NOTIFICACIÓN
            Swal.fire({
                icon: 'success',
                title: 'Paciente creado',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            });

        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message
            });
        }

    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error de conexión'
        });
    }
});
</script>