<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">

            <h5 class="mb-3">Nuevo Servicio</h5>

            <form id="formService">

                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" class="form-control" name="name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea class="form-control" name="description"></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Precio</label>
                        <input type="number" step="0.01" class="form-control" name="price" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Duración (minutos)</label>
                        <input type="number" class="form-control" name="duration_minutes">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Guardar Servicio
                </button>

            </form>

        </div>
    </div>
</div>
<script>
    document.getElementById('formService').addEventListener('submit', async function (e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        try {

            const res = await fetch('../../services/services/create_service.php', {
                method: 'POST',
                body: formData
            });

            const data = await res.json();

            if (data.success) {

                // ✅ Notificación éxito
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                });

                // 🧹 Limpiar formulario
                form.reset();

            } else {

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message
                });

            }

        } catch (error) {

            Swal.fire({
                icon: 'error',
                title: 'Error de conexión',
                text: 'Intenta nuevamente'
            });

        }

    });
</script>