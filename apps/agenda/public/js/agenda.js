const calendarEl = document.getElementById('calendar');
const eventModal = new bootstrap.Modal(document.getElementById('eventModal'));

const createModal = new bootstrap.Modal(document.getElementById('createEventModal'));
let selectedInfo = null;

// --- SIMULACIÓN DE BACKEND ---
// Esta función imita una petición fetch()
const getEventsFromBackend = () => {
    return new Promise((resolve) => {
        setTimeout(() => {
            resolve([
                {
                    title: 'Limpieza Dental',
                    start: '2026-03-28T10:00:00',
                    end: '2026-03-28T11:00:00',
                    backgroundColor: '#0d6efd', // Azul Bootstrap
                    extendedProps: {
                        doctor: 'Dr. Arriaga',
                        paciente: 'Juan Pérez',
                        consultorio: 'A-1'
                    }
                },
                {
                    title: 'Extracción',
                    start: '2026-03-29T14:30:00',
                    end: '2026-03-29T16:00:00',
                    backgroundColor: '#dc3545', // Rojo Bootstrap
                    extendedProps: {
                        doctor: 'Dra. García',
                        paciente: 'María López',
                        consultorio: 'B-3'
                    }
                },
                {
                    title: 'Revisión General',
                    start: '2026-03-30T09:00:00',
                    end: '2026-03-30T10:00:00',
                    backgroundColor: '#198754', // Verde Bootstrap
                    extendedProps: {
                        doctor: 'Dr. Arriaga',
                        paciente: 'Carlos Ruiz',
                        consultorio: 'A-1'
                    }
                }
            ]);
        }, 500); // Simula medio segundo de carga
    });
};

// --- CONFIGURACIÓN DEL CALENDARIO ---
const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    locale: 'es',   
    selectable: true,
    headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },
    // Cargamos los eventos llamando a nuestra función simulada
    events: function (info, successCallback, failureCallback) {
            getEventsFromBackend().then(data => successCallback(data));
    },
    //this is when the user do click about a date or a time slot
    select: function(info) {
            selectedInfo = info; // save the date that the user clicked
            
            // Formatear fecha para mostrar en el Pop
            const options = { weekday: 'long', day: 'numeric', month: 'long' };
            const fechaAmigable = info.start.toLocaleDateString('es-ES', options);
            const horaAmigable = info.allDay ? "" : ` a las ${info.start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}`;
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
            open_pop_appointments(fecha, start_time, end_time)
    },
    // Acción al hacer clic en un evento
    eventClick: function (info) {
        const props = info.event.extendedProps;
        document.getElementById('modalTitle').innerText = info.event.title;
        document.getElementById('modalBody').innerHTML = `
                <p><strong>Paciente:</strong> ${props.paciente}</p>
                <p><strong>Doctor:</strong> ${props.doctor}</p>
                <p><strong>Hora:</strong> ${info.event.start.toLocaleTimeString()}</p>
                <p><strong>Consultorio:</strong> ${props.consultorio}</p>
            `;
        //eventModal.show();
        openPop('pop_appointments')
    }
});

calendar.render();





/*-------------FORMS SAVE EVENT------------------ */
//this function is for that when the user do a click about a color box,
//the color of the event that the user is creating change to the color of the box
document.querySelectorAll('.color-dot').forEach(box => {
    box.addEventListener('click', () => {
        document.getElementById('colorPicker').value = box.dataset.color;
    });
});

document.getElementById('btnGuardar').addEventListener('click', function() {
    const paciente = document.getElementById('newPaciente').value;
    const title = document.getElementById('newTitle').value;

    if (paciente && selectedInfo) {
        calendar.addEvent({
            title: title + ": " + paciente,
            start: selectedInfo.startStr,
            end: selectedInfo.endStr,
            backgroundColor: document.getElementById('colorPicker').value, // use the color of the input
            extendedProps: {
                paciente: paciente,
                doctor: document.getElementById('newDoctor').value,
                telefono: document.getElementById('newPhone').value
            }
        });
        createModal.hide();
    }
});