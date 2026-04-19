<?php
include '../../../middleware/authentication.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Médico</title>
    <?php
    include '../../../layouts/styles.php';
    ?>
</head>

<body>
    <?php
    include '../../../layouts/navbar.php';
    include '../partials/menu.php';
    ?>
    <?php
    include '../partials/medicalRecord/form_complete.php';
    ?>
    <!-- Botón -->
    <div class="container">
        <div class="mt-5 d-grid">
            <button class="btn btn-medical shadow-sm" onclick="send_form_update_medical_record()"
                type="button">Actualizar
                Registro Clínico</button>
        </div>
    </div>

    <?php
    include '../../../layouts/scripts.php';
    ?>
    <script>
function render_laboratory_data(laboratory_dataBackned) {

    let laboratoryData = laboratory_dataBackned;

    // Parsear si viene como string
    if (typeof laboratoryData === "string") {
        try {
            laboratoryData = JSON.parse(laboratoryData);
        } catch (e) {
            console.error("Error parseando laboratory_data:", e);
            laboratoryData = [];
        }
    }

    const tbody = document.getElementById("bodyLaboral");

    // Limpiar tabla
    tbody.innerHTML = "";

    // Validar
    if (!Array.isArray(laboratoryData) || laboratoryData.length === 0) {
        return;
    }

    // Recorrer datos
    laboratoryData.forEach(item => {

        const data = {
            empresa: item.company || '',
            puesto: item.job_title || '',
            tiempo: item.duration || '',
            accidentes: item.had_accidents ? "Si" : "No",
            exposicion: Array.isArray(item.exposures) ? item.exposures : []
        };

        agregarLaboral(data);
    });
}

        function renderActividadFisica(record){

            // checkbox
            document.querySelector('[name="does_exercise"]').checked = !!record.does_exercise;

            // inputs
            document.querySelector('[name="exercise_type"]').value = record.exercise_type || '';
            document.querySelector('[name="exercise_frequency"]').value = record.exercise_frequency || '';

            // otro checkbox
            document.querySelector('[name="safety_shoe_impediment"]').checked = !!record.safety_shoe_impediment;

            // select
            document.querySelector('[name="dominant_hand"]').value = record.dominant_hand || '';
        }

        function renderAntecedentesPersonalesTable(record) {

            if (!record) return;

            // Helper para radios
            function setRadio(name, value) {
                const radios = document.querySelectorAll(`input[name="${name}"]`);
                radios.forEach(radio => {
                    radio.checked = (radio.value === value);
                });
            }

            // Helper para inputs
            function setInput(name, value) {
                const input = document.querySelector(`[name="${name}"]`);
                if (input) input.value = value ?? '';
            }

            // 🔬 CRÓNICOS
            setRadio('personal_chronic', record.personal_chronic);
            setInput('personal_chronic_comment', record.personal_chronic_comment);

            // 🩹 TRAUMA
            setRadio('personal_trauma', record.personal_trauma);
            setInput('personal_trauma_comment', record.personal_trauma_comment);

            // 🏥 CIRUGÍAS
            setRadio('personal_surgery', record.personal_surgery);
            setInput('personal_surgery_comment', record.personal_surgery_comment);

            // 🌿 ALERGIAS
            setRadio('personal_allergy', record.personal_allergy);
            setInput('personal_allergy_comment', record.personal_allergy_comment);

            // 🩸 TRANSFUSIÓN
            setRadio('personal_transfusion', record.personal_transfusion);
            setInput('personal_transfusion_comment', record.personal_transfusion_comment);
            setInput('personal_transfusion_date', record.personal_transfusion_date);
            setInput('personal_transfusion_type', record.personal_transfusion_type);
        }

        async function load_medical_record(id) {

            try {
                /*
                Información General del Paciente
                Antecedentes Médico - Laborales tabla
                 */
                const res = await fetch(`../../patients/services/get_patient_full.php?id=${id}`);
                const result = await res.json();

                if (!result.success) {
                    throw new Error(result.message || 'Error al cargar');
                }

                const data = result.data;
                console.log(JSON.stringify(data, null, 2));

                const record = data.record || {};
                renderAntecedentesPersonalesTable(record);
                renderActividadFisica(record);
                render_laboratory_data(record.laboratory_data);

                // 🧍 DATOS PACIENTE
                document.querySelector('[name="name"]').value = data.name || '';
                document.querySelector('[name="email"]').value = data.email || '';
                document.querySelector('[name="phone"]').value = data.phone || '';
                document.querySelector('[name="cellphone"]').value = data.cellphone || '';

                // 🏢 COMPANY
                setValue('company_date', record.company_date);
                setValue('company_name', record.company_name);
                setValue('company_center_type', record.company_center_type);

                // 🏠 DOMICILIO
                setValue('domicilio', record.domicilio);
                setValue('num_ext', record.num_ext);
                setValue('num_int', record.num_int);
                setValue('colonia', record.colonia);
                setValue('ciudad', record.ciudad);
                setValue('state', record.state);
                setValue('zip_code', record.zip_code);

                // 👤 PERSONALES
                setValue('marital_status', capitalize(record.marital_status));
                setValue('level_school', capitalize(record.level_school));

                // 🧪 NOTES
                setValue('note_laboratory', record.note_laboratory);
                setValue('physical_exam_notes', record.physical_exam_notes);

                // 🧠 LIFESTYLE
                setValue('lifestyle_smoking', record.lifestyle_smoking);
                setValue('lifestyle_alcohol', record.lifestyle_alcohol);
                setValue('lifestyle_drugs', record.lifestyle_drugs);
                setValue('lifestyle_drugs_type', record.lifestyle_drugs_type);
                setValue('lifestyle_drugs_frequency', record.lifestyle_drugs_frequency);
                setValue('lifestyle_activity', record.lifestyle_activity);
                setValue('lifestyle_diet', record.lifestyle_diet);

                // 🧬 GINECO
                setValue('menarche_age', record.menarche_age);
                setValue('sexual_onset_age', record.sexual_onset_age);
                setValue('sexual_partners_count', record.sexual_partners_count);
                setValue('contraceptive_method', record.contraceptive_method);
                setValue('last_menstrual_period', record.last_menstrual_period);
                setValue('menstrual_rhythm', record.menstrual_rhythm);
                setValue('pregnancies_count', record.pregnancies_count);
                setValue('births_count', record.births_count);
                setValue('c_sections_count', record.c_sections_count);
                setValue('abortions_count', record.abortions_count);
                setValue('living_children_count', record.living_children_count);
                setValue('pap_smear_done', record.pap_smear_done);
                setValue('pap_smear_result', record.pap_smear_result);
                setValue('ob_gyn_observations', record.ob_gyn_observations);

                

                    // 👨‍👩‍👧 FAMILY
                    (record.family_history || []).forEach(f => {
                        agregarFamiliar({
                            familiar: f.family,
                            vive: f.live,
                            edad: f.old,
                            sano: f.he_is_healthy,
                            comentarios: f.comment
                        });
                    });

                // 👶 HIJOS
                (record.children_data || []).forEach(c => {
                    agregarHijo({
                        genero: c.gender === "Masculino" ? "Masculino" : "Femenino",
                        edad: c.age,
                        sano: c.is_healthy ? "Si" : "No",
                        observaciones: c.observations
                    });
                });


                // 🧠 SÍNTOMAS (CHECKBOXES)
                const symptoms = record.symptoms || {};
                Object.keys(symptoms).forEach(key => {
                    const checkbox = document.querySelector(`[name="${key}"]`);
                    if (checkbox) {
                        checkbox.checked = !!symptoms[key];
                    }
                });

            } catch (error) {
                console.error(error);
                Swal.fire('Error', error.message, 'error');
            }
        }

        // HELPERS
        function setValue(name, value) {
            const el = document.querySelector(`[name="${name}"]`);
            if (el) {
                el.value = value ?? '';
            }
        }

        function capitalize(val) {
            if (!val) return '';
            return val.charAt(0).toUpperCase() + val.slice(1);
        }

        async function send_form_update_medical_record() {
            // 1. Obtener el formulario y validar (opcional pero recomendado)
            const form = document.getElementById('form-medical-record');
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            if (!data.id) {
                Swal.fire('Error', 'No se encontró el ID del paciente para actualizar.', 'error');
                return;
            }

            // Mostramos un mensaje de "Cargando..."
            Swal.fire({
                title: 'Guardando expediente...',
                text: 'Por favor, espere un momento.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            try {
                // 2. Recolectar datos básicos del formulario
                const formData = new FormData(form);
                const data = Object.fromEntries(formData.entries());

                // 3. Inyectar los datos complejos (Tablas/JSON)
                // Nota: Asegúrate de que estas funciones devuelvan objetos o arrays, no strings.
                data.family_history = get_data_table_family();
                data.children_data = get_children_data();
                data.laboratory_data = get_ccupational_data();
                data.symptoms = get_medical_examination_data();
                console.log(JSON.stringify(data, null, 2));
                // 4. Enviar a la API
                const response = await fetch('../../patients/services/update_patient_medical.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                // 5. Manejar la respuesta del servidor
                if (result.success) {
                    // Éxito: Mostrar alerta, resetear formulario y volver a la primera pestaña
                    await Swal.fire({
                        icon: 'success',
                        title: '¡Guardado correctamente!',
                        text: result.message || 'El expediente clínico ha sido actualizado.',
                        confirmButtonColor: '#004AAD',
                        allowEnterKey: false
                    });

                    return;
                } else {
                    // Error de validación o del servidor
                    throw new Error(result.message || 'Error desconocido al procesar la solicitud.');
                    return;
                }

            } catch (error) {
                console.error("Error en send_form_update_medical_record:", error);

                Swal.fire({
                    icon: 'error',
                    title: 'Error al guardar',
                    text: error.message || 'No se pudo conectar con el servidor.',
                    confirmButtonColor: '#004AAD',
                    allowEnterKey: false
                });
                return;
            }
        }

    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let patient_id = new URLSearchParams(window.location.search).get('id');
            if (patient_id) {
                // Asignamos el ID al campo oculto para que se envíe en el POST
                document.getElementById('patient_id_input').value = patient_id;
                load_medical_record(patient_id);
            }
        });
    </script>
</body>

</html>