<?php
// Verificar el usuario admin en la base de datos
include_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

echo "<h2>Verificación del Usuario Admin</h2>";

try {
    // Verificar si existe el usuario admin
    $query = "SELECT id_usuario, nombre_completo, email, password, rol, activo FROM usuarios WHERE email = 'admin@tercertiempo.com'";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "✅ Usuario admin encontrado:<br>";
        echo "ID: " . $admin['id_usuario'] . "<br>";
        echo "Nombre: " . $admin['nombre_completo'] . "<br>";
        echo "Email: " . $admin['email'] . "<br>";
        echo "Rol: " . $admin['rol'] . "<br>";
        echo "Activo: " . ($admin['activo'] ? 'Sí' : 'No') . "<br>";
        
        // Verificar la contraseña
        $password_input = 'Admin123!';
        if (password_verify($password_input, $admin['password'])) {
            echo "✅ Contraseña correcta para 'Admin123!'<br>";
        } else {
            echo "❌ Contraseña INCORRECTA para 'Admin123!'<br>";
            echo "Hash almacenado: " . $admin['password'] . "<br>";
        }
        
    } else {
        echo "❌ Usuario admin NO encontrado<br>";
        
        // Crear el usuario admin
        echo "<h3>Creando usuario admin...</h3>";
        $hashed_password = password_hash('Admin123!', PASSWORD_DEFAULT);
        $query = "INSERT INTO usuarios (nombre_completo, fecha_nacimiento, genero, pais_nacimiento, nacionalidad, email, password, rol) 
                 VALUES ('Administrador', '1990-01-01', 'masculino', 'mx', 'mx', 'admin@tercertiempo.com', ?, 'admin')";
        $stmt = $db->prepare($query);
        
        if ($stmt->execute([$hashed_password])) {
            echo "✅ Usuario admin creado exitosamente<br>";
            echo "Email: admin@tercertiempo.com<br>";
            echo "Contraseña: Admin123!<br>";
        } else {
            echo "❌ Error al crear usuario admin<br>";
        }
    }
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

// Listar todos los usuarios
echo "<h3>Todos los usuarios en la base de datos:</h3>";
try {
    $query = "SELECT id_usuario, nombre_completo, email, rol, activo FROM usuarios ORDER BY id_usuario";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Activo</th></tr>";
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row['id_usuario'] . "</td>";
        echo "<td>" . $row['nombre_completo'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['rol'] . "</td>";
        echo "<td>" . ($row['activo'] ? 'Sí' : 'No') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (PDOException $e) {
    echo "Error al listar usuarios: " . $e->getMessage() . "<br>";
}
?>