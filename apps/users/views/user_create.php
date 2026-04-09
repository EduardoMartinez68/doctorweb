<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Usuario</title>
    <?php include '../../../layouts/styles.php'; ?>
</head>
<body>

<?php include '../../../layouts/navbar.php'; ?>

<style>
    .user-card {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--med-border);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .form-label {
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        color: var(--med-primary);
    }
    .form-control, .form-select {
        border-radius: 8px;
        padding: 0.6rem 1rem;
    }
    .btn-primary-med {
        background-color: var(--med-primary);
        border: none;
        border-radius: 8px;
        padding: 10px;
    }
</style>

<div class=" mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">

            <div class="user-card p-4 p-md-5">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold m-0">
                        <i class="bi bi-person-plus me-2"></i>Nuevo Usuario
                    </h4>
                </div>

                <form id="formUser">

                    <!-- NOMBRE -->
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="name" class="form-control" placeholder="Ej. Dr. Juan Pérez" required>
                    </div>

                    <!-- EMAIL -->
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="correo@ejemplo.com" required>
                    </div>

                    <!-- PASSWORD -->
                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>

                    <!-- TELÉFONO -->
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="phone" class="form-control" placeholder="Opcional">
                    </div>

                    <!-- CELULAR -->
                    <div class="mb-3">
                        <label class="form-label">Celular</label>
                        <input type="text" name="cellphone" class="form-control" placeholder="Opcional">
                    </div>

                    <!-- ROL -->
                    <div class="mb-4">
                        <label class="form-label">Rol</label>
                        <select name="role" class="form-select">
                            <option value="user">Usuario</option>
                            <option value="doctor">Doctor</option>
                            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                <option value="admin">Administrador</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- BOTONES -->
                    <div class="row">
                        <div class="col-md-6">
                            <button id="btnSave" class="btn btn-primary-med w-100 text-white">
                                <i class="bi bi-check2-circle me-2"></i>Crear Usuario
                            </button>
                        </div>
                        <div class="col-md-6">
                            <a href="home.php" class="btn btn-outline-primary w-100 py-2">
                                Cancelar
                            </a>
                        </div>
                    </div>

                </form>

            </div>

        </div>
    </div>
</div>

<?php include '../../../layouts/scripts.php'; ?>

<script>
document.getElementById('formUser').addEventListener('submit', async function(e){
    e.preventDefault();

    const btn = document.getElementById('btnSave');
    btn.disabled = true;
    btn.innerHTML = "Creando...";

    const formData = new FormData(this);

    const res = await fetch('../../users/services/create_user.php', {
        method: 'POST',
        body: formData
    });

    const data = await res.json();

    if (data.success) {

        Swal.fire({
            title: 'Usuario creado',
            text: data.message,
            icon: 'success'
        }).then(() => {
            window.location.href = 'home.php';
        });

    } else {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check2-circle me-2"></i>Crear Usuario';

        Swal.fire('Error', data.message, 'error');
    }
});
</script>

</body>
</html>