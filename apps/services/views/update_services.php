<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios</title>
    <?php
    include '../../../layouts/styles.php';
    ?>
</head>
<body>
    <?php
    include '../../../layouts/navbar.php';
    ?>

    <?php
    include '../partials/update_services/form_update.php';
    ?>

    <?php
    include '../../../layouts/scripts.php';
    ?>
</body>
</html>