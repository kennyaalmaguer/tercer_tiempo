<?php
// Test de conexión a la base de datos
include_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

if ($db) {
    echo "✅ Conexión a la base de datos exitosa<br>";
    
    // Verificar si la tabla existe
    $query = "SHOW TABLES LIKE 'usuarios'";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        echo "✅ Tabla 'usuarios' existe<br>";
        
        // Contar usuarios
        $query = "SELECT COUNT(*) as total FROM usuarios";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "✅ Total de usuarios: " . $result['total'] . "<br>";
    } else {
        echo "❌ Tabla 'usuarios' NO existe<br>";
    }
} else {
    echo "❌ Error de conexión a la base de datos<br>";
}
?>