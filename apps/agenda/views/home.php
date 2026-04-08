<?php
include '../../../middleware/authentication.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda</title>
    <?php
    include '../../../layouts/styles.php';
    ?>

    <link rel="stylesheet" href="../public/css/agenda.css?v=1.0.3">
</head>


<body>

    <?php include '../../../layouts/navbar.php'; ?>

    <div class="container mt-4">

        <h4>Calendario de Citas</h4>

        <!-- 🔍 FILTROS -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label>Doctor</label>
                <!---link="../../users/services/search_users.php?role=doctor"---->
                <dynamic-selector title="Seleccionar Doctor" link="../../users/services/search_users.php"
                    name="doctor_search_id" columns="Nombre,Email,Teléfono,Rol" keys="name,email,phone,role">
                </dynamic-selector>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button id="btnUpdate" class="btn btn-primary w-100">
                    Actualizar
                </button>
            </div>
        </div>

        <!-- 📅 CALENDARIO -->
        <div id="calendar"></div>

    </div>

    <?php
    include '../partials/pop_appointments.php';
    ?>
    <?php
    include '../../../layouts/scripts.php';
    ?>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <script>
        let calendar;

        async function loadAppointments(doctorId = '') {

            let url = '../../agenda/services/get_appointments.php';

            if (doctorId) {
                url += '?doctor_id=' + doctorId;
            }

            const res = await fetch(url);
            const events = await res.json();

            return events;
        }

        async function loadAppointment(id) {

            try {
                const res = await fetch(`../../agenda/services/get_appointment.php?id=${id}`);
                const result = await res.json();

                if (!result.success) {
                    throw new Error(result.message);
                }

                const a = result.data;

                // 🔥 RELLENAR FORM
                document.querySelector('[name="doctor_id"]').value = a.user_id;
                document.querySelector('[name="date"]').value = a.date;
                document.querySelector('[name="start_time"]').value = a.start_time;
                document.querySelector('[name="end_time"]').value = a.end_time;
                document.querySelector('[name="reason"]').value = a.reason ?? '';
                document.querySelector('[name="notes"]').value = a.notes ?? '';

                document.getElementById('form_appoint_patient_id').setValue(a.patient_id, a.patient_name)
                document.getElementById('form_appoint_doctor_id').setValue(a.user_id, a.doctor_name)

                // 🔥 BOTÓN IR A CONSULTA
                const btn = document.getElementById('goConsultation');

                btn.href = `../../consulta/views/view_consultation.php?id=${a.medical_consultation_id}`;
                btn.style.display = 'block';

                // 🔥 GUARDAR ID ACTUAL (para update después)
                document.getElementById('appointment_id').value = a.id;

            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: err.message
                });
            }
        }

        async function renderCalendar(doctorId = '') {

            const events = await loadAppointments(doctorId);
            console.log(events)
            const calendarEl = document.getElementById('calendar');

            // 🔥 destruir si ya existe
            if (calendar) {
                calendar.destroy();
            }

            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                selectable: true,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                // Cargamos los eventos llamando a nuestra función simulada
                events: events,
                //this is when the user do click about a date or a time slot
                select: function (info) {
                    selectedInfo = info; // save the date that the user clicked

                    // Formatear fecha para mostrar en el Pop
                    const options = { weekday: 'long', day: 'numeric', month: 'long' };
                    const fechaAmigable = info.start.toLocaleDateString('es-ES', options);
                    const horaAmigable = info.allDay ? "" : ` a las ${info.start.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}`;
                    // Tu variable original
                    const start = new Date(info.start);

                    // 1. Obtener la fecha en formato YYYY-MM-DD (útil para bases de datos)
                    const fecha = start.toISOString().split('T')[0];

                    // 2. Obtener la hora de inicio (HH:mm)
                    const start_time = start.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: false });

                    // 3. Crear end_time sumando una hora
                    const end = new Date(start);
                    end.setHours(start.getHours() + 1); // Sumamos 1 hora exacta
                    const end_time = end.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: false });

                    document.getElementById('appointment_id').value='';
                    hidden_or_activate_button_cancel_of_the_form_appoint();
                    open_pop_appointments(fecha, start_time, end_time)
                },
                // Acción al hacer clic en un evento
                eventClick: function (info) {
                    const id = info.event.id;
                    document.getElementById('appointment_id').value=id;
                    loadAppointment(id);
                    hidden_or_activate_button_cancel_of_the_form_appoint();
                    openPop('pop_appointments')
                }
            });


            calendar.render();
        }

        // 🚀 INIT
        renderCalendar();

        // 🔄 UPDATE
        document.getElementById('btnUpdate').addEventListener('click', () => {
            const doctorId = document.querySelector('[name="doctor_search_id"]').value;
            renderCalendar(doctorId);
        });
    </script>

</body>

</html>