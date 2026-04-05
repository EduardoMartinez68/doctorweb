<?php
//here we will check if the database.php file exist, if not exist we will redirect to the install page
if (!file_exists('middleware/database.php')) {
    header("Location: install.php");
    exit;
}
// if the file exists, continue with the login or dashboard
header("Location: login.php");
?>