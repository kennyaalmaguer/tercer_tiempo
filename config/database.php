<?php
// Archivo: config/database.php - VERSIÓN CORREGIDA

class Database {
    private $host = 'localhost';
    private $db = 'tercer_tiempo'; 
    private $username = 'root';        
    private $password = '';            
    private $charset = 'utf8mb4';     
    private $pdo = null;
    
    public function connect() {
        if ($this->pdo === null) {
            try {
                $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                    PDO::ATTR_PERSISTENT         => true 
                ];
                
                $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
                
            } catch (\PDOException $e) {
                throw new \PDOException("Error de conexión a la BD: " . $e->getMessage(), (int)$e->getCode());
            }
        }
        return $this->pdo;
    }
    
    public function getConnection() {
        return $this->connect();
    }
    
    public function query($sql, $params = []) {
        try {
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (\PDOException $e) {
            throw $e;
        }
    }
    
    public function isConnected() {
        try {
            $this->getConnection()->query('SELECT 1');
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }
    
    public function close() {
        $this->pdo = null;
    }
}

// ✅ Crear la instancia global de PDO
try {
    $database = new Database();
    $pdo = $database->getConnection();
} catch (Exception $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>