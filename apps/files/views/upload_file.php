<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PLUS</title>
    <?php
    include '../../../layouts/styles.php';
    ?>
</head>

<body>
    <?php
    include '../../../layouts/navbar.php';
    ?>


    <form id="uploadForm" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Archivo médico</label>
            <input type="file" name="file" class="form-control" required>
        </div>

        <button class="btn btn-primary">Subir archivo</button>
    </form>

    <script>
        document.getElementById('uploadForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(e.target);

            const res = await fetch('../../files/services/upload_file.php', {
                method: 'POST',
                body: formData
            });

            const data = await res.json();

            if (data.success) {
                Swal.fire('Éxito', 'Archivo subido', 'success');
                e.target.reset();
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        });
    </script>


    <?php
    include '../../../layouts/scripts.php';
    ?>
</body>

</html>