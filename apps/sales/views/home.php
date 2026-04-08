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

    <dynamic-table 
        link="../../sales/services/search_sales.php"
        columns="Fecha,Total,Método,Usuario,Estado"
        keys="sale_date,total,payment_method,user_name,status"
        add="../../sales/views/create_sale.php"
        edit="../../sales/views/view_sale.php?id=">
    </dynamic-table>

    <?php
    include '../../../layouts/scripts.php';
    ?>
</body>
</html>