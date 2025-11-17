<?php
class Database {
    private $host = "127.0.0.1:3307";
    private $db_name = 'tercer_tiempo';
    private $username = 'root';
    private $password = '';
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            // Primero intentar conectar sin especificar base de datos
            $this->conn = new PDO("mysql:host=" . $this->host, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Verificar si la base de datos existe, si no, crearla
            $this->createDatabaseIfNotExists();
            
            // Ahora conectar a la base de datos específica
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch(PDOException $exception) {
            // Si hay error, intentar crear la base de datos
            try {
                $this->conn = new PDO("mysql:host=" . $this->host, $this->username, $this->password);
                $this->createDatabaseAndTables();
                
                // Reconectar a la nueva base de datos
                $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
                $this->conn->exec("set names utf8");
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
            } catch(PDOException $e) {
                error_log("Error crítico de conexión: " . $e->getMessage());
                return null;
            }
        }
        return $this->conn;
    }

    private function createDatabaseIfNotExists() {
        $check_db = $this->conn->prepare("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?");
        $check_db->execute([$this->db_name]);
        
        if ($check_db->rowCount() == 0) {
            $this->createDatabaseAndTables();
        }
    }

    private function createDatabaseAndTables() {
        // Crear base de datos
        $this->conn->exec("CREATE DATABASE IF NOT EXISTS " . $this->db_name . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->conn->exec("USE " . $this->db_name);
        
        // Crear tabla de usuarios
        $this->conn->exec("
            CREATE TABLE IF NOT EXISTS usuarios (
                id_usuario INT AUTO_INCREMENT PRIMARY KEY,
                nombre_completo VARCHAR(100) NOT NULL,
                fecha_nacimiento DATE NOT NULL,
                foto LONGBLOB NULL,
                genero ENUM('masculino', 'femenino', 'otro') NOT NULL,
                pais_nacimiento VARCHAR(50) NOT NULL,
                nacionalidad VARCHAR(50) NOT NULL,
                email VARCHAR(100) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                rol ENUM('admin', 'usuario') DEFAULT 'usuario',
                fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
                activo BOOLEAN DEFAULT TRUE
            )
        ");

        // Insertar usuario admin por defecto si no existe
        $check_admin = $this->conn->prepare("SELECT id_usuario FROM usuarios WHERE email = 'admin@tercertiempo.com'");
        $check_admin->execute();
        
        if ($check_admin->rowCount() == 0) {
            $hashed_password = password_hash('Admin123!', PASSWORD_DEFAULT);
            $insert_admin = $this->conn->prepare("
                INSERT INTO usuarios (nombre_completo, fecha_nacimiento, genero, pais_nacimiento, nacionalidad, email, password, rol) 
                VALUES ('Administrador', '1990-01-01', 'masculino', 'mx', 'mx', 'admin@tercertiempo.com', ?, 'admin')
            ");
            $insert_admin->execute([$hashed_password]);
        }
    }
}
?>