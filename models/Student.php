<?php
// models/Student.php - Modelo para la gestión de estudiantes

class Student {
    private $conn;
    private $table_name = "estudiantes";

    public $id;
    public $cedula;
    public $nombre_completo;
    public $anio_secundaria;
    public $fecha_registro;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear o actualizar un estudiante por su cédula
    public function findOrCreate() {
        // Buscar si ya existe por cédula
        $query = "SELECT id, nombre_completo, anio_secundaria FROM " . $this->table_name . " WHERE cedula = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->cedula);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->id = $row['id'];
            // Opcionalmente actualizar nombre y año si cambiaron
            if ($row['nombre_completo'] !== $this->nombre_completo || $row['anio_secundaria'] !== $this->anio_secundaria) {
                $this->update();
            }
            return $this->id;
        }

        // Si no existe, crear
        $query = "INSERT INTO " . $this->table_name . "
                  SET nombre_completo=:nombre_completo, cedula=:cedula, anio_secundaria=:anio_secundaria";

        $stmt = $this->conn->prepare($query);
        $this->nombre_completo = htmlspecialchars(strip_tags($this->nombre_completo));
        $this->cedula = htmlspecialchars(strip_tags($this->cedula));
        $this->anio_secundaria = htmlspecialchars(strip_tags($this->anio_secundaria));

        $stmt->bindParam(":nombre_completo", $this->nombre_completo);
        $stmt->bindParam(":cedula", $this->cedula);
        $stmt->bindParam(":anio_secundaria", $this->anio_secundaria);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return $this->id;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . "
                  SET nombre_completo=:nombre_completo, anio_secundaria=:anio_secundaria
                  WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nombre_completo", $this->nombre_completo);
        $stmt->bindParam(":anio_secundaria", $this->anio_secundaria);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }

    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nombre_completo ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>
