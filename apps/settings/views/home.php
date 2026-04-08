<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Configuración Clínica</title>
    <?php include '../../../layouts/styles.php'; ?>
</head>

<body>

    <?php include '../../../layouts/navbar.php'; ?>

    <div class="container mt-4">

        <h4>Configuración de la Clínica</h4>

        <form id="clinicForm" enctype="multipart/form-data">

            <div class="mb-3">
                <label>Logo</label>
                <br>
                <img id="logoPreview" style="max-width:120px;margin-top:10px;">
                <input type="file" name="logo" class="form-control">
            </div>

            <div class="mb-3">
                <label>Nombre</label>
                <input type="text" name="name" class="form-control">
            </div>

            <div class="mb-3">
                <label>Teléfono</label>
                <input type="text" name="phone" class="form-control">
            </div>

            <div class="mb-3">
                <label>Celular</label>
                <input type="text" name="cellphone" class="form-control">
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control">
            </div>

            <div class="mb-3">
                <label>Dirección</label>
                <input type="text" name="address" class="form-control">
            </div>

            <div class="mb-3">
                <label>Moneda</label>
                <select name="currency" class="form-control">
                    <option value="MXN">MXN</option>
                    <option value="USD">USD</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Zona horaria</label>
                <select name="timezone" class="form-control">

                    <!-- 🇲🇽 MÉXICO -->
                    <option value="America/Mexico_City">México (CDMX)</option>
                    <option value="America/Tijuana">México (Tijuana)</option>
                    <option value="America/Cancun">México (Cancún)</option>

                    <!-- 🇺🇸 USA -->
                    <option value="America/Los_Angeles">USA (Los Ángeles)</option>
                    <option value="America/Denver">USA (Denver)</option>
                    <option value="America/Chicago">USA (Chicago)</option>
                    <option value="America/New_York">USA (New York)</option>

                    <!-- 🇨🇴 LATAM -->
                    <option value="America/Bogota">Colombia</option>
                    <option value="America/Lima">Perú</option>
                    <option value="America/Santiago">Chile</option>
                    <option value="America/Buenos_Aires">Argentina</option>

                    <!-- 🌍 EUROPA -->
                    <option value="Europe/Madrid">España</option>
                    <option value="Europe/London">Reino Unido</option>

                </select>
            </div>

            <button class="btn btn-primary">Guardar</button>

        </form>

    </div>

    <?php include '../../../layouts/scripts.php'; ?>

    <script>
        async function loadClinic() {

            const res = await fetch('../../settings/services/get_clinic.php');
            const data = await res.json();

            const clinic = data.data;

            for (let key in clinic) {

                const input = document.querySelector(`[name="${key}"]`);

                if (!input) continue;

                // 🚫 IGNORAR FILE INPUT
                if (input.type === 'file') continue;

                // ✅ SELECT
                if (input.tagName === 'SELECT') {
                    input.value = clinic[key] || '';
                }
                // ✅ INPUT NORMAL
                else {
                    input.value = clinic[key] ?? '';
                }
            }

            // 🖼️ LOGO PREVIEW
            console.log(clinic.logo)
            if (clinic.logo) {
                document.getElementById('logoPreview').src = '../../../' + clinic.logo;
            }
        }


        document.getElementById('clinicForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(e.target);

            const res = await fetch('../../settings/services/update_clinic.php', {
                method: 'POST',
                body: formData
            });

            const data = await res.json();

            if (data.success) {
                Swal.fire('Guardado', 'Configuración actualizada', 'success');
                loadClinic();
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        });

        loadClinic();
    </script>

</body>

</html>