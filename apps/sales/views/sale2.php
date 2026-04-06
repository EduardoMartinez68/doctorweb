<?php
include '../../../middleware/authentication.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas</title>
    <?php
    include '../../../layouts/styles.php';
    ?>
</head>
<body>
    <?php
    include '../../../layouts/navbar.php';
    ?>

    <?php
    include '../partials/sale/form.php';
    ?>

    <?php
    include '../../../layouts/scripts.php';
    ?>
    <script>
        renderTabla();
    </script>
</body>
</html>