<div class="container mt-4">
    <h4>Nuevo Paciente</h4>

    <form id="patientForm">
        <div class="row">
            <div class="col-md-6 mb-2">
                <input type="text" name="key_id" class="form-control" placeholder="Key ID" required>
            </div>

            <div class="col-md-6 mb-2">
                <input type="text" name="name" class="form-control" placeholder="Nombre" required>
            </div>

            <div class="col-md-6 mb-2">
                <input type="text" name="phone" class="form-control" placeholder="Teléfono">
            </div>

            <div class="col-md-6 mb-2">
                <input type="text" name="cellphone" class="form-control" placeholder="Celular">
            </div>

            <div class="col-md-6 mb-2">
                <input type="email" name="email" class="form-control" placeholder="Email">
            </div>

            <div class="col-md-6 mb-2">
                <input type="date" name="birth_date" class="form-control">
            </div>

            <div class="col-md-12 mb-2">
                <input type="text" name="address" class="form-control" placeholder="Dirección">
            </div>

            <div class="col-md-12 mb-2">
                <textarea name="notes" class="form-control" placeholder="Notas"></textarea>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-2">Guardar</button>
    </form>
</div>

<script>
document.getElementById('patientForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    try {
        const res = await fetch('../../patients/services/create_patient.php', {
            method: 'POST',
            body: formData
        });

        const data = await res.json();

        if (data.success) {
            // ✅ LIMPIAR FORMULARIO
            form.reset();

            // 🔔 NOTIFICACIÓN
            Swal.fire({
                icon: 'success',
                title: 'Paciente creado',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            });

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
            title: 'Error',
            text: 'Error de conexión'
        });
    }
});
</script>