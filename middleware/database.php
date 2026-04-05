<?php
$host = "localhost";
$db   = "plus_medico"; // <-- Cambia esto por el nombre que le pusiste
$user = "root";            // Usuario por defecto en XAMPP
$pass = "";                // Contraseña por defecto en XAMPP (vacía)
$charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     echo "¡Conexión exitosa a la base de datos!";
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>