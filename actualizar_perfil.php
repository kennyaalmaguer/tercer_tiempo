<?php
// Mostrar todos los errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Verificar sesión
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit();
}

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit();
}

$user_id = $_SESSION['user_id'];
$response = ['success' => false, 'message' => ''];

try {
    $database_file = 'config/database.php';
    if (!file_exists($database_file)) {
        throw new Exception("Archivo de base de datos no encontrado");
    }
    
    require_once $database_file;
    
    // Verificar que $pdo existe
    if (!isset($pdo)) {
        throw new Exception("Variable \$pdo no definida en database.php");
    }
    
    // Probar la conexión
    $pdo->query("SELECT 1")->execute();
    
    // Procesar datos básicos 
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido_paterno = trim($_POST['apellido_paterno'] ?? '');
    $apellido_materno = trim($_POST['apellido_materno'] ?? '');
    $birthdate = $_POST['birthdate'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $country = $_POST['country'] ?? '';
    $nationality = $_POST['nationality'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $description = trim($_POST['description'] ?? '');
    
    // Validaciones básicas
    if (empty($nombre) || empty($birthdate) || empty($gender) || empty($email)) {
        throw new Exception('Todos los campos obligatorios deben estar completos');
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('El formato del email no es válido');
    }
    
    // ✅ CORREGIDO: Usar id_usuario en lugar de id
    // Verificar si el email ya existe
    $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = ? AND id_usuario != ?");
    $stmt->execute([$email, $user_id]);
    if ($stmt->fetch()) {
        throw new Exception('El email ya está en uso por otro usuario');
    }
    
    // ✅ CORREGIDO: Construir nombre_completo con los campos separados
    $nombre_completo = $nombre;
    if (!empty($apellido_paterno)) {
        $nombre_completo .= ' ' . $apellido_paterno;
    }
    if (!empty($apellido_materno)) {
        $nombre_completo .= ' ' . $apellido_materno;
    }
    
    // ✅ CORREGIDO: Actualización con la estructura real de la tabla
    $sql = "UPDATE usuarios SET 
            nombre_completo = ?, 
            email = ?, 
            fecha_nacimiento = ?, 
            genero = ?, 
            pais_nacimiento = ?, 
            nacionalidad = ? 
            WHERE id_usuario = ?";
    
    $params = [
        $nombre_completo, 
        $email, 
        $birthdate, 
        $gender, 
        $country, 
        $nationality,
        $user_id
    ];
    
    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute($params);
    
    if ($success) {
        // Actualizar sesión
        $_SESSION['user_nombre'] = $nombre;
        $_SESSION['user_apellido_paterno'] = $apellido_paterno;
        $_SESSION['user_apellido_materno'] = $apellido_materno;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_fecha_nacimiento'] = $birthdate;
        $_SESSION['user_genero'] = $gender;
        $_SESSION['user_pais_nacimiento'] = $country;
        $_SESSION['user_nacionalidad'] = $nationality;
        $_SESSION['user_descripcion'] = $description;
        
        $response['success'] = true;
        $response['message'] = 'Perfil actualizado correctamente';
    } else {
        $errorInfo = $stmt->errorInfo();
        throw new Exception('Error en base de datos: ' . $errorInfo[2]);
    }
    
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
}

// Asegurar que la respuesta sea JSON
header('Content-Type: application/json');
echo json_encode($response);
?>