<?php
// test_login_vacio.php - FORMULARIO VACÍO PARA PROBAR
session_start();
include_once 'config/database.php';

// Si ya está logueado, redirigir
if (isset($_SESSION['user_id'])) {
    $redirect = ($_SESSION['user_role'] == 'admin' || $_SESSION['user_id'] == 2) ? 'admin.php' : 'perfil.php';
    header('Location: ' . $redirect);
    exit;
}

// Procesar login si se envió el formulario
$error = '';
if ($_POST && !empty($_POST['email']) && !empty($_POST['password'])) {
    $database = new Database();
    $db = $database->getConnection();
    
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    try {
        // QUERY SIN CAMPO ACTIVO
        $query = "SELECT id_usuario, nombre_completo, email, password, rol FROM usuarios WHERE email = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (password_verify($password, $user['password'])) {
                // Configurar sesión (SIN VALIDAR ACTIVO)
                $_SESSION['user_id'] = $user['id_usuario'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['nombre_completo'];
                $_SESSION['user_role'] = $user['rol'];
                
                // Redirigir según el rol
                $user_role = strtolower(trim($user['rol']));
                $is_admin = ($user_role === 'admin' || strpos($user_role, 'admin') !== false || $user['id_usuario'] == 2);
                
                $redirect = $is_admin ? 'admin.php' : 'perfil.php';
                
                header('Location: ' . $redirect);
                exit;
                
            } else {
                $error = "❌ Contraseña incorrecta";
            }
        } else {
            $error = "❌ Usuario no encontrado";
        }
    } catch (PDOException $e) {
        $error = "❌ Error del sistema: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Login Vacío - Tercer Tiempo</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            max-width: 400px; 
            margin: 50px auto; 
            padding: 20px; 
            background: #f5f5f5; 
        }
        .login-box { 
            background: white; 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.1); 
        }
        h2 { 
            text-align: center; 
            color: #333; 
            margin-bottom: 10px; 
        }
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }
        .form-group { 
            margin-bottom: 20px; 
        }
        label { 
            display: block; 
            margin-bottom: 5px; 
            font-weight: bold; 
            color: #555; 
        }
        input[type="email"], 
        input[type="password"] { 
            width: 100%; 
            padding: 12px; 
            border: 1px solid #ddd; 
            border-radius: 5px; 
            box-sizing: border-box; 
            font-size: 16px;
        }
        button { 
            width: 100%; 
            padding: 12px; 
            background: #007bff; 
            color: white; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            font-size: 16px; 
            margin-top: 10px;
        }
        button:hover { 
            background: #0056b3; 
        }
        .error { 
            background: #ffebee; 
            color: #c62828; 
            padding: 12px; 
            border-radius: 5px; 
            margin-bottom: 20px; 
            border: 1px solid #ffcdd2; 
        }
        .success { 
            background: #e8f5e8; 
            color: #2e7d32; 
            padding: 12px; 
            border-radius: 5px; 
            margin-bottom: 20px; 
            border: 1px solid #c8e6c9; 
        }
        .debug-info {
            background: #fff3e0;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 14px;
        }
        .credential-hint {
            background: #e3f2fd;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>🔐 TEST LOGIN VACÍO</h2>
        <div class="subtitle">Tercer Tiempo - Llena los datos manualmente</div>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (isset($_GET['logout'])): ?>
            <div class="success">✅ Sesión cerrada correctamente</div>
        <?php endif; ?>

        <div class="credential-hint">
            💡 <strong>Hint:</strong> admin@tercertiempo.com / admin123
        </div>

        <form method="POST">
            <div class="form-group">
                <label for="email">📧 Correo Electrónico:</label>
                <input type="email" id="email" name="email" placeholder="Ingresa tu email" required>
            </div>
            
            <div class="form-group">
                <label for="password">🔑 Contraseña:</label>
                <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña" required>
            </div>
            
            <button type="submit">Iniciar Sesión</button>
        </form>

        <div class="debug-info">
            <strong>🔍 Información de Debug:</strong><br>
            <?php
            echo "User ID: " . ($_SESSION['user_id'] ?? 'No logueado') . "<br>";
            echo "Email: " . ($_SESSION['user_email'] ?? 'No logueado') . "<br>";
            echo "Rol: " . ($_SESSION['user_role'] ?? 'No logueado') . "<br>";
            echo "Query: SELECT id_usuario, nombre_completo, email, password, rol FROM usuarios...<br>";
            echo "Validación activo: <strong>DESACTIVADA</strong>";
            ?>
        </div>

        <?php if (isset($_SESSION['user_id'])): ?>
            <div style="text-align: center; margin-top: 20px;">
                <a href="test_login_vacio.php?logout=1" style="color: #007bff; text-decoration: none;">🚪 Cerrar Sesión</a> | 
                <a href="<?php echo ($_SESSION['user_role'] == 'admin' || $_SESSION['user_id'] == 2 ? 'admin.php' : 'perfil.php'); ?>" style="color: #007bff; text-decoration: none;">⚙️ Ir al Panel</a>
            </div>
        <?php endif; ?>
    </div>

    <?php
    // Procesar logout
    if (isset($_GET['logout'])) {
        session_destroy();
        header('Location: test_login_vacio.php');
        exit;
    }
    ?>
</body>
</html>