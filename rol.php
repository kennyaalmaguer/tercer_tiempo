<?php
include_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

echo "<h2>Verificando Rol del Admin</h2>";

try {
    $query = "SELECT id_usuario, nombre_completo, email, rol FROM usuarios WHERE email = 'admin@tercertiempo.com'";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Rol actual: '" . $admin['rol'] . "'<br>";
    echo "Tipo de dato: " . gettype($admin['rol']) . "<br>";
    echo "Longitud: " . strlen($admin['rol']) . "<br>";
    
    // Probando diferentes comparaciones
    echo "<h3>Comparaciones:</h3>";
    echo "== 'admin': " . ($admin['rol'] == 'admin' ? '✅ TRUE' : '❌ FALSE') . "<br>";
    echo "== 'Administrador': " . ($admin['rol'] == 'Administrador' ? '✅ TRUE' : '❌ FALSE') . "<br>";
    echo "== 'administrador': " . ($admin['rol'] == 'administrador' ? '✅ TRUE' : '❌ FALSE') . "<br>";
    
    // Buscar cualquier variante de admin
    echo "stripos 'admin': " . (stripos($admin['rol'], 'admin') !== false ? '✅ ENCONTRADO' : '❌ NO ENCONTRADO') . "<br>";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>