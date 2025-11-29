<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit();
}

$user_id = $_SESSION['user_id'];
$response = ['success' => false];

try {
    // Asegúrate de que esta ruta sea correcta para tu conexión a la DB
    require 'config/database.php'; 

    if (!isset($pdo)) {
        throw new Exception("No existe conexión a la base de datos");
    }

    // Datos texto
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido_paterno = trim($_POST['apellido_paterno'] ?? '');
    $apellido_materno = trim($_POST['apellido_materno'] ?? '');
    $birthdate = $_POST['birthdate'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $country = $_POST['country'] ?? '';
    $nationality = $_POST['nationality'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $description = trim($_POST['description'] ?? '');
    
    // Contraseñas (aunque se manejarán por separado, las leemos aquí)
    $current_password = $_POST['current-password'] ?? '';
    $new_password = $_POST['new-password'] ?? '';
    
    // --- VALIDACIONES DE DATOS DE TEXTO ---

    if (!$nombre || !$birthdate || !$gender || !$email) {
        throw new Exception("Faltan datos obligatorios");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Email no válido");
    }

    // Validar email único
    $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = ? AND id_usuario != ?");
    $stmt->execute([$email, $user_id]);

    if ($stmt->fetch()) {
        throw new Exception("El email ya está en uso");
    }

    // --- MANEJO DE FOTO DE PERFIL (BLOB) ---
    $newPhotoContent = null;
    $base64_photo_for_session = null;

    if (!empty($_FILES['profile_picture']['name'])) {

        $foto = $_FILES['profile_picture'];
        
        if ($foto['error'] !== 0) {
            throw new Exception("Error al subir la foto (código: " . $foto['error'] . ")");
        }

        if ($foto['size'] > 5 * 1024 * 1024) { 
            throw new Exception("La foto es demasiado grande (máx 5MB)");
        }

        $ext = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($ext, $permitidas)) {
            throw new Exception("Formato de imagen no permitido. Solo JPG, PNG, GIF.");
        }

        // ✅ CORRECCIÓN CLAVE: LEER EL CONTENIDO BINARIO (BLOB)
        $newPhotoContent = file_get_contents($foto['tmp_name']);
        
        if ($newPhotoContent === false) {
            throw new Exception("No se pudo leer el contenido de la foto.");
        }
        
        // Obtener el tipo MIME para la codificación Base64
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $newPhotoMimeType = $finfo->buffer($newPhotoContent);

        // Actualizar la columna 'foto' (BLOB) en la BD
        $stmtFoto = $pdo->prepare("UPDATE usuarios SET foto = ? WHERE id_usuario = ?");
        // Usar PDO::PARAM_LOB es vital para BLOB
        $stmtFoto->bindParam(1, $newPhotoContent, PDO::PARAM_LOB); 
        $stmtFoto->bindParam(2, $user_id);
        $stmtFoto->execute();
        
        // Generar Base64 para actualizar la sesión y la interfaz
        $base64_photo_for_session = "data:{$newPhotoMimeType};base64," . base64_encode($newPhotoContent);
        
        // Actualizar sesión
        $_SESSION['user_foto'] = $base64_photo_for_session;
        $response['new_photo'] = $base64_photo_for_session;
    }
    
    // --- MANEJO DE CONTRASEÑA ---
    
    $password_update_sql = "";
    $password_bindings = [];
    
    if (!empty($new_password)) {
        // 1. Verificar que la contraseña actual sea correcta (necesitas obtener el hash)
        $stmt_hash = $pdo->prepare("SELECT password FROM usuarios WHERE id_usuario = ?");
        $stmt_hash->execute([$user_id]);
        $user_hash = $stmt_hash->fetchColumn();
        
        if (!$user_hash || !password_verify($current_password, $user_hash)) {
             throw new Exception("La contraseña actual es incorrecta.");
        }

        // 2. Validar la nueva contraseña (usando la clase Usuario si existe)
        // require 'clases/Usuario.php'; // Asume que tienes este archivo para la validación
        // $validation_errors = Usuario::validarPassword($new_password);
        // if (!empty($validation_errors)) {
        //      throw new Exception("Errores en la nueva contraseña: " . implode(", ", $validation_errors));
        // }
        
        // 3. Hashear y añadir a la actualización
        $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
        $password_update_sql = ", password = ?";
        $password_bindings[] = $hashed_new_password;
    }


    // --- ACTUALIZACIÓN PRINCIPAL (DATOS DE TEXTO + CONTRASEÑA) ---
    
    // ✅ CORREGIDO: Consulta principal SIN la columna 'foto', ya que se maneja separadamente
    $sql = "UPDATE usuarios SET 
                nombres = ?, 
                apellido_paterno = ?, 
                apellido_materno = ?, 
                email = ?, 
                fecha_nacimiento = ?, 
                genero = ?, 
                pais_nacimiento = ?, 
                nacionalidad = ?,
                descripcion = ?
                {$password_update_sql}
            WHERE id_usuario = ?";

    $bindings = [
        $nombre,
        $apellido_paterno,
        $apellido_materno,
        $email,
        $birthdate,
        $gender,
        $country,
        $nationality,
        $description
    ];
    
    // Añadir el nuevo hash de contraseña si existe
    $bindings = array_merge($bindings, $password_bindings);
    
    // Añadir el ID del usuario como el último binding
    $bindings[] = $user_id;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($bindings);

    // --- ACTUALIZAR SESIÓN (DATOS DE TEXTO) ---
    
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
    $response['message'] = "Perfil actualizado exitosamente";


} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>