<?php
// 1. Iniciamos la sesión para poder acceder a ella y destruirla
session_start();

// 2. Limpiamos todas las variables de sesión
$_SESSION = array();

// 3. Si se desea destruir la sesión completamente, también se debe borrar la cookie de sesión.
// Nota: Esto es opcional pero recomendado por seguridad.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Finalmente, destruimos la sesión en el servidor
session_destroy();

// 5. Redirigimos al login con un mensaje de éxito (opcional)
header("Location: ../login.php?logged_out=1");
exit();