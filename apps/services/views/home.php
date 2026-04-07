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

    <div class="container">
        <br>
        <dynamic-table 
            link="../../services/services/search_services.php"
            columns="Nombre,Descripcion,Precio,Favorito"
            keys="name,description,price,favorite"
            edit="../../services/views/update_services.php?id="
            add="../../services/views/add_service.php"
            >
        </dynamic-table>
    </div>


    <?php
    include '../../../layouts/scripts.php';
    ?>
</body>
</html>