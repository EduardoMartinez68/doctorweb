<style>
    #btnCancelAppoint {
        display: none;
    }

    #goConsultation {
        : none;
    }
</style>
<style>
    :root {
        --med-primary: #004AAD;
        --med-secondary: #38B6FF;
        --med-bg-input: #f8fafc;
        --med-border: #e2e8f0;
        --med-text-muted: #64748b;
    }

    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        border-bottom: 1px solid var(--med-border);
        padding-bottom: 1rem;
    }

    .form-header h4 {
        color: var(--med-primary);
        font-weight: 700;
        margin: 0;
        letter-spacing: -0.5px;
    }

    /* Estilo para los Labels */
    .form-label-minimal {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--med-text-muted);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Inputs Personalizados */
    .form-control-minimal {
        background-color: var(--med-bg-input);
        border: 1px solid var(--med-border);
        border-radius: 12px;
        padding: 0.75rem 1rem;
        transition: all 0.2s ease-in-out;
        color: #1e293b;
    }

    .form-control-minimal:focus {
        background-color: #fff;
        border-color: var(--med-secondary);
        box-shadow: 0 0 0 4px rgba(56, 182, 255, 0.1);
        outline: none;
    }

    /* Enlace "Ir a consulta" */
    .link-consultation {
        color: var(--med-secondary);
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: color 0.2s;
        background: none;
        border: none;
        padding: 0;
    }

    .link-consultation:hover {
        color: var(--med-primary);
        text-decoration: underline;
    }

    /* Botones de Acción */
    .btn-save-appoint {
        background: var(--med-primary);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.8rem 1.5rem;
        font-weight: 600;
        transition: transform 0.2s, background 0.2s;
    }

    .btn-save-appoint:hover {
        background: #003a8a;
    }

    .btn-cancel-minimal {
        background: #fff;
        color: #ef4444;
        border: 1px solid #fee2e2;
        border-radius: 12px;
        padding: 0.8rem 1.5rem;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-cancel-minimal:hover {
        background: #fee2e2;
    }

    /* Ajuste para los Selectores Dinámicos */
    dynamic-selector {
        display: block;
        margin-bottom: 1rem;
    }
</style>


<plus-pop name="pop_appointments" title="Formulario de Cita">
    <div class="container mt-5">
        <div class="appointment-container">
            <div class="form-header">
                <div>
                </div>
                <button id="goConsultation" type="button" class="link-consultation">
                    Ir a consulta <i class="bi bi-arrow-right-short"></i>
                </button>
            </div>
            <form id="formAppointment">
                <input type="hidden" id="appointment_id" name="appointment_id">

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label-minimal"><i class="bi bi-person"></i> Paciente</label>
                        <dynamic-selector id="form_appoint_patient_id" title="Seleccionar Paciente"
                            link="../../patients/services/search_patients.php" columns="ID,Nombre,email,Teléfono"
                            keys="key_id,name,email,cellphone" name="patient_id" add="openPop('pop_add_patient_flash')"
                            edit="update_data_patient_pop_flash">
                        </dynamic-selector>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-minimal"><i class="bi bi-person-badge"></i> Doctor Responsable</label>
                        <dynamic-selector id="form_appoint_doctor_id" title="Seleccionar Doctor"
                            link="../../users/services/search_users.php" name="doctor_id"
                            columns="Nombre,Email,Teléfono,Rol" keys="name,email,phone,role">
                        </dynamic-selector>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label-minimal"><i class="bi bi-calendar3"></i> Fecha</label>
                        <input type="date" name="date" class="form-control-minimal w-100" required
                            id="form_appointments_date">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-minimal"><i class="bi bi-clock"></i> Hora Inicio</label>
                        <input type="time" name="start_time" class="form-control-minimal w-100" required
                            id="form_appointments_start_time">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-minimal"><i class="bi bi-clock-fill"></i> Hora Fin</label>
                        <input type="time" name="end_time" class="form-control-minimal w-100" required
                            id="form_appointments_end_time">
                    </div>
                </div>

                <div class="mb-5">
                    <label class="form-label-minimal"><i class="bi bi-chat-left-text"></i> Motivo de la Cita</label>
                    <textarea name="reason" class="form-control-minimal w-100" rows="3"
                        placeholder="Escriba brevemente el motivo..."></textarea>
                </div>

                <div class="row pt-3 border-top">
                    <div class="col-6">
                        <button class="btn-cancel-minimal w-100" id="btnCancelAppoint" type="button">
                            Cancelar Cita
                        </button>
                    </div>
                    <div class="col-6">
                        <button class="btn-save-appoint w-100" onclick="send_form_appoint()" type="button">
                            Guardar Cita
                        </button>
                    </div>
                </div>


            </form>
        </div>
    </div>
</plus-pop>
<?php
include '../../../apps/patients/partials/pop_add_patient_flash.php';
include '../../../apps/patients/partials/pop_view_patient_flash.php';
?>


<script>
    function open_pop_appointments(date, start, end) {
        document.getElementById('form_appointments_date').value = date;
        document.getElementById('form_appointments_start_time').value = start;
        document.getElementById('form_appointments_end_time').value = end;

        openPop('pop_appointments');
    }

    async function create_appoint() {
        const form = document.getElementById('formAppointment');
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        try {
            const res = await fetch('../../agenda/services/create_appointment.php', {
                method: 'POST',
                body: formData
            });

            const data = await res.json();
            console.log(data)
            if (data.success) {

                // 🔥 LIMPIAR FORM
                form.reset();

                // 🔥 NOTIFICACIÓN
                closePop('pop_appointments');
                Swal.fire({
                    icon: 'success',
                    title: 'Cita creada',
                    text: data.message,
                    confirmButtonText: 'OK'
                });

                //now we will see if exist this function 
                if (typeof window.renderCalendar === 'function') {
                    window.renderCalendar();
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'La Cita no fue creada',
                    text: data.message,
                    confirmButtonText: 'OK'
                });
            }

        } catch (err) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: err.message
            });
        }
    }

    async function update_appoint() {
        const form = document.getElementById('formAppointment');
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        try {
            const res = await fetch('../../agenda/services/update_appointment.php', {
                method: 'POST',
                body: formData
            });

            const data = await res.json();
            console.log(data)
            if (data.success) {

                // 🔥 LIMPIAR FORM
                form.reset();

                // 🔥 NOTIFICACIÓN
                closePop('pop_appointments');
                Swal.fire({
                    icon: 'success',
                    title: 'Cita Actualizada',
                    text: data.message,
                    confirmButtonText: 'OK'
                });
                
                if (typeof window.renderCalendar === 'function') {
                    window.renderCalendar();
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'La Cita no fue Actualizada',
                    text: data.message,
                    confirmButtonText: 'OK'
                });
            }

        } catch (err) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: err.message
            });
        }
    }

    async function send_form_appoint() {
        //first we will see if the user would like update a appoint or crete a appoint 
        const appointmentInput = document.getElementById('appointment_id');
        if (appointmentInput && appointmentInput.value.trim()) {
            update_appoint();
        } else {
            create_appoint();
        }
    };

    function hidden_or_activate_button_cancel_of_the_form_appoint() {
        const appointmentId = document.getElementById('appointment_id')?.value;
        const btnCancel = document.getElementById('btnCancelAppoint');
        const goConsultation = document.getElementById('goConsultation')

        if (!btnCancel) return;

        // Si el ID tiene contenido (está editando), mostramos el botón. 
        // Si está vacío (es nueva), lo ocultamos.
        if (appointmentId && appointmentId.trim() !== "") {
            btnCancel.style.display = 'block'; // O 'inline-block' según tu CSS
            goConsultation.style.display = 'block';
        } else {
            btnCancel.style.display = 'none';
            goConsultation.style.display = 'none';
        }
    }

    document.getElementById('btnCancelAppoint').addEventListener('click', async () => {

        const appointmentId = document.getElementById('appointment_id')?.value;

        if (!appointmentId) {
            return Swal.fire('Error', 'No hay cita seleccionada', 'error');
        }

        const confirm = await Swal.fire({
            title: '¿Cancelar cita?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, cancelar'
        });

        if (!confirm.isConfirmed) return;

        const formData = new FormData();
        formData.append('appointment_id', appointmentId);

        try {
            const res = await fetch('../../agenda/services/cancel_appointment.php', {
                method: 'POST',
                body: formData
            });

            const data = await res.json();

            if (data.success) {
                closePop('pop_appointments');
                document.getElementById('formAppointment').reset();
                document.getElementById('appointment_id').value = '';
                document.getElementById('goConsultation').style.display = 'none';

                Swal.fire('Cancelada', data.message, 'success');

                // 🔄 refrescar calendario
                const doctorId = document.getElementById('doctorSelect')?.value || '';
                if (typeof renderCalendar === 'function') {
                    renderCalendar(doctorId);
                }

            } else {
                throw new Error(data.message);
            }

        } catch (err) {
            Swal.fire('Error', err.message, 'error');
        }
    });


</script>