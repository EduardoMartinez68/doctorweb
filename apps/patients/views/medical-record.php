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
    <div class="container py-5">
        <h2>Formulario Médico</h2>

        <form id="form-medical-record">
            <?php
            include '../partials/medicalRecord/form.php';
            include '../partials/medicalRecord/data-pacient.php';
            include '../partials/medicalRecord/son-table.php';
            include '../partials/medicalRecord/medical-history.php';
            include '../partials/medicalRecord/family-history.php';
            include '../partials/medicalRecord/personal-background.php';
            include '../partials/medicalRecord/gynecological-and-obstetrical.php';
            include '../partials/medicalRecord/medical-examination.php';
            ?>

            <!-- Botón -->
            <div class="mt-5 d-grid">
                <button class="btn btn-medical shadow-sm" onclick="send_form_medical_record()" type="button">Guardar
                    Registro Clínico</button>
            </div>
        </form>
    </div>


    <?php
    include '../../../layouts/scripts.php';
    ?>


    <script>
        function clear_custom_tables() {
            const tableBody = document.getElementById('bodyFamiliares');
            tableBody.innerHTML = '';

            reset_occupational_table();
            reset_children_table();
        }


        async function send_form_medical_record() {
            // 1. Obtener el formulario y validar (opcional pero recomendado)
            const form = document.getElementById('form-medical-record');

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

                // 4. Enviar a la API
                const response = await fetch('../../patients/services/create_patient_medical.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                console.log(JSON.stringify(data, null, 2));
                console.log(result)
                // 5. Manejar la respuesta del servidor
                if (result.success) {
                    // Éxito: Mostrar alerta, resetear formulario y volver a la primera pestaña
                    await Swal.fire({
                        icon: 'success',
                        title: '¡Guardado correctamente!',
                        text: result.message || 'El expediente clínico ha sido actualizado.',
                        confirmButtonColor: '#004AAD'
                    });

                    form.reset(); // Reinicia los inputs estándar

                    // Si usas Bootstrap Tabs, esto regresa a la primera pestaña visualmente
                    const firstTabEl = document.querySelector('#recordTabs button[data-bs-toggle="tab"]');
                    const firstTab = new bootstrap.Tab(firstTabEl);
                    firstTab.show();

                    // Opcional: Si tienes funciones para limpiar tus tablas manuales
                    clear_custom_tables(); 

                } else {
                    // Error de validación o del servidor
                    throw new Error(result.message || 'Error desconocido al procesar la solicitud.');
                }

            } catch (error) {
                console.error("Error en send_form_medical_record:", error);

                Swal.fire({
                    icon: 'error',
                    title: 'Error al guardar',
                    text: error.message || 'No se pudo conectar con el servidor.',
                    confirmButtonColor: '#004AAD'
                });
            }
        }


    </script>
</body>

</html>