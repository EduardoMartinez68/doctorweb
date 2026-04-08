<?php
session_start();
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        die("Por favor, completa todos los campos.");
    }

    try {

        // 🔍 USUARIO
        $stmt = $pdo->prepare("
            SELECT id, name, password, role 
            FROM users 
            WHERE email = ? 
            LIMIT 1
        ");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {

            // 🔍 CLÍNICA
            $stmt = $pdo->prepare("
                SELECT id, name, logo, currency, timezone 
                FROM clinic 
                WHERE user_id = ? 
                LIMIT 1
            ");
            $stmt->execute([$user['id']]);
            $clinic = $stmt->fetch();

            if (!$clinic) {
                throw new Exception("El usuario no tiene clínica asociada.");
            }

            // ✅ SESIÓN USUARIO
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            // ✅ SESIÓN CLÍNICA
            $_SESSION['clinic_id']   = $clinic['id'];
            $_SESSION['clinic_name'] = $clinic['name'];
            $_SESSION['clinic_logo'] = $clinic['logo'];
            $_SESSION['currency']    = $clinic['currency'];
            $_SESSION['timezone']    = $clinic['timezone'];

            header("Location: ../apps/dashboard/views/home.php");
            exit();

        } else {
            header("Location: ../login.php?error=1");
            exit();
        }

    } catch (Exception $e) {
        error_log($e->getMessage());
        die("Error interno en el servidor.");
    }

} else {
    header("Location: ../login.php");
    exit();
}