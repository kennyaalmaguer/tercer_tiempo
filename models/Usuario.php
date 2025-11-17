<?php
class Usuario {
    private $conn;
    private $table_name = "usuarios";

    public $id_usuario;
    public $nombre_completo;
    public $fecha_nacimiento;
    public $foto;
    public $genero;
    public $pais_nacimiento;
    public $nacionalidad;
    public $email;
    public $password;
    public $rol;
    public $fecha_registro;
    public $activo;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Registrar nuevo usuario
    public function registrar() {
        try {
            // Verificar si el email ya existe
            $query = "SELECT id_usuario FROM " . $this->table_name . " WHERE email = ? LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return array("success" => false, "message" => "El email ya está registrado");
            }

            // Hash de la contraseña
            $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);

            // Insertar usuario
            $query = "INSERT INTO " . $this->table_name . " 
                     (nombre_completo, fecha_nacimiento, genero, pais_nacimiento, nacionalidad, email, password, foto) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(1, $this->nombre_completo);
            $stmt->bindParam(2, $this->fecha_nacimiento);
            $stmt->bindParam(3, $this->genero);
            $stmt->bindParam(4, $this->pais_nacimiento);
            $stmt->bindParam(5, $this->nacionalidad);
            $stmt->bindParam(6, $this->email);
            $stmt->bindParam(7, $hashed_password);
            
            // Manejar la foto (puede ser null)
            if ($this->foto) {
                $stmt->bindParam(8, $this->foto, PDO::PARAM_LOB);
            } else {
                $null = null;
                $stmt->bindParam(8, $null, PDO::PARAM_NULL);
            }

            if ($stmt->execute()) {
                $this->id_usuario = $this->conn->lastInsertId();
                return array("success" => true, "message" => "Usuario registrado correctamente");
            } else {
                return array("success" => false, "message" => "Error al ejecutar la consulta");
            }
        } catch (PDOException $exception) {
            error_log("Error en registro: " . $exception->getMessage());
            return array("success" => false, "message" => "Error de base de datos: " . $exception->getMessage());
        }
    }

    // Login de usuario
    public function login() {
        try {
            $query = "SELECT id_usuario, nombre_completo, email, password, rol, foto, activo 
                      FROM " . $this->table_name . " 
                      WHERE email = ? AND activo = 1 LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->email);
            $stmt->execute();

            if ($stmt->rowCount() == 1) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Verificar contraseña
                if (password_verify($this->password, $row['password'])) {
                    $this->id_usuario = $row['id_usuario'];
                    $this->nombre_completo = $row['nombre_completo'];
                    $this->email = $row['email'];
                    $this->rol = $row['rol'];
                    $this->foto = $row['foto'];
                    
                    return array(
                        "success" => true,
                        "message" => "Login exitoso",
                        "usuario" => array(
                            "id" => $this->id_usuario,
                            "nombre" => $this->nombre_completo,
                            "email" => $this->email,
                            "rol" => $this->rol,
                            "foto" => $this->foto
                        )
                    );
                } else {
                    return array("success" => false, "message" => "Contraseña incorrecta");
                }
            } else {
                return array("success" => false, "message" => "Usuario no encontrado");
            }
        } catch (PDOException $exception) {
            error_log("Error en login: " . $exception->getMessage());
            return array("success" => false, "message" => "Error de base de datos: " . $exception->getMessage());
        }
    }

    // Validar requisitos de contraseña
    public static function validarPassword($password) {
        $errors = array();
        
        if (strlen($password) < 8) {
            $errors[] = "La contraseña debe tener al menos 8 caracteres";
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "La contraseña debe tener al menos una letra mayúscula";
        }
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = "La contraseña debe tener al menos una letra minúscula";
        }
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = "La contraseña debe tener al menos un número";
        }
        if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/', $password)) {
            $errors[] = "La contraseña debe tener al menos un carácter especial";
        }
        
        return $errors;
    }

    // Validar edad (mínimo 12 años)
    public static function validarEdad($fecha_nacimiento) {
        $fecha_nac = new DateTime($fecha_nacimiento);
        $hoy = new DateTime();
        $edad = $hoy->diff($fecha_nac)->y;
        
        return $edad >= 12;
    }
}
?>