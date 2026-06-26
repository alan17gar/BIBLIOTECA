<?php
// models/Student.php - Modelo para la gestión de estudiantes

class Student {
    private $conn;
    private $table_name = "estudiantes";

    public $id;
    public $cedula;
    public $nombre_completo;
    public $anio_secundaria;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET cedula=:cedula, nombre_completo=:nombre_completo, anio_secundaria=:anio_secundaria";
        $stmt = $this->conn->prepare($query);

        $this->cedula = htmlspecialchars(strip_tags($this->cedula));
        $this->nombre_completo = htmlspecialchars(strip_tags($this->nombre_completo));
        $this->anio_secundaria = htmlspecialchars(strip_tags($this->anio_secundaria));

        $stmt->bindParam(":cedula", $this->cedula);
        $stmt->bindParam(":nombre_completo", $this->nombre_completo);
        $stmt->bindParam(":anio_secundaria", $this->anio_secundaria);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nombre_completo ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->cedula = $row['cedula'];
            $this->nombre_completo = $row['nombre_completo'];
            $this->anio_secundaria = $row['anio_secundaria'];
            return true;
        }
        return false;
    }
}
?>
