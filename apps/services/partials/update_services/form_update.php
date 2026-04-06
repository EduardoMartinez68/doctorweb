<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';

$clinic_id = $_SESSION['clinic_id'];

// 🛑 VALIDAR ID DEL LINK
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die('ID inválido');
}

// 🔍 OBTENER SERVICIO SOLO SI ES DE LA CLÍNICA
$stmt = $pdo->prepare("
    SELECT id, name, description, price, duration_minutes, status
    FROM services
    WHERE id = ? AND clinic_id = ?
    LIMIT 1
");

$stmt->execute([$id, $clinic_id]);
$service = $stmt->fetch();

// 🛑 SEGURIDAD
if (!$service) {
    die('Servicio no encontrado o sin permisos');
}
?>



<form id="formService">

    <!-- ID oculto -->
    <input type="hidden" name="id" value="<?= $service['id'] ?>">

    <!-- Nombre -->
    <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input 
            type="text" 
            class="form-control" 
            name="name"
            value="<?= htmlspecialchars($service['name']) ?>"
            <?= $service['status'] === 'inactive' ? 'disabled' : '' ?>
            required
        >
    </div>

    <!-- Descripción -->
    <div class="mb-3">
        <label class="form-label">Descripción</label>
        <textarea 
            class="form-control" 
            name="description"
            <?= $service['status'] === 'inactive' ? 'disabled' : '' ?>
        ><?= htmlspecialchars($service['description'] ?? '') ?></textarea>
    </div>

    <!-- Precio y duración -->
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Precio</label>
            <input 
                type="number" 
                step="0.01" 
                class="form-control" 
                name="price"
                value="<?= htmlspecialchars($service['price']) ?>"
                <?= $service['status'] === 'inactive' ? 'disabled' : '' ?>
                required
            >
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Duración (minutos)</label>
            <input 
                type="number" 
                class="form-control" 
                name="duration_minutes"
                value="<?= htmlspecialchars($service['duration_minutes'] ?? '') ?>"
                <?= $service['status'] === 'inactive' ? 'disabled' : '' ?>
            >
        </div>
    </div>

    <!-- Botones -->
    <?php if ($service['status'] === 'active'): ?>

        <button type="submit" class="btn btn-primary w-100 mb-2" id="btnSave">
            Guardar Cambios
        </button>

        <button type="button" class="btn btn-danger w-100" id="btnDelete">
            Eliminar Servicio
        </button>

    <?php else: ?>

        <button type="button" class="btn btn-success w-100" id="btnRestore">
            Restaurar Servicio
        </button>

    <?php endif; ?>

</form>


<script>
const form = document.getElementById('formService');
const btnDelete = document.getElementById('btnDelete');
const btnRestore = document.getElementById('btnRestore');

// 🔥 STATUS REAL DESDE PHP
let currentStatus = "<?= $service['status'] ?>";


// 🚀 ACTUALIZAR
if (form) {
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (currentStatus === 'inactive') {
            Swal.fire('Bloqueado', 'Debes restaurar el servicio primero', 'warning');
            return;
        }

        const formData = new FormData(form);

        const res = await fetch('../../services/services/update_service.php', {
            method: 'POST',
            body: formData
        });

        const data = await res.json();

        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Actualizado',
                text: data.message,
                timer: 1500,
                showConfirmButton: false
            });
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    });
}


// 🗑️ ELIMINAR
if (btnDelete) {
    btnDelete.addEventListener('click', async () => {

        const result = await Swal.fire({
            title: '¿Deseas eliminar este servicio?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        });

        if (!result.isConfirmed) return;

        const formData = new FormData();
        formData.append('id', <?= $service['id'] ?>);

        const res = await fetch('../../services/services/toggle_service_status.php', {
            method: 'POST',
            body: formData
        });

        const data = await res.json();

        if (data.success) {

            Swal.fire({
                icon: 'success',
                title: data.message,
                timer: 1500,
                showConfirmButton: false
            });

            // 🔄 recargar para reflejar estado
            setTimeout(() => location.reload(), 1500);

        } else {
            Swal.fire('Error', data.message, 'error');
        }
    });
}


// ♻️ RESTAURAR
if (btnRestore) {
    btnRestore.addEventListener('click', async () => {

        const result = await Swal.fire({
            title: '¿Deseas restaurar este servicio?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, restaurar',
            cancelButtonText: 'Cancelar'
        });

        if (!result.isConfirmed) return;

        const formData = new FormData();
        formData.append('id', <?= $service['id'] ?>);

        const res = await fetch('../../services/services/toggle_service_status.php', {
            method: 'POST',
            body: formData
        });

        const data = await res.json();

        if (data.success) {

            Swal.fire({
                icon: 'success',
                title: data.message,
                timer: 1500,
                showConfirmButton: false
            });

            // 🔄 recargar para habilitar edición
            setTimeout(() => location.reload(), 1500);

        } else {
            Swal.fire('Error', data.message, 'error');
        }
    });
}
</script>