<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <?php
    include '../../../layouts/styles.php';
    ?>
</head>
<body>
    <?php
    include '../../../layouts/navbar.php';
    ?>


    <dynamic-table 
        link="../../users/services/search_users.php"
        columns="Nombre,Email,Rol,Teléfono"
        keys="name,email,role,phone"
        edit="../../users/views/user_edit.php?id="
        add="../../users/views/user_create.php">
    </dynamic-table>


    <?php
    include '../../../layouts/scripts.php';
    ?>
</body>
</html>