<?php
require 'database.php'; // Asegúrate de que este archivo ya existe

// Datos del administrador
$name     = "Administrador Principal";
$email    = "admin@doctorclick.com"; // Usa este para tu login
$password = "admin123";               // Esta será tu contraseña real
$role     = "admin";

// Encriptamos la contraseña con BCRYPT
$passwordHash = password_hash($password, PASSWORD_BCRYPT);

try {
    // Verificamos si ya existe para no duplicar
    $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);
    
    if ($check->rowCount() > 0) {
        header("Location: ../login.php");
        exit();
    } else {
        $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $email, $passwordHash, $role]);
        
        echo "### ¡Usuario Creado Exitosamente! ###<br>";
        echo "Email: $email <br>";
        echo "Pass: $password";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>