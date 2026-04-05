<?php
// Paso 1: Si el archivo de conexión ya existe, no permitir volver a instalar
if (file_exists('middleware/database.php')) {
    header("Location: login.php");
    exit;
}

$mensaje = "";
$tipo_alerta = "danger";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = $_POST['host'];
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    $db_name = $_POST['db_name'];

    try {
        // 1. Intentar conexión al servidor MySQL
        $pdo = new PDO("mysql:host=$host", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 2. Crear la base de datos si no existe
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
        $pdo->exec("USE `$db_name`;");

        // 3. Leer e importar el archivo SQL (Tablas)
        $sql_file = 'database.sql';
        if (file_exists($sql_file)) {
            $sql_content = file_get_contents($sql_file);

            // 1. Limpieza profunda de comentarios
            $sql_content = preg_replace('!/\*.*?\*/!s', '', $sql_content); // Bloques /* */
            $sql_content = preg_replace('/^--.*$/m', '', $sql_content);    // Líneas --

            // 2. Dividir el archivo en consultas individuales usando el ";"
            // Usamos una expresión regular para no romper si hay ; dentro de textos
            $queries = explode(';', $sql_content);

            foreach ($queries as $query) {
                $query = trim($query);
                if (!empty($query)) {
                    try {
                        $pdo->exec($query);
                    } catch (PDOException $e) {
                        // Si una consulta falla, te dirá exactamente cuál fue
                        throw new Exception("Error en la consulta: " . substr($query, 0, 50) . "... -> " . $e->getMessage());
                    }
                }
            }
        } else {
            throw new Exception("No se encontró el archivo database.sql.");
        }

        // 4. CREAR USUARIO ADMIN Y CLÍNICA INICIAL
        $adminName = "Administrador Principal";
        $adminEmail = "admin@doctorclick.com";
        $adminPass = "admin123";
        $passwordHash = password_hash($adminPass, PASSWORD_BCRYPT);
        $role = "admin";

        // Datos de la clínica (puedes recibirlos por POST o usar valores por defecto)
        $clinicName = $_POST['clinic_name'] ?? "Mi Clínica Principal";
        $clinicAddress = $_POST['clinic_address'] ?? "Dirección de la clínica";

        try {
            // Iniciamos una transacción para que si algo falla, no se cree nada a medias
            $pdo->beginTransaction();

            // A. Verificamos si el admin ya existe
            $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $check->execute([$adminEmail]);

            if ($check->rowCount() === 0) {

                // B. Insertamos el Usuario primero
                $sqlUser = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
                $stmtUser = $pdo->prepare($sqlUser);
                $stmtUser->execute([$adminName, $adminEmail, $passwordHash, $role]);

                $newUserId = $pdo->lastInsertId(); // Obtenemos el ID del admin

                // C. Insertamos la Clínica vinculándola al usuario
                $sqlClinic = "INSERT INTO clinic (name, address, user_id) VALUES (?, ?, ?)";
                $stmtClinic = $pdo->prepare($sqlClinic);
                $stmtClinic->execute([$clinicName, $clinicAddress, $newUserId]);

                $newClinicId = $pdo->lastInsertId(); // Obtenemos el ID de la clínica

                // D. Cerramos el círculo: Actualizamos al usuario con su clinic_id
                $sqlUpdateUser = "UPDATE users SET clinic_id = ? WHERE id = ?";
                $pdo->prepare($sqlUpdateUser)->execute([$newClinicId, $newUserId]);

                // Confirmamos todos los cambios
                $pdo->commit();

            } else {
                $pdo->rollBack(); // Si ya existe, no hacemos nada
            }

        } catch (Exception $e) {
            $pdo->rollBack(); // Si hay error, deshacemos los inserts
            throw new Exception("Error al crear usuario y clínica: " . $e->getMessage());
        }

        // 5. Crear el archivo database.php dinámicamente
        // Nota: He ajustado la ruta de guardado a 'middleware/database.php' como pediste
        $config_template = "<?php
        \$host = '$host';
        \$db   = '$db_name';
        \$user = '$user';
        \$pass = '$pass';
        \$charset = 'utf8mb4';

        \$dsn = \"mysql:host=\$host;dbname=\$db;charset=\$charset\";
        \$options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
             \$pdo = new PDO(\$dsn, \$user, \$pass, \$options);
        } catch (\\PDOException \$e) {
             die('Error de conexión: ' . \$e->getMessage());
        }
        ?>";

        // Asegúrate de que la carpeta 'middleware' exista
        if (!is_dir('middleware')) {
            mkdir('middleware', 0777, true);
        }

        if (file_put_contents('middleware/database.php', $config_template)) {
            $mensaje = "¡Sistema instalado y Administrador creado! Redirigiendo...";
            $tipo_alerta = "success";
            header("refresh:3;url=login.php");
        }

    } catch (Exception $e) {
        $mensaje = "Error en la instalación: " . $e->getMessage();
        $tipo_alerta = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Configuración Inicial - Doctor Click</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" href="public/img/logo-doc-blue.ico?v=1.1" type="image/x-icon">
    <style>
        body {
            background-color: #F4F5FF;
            font-family: 'Inter', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
        }

        .install-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            padding: 2rem;
            width: 100%;
            max-width: 500px;
            margin: auto;
        }

        .btn-install {
            background: #004AAD;
            color: white;
            font-weight: 600;
        }

        .btn-install:hover {
            background: #02377e;
            color: white;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="install-card">
            <h4 class="text-center mb-1" style="color: #004AAD;">Configuración del Sistema</h4>
            <div class="d-flex justify-content-center gap-2 mb-4 mt-3">
                <div id="step-indicator-1" class="rounded-pill" style="width: 30px; height: 6px; background: #004AAD;">
                </div>
                <div id="step-indicator-2" class="rounded-pill" style="width: 30px; height: 6px; background: #e2e8f0;">
                </div>
            </div>

            <?php if ($mensaje): ?>
                <div class="alert alert-<?php echo $tipo_alerta; ?> small" role="alert">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

            <form method="POST" id="installForm">

                <div id="step1">
                    <p class="text-muted text-center small mb-4">Paso 1: Información de tu consultorio</p>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nombre de la Clínica</label>
                        <input type="text" name="clinic_name" class="form-control form-control-sm"
                            placeholder="Ej. Clinica Plus" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">Dirección</label>
                        <input type="text" name="clinic_address" class="form-control form-control-sm"
                            placeholder="Calle Falsa 123, Ciudad" required>
                    </div>

                    <button type="button" class="btn btn-install w-100" onclick="nextStep()">Siguiente</button>
                </div>

                <div id="step2" style="display: none;">
                    <p class="text-muted text-center small mb-4">Paso 2: Conexión con el servidor</p>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Host</label>
                            <input type="text" name="host" class="form-control form-control-sm" value="localhost"
                                required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Nombre DB</label>
                            <input type="text" name="db_name" class="form-control form-control-sm"
                                placeholder="plus_medico" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Usuario MySQL</label>
                        <input type="text" name="user" class="form-control form-control-sm" value="root" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">Contraseña MySQL</label>
                        <input type="password" name="pass" class="form-control form-control-sm"
                            placeholder="Vacío en XAMPP">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light border btn-sm w-50"
                            onclick="prevStep()">Atrás</button>
                        <button type="submit" class="btn btn-install btn-sm w-50">Enviar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        function nextStep() {
            const name = document.querySelector('input[name="clinic_name"]').value;
            const address = document.querySelector('input[name="clinic_address"]').value;

            if (!name || !address) {
                alert("Por favor, completa los datos de la clínica.");
                return;
            }

            document.getElementById('step1').style.display = 'none';
            document.getElementById('step2').style.display = 'block';
            document.getElementById('step-indicator-2').style.background = '#004AAD';
        }

        function prevStep() {
            document.getElementById('step2').style.display = 'none';
            document.getElementById('step1').style.display = 'block';
            document.getElementById('step-indicator-2').style.background = '#e2e8f0';
        }
    </script>
</body>

</html>