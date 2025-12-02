<?php
// logout_admin.php
session_start();

// NO verificar el rol aquí - permitir logout incluso si la sesión está expirada o corrupta
// Destruir todas las variables de sesión específicas
$session_vars = [
    'user_id', 'user_nombre', 'user_apellido_paterno', 
    'user_apellido_materno', 'user_email', 'user_rol', 
    'user_activo', 'loggedin'
];

foreach ($session_vars as $var) {
    if (isset($_SESSION[$var])) {
        unset($_SESSION[$var]);
    }
}

// Destruir la sesión completamente
if (session_status() === PHP_SESSION_ACTIVE) {
    session_destroy();
}

// También destruir la cookie de sesión
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 3600, 
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redirigir al login
header('Location: login.php');
exit();
?>