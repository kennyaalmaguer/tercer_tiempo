<?php
session_start();
header('Content-Type: application/json');

// Habilitar logging de errores
error_reporting(E_ALL);
ini_set('display_errors', 0); // No mostrar errores al usuario
ini_set('log_errors', 1);

$response = array();

try {
    // Verificar si los archivos existen
    if (!file_exists('config/database.php')) {
        throw new Exception("Archivo de configuración no encontrado");
    }
    
    if (!file_exists('models/Usuario.php')) {
        throw new Exception("Archivo de modelo no encontrado");
    }

    // Incluir configuración y modelo
    include_once 'config/database.php';
    include_once 'models/Usuario.php';

    // Obtener conexión a la base de datos
    $database = new Database();
    $db = $database->getConnection();

    if (!$db) {
        throw new Exception("No se pudo conectar a la base de datos");
    }

    // Crear objeto usuario
    $usuario = new Usuario($db);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recoger datos del formulario
        $nombre = trim($_POST['nombre'] ?? '');
        $apellido_paterno = trim($_POST['apellido_paterno'] ?? '');
        $apellido_materno = trim($_POST['apellido_materno'] ?? '');
        $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
        $genero = $_POST['genero'] ?? '';
        $pais_nacimiento = $_POST['pais_nacimiento'] ?? '';
        $nacionalidad = $_POST['nacionalidad'] ?? '';
        $email = trim($_POST['correo'] ?? '');
        $password = $_POST['contrasena'] ?? '';
        $confirmar_password = $_POST['confirmar_contrasena'] ?? '';

        // Validaciones
        $errors = array();

        // Validar campos obligatorios
        if (empty($nombre)) $errors[] = "El nombre es obligatorio";
        if (empty($apellido_paterno)) $errors[] = "El apellido paterno es obligatorio";
        if (empty($fecha_nacimiento)) $errors[] = "La fecha de nacimiento es obligatoria";
        if (empty($genero)) $errors[] = "El género es obligatorio";
        if (empty($pais_nacimiento)) $errors[] = "El país de nacimiento es obligatorio";
        if (empty($nacionalidad)) $errors[] = "La nacionalidad es obligatoria";
        if (empty($email)) $errors[] = "El correo electrónico es obligatorio";
        if (empty($password)) $errors[] = "La contraseña es obligatoria";

        // Validar email
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "El formato del correo electrónico no es válido";
        }

        // Validar edad si la fecha está presente
        if (!empty($fecha_nacimiento)) {
            if (!Usuario::validarEdad($fecha_nacimiento)) {
                $errors[] = "Debes ser mayor de 12 años para registrarte";
            }
        }

        // Validar contraseña si está presente
        if (!empty($password)) {
            $password_errors = Usuario::validarPassword($password);
            if (!empty($password_errors)) {
                $errors = array_merge($errors, $password_errors);
            }
        }

        // Verificar que las contraseñas coincidan
        if ($password !== $confirmar_password) {
            $errors[] = "Las contraseñas no coinciden";
        }

        // Si hay errores, retornarlos
        if (!empty($errors)) {
            $response = array("success" => false, "errors" => $errors);
            echo json_encode($response);
            exit();
        }

        // Procesar foto si se subió
        $foto = null;
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            // Validar tipo de archivo
            $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
            $file_extension = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            
            if (!in_array($file_extension, $allowed_types)) {
                $errors[] = "Solo se permiten archivos JPG, PNG y GIF";
            } elseif ($_FILES['foto']['size'] > 5 * 1024 * 1024) { // 5MB
                $errors[] = "El archivo es demasiado grande (máximo 5MB)";
            } else {
                $foto = file_get_contents($_FILES['foto']['tmp_name']);
            }
        }

        // Si hay errores con la foto, retornarlos
        if (!empty($errors)) {
            $response = array("success" => false, "errors" => $errors);
            echo json_encode($response);
            exit();
        }

        // Construir nombre completo
       /* $nombre_completo = $nombre . ' ' . $apellido_paterno;
        if (!empty($apellido_materno)) {
            $nombre_completo .= ' ' . $apellido_materno;
        }*/

        // Asignar propiedades del usuario
        $usuario->nombres = $nombre;
        $usuario->apellido_paterno = $apellido_paterno;
        $usuario->apellido_materno = $apellido_materno;
        $usuario->fecha_nacimiento = $fecha_nacimiento;
        $usuario->genero = $genero;
        $usuario->pais_nacimiento = $pais_nacimiento;
        $usuario->nacionalidad = $nacionalidad;
        $usuario->email = $email;
        $usuario->password = $password;
        $usuario->foto = $foto;

        // Intentar registrar
        $result = $usuario->registrar();

        if ($result["success"]) {
            // Iniciar sesión automáticamente
            $usuario_login = new Usuario($db);
            $usuario_login->email = $email;
            $usuario_login->password = $password;
            $login_result = $usuario_login->login();
            
            if ($login_result["success"]) {
                $_SESSION['usuario'] = $login_result["usuario"];
                $response = array(
                    "success" => true, 
                    "message" => $result["message"], 
                    "redirect" => "perfil.php"
                );
            } else {
                $response = array("success" => true, "message" => $result["message"], "redirect" => "login.php");
            }
        } else {
            $response = array("success" => false, "errors" => array($result["message"]));
        }
    } else {
        $response = array("success" => false, "errors" => array("Método no permitido"));
    }
} catch (Exception $e) {
    error_log("Error general: " . $e->getMessage());
    $response = array("success" => false, "errors" => array("Error interno del servidor: " . $e->getMessage()));
}

// Asegurarse de que la respuesta sea JSON válido
echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit();
?>