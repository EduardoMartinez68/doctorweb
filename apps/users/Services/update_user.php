<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';

header('Content-Type: application/json');

$clinic_id = $_SESSION['clinic_id'];

try {

    $id       = (int)($_POST['id'] ?? 0);
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = $_POST['phone'] ?? null;
    $cellphone= $_POST['cellphone'] ?? null;
    $role     = $_POST['role'] ?? 'user';
    $password = $_POST['password'] ?? null;

    if (!$id || !$name || !$email) {
        throw new Exception("Datos incompletos");
    }

    // VALIDAR EXISTENCIA
    $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ? AND clinic_id = ?");
    $stmt->execute([$id, $clinic_id]);

    if (!$stmt->fetch()) {
        throw new Exception("Usuario no encontrado");
    }

    // VALIDAR EMAIL ÚNICO
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->execute([$email, $id]);

    if ($stmt->fetch()) {
        throw new Exception("El email ya está en uso");
    }

    // 🔐 CONTROL DE ROLES
    $allowedRoles = ['user', 'doctor'];
    if ($_SESSION['user_role'] === 'admin') {
        $allowedRoles[] = 'admin';
    }

    if (!in_array($role, $allowedRoles)) {
        throw new Exception("Rol no permitido");
    }

    // UPDATE BASE
    $sql = "
        UPDATE users 
        SET name=?, email=?, phone=?, cellphone=?, role=?
    ";

    $params = [$name, $email, $phone, $cellphone, $role];

    // 🔐 PASSWORD OPCIONAL
    if (!empty($password)) {
        $sql .= ", password=?";
        $params[] = password_hash($password, PASSWORD_DEFAULT);
    }

    $sql .= " WHERE id=? AND clinic_id=?";
    $params[] = $id;
    $params[] = $clinic_id;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    echo json_encode([
        'success' => true,
        'message' => 'Usuario actualizado correctamente'
    ]);

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}