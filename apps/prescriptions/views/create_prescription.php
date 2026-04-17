<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Nueva Receta</title>
    <?php include '../../../layouts/styles.php'; ?>
</head>

<body>

    <?php include '../../../layouts/navbar.php'; ?>
    <style>
        .prescription-card {
            background: white;
            border-radius: 12px;
            border: 1px solid var(--med-border);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .med-item {
            background: #fff;
            border-left: 4px solid var(--med-primary);
            transition: all 0.2s;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            color: var(--med-primary);
            margin-bottom: 0.5rem;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid var(--med-border);
            padding: 0.6rem 1rem;
        }

        .form-control:focus {
            border-color: var(--med-secondary);
            box-shadow: 0 0 0 3px rgba(56, 182, 255, 0.2);
        }

        .btn-primary-med {
            background-color: var(--med-primary);
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
        }

        .btn-primary-med:hover {
            background-color: var(--med-primary-hover);
        }
    </style>
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="prescription-card p-4 p-md-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="text-color-title fw-bold m-0">
                            <i class="bi bi-file-earmark-medical me-2"></i>Nueva Receta Médica
                        </h4>
                        <span class="badge bg-light text-dark border"><?php echo date('d/m/Y'); ?></span>
                    </div>

                    <form id="formPrescription">
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label">Paciente</label>
                                <dynamic-selector id="patient_id" name="patient_id"
                                    link="../../patients/services/search_patients.php" columns="Nombre,Email,Teléfono"
                                    keys="name,email,phone">
                                </dynamic-selector>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col">
                                <label class="form-label">Diagnóstico</label>
                                <textarea class="form-control" name="diagnosis" rows="5"
                                    placeholder="Ej. Faringoamigdalitis aguda..."></textarea>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col">
                                <label class="form-label">Indicaciones Generales</label>
                                <textarea class="form-control" name="general_instructions" rows="5"
                                    placeholder="Ej. Reposo absoluto por 3 días..."></textarea>
                            </div>
                        </div>

                        <hr class="opacity-10">

                        <div class="mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold m-0" style="color: var(--med-primary);">Medicamentos (Rp)</h5>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addItem()">
                                    <i class="bi bi-plus-lg"></i> Agregar Medicamento
                                </button>
                            </div>

                            <div id="items" class="d-grid gap-3">
                            </div>
                        </div>

                        <div class="row mt-5">
                            <div class="col-md-6">
                                <button id="btnSave" class="btn btn-primary-med w-100 text-white">
                                    <i class="bi bi-check2-circle me-2"></i>Guardar Receta
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="button" id="btnPrint" class="btn btn-outline-dark w-100" disabled
                                    onclick="printPrescription()">
                                    <i class="bi bi-printer me-2"></i>Imprimir Receta
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="printArea" style="display:none;">
        <div class="p-4" style="font-family: Arial; width: 800px;">

            <!-- HEADER -->
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <h3>CLÍNICA PLUS</h3>
                    <p>Dirección del consultorio</p>
                    <p>Tel: 444-123-4567</p>
                </div>

                <img src="/assets/logo.png" width="120">
            </div>

            <hr>

            <!-- INFO PACIENTE -->
            <p><strong>Paciente:</strong> <span id="print_patient"></span></p>
            <p><strong>Fecha:</strong> <span id="print_date"></span></p>

            <!-- DIAGNOSTICO -->
            <p><strong>Diagnóstico:</strong></p>
            <p id="print_diagnosis"></p>

            <hr>

            <!-- MEDICAMENTOS -->
            <h5>Rp:</h5>
            <div id="print_items"></div>

            <hr>

            <!-- INDICACIONES -->
            <p><strong>Indicaciones:</strong></p>
            <p id="print_instructions"></p>

            <br><br>

            <!-- FIRMA -->
            <div style="text-align:right;">
                <p>________________________</p>
                <p>Médico</p>
            </div>

        </div>
    </div>


    <?php include '../../../layouts/scripts.php'; ?>

    <script>
        let items = [];
        let savedPrescription = null;

        window.addItem = function () {
            const index = items.length;
            const itemContainer = document.createElement('div');
            itemContainer.className = 'card med-item p-3 shadow-sm border-0';
            itemContainer.id = `item-row-${index}`;

            itemContainer.innerHTML = `
                <div class="row g-2">
                    <div class="col-md-4">
                        <input type="text" class="form-control" placeholder="Nombre del medicamento" 
                            onchange="updateItem(${index}, 'medicine_name', this.value)">
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cant." onchange="updateItem(${index}, 'dosage_qty', this.value)">
                            <select class="form-select" onchange="updateItem(${index}, 'presentation', this.value)">
                                <option value="Tableta(s)">Tableta(s)</option>
                                <option value="Cápsula(s)">Cápsula(s)</option>
                                <option value="Jarabe (ml)">Jarabe (ml)</option>
                                <option value="Ampolleta(s)">Ampolleta(s)</option>
                                <option value="Gotas">Gotas</option>
                                <option value="Aplicación">Aplicación</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white">Cada</span>
                            <input type="number" class="form-control" placeholder="8" onchange="updateItem(${index}, 'freq_val', this.value)">
                            <select class="form-select" onchange="updateItem(${index}, 'freq_unit', this.value)">
                                <option value="Horas">Horas</option>
                                <option value="Días">Días</option>
                                <option value="Única dosis">Única dosis</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5 mt-2">
                        <div class="input-group">
                            <span class="input-group-text bg-white">Durante</span>
                            <input type="number" class="form-control" placeholder="7" onchange="updateItem(${index}, 'dur_val', this.value)">
                            <select class="form-select" onchange="updateItem(${index}, 'dur_unit', this.value)">
                                <option value="Días">Días</option>
                                <option value="Semanas">Semanas</option>
                                <option value="Meses">Meses</option>
                                <option value="Indefinido">Indefinido</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <input type="text" class="form-control" placeholder="Nota adicional (ej. después de comer)" onchange="updateItem(${index}, 'note', this.value)">
                    </div>
                    <div class="col-md-1 mt-2 text-end">
                        <button type="button" class="btn btn-link text-danger" onclick="removeItem(${index})"><i class="bi bi-trash"></i></button>
                    </div>
                </div>
            `;

            document.getElementById('items').appendChild(itemContainer);
            items.push({
                medicine_name: '', dosage_qty: '', presentation: 'Tableta(s)',
                freq_val: '', freq_unit: 'Horas', dur_val: '', dur_unit: 'Días', note: ''
            });
        }

        function updateItem(index, key, value) {
            items[index][key] = value;
        }

        // GUARDAR
        document.getElementById('formPrescription').addEventListener('submit', async function (e) {
            e.preventDefault();

            const btnSave = document.getElementById('btnSave');
            const btnPrint = document.getElementById('btnPrint');

            btnSave.disabled = true;
            btnSave.innerText = "Guardando...";

            const formData = new FormData(this);
            //formData.append('patient_id', document.querySelector('#patient_id').value);
            formData.append('items', JSON.stringify(items));

            const res = await fetch('../../prescriptions/services/create_prescription.php', {
                method: 'POST',
                body: formData
            });

            const data = await res.json();

            if (data.success) {

                savedPrescription = {
                    patient: document.getElementById('patient_id').getValue() || '',
                    date: new Date().toLocaleDateString(),
                    diagnosis: formData.get('diagnosis'),
                    instructions: formData.get('general_instructions'),
                    items: items
                };

                Swal.fire('Éxito', data.message, 'success');

                // 🔥 BLOQUEAR GUARDADO / ACTIVAR IMPRESIÓN
                btnSave.innerText = "Guardado";
                btnPrint.disabled = false;

            } else {
                btnSave.disabled = false;
                btnSave.innerText = "Guardar receta";
                Swal.fire('Error', data.message, 'error');
            }
        });


        let clinicData = {};

        async function loadClinicInfo() {
            const res = await fetch('../../settings/services/get_clinic.php');
            clinicData = await res.json();
        }


        function printPrescription() {
            if (!savedPrescription) {
                Swal.fire('Error', 'No hay datos para imprimir', 'error');
                return;
            }

            // 1. Construimos el contenido de los medicamentos (Rp)
            // Usamos los nuevos campos: dosage_qty, presentation, freq_val, etc.
            let itemsListHTML = savedPrescription.items.map(i => `
                <div style="margin-bottom: 20px; border-bottom: 1px dotted #ccc; padding-bottom: 10px;">
                    <strong style="font-size: 16px; color: #1e293b;">${i.medicine_name || 'Sin nombre'}</strong><br>
                    <span style="font-size: 14px; color: #475569;">
                        Tomar ${i.dosage_qty || ''} ${i.presentation || ''} 
                        cada ${i.freq_val || ''} ${i.freq_unit || ''} 
                        durante ${i.dur_val || ''} ${i.dur_unit || ''}.
                    </span>
                    ${i.note ? `<br><em style="font-size: 13px; color: #64748b;">Nota: ${i.note}</em>` : ''}
                </div>
            `).join('');

            // 2. Creamos el diseño completo de la receta profesional
            const finalHTML = `
                <html>
                <head>
                    <title>Receta Médica - ${savedPrescription.patient}</title>
                    <style>
                        body { font-family: 'Segoe UI', Arial, sans-serif; margin: 0; padding: 40px; color: #334155; line-height: 1.6; }
                        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 3px solid ${clinicData.brand_color || '#004AAD'}; padding-bottom: 20px; }
                        .clinic-info h2 { margin: 0; color: ${clinicData.brand_color || '#004AAD'}; text-transform: uppercase; }
                        .clinic-info p { margin: 2px 0; font-size: 13px; color: #64748b; }
                        .patient-section { margin-top: 30px; display: grid; grid-template-columns: 1fr 1fr; gap: 10px; background: #f8fafc; padding: 15px; border-radius: 8px; }
                        .rp-section { margin-top: 40px; min-height: 300px; }
                        .rp-title { font-size: 24px; color: ${clinicData.brand_color || '#004AAD'}; font-weight: bold; margin-bottom: 20px; border-bottom: 1px solid #e2e8f0; }
                        .footer { margin-top: 50px; text-align: center; }
                        .signature-box { margin-left: auto; width: 280px; border-top: 1px solid #334155; padding-top: 10px; margin-top: 60px; }
                        @media print { .no-print { display: none; } }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <div class="clinic-info">
                            <h2>${clinicData.name || 'Clínica Plus'}</h2>
                            <p>${clinicData.address || ''}</p>
                            <p>Tel: ${clinicData.phone || ''} | Cel: ${clinicData.cellphone || ''}</p>
                            <p>${clinicData.email || ''}</p>
                        </div>
                        ${clinicData.logo ? `<img src="${clinicData.logo}" style="max-height: 80px;">` : ''}
                    </div>

                    <div class="patient-section">
                        <div><strong>Paciente:</strong> ${savedPrescription.patient}</div>
                        <div style="text-align: right;"><strong>Fecha:</strong> ${savedPrescription.date}</div>
                        <div style="grid-column: span 2;"><strong>Diagnóstico:</strong> ${savedPrescription.diagnosis || 'N/A'}</div>
                    </div>

                    <div class="rp-section">
                        <div class="rp-title">Rp.</div>
                        ${itemsListHTML}
                    </div>

                    <div style="margin-top: 20px;">
                        <strong>Indicaciones Generales:</strong><br>
                        <p style="font-size: 14px;">${savedPrescription.instructions || 'Sin indicaciones adicionales.'}</p>
                    </div>

                    <div class="footer">
                        <div class="signature-box">
                            <strong>Dr. <?php echo $_SESSION['user_name']; ?></strong><br>
                            <small>Cédula Profesional: ___________</small>
                        </div>
                    </div>
                </body>
                </html>
            `;

            // 3. Abrimos la ventana e imprimimos el contenido generado
            const win = window.open('', '', 'width=900,height=800');
            win.document.write(finalHTML);
            win.document.close();

            // Esperamos un momento a que carguen las imágenes (logo) antes de imprimir
            setTimeout(() => {
                win.print();
                // win.close(); // Opcional: cerrar la pestaña después de imprimir
            }, 500);
        }


        async function loadPrescription() {
            const id = new URLSearchParams(window.location.search).get('id');
            if(!id){
                return;
            }
            const res = await fetch(`../../prescriptions/services/get_prescription.php?id=${id}`);
            const data = await res.json();

            if (!data.success) {
                Swal.fire('Error', data.message, 'error');
                return;
            }

            savedPrescription = data.data;

            // 🔥 LLENAR UI
            document.querySelector('[name="diagnosis"]').value = savedPrescription.diagnosis;
            document.querySelector('[name="general_instructions"]').value = savedPrescription.instructions;

            // 🔒 BLOQUEAR TODO
            document.querySelectorAll('input, textarea, select').forEach(el => el.disabled = true);

            // 🧾 CARGAR ITEMS
            savedPrescription.items.forEach(i => {
                items.push(i);
                renderItem(i); // puedes reutilizar tu addItem pero sin inputs editables
            });

            // 🟢 ACTIVAR IMPRIMIR
            document.getElementById('btnPrint').disabled = false;

            // ❌ BOTÓN CANCELAR
            if (savedPrescription.status === 'active') {
                addCancelButton(savedPrescription.id);
            }
        }



        function addCancelButton(id) {
            const btn = document.createElement('button');
            btn.className = 'btn btn-danger w-100 mt-2';
            btn.innerHTML = '<i class="bi bi-x-circle"></i> Cancelar Receta';

            btn.onclick = async () => {

                const confirm = await Swal.fire({
                    title: '¿Cancelar receta?',
                    text: 'Esta acción no se puede revertir',
                    icon: 'warning',
                    showCancelButton: true
                });

                if (!confirm.isConfirmed) return;

                const formData = new FormData();
                formData.append('id', id);

                const res = await fetch('../../prescriptions/services/cancel_prescription.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await res.json();

                if (data.success) {
                    Swal.fire('Éxito', data.message, 'success')
                        .then(() => location.reload());
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            };

            document.querySelector('.row.mt-5').appendChild(btn);
        }
    
        document.addEventListener('DOMContentLoaded', loadPrescription);
        // Llama a esto al cargar el DOM
        document.addEventListener('DOMContentLoaded', loadClinicInfo);
    </script>

</body>

</html>