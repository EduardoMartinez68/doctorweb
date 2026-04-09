<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Receta</title>
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
    }
    .form-label {
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        color: var(--med-primary);
    }
</style>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <div class="prescription-card p-4 p-md-5">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold m-0">
                        <i class="bi bi-file-earmark-medical me-2"></i>Receta Médica
                    </h4>
                    <span id="statusBadge" class="badge bg-success">Activa</span>
                </div>

                <!-- PACIENTE -->
                <div class="mb-3">
                    <label class="form-label">Paciente</label>
                    <input type="text" id="patient" class="form-control" disabled>
                </div>

                <!-- DIAGNOSTICO -->
                <div class="mb-3">
                    <label class="form-label">Diagnóstico</label>
                    <textarea id="diagnosis" class="form-control" rows="4" disabled></textarea>
                </div>

                <!-- INSTRUCCIONES -->
                <div class="mb-3">
                    <label class="form-label">Indicaciones</label>
                    <textarea id="instructions" class="form-control" rows="4" disabled></textarea>
                </div>

                <hr>

                <!-- MEDICAMENTOS -->
                <h5 class="fw-bold mb-3">Medicamentos (Rp)</h5>
                <div id="items" class="d-grid gap-3"></div>

                <!-- BOTONES -->
                <div class="row mt-5">
                    <div class="col-md-4">
                        <button id="btnPrint" class="btn btn-dark w-100" disabled onclick="printPrescription()">
                            <i class="bi bi-printer"></i> Imprimir
                        </button>
                    </div>
                    <div class="col-md-4">
                        <button id="btnCancel" class="btn btn-danger w-100 d-none">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </button>
                    </div>
                    <div class="col-md-4">
                        <a href="home.php" class="btn btn-outline-primary w-100">
                            Volver
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include '../../../layouts/scripts.php'; ?>

<script>
let savedPrescription = null;
let clinicData = {};

async function loadClinicInfo() {
    const res = await fetch('../../settings/services/get_clinic.php');
    clinicData = await res.json();
}

async function loadPrescription() {
    const id = new URLSearchParams(window.location.search).get('id');

    const res = await fetch(`../../prescriptions/services/get_prescription.php?id=${id}`);
    const data = await res.json();

    if (!data.success) {
        Swal.fire('Error', data.message, 'error');
        return;
    }

    savedPrescription = data.data;

    // 🔥 LLENAR UI
    document.getElementById('patient').value = savedPrescription.patient;
    document.getElementById('diagnosis').value = savedPrescription.diagnosis;
    document.getElementById('instructions').value = savedPrescription.instructions;

    // STATUS
    const badge = document.getElementById('statusBadge');
    badge.innerText = savedPrescription.status;

    if (savedPrescription.status === 'active') {
        badge.className = 'badge bg-success';
        document.getElementById('btnCancel').classList.remove('d-none');
    } else if (savedPrescription.status === 'cancelled') {
        badge.className = 'badge bg-danger';
    } else {
        badge.className = 'badge bg-secondary';
    }

    // ITEMS
    const container = document.getElementById('items');
    container.innerHTML = '';

    savedPrescription.items.forEach(i => {
        const div = document.createElement('div');
        div.className = 'card med-item p-3 shadow-sm border-0';

        div.innerHTML = `
            <strong>${i.medicine_name}</strong><br>
            <small>
                ${i.dosage_qty || ''} ${i.presentation || ''} |
                Cada ${i.freq_val || ''} ${i.freq_unit || ''} |
                Durante ${i.dur_val || ''} ${i.dur_unit || ''}
            </small>
            ${i.note ? `<br><em>${i.note}</em>` : ''}
        `;

        container.appendChild(div);
    });

    // ACTIVAR IMPRIMIR
    document.getElementById('btnPrint').disabled = false;

    // CANCELAR
    document.getElementById('btnCancel').onclick = () => cancelPrescription(savedPrescription.id);
}

async function cancelPrescription(id) {

    const confirm = await Swal.fire({
        title: '¿Cancelar receta?',
        text: 'Esta acción no se puede revertir',
        confirmButtonColor: '#004AAD',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Sí, cancelar',
        cancelButtonText: 'Salir',
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
}

// 🖨️ IMPRESIÓN PRO
function printPrescription() {

    let itemsHTML = savedPrescription.items.map(i => `
        <div style="margin-bottom: 20px;">
            <strong>${i.medicine_name}</strong><br>
            Tomar ${i.dosage_qty || ''} ${i.presentation || ''} 
            cada ${i.freq_val || ''} ${i.freq_unit || ''} 
            durante ${i.dur_val || ''} ${i.dur_unit || ''}.
            ${i.note ? `<br><em>${i.note}</em>` : ''}
        </div>
    `).join('');

    const html = `
    <html>
    <head>
        <title>Receta</title>
        <style>
            body { font-family: Arial; padding: 40px; }
            .header { display:flex; justify-content:space-between; }
        </style>
    </head>
    <body>

        <div class="header">
            <div>
                <h2>${clinicData.name || ''}</h2>
                <p>${clinicData.address || ''}</p>
            </div>
            ${clinicData.logo ? `<img src="${clinicData.logo}" width="120">` : ''}
        </div>

        <hr>

        <p><strong>Paciente:</strong> ${savedPrescription.patient}</p>
        <p><strong>Fecha:</strong> ${savedPrescription.date}</p>

        <p><strong>Diagnóstico:</strong></p>
        <p>${savedPrescription.diagnosis}</p>

        <hr>

        <h3>Rp:</h3>
        ${itemsHTML}

        <hr>

        <p><strong>Indicaciones:</strong></p>
        <p>${savedPrescription.instructions}</p>

        <br><br>
        <p>________________________</p>

    </body>
    </html>
    `;

    const win = window.open('', '', 'width=900,height=800');
    win.document.write(html);
    win.document.close();

    setTimeout(() => win.print(), 500);
}

// INIT
document.addEventListener('DOMContentLoaded', async () => {
    await loadClinicInfo();
    await loadPrescription();
});
</script>

</body>
</html>