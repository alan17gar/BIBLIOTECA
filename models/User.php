<?php
// models/User.php - Modelo para la gestión de usuarios

class User {
    private $conn;
    private $table_name = "usuarios";

    // Propiedades del objeto Usuario
    public $id;
    public $nombre_usuario;
    public $password;
    public $rol; // 'admin' o 'student'
    public $nombre_completo;
    public $correo;
    public $fecha_creacion;

    // Constructor que recibe la conexión a la base de datos
    public function __construct($db) {
        $this->conn = $db;
    }

    // --- Métodos CRUD ---

    // Crear un nuevo usuario
    public function create() {
        // Consulta para insertar un nuevo registro de usuario
        $query = "INSERT INTO " . $this->table_name . "
                  SET
                    nombre_usuario = :nombre_usuario,
                    password = :password,
                    rol = :rol,
                    nombre_completo = :nombre_completo,
                    correo = :correo";

        // Preparar la consulta
        $stmt = $this->conn->prepare($query);

        // Limpiar los datos (sanitizar)
        $this->nombre_usuario = htmlspecialchars(strip_tags($this->nombre_usuario));
        $this->rol = htmlspecialchars(strip_tags($this->rol));
        $this->nombre_completo = htmlspecialchars(strip_tags($this->nombre_completo));
        $this->correo = htmlspecialchars(strip_tags($this->correo));

        // Hashear la contraseña antes de guardarla
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);

        // Vincular los parámetros
        $stmt->bindParam(':nombre_usuario', $this->nombre_usuario);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':rol', $this->rol);
        $stmt->bindParam(':nombre_completo', $this->nombre_completo);
        $stmt->bindParam(':correo', $this->correo);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Leer todos los usuarios (para el admin)
    public function readAll() {
        $query = "SELECT id, nombre_usuario, rol, nombre_completo, correo, fecha_creacion
                  FROM " . $this->table_name . "
                  ORDER BY fecha_creacion DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Leer un solo usuario por ID
    public function readOne() {
        $query = "SELECT id, nombre_usuario, rol, nombre_completo, correo
                  FROM " . $this->table_name . "
                  WHERE id = ?
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->nombre_usuario = $row['nombre_usuario'];
            $this->rol = $row['rol'];
            $this->nombre_completo = $row['nombre_completo'];
            $this->correo = $row['correo'];
            return true;
        }
        return false;
    }

    // Actualizar un usuario
    public function update() {
        // Si la contraseña está presente, se incluye en la actualización
        $password_set = !empty($this->password) ? "password = :password," : "";

        $query = "UPDATE " . $this->table_name . "
                  SET
                    nombre_usuario = :nombre_usuario,
                    {$password_set}
                    rol = :rol,
                    nombre_completo = :nombre_completo,
                    correo = :correo
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->nombre_usuario=htmlspecialchars(strip_tags($this->nombre_usuario));
        $this->rol=htmlspecialchars(strip_tags($this->rol));
        $this->nombre_completo=htmlspecialchars(strip_tags($this->nombre_completo));
        $this->correo=htmlspecialchars(strip_tags($this->correo));
        $this->id=htmlspecialchars(strip_tags($this->id));

        // Vincular parámetros
        $stmt->bindParam(':nombre_usuario', $this->nombre_usuario);
        $stmt->bindParam(':rol', $this->rol);
        $stmt->bindParam(':nombre_completo', $this->nombre_completo);
        $stmt->bindParam(':correo', $this->correo);
        $stmt->bindParam(':id', $this->id);

        // Si se va a cambiar la contraseña
        if(!empty($this->password)){
            $this->password = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $this->password);
        }

        if($stmt->execute()){
            return true;
        }
        return false;
    }

    // Eliminar un usuario
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        $this->id=htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);

        if($stmt->execute()){
            return true;
        }
        return false;
    }

    // --- Métodos de Autenticación ---

    // Verificar si el nombre de usuario ya existe
    public function usernameExists() {
        $query = "SELECT id FROM " . $this->table_name . "
                  WHERE nombre_usuario = ?
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $this->nombre_usuario = htmlspecialchars(strip_tags($this->nombre_usuario));
        $stmt->bindParam(1, $this->nombre_usuario);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Usuario encontrado
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            return true;
        }
        return false;
    }

    // Método para el login del usuario
    public function login() {
        // Buscar al usuario por su 'nombre_usuario'
        $query = "SELECT id, nombre_usuario, password, rol
                  FROM " . $this->table_name . "
                  WHERE nombre_usuario = :nombre_usuario
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);

        $this->nombre_usuario = htmlspecialchars(strip_tags($this->nombre_usuario));
        $stmt->bindParam(':nombre_usuario', $this->nombre_usuario);

        $stmt->execute();

        // 1. Verificar si el usuario existe
        if ($stmt->rowCount() != 1) {
            return 1; // Código de error: Usuario no encontrado
        }

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // 2. Verificar la contraseña
        if (password_verify($this->password, $row['password'])) {
            $this->id = $row['id'];
            $this->rol = $row['rol'];
            return 0; // Código de éxito
        } else {
            return 2; // Código de error: Contraseña incorrecta
        }
    }
}
?>
