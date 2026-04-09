<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Configuración Clínica | Panel de Control</title>
    <?php include '../../../layouts/styles.php'; ?>
    <style>
        :root {
            --med-primary: #004AAD;
            --med-primary-hover: #02377e;
            --med-bg: #F4F5FF;
            --med-text: #334155;
            --med-border: #e2e8f0;
            --med-secondary: #38B6FF;
        }

        body {
            background-color: var(--med-bg);
            color: var(--med-text);
            font-family: 'Inter', sans-serif;
        }

        .settings-card {
            background: white;
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 74, 173, 0.05);
            overflow: hidden;
        }

        .settings-header {
            background: linear-gradient(135deg, var(--med-primary), var(--med-secondary));
            padding: 2rem;
            color: white;
        }

        /* --- Estilo del Logo --- */
        .logo-section {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem;
            background: #f8fafc;
            border-radius: 15px;
            margin-bottom: 2rem;
            border: 1px dashed var(--med-border);
        }

        .logo-wrapper {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border: 4px solid white;
            overflow: hidden;
            position: relative;
            margin-bottom: 1rem;
        }

        #logoPreview {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 10px;
        }

        .btn-change-logo {
            font-size: 0.85rem;
            background: white;
            border: 1px solid var(--med-border);
            padding: 5px 15px;
            border-radius: 20px;
            cursor: pointer;
            transition: 0.3s;
            color: var(--med-primary);
            font-weight: 600;
        }

        .btn-change-logo:hover {
            background: var(--med-primary);
            color: white;
        }

        /* --- Formulario --- */
        .form-label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #64748b;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border-radius: 12px;
            border: 1px solid var(--med-border);
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--med-secondary);
            box-shadow: 0 0 0 4px rgba(56, 182, 255, 0.1);
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--med-primary);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-save {
            background: var(--med-primary);
            border: none;
            border-radius: 12px;
            padding: 1rem 2.5rem;
            font-weight: 700;
            color: white;
            transition: 0.3s;
            width: 100%;
        }

        .btn-save:hover {
            background: var(--med-primary-hover);
            box-shadow: 0 8px 20px rgba(0, 74, 173, 0.2);
        }
    </style>
</head>
<body>

<?php include '../../../layouts/navbar.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="settings-card">
                <div class="settings-header text-center">
                    <h2 class="m-0 fw-bold">Configuración Clínica</h2>
                    <p class="mb-0 opacity-75">Administra la identidad y datos operativos de tu consultorio</p>
                </div>

                <div class="p-4 p-md-5">
                    <form id="clinicForm" enctype="multipart/form-data">
                        
                        <div class="section-title">
                            <i class="fas fa-building"></i> Identidad Visual
                        </div>

                        <div class="logo-section">
                            <div class="logo-wrapper">
                                <img id="logoPreview" src="https://via.placeholder.com/150?text=Logo" alt="Logo Clínica">
                            </div>
                            <label for="logoInput" class="btn-change-logo shadow-sm">
                                <i class="fas fa-camera me-1"></i> Cambiar Logo
                            </label>
                            <input type="file" name="logo" id="logoInput" class="d-none" accept="image/*">
                            <small class="text-muted mt-2">Se recomienda PNG transparente de 500x500px</small>
                        </div>

                        <div class="section-title">
                            <i class="fas fa-info-circle"></i> Información de Contacto
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Nombre de la Clínica</label>
                                <input type="text" name="name" class="form-control" placeholder="Ej. Centro Médico Especializado">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Teléfono Fijo</label>
                                <input type="text" name="phone" class="form-control" placeholder="+00 000 0000">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Celular / WhatsApp</label>
                                <input type="text" name="cellphone" class="form-control" placeholder="+00 0 0000 0000">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Correo Electrónico</label>
                                <input type="email" name="email" class="form-control" placeholder="contacto@clinica.com">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Dirección Completa</label>
                                <input type="text" name="address" class="form-control" placeholder="Calle, Ciudad, Estado">
                            </div>
                        </div>

                        <div class="section-title mt-4">
                            <i class="fas fa-globe-americas"></i> Ajustes Regionales
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Moneda de Operación</label>
                                <select name="currency" class="form-select">
                                    <option value="MXN">Peso Mexicano (MXN)</option>
                                    <option value="USD">Dólar Americano (USD)</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Zona Horaria</label>
                                <select name="timezone" class="form-select">
                                    <optgroup label="México">
                                        <option value="America/Mexico_City">Ciudad de México</option>
                                        <option value="America/Tijuana">Tijuana</option>
                                        <option value="America/Cancun">Cancún</option>
                                    </optgroup>
                                    <optgroup label="USA">
                                        <option value="America/New_York">New York</option>
                                        <option value="America/Chicago">Chicago</option>
                                    </optgroup>
                                    </select>
                            </div>
                        </div>

                        <div class="text-center pt-3">
                            <button type="submit" class="btn btn-save shadow-sm">
                                <i class="fas fa-save me-2"></i> Guardar Cambios
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../../layouts/scripts.php'; ?>

<script>
    // Preview del logo en tiempo real
    document.getElementById('logoInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('logoPreview').src = event.target.result;
            }
            reader.readAsDataURL(file);
        }
    });

    async function loadClinic() {
        const res = await fetch('../../settings/services/get_clinic.php');
        const data = await res.json();
        const clinic = data.data;

        for (let key in clinic) {
            const input = document.querySelector(`[name="${key}"]`);
            if (!input || input.type === 'file') continue;
            input.value = clinic[key] ?? '';
        }

        if (clinic.logo) {
            document.getElementById('logoPreview').src = '../../../' + clinic.logo + '?t=' + new Date().getTime();
        }
    }

    document.getElementById('clinicForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const btn = e.target.querySelector('button');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';

        const formData = new FormData(e.target);
        const res = await fetch('../../settings/services/update_clinic.php', {
            method: 'POST',
            body: formData
        });

        const data = await res.json();
        if (data.success) {
            Swal.fire({
                title: '¡Éxito!',
                text: 'Configuración actualizada correctamente',
                icon: 'success',
                confirmButtonColor: '#004AAD'
            });
            loadClinic();
        } else {
            Swal.fire('Error', data.message, 'error');
        }
        
        btn.disabled = false;
        btn.innerHTML = originalText;
    });

    loadClinic();
</script>

</body>
</html>