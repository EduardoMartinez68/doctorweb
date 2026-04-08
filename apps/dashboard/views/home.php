<?php
include '../../../middleware/authentication.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <?php
    include '../../../layouts/styles.php';
    ?>
</head>

<body>
    <?php
    include '../../../layouts/navbar.php';
    ?>
    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Si no existe la sesión, lo mandamos de patitas a la calle (al login)
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    // Opcional: Verificar si es administrador para ciertas funciones
    $esAdmin = ($_SESSION['user_role'] === 'admin');
    ?>
    <?php
    include '../partials/home/dashboard.php';
    ?>


    <?php
    include '../../../layouts/scripts.php';
    ?>
</body>

</html>