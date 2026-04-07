<style>
#btnCancelAppoint {
    display: none;
}
</style>
<plus-pop name="pop_appointments" title="Formulario de Cita">
    <div class="container mt-4">
        <h4>Datos de la Cita</h4>

        <form id="formAppointment">

            <!-- PACIENTE -->
            <input type="hidden" id="appointment_id" name="appointment_id">
            <div class="row">
                <div class="col">
                    <label>Paciente</label>
                    <dynamic-selector title="Seleccionar Paciente" link="../../patients/services/search_patients.php"
                        columns="ID,Nombre,email,Teléfono" keys="key_id,name,email,cellphone" name="patient_id"
                        add="openPop('pop_add_patient_flash')" edit="update_data_patient_pop_flash">
                    </dynamic-selector>
                </div>
                <div class="col">
                    <label>Doctor</label>
                    <!---link="../../users/services/search_users.php?role=doctor"---->
                    <dynamic-selector title="Seleccionar Doctor" link="../../users/services/search_users.php"
                        name="doctor_id" columns="Nombre,Email,Teléfono,Rol" keys="name,email,phone,role">
                    </dynamic-selector>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col">
                    <label>Fecha</label>
                    <input type="date" name="date" class="form-control" required id="form_appointments_date">
                </div>
            </div>
            <br>
            <!-- HORAS -->
            <div class="row">
                <div class="col">
                    <label>Inicio</label>
                    <input type="time" name="start_time" class="form-control" required
                        id="form_appointments_start_time">
                </div>
                <div class="col">
                    <label>Fin</label>
                    <input type="time" name="end_time" class="form-control" required id="form_appointments_end_time">
                </div>
            </div>

            <!-- MOTIVO -->
            <div class="mt-3">
                <label>Motivo</label>
                <textarea name="reason" class="form-control"></textarea>
            </div>
            <button id="goConsultation" type="button">Ir a consulta</button>

            <div class="row">
                <div class="col">
                    <button class="btn btn-danger mt-3 w-100" id="btnCancelAppoint" type="button">Cancelar Cita</button>
                </div>
                <div class="col">
                    <button class="btn btn-primary mt-3 w-100" onclick="send_form_appoint()" type="button">
                        Guardar Cita
                    </button>
                </div>
            </div>
        </form>
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

        if (!btnCancel) return;

        // Si el ID tiene contenido (está editando), mostramos el botón. 
        // Si está vacío (es nueva), lo ocultamos.
        if (appointmentId && appointmentId.trim() !== "") {
            btnCancel.style.display = 'block'; // O 'inline-block' según tu CSS
        } else {
            btnCancel.style.display = 'none';
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