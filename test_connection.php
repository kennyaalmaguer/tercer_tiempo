<?php
// Archivo: test_connection.php
// Test completo de conexión y verificación de base de datos

// Incluir la clase Database
include_once 'config/database.php';

echo "<h2>🧪 Test de Conexión a Base de Datos</h2>";
echo "<div style='font-family: Arial; padding: 20px; border: 1px solid #ccc;'>";

try {
    // Crear instancia de Database
    $database = new Database();
    
    // 1. Test de conexión básica
    echo "<h3>1. 🔌 Test de Conexión</h3>";
    $db = $database->getConnection();
    
    if ($db) {
        echo "✅ <strong>Conexión exitosa</strong><br>";
        echo "📊 Base de datos: <strong>tercer_tiempo</strong><br>";
        echo "🖥️ Servidor: <strong>localhost</strong><br>";
        echo "👤 Usuario: <strong>root</strong><br>";
    } else {
        echo "❌ <strong>Error de conexión</strong><br>";
        exit;
    }
    
    // 2. Test de estado de conexión
    echo "<h3>2. 📡 Estado de Conexión</h3>";
    if ($database->isConnected()) {
        echo "✅ <strong>Conexión activa y funcionando</strong><br>";
    } else {
        echo "❌ <strong>Conexión inactiva</strong><br>";
    }
    
    // 3. Verificar tablas existentes
    echo "<h3>3. 🗃️ Tablas en la Base de Datos</h3>";
    $tablesQuery = "SHOW TABLES";
    $stmt = $database->query($tablesQuery);
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($tables) > 0) {
        echo "✅ <strong>Tablas encontradas (" . count($tables) . "):</strong><br>";
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>📋 {$table}</li>";
        }
        echo "</ul>";
    } else {
        echo "❌ <strong>No se encontraron tablas</strong><br>";
    }
    
    // 4. Verificar tabla 'usuarios' específicamente
    echo "<h3>4. 👥 Verificación de Tabla 'usuarios'</h3>";
    $query = "SHOW TABLES LIKE 'usuarios'";
    $stmt = $database->query($query);
    
    if ($stmt->rowCount() > 0) {
        echo "✅ <strong>Tabla 'usuarios' existe</strong><br>";
        
        // Contar usuarios
        $query = "SELECT COUNT(*) as total FROM usuarios";
        $stmt = $database->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "👥 <strong>Total de usuarios:</strong> " . $result['total'] . "<br>";
        
        // Mostrar estructura de la tabla
        $structureQuery = "DESCRIBE usuarios";
        $structureStmt = $database->query($structureQuery);
        $columns = $structureStmt->fetchAll();
        
        echo "📋 <strong>Estructura de la tabla:</strong><br>";
        echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
        echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Llave</th></tr>";
        foreach ($columns as $column) {
            echo "<tr>";
            echo "<td>{$column['Field']}</td>";
            echo "<td>{$column['Type']}</td>";
            echo "<td>{$column['Null']}</td>";
            echo "<td>{$column['Key']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
    } else {
        echo "❌ <strong>Tabla 'usuarios' NO existe</strong><br>";
        echo "💡 <em>Necesitas crear la tabla usuarios</em><br>";
    }
    
    // 5. Test de consulta personalizada
    echo "<h3>5. 🧪 Test de Consulta Personalizada</h3>";
    try {
        $testQuery = "SELECT NOW() as hora_actual, VERSION() as version_mysql";
        $testStmt = $database->query($testQuery);
        $serverInfo = $testStmt->fetch(PDO::FETCH_ASSOC);
        
        echo "🕒 <strong>Hora del servidor:</strong> " . $serverInfo['hora_actual'] . "<br>";
        echo "⚡ <strong>Versión MySQL:</strong> " . $serverInfo['version_mysql'] . "<br>";
        echo "✅ <strong>Consulta ejecutada correctamente</strong><br>";
        
    } catch (Exception $e) {
        echo "❌ <strong>Error en consulta de test:</strong> " . $e->getMessage() . "<br>";
    }
    
    // 6. Resumen final
    echo "<h3>6. 📊 Resumen del Test</h3>";
    echo "🎉 <strong>¡Todos los tests completados!</strong><br>";
    echo "✅ La base de datos está funcionando correctamente<br>";
    echo "✅ La conexión PDO está configurada apropiadamente<br>";
    echo "✅ Puedes comenzar a desarrollar tu aplicación<br>";
    
} catch (Exception $e) {
    echo "<h3>💥 Error Crítico</h3>";
    echo "❌ <strong>Error:</strong> " . $e->getMessage() . "<br>";
    echo "🔧 <strong>Solución:</strong> Verifica la configuración de la base de datos<br>";
    echo "📋 <strong>Detalles técnicos:</strong><br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "</div>";
echo "<br><hr>";
echo "<small>Test ejecutado el: " . date('Y-m-d H:i:s') . "</small>";
?>