<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];

try {

    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $phone    = $_POST['phone'] ?? null;
    $cellphone= $_POST['cellphone'] ?? null;
    $role     = $_POST['role'] ?? 'user';

    // VALIDACIONES
    if (!$name || !$email || !$password) {
        throw new Exception("Nombre, email y contraseña son obligatorios");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Email inválido");
    }

    // 🔥 VALIDAR EMAIL ÚNICO
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        throw new Exception("El email ya está registrado");
    }

    // 🔐 HASH PASSWORD
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // INSERT
    $stmt = $pdo->prepare("
        INSERT INTO users 
        (name, email, password, phone, cellphone, role, clinic_id)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $name,
        $email,
        $hashedPassword,
        $phone,
        $cellphone,
        $role,
        $clinic_id
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Usuario creado correctamente'
    ]);

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}