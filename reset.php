<?php
include_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

echo "<h2>🔄 Restablecer Contraseña del Admin</h2>";

try {
    $new_password = "Admin123!";
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
    $query = "UPDATE usuarios SET password = ? WHERE email = 'admin@tercertiempo.com'";
    $stmt = $db->prepare($query);
    
    if ($stmt->execute([$hashed_password])) {
        echo "✅ Contraseña restablecida exitosamente<br>";
        echo "Email: <strong>admin@tercertiempo.com</strong><br>";
        echo "Nueva contraseña: <strong>Admin123!</strong><br>";
        echo "Ahora puedes usar estas credenciales para hacer login.<br>";
    } else {
        echo "❌ Error al restablecer contraseña<br>";
    }
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}
?>