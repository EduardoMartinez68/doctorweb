<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <?php include '../../../layouts/styles.php'; ?>
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

        .form-control,
        .form-select {
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

</head>

<body>

    <?php include '../../../layouts/navbar.php'; ?>

    <div class=" mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">

                <div class="user-card p-4 p-md-5">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold m-0">
                            <i class="bi bi-person-plus me-2"></i>Actualizar Usuario
                        </h4>
                    </div>

                    <form id="formUser">
                        <input type="hidden" name="id" id="user_id">
                        <!-- NOMBRE -->
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="name" class="form-control" placeholder="Ej. Dr. Juan Pérez" id="name"
                                required>
                        </div>

                        <!-- EMAIL -->
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="correo@ejemplo.com" id="email"
                                required>
                        </div>

                        <!-- PASSWORD -->
                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input class="form-control mb-3" name="password" placeholder="Nueva contraseña (opcional)">
                        </div>

                        <!-- TELÉFONO -->
                        <div class="mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="phone" class="form-control" placeholder="Opcional" id="phone">
                        </div>

                        <!-- CELULAR -->
                        <div class="mb-3">
                            <label class="form-label">Celular</label>
                            <input type="text" name="cellphone" class="form-control" placeholder="Opcional" id="cellphone">
                        </div>

                        <!-- ROL -->
                        <div class="mb-4">
                            <label class="form-label">Rol</label>
                            <select name="role" class="form-select" id="role">
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
                                <button class="btn btn-primary-med w-100 text-white" id="btnSave">
                                    <i class="bi bi-save2 me-2"></i> Actualizar Datos
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-light w-100 py-2 btn-status border"
                                    id="btnStatusAction">
                                </button>
                            </div>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>

    <?php include '../../../layouts/scripts.php'; ?>

    <script>
        const id = new URLSearchParams(window.location.search).get('id');

        function setupStatusButton(user) {
            const btn = document.getElementById('btnStatusAction');
            if (!btn) return;

            if (user.status === 'active') {
                btn.className = 'btn btn-outline-danger w-100 py-2 btn-status';
                btn.innerHTML = '<i class="bi bi-person-x me-2"></i> Desactivar Cuenta';
                btn.onclick = () => changeStatus(user.id, 'deactivate');
            } else {
                btn.className = 'btn btn-outline-success w-100 py-2 btn-status';
                btn.innerHTML = '<i class="bi bi-person-check me-2"></i> Activar Cuenta';
                btn.onclick = () => changeStatus(user.id, 'activate');
            }
        }

        async function loadUser() {
            try {
                const res = await fetch(`../../users/services/get_user.php?id=${id}`);
                const data = await res.json();

                if (!data.success) {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#212529' });
                    return;
                }

                const u = data.data;
                setupStatusButton(u);
                document.getElementById('user_id').value = u.id;
                document.getElementById('name').value = u.name;
                document.getElementById('email').value = u.email;
                document.getElementById('phone').value = u.phone || '';
                document.getElementById('cellphone').value = u.cellphone || '';
                document.getElementById('role').value = u.role;
            } catch (e) {
                console.error("Error cargando usuario:", e);
            }
        }

        document.getElementById('formUser').addEventListener('submit', async function (e) {
            e.preventDefault();

            const btn = document.getElementById('btnSave');
            const originalText = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Procesando...';

            const formData = new FormData(this);

            try {
                const res = await fetch('../../users/services/update_user.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();

                if (data.success) {
                    Swal.fire({ icon: 'success', title: 'Actualizado', text: data.message, confirmButtonColor: '#212529' });
                    btn.innerHTML = '<i class="bi bi-check-lg me-2"></i> ¡Listo!';
                    setTimeout(() => {
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    }, 2000);
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                btn.disabled = false;
                btn.innerHTML = originalText;
                Swal.fire({ icon: 'error', title: 'Error', text: error.message, confirmButtonColor: '#212529' });
            }
        });

        async function changeStatus(id, action) {
            const confirm = await Swal.fire({
                title: action === 'activate' ? '¿Activar usuario?' : '¿Desactivar usuario?',
                text: "Podrás cambiar esta configuración después.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: action === 'activate' ? '#198754' : '#dc3545',
                cancelButtonColor: '#6e7881',
                confirmButtonText: 'Sí, continuar',
                cancelButtonText: 'Cancelar'
            });

            if (!confirm.isConfirmed) return;

            const formData = new FormData();
            formData.append('id', id);

            const url = action === 'activate'
                ? '../../users/services/activate_user.php'
                : '../../users/services/deactivate_user.php';

            const res = await fetch(url, { method: 'POST', body: formData });
            const data = await res.json();

            if (data.success) {
                Swal.fire({ icon: 'success', title: 'Realizado', showConfirmButton: false, timer: 1500 })
                    .then(() => location.reload());
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        }

        document.addEventListener('DOMContentLoaded', loadUser);
    </script>

</body>

</html>