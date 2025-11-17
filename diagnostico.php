<?php
echo "<h2>Diagnóstico del Sistema</h2>";

// 1. Verificar PHP
echo "<h3>1. Configuración PHP</h3>";
echo "PHP Version: " . phpversion() . "<br>";
echo "PDO MySQL disponible: " . (extension_loaded('pdo_mysql') ? '✅ Sí' : '❌ No') . "<br>";

// 2. Verificar archivos
echo "<h3>2. Archivos del Sistema</h3>";
$files = [
    'config/database.php',
    'models/Usuario.php',
    'procesar_registro.php'
];

foreach ($files as $file) {
    echo "$file: " . (file_exists($file) ? '✅ Existe' : '❌ No existe') . "<br>";
}

// 3. Verificar conexión a MySQL
echo "<h3>3. Conexión MySQL</h3>";
try {
    $pdo = new PDO("mysql:host=localhost", "root", "");
    echo "✅ Conexión básica a MySQL exitosa<br>";
    
    // Verificar bases de datos
    $stmt = $pdo->query("SHOW DATABASES LIKE 'tercer_tiempo'");
    $db_exists = $stmt->fetch();
    
    if ($db_exists) {
        echo "✅ Base de datos 'tercer_tiempo' existe<br>";
        
        // Verificar tabla usuarios
        $pdo_db = new PDO("mysql:host=localhost;dbname=tercer_tiempo", "root", "");
        $stmt = $pdo_db->query("SHOW TABLES LIKE 'usuarios'");
        $table_exists = $stmt->fetch();
        
        if ($table_exists) {
            echo "✅ Tabla 'usuarios' existe<br>";
            
            // Contar usuarios
            $stmt = $pdo_db->query("SELECT COUNT(*) as total FROM usuarios");
            $result = $stmt->fetch();
            echo "✅ Total usuarios: " . $result['total'] . "<br>";
        } else {
            echo "❌ Tabla 'usuarios' NO existe<br>";
        }
    } else {
        echo "❌ Base de datos 'tercer_tiempo' NO existe<br>";
    }
    
} catch (PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "<br>";
    echo "Sugerencia: Verifica que MySQL esté ejecutándose y las credenciales sean correctas.<br>";
}

// 4. Probar nuestra clase Database
echo "<h3>4. Prueba de Clase Database</h3>";
if (file_exists('config/database.php')) {
    include_once 'config/database.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    if ($db) {
        echo "✅ Clase Database funciona correctamente<br>";
    } else {
        echo "❌ Clase Database NO pudo conectar<br>";
    }
}
?>