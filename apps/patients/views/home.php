<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Ventas</title>
    <?php
    include '../../../layouts/styles.php';
    ?>
    <style>
        :root {
            --white: #ffffff;
            --soft-shadow: 0 8px 30px rgba(0, 0, 0, 0.04);
        }


        .search-container {
            background: var(--white);
            padding: 20px;
            border-radius: 16px;
            box-shadow: var(--soft-shadow);
            margin-bottom: 25px;
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .input-soft {
            border: 1px solid var(--med-border);
            border-radius: 10px;
            padding: 10px 15px;
            transition: all 0.3s ease;
            width: 100%;
        }

        .input-soft:focus {
            outline: none;
            border-color: var(--med-secondary);
            box-shadow: 0 0 0 4px rgba(56, 182, 255, 0.1);
        }

        .patient-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
        }

        .patient-card {
            background: var(--white);
            border-radius: 20px;
            padding: 20px;
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: var(--soft-shadow);
            transition: transform 0.2s ease;
        }

        .patient-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .patient-name {
            color: var(--med-primary);
            font-weight: 700;
            font-size: 1.1rem;
            margin: 0;
        }

        .patient-meta {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-action {
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: 0.2s;
        }

        .btn-primary-soft {
            background: var(--med-primary);
            color: white;
        }

        .btn-outline-soft {
            border: 1px solid var(--med-border);
            color: var(--med-text);
        }

        .btn-outline-soft:hover {
            background: var(--med-bg);
        }

        .badge-id {
            background: var(--med-bg);
            color: var(--med-secondary);
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <?php
    include '../../../layouts/navbar.php';
    include '../partials/menu.php';
    ?>

    <br>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="color: var(--text-color-title); font-weight: 800;">Directorio de Pacientes</h2>
            <a href="../../patients/views/medical-record.php" class="btn btn-action btn-primary-soft">
                <i class="fas fa-plus"></i> Nuevo Paciente
            </a>
        </div>

        <div class="search-container">
            <i class="fas fa-search text-muted"></i>
            <input type="text" id="patientSearch" class="input-soft"
                placeholder="Buscar por ID de paciente (Key ID)...">
        </div>

        <div id="patientsContainer" class="patient-grid">
        </div>
    </div>

    <?php
    include '../../../layouts/scripts.php';
    ?>
    <script>
        let searchTimeout;

        async function fetchPatients(query = '') {
            const container = document.getElementById('patientsContainer');

            try {
                const response = await fetch(`../../patients/services/search_patients.php?search=${query}`);
                const result = await response.json();

                container.innerHTML = '';

                if (result.data.length === 0) {
                    container.innerHTML = '<p class="text-center w-100 opacity-50">No se encontraron pacientes.</p>';
                    return;
                }

                result.data.forEach(patient => {
                    const card = `
                <div class="patient-card">
                    <div class="patient-header">
                        <div>
                            <span class="badge-id">#${patient.key_id}</span>
                            <h3 class="patient-name mt-2">${patient.name || 'Sin nombre'}</h3>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="patient-meta"><i class="fas fa-envelope"></i> ${patient.email || 'N/A'}</div>
                        <div class="patient-meta"><i class="fas fa-phone"></i> ${patient.cellphone || patient.phone || 'N/A'}</div>
                        <div class="patient-meta"><i class="fas fa-calendar"></i> ${patient.birth_date || 'N/A'}</div>
                    </div>

                    <div class="d-flex gap-2 pt-2 border-top">
                        <a href="../../patients/views/view_patient.php?id=${patient.id}" class="btn-action btn-outline-soft flex-grow-1 justify-content-center">
                            <i class="fas fa-user-edit"></i> Perfil
                        </a>
                        <a href="../../patients/views/view_medical_record.php?id=${patient.id}" class="btn-action btn-outline-soft flex-grow-1 justify-content-center">
                            <i class="fas fa-notes-medical"></i> Historial
                        </a>
                    </div>
                </div>
            `;
                    container.innerHTML += card;
                });
            } catch (error) {
                console.error('Error cargando pacientes:', error);
            }
        }

        // Escuchador del buscador con Debounce (para no saturar el servidor)
        document.getElementById('patientSearch').addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                fetchPatients(e.target.value);
            }, 400);
        });

        // Carga inicial
        fetchPatients();
    </script>
</body>

</html>