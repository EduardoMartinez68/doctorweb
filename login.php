<?php
//we will see if the user have a session in this page, if have a session we will redirect to the dashboard
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: apps/dashboard/views/home.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Profesional - Gestión Médica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="public/img/logo-doc-blue.ico?v=1.1" type="image/x-icon">
    <style>
        :root {
            --med-primary: #004AAD;
            --med-primary-hover: #02377e;
            --text-color-title: #38B6FF;
            --med-bg: #F4F5FF;
            --med-text: #334155;
            --med-border: #e2e8f0;
            --doctor-brand-color: #004AAD;
        }

        body {
            background-color: var(--med-bg);
            font-family: 'Inter', sans-serif;
            color: var(--med-text);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: #ffffff;
            border: 1px solid var(--med-border);
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 74, 173, 0.05);
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
        }

        .brand-logo {
            color: var(--med-primary);
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .login-title {
            color: var(--text-color-title);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-label {
            font-weight: 500;
            font-size: 0.85rem;
            color: var(--med-text);
        }

        .form-control {
            border: 1.5px solid var(--med-border);
            padding: 0.75rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--text-color-title);
            box-shadow: 0 0 0 4px rgba(56, 182, 255, 0.1);
        }

        .btn-med-primary {
            background-color: var(--med-primary);
            border: none;
            color: white;
            padding: 0.8rem;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            transition: background 0.3s ease;
        }

        .btn-med-primary:hover {
            background-color: var(--med-primary-hover);
            color: white;
        }

        .footer-text {
            font-size: 0.8rem;
            text-align: center;
            margin-top: 1.5rem;
            color: #94a3b8;
        }
    </style>
</head>
<body>

<div class="container px-4">
    <div class="login-card mx-auto">
        <div class="brand-logo">
            <span>Doctor Click</span>
        </div>
        <p class="login-title">Panel de Control Médico</p>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger d-flex align-items-center" role="alert" 
                 style="font-size: 0.85rem; border-radius: 8px; margin-bottom: 1.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle-fill me-2" viewBox="0 0 16 16">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </svg>
                <div>
                    Correo o contraseña incorrectos.
                </div>
            </div>
        <?php endif; ?>

        <form action="middleware/auth.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="nombre@consultorio.com" required>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
            </div>
            
            <button type="submit" class="btn btn-med-primary">
                Iniciar Sesión
            </button>
        </form>

        <div class="footer-text">
            &copy; 2026 Sistema de Gestión Médica. <br> Acceso restringido a personal autorizado.
        </div>
    </div>
</div>

</body>
</html>