<?php
include_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

echo "<h2>Crear/Resetear Usuario Admin</h2>";

try {
    // Verificar si ya existe
    $check_query = "SELECT id_usuario FROM usuarios WHERE email = 'admin@tercertiempo.com'";
    $stmt = $db->prepare($check_query);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        // Actualizar existente
        $hashed_password = password_hash('Admin123!', PASSWORD_DEFAULT);
        $update_query = "UPDATE usuarios SET password = ?, rol = 'admin', activo = 1 WHERE email = 'admin@tercertiempo.com'";
        $stmt = $db->prepare($update_query);
        
        if ($stmt->execute([$hashed_password])) {
            echo "✅ Usuario admin actualizado exitosamente<br>";
        }
    } else {
        // Crear nuevo
        $hashed_password = password_hash('Admin123!', PASSWORD_DEFAULT);
        $insert_query = "INSERT INTO usuarios (nombre_completo, fecha_nacimiento, genero, pais_nacimiento, nacionalidad, email, password, rol, activo) 
                        VALUES ('Administrador Principal', '1990-01-01', 'masculino', 'mx', 'mx', 'admin@tercertiempo.com', ?, 'admin', 1)";
        
        $stmt = $db->prepare($insert_query);
        
        if ($stmt->execute([$hashed_password])) {
            echo "✅ Usuario admin creado exitosamente<br>";
        }
    }
    
    echo "<strong>Credenciales:</strong><br>";
    echo "Email: admin@tercertiempo.com<br>";
    echo "Contraseña: Admin123!<br>";
    echo "Rol: admin<br>";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

echo '<br><a href="login.php">Ir al Login</a> | <a href="verificar_admin.php">Verificar Admin</a>';
?>