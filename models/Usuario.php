<?php
class Usuario {
    private $conn;
    private $table_name = "usuarios";

    public $id_usuario;
    public $nombres; 
    public $apellido_paterno; 
    public $apellido_materno;
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

            $query = "INSERT INTO " . $this->table_name . " 
                     (nombres, apellido_paterno, apellido_materno, fecha_nacimiento, genero, pais_nacimiento, nacionalidad, email, password, rol, activo)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(1, $this->nombres);
            $stmt->bindParam(2, $this->apellido_paterno);
            $stmt->bindParam(3, $this->apellido_materno);
            $stmt->bindParam(4, $this->fecha_nacimiento);
            $stmt->bindParam(5, $this->genero);
            $stmt->bindParam(6, $this->pais_nacimiento);
            $stmt->bindParam(7, $this->nacionalidad);
            $stmt->bindParam(8, $this->email);
            $stmt->bindParam(9, $hashed_password);
            
            // Manejar la foto (puede ser null)
            if ($this->foto) {
                $stmt->bindParam(10, $this->foto, PDO::PARAM_LOB);
            } else {
                $null = null;
                $stmt->bindParam(10, $null, PDO::PARAM_NULL);
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
                        $query = "SELECT id_usuario, nombres, apellido_paterno, apellido_materno, email, password, rol, foto, activo 
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
                    $this->nombres = $row['nombres'];
                    $this->apellido_paterno = $row['apellido_paterno'];
                    $this->apellido_materno = $row['apellido_materno'];
                  
                    $this->nombre_completo = trim($this->nombres . ' ' . $this->apellido_paterno . ' ' . $this->apellido_materno);
                    $this->email = $row['email'];
                    $this->rol = $row['rol'];
                    $this->foto = $row['foto'];
                    
                    return array(
                        "success" => true,
                        "message" => "Login exitoso",
                        "usuario" => array(
                            "id" => $this->id_usuario,
                            "nombres" => $this->nombres,
                            "apellido_paterno" => $this->apellido_paterno,
                            "apellido_materno" => $this->apellido_materno,
                            "nombre_completo" => $this->nombre_completo,
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
    if (!preg_match('/[A-ZÁÉÍÓÚÜÑ]/u', $password)) {
        $errors[] = "La contraseña debe tener al menos una letra mayúscula";
    }
    if (!preg_match('/[a-záéíóúüñ]/u', $password)) {
        $errors[] = "La contraseña debe tener al menos una letra minúscula";
    }
    if (!preg_match('/\d/', $password)) {
        $errors[] = "La contraseña debe tener al menos un número";
    }

    // ACEPTA cualquier símbolo, emoji o caracter especial
    if (!preg_match('/[\W_]/u', $password)) {
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