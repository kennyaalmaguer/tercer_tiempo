<?php
include_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

echo "<h2>👥 Usuario en la Base de Datos</h2>";

try {
    $query = "SELECT id_usuario, nombre_completo, email, password, rol, activo, genero FROM usuarios";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Activo</th><th>Género</th></tr>";
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row['id_usuario'] . "</td>";
        echo "<td>" . $row['nombre_completo'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['rol'] . "</td>";
        echo "<td>" . ($row['activo'] ? 'Sí' : 'No') . "</td>";
        echo "<td>" . $row['genero'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>