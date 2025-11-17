<?php
// procesar_login.php - Versión mejorada
session_start();
include_once 'config/database.php';

if ($_POST) {
    $database = new Database();
    $db = $database->getConnection();
    
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    try {
        $query = "SELECT id_usuario, nombre_completo, email, password, rol, activo FROM usuarios WHERE email = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (password_verify($password, $user['password'])) {
                if ($user['activo'] == 1) {
                    $_SESSION['user_id'] = $user['id_usuario'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_name'] = $user['nombre_completo'];
                    $_SESSION['user_role'] = $user['rol'];
                    
                    // Redirigir según el rol
                    if ($user['rol'] == 'admin') {
                        echo json_encode([
                            'success' => true,
                            'message' => 'Login exitoso',
                            'redirect' => 'admin.php'
                        ]);
                    } else {
                        echo json_encode([
                            'success' => true,
                            'message' => 'Login exitoso',
                            'redirect' => 'perfil.php'
                        ]);
                    }
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Cuenta desactivada'
                    ]);
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Contraseña incorrecta'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error del sistema: ' . $e->getMessage()
        ]);
    }
}
?>