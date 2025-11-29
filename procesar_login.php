<?php
// procesar_login.php - VERSIÓN CORREGIDA
session_start();

// HEADER PRIMERO - antes de cualquier output
header('Content-Type: application/json');

try {
    // Incluir database.php
    if (!file_exists('config/database.php')) {
        throw new Exception('Archivo de configuración no encontrado');
    }
    
    include_once 'config/database.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    // Verificar datos POST
    if (!isset($_POST['email']) || !isset($_POST['password']) || empty($_POST['email']) || empty($_POST['password'])) {
        throw new Exception('Por favor, completa todos los campos');
    }
    
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Buscar usuario - CONSULTA CORREGIDA para obtener todos los datos necesarios
    $query = "SELECT id_usuario, nombres, apellido_paterno, apellido_materno, fecha_nacimiento, foto, genero, pais_nacimiento, nacionalidad, email, password, rol, activo FROM usuarios WHERE email = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (password_verify($password, $user['password'])) {
            if ($user['activo'] == 1) {
                // Configurar sesión CON TODOS LOS DATOS
                $_SESSION['user_id'] = $user['id_usuario'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['nombres'] . ' ' . $user['apellido_paterno'] . ' ' . $user['apellido_materno'];
                $_SESSION['user_role'] = $user['rol'];
                
                // Nuevos campos agregados para el perfil
                $_SESSION['user_nombre'] = $user['nombres'];
                $_SESSION['user_apellido_paterno'] = $user['apellido_paterno'];
                $_SESSION['user_apellido_materno'] = $user['apellido_materno'];
                $_SESSION['user_fecha_nacimiento'] = $user['fecha_nacimiento'];
                $_SESSION['user_genero'] = $user['genero'];
                $_SESSION['user_pais_nacimiento'] = $user['pais_nacimiento'];
                $_SESSION['user_nacionalidad'] = $user['nacionalidad'];
                $_SESSION['user_foto'] = $user['foto'] ? 'data:image/jpeg;base64,' . base64_encode($user['foto']) : 'img/profile2.jpg';
                
                // Determinar redirección
                $user_role = strtolower(trim($user['rol']));
                $is_admin = ($user_role === 'admin' || strpos($user_role, 'admin') !== false || $user['id_usuario'] == 2);
                $redirect = $is_admin ? 'admin.php' : 'perfil.php';
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Login exitoso',
                    'redirect' => $redirect
                ]);
                
            } else {
                throw new Exception('Cuenta desactivada');
            }
        } else {
            throw new Exception('Contraseña incorrecta');
        }
    } else {
        throw new Exception('Usuario no encontrado');
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>