<?php
// 1. Iniciamos la sesión para poder recordar al usuario en otras páginas
session_start();

// 2. Importamos la conexión (asumiendo que se llama database.php)
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizamos los datos básicos
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        die("Por favor, completa todos los campos.");
    }

    try {
        // 3. Buscamos al usuario por su email
        $stmt = $pdo->prepare("SELECT id, name, password, role FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // 4. Verificamos si el usuario existe y si la contraseña es correcta
        if ($user && password_verify($password, $user['password'])) {
            
            // 5. ¡Éxito! Guardamos datos importantes en la sesión
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            // Redirigir al panel principal o dashboard
            header("Location: ../apps/dashboard/views/home.php");
            exit();

        } else {
            // 6. Error: No especificamos si fue el correo o la clave por seguridad
            header("Location: ../login.php?error=1");
            exit();
        }

    } catch (PDOException $e) {
        error_log($e->getMessage());
        die("Error interno en el servidor.");
    }
} else {
    // Si alguien intenta entrar a este archivo sin enviar el formulario
    header("Location: ../login.php");
    exit();
}