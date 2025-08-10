<?php
// models/Task.php - Modelo para la gestión de tareas

class Task {
    private $conn;
    private $table_name = "tareas";

    // Propiedades del objeto Tarea
    public $id;
    public $titulo;
    public $descripcion;
    public $usuario_asignado_id;
    public $libro_relacionado_id;
    public $fecha_asignacion;
    public 'fecha_limite';
    public $estado; // 'pendiente', 'completada'
    public $respuesta; // Respuesta del estudiante a la tarea

    public function __construct($db) {
        $this->conn = $db;
    }

    // --- Métodos del Modelo ---

    // Asignar una nueva tarea a un estudiante
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET
                    titulo=:titulo, descripcion=:descripcion, usuario_asignado_id=:usuario_id,
                    libro_relacionado_id=:libro_id, fecha_limite=:fecha_limite, estado=:estado";

        $stmt = $this->conn->prepare($query);

        // Sanitizar datos
        $this->titulo = htmlspecialchars(strip_tags($this->titulo));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->usuario_asignado_id = htmlspecialchars(strip_tags($this->usuario_asignado_id));
        $this->libro_relacionado_id = htmlspecialchars(strip_tags($this->libro_relacionado_id));
        $this->fecha_limite = htmlspecialchars(strip_tags($this->fecha_limite));
        $this->estado = 'pendiente'; // Por defecto, una nueva tarea está pendiente

        // Vincular parámetros
        $stmt->bindParam(":titulo", $this->titulo);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":usuario_id", $this->usuario_asignado_id);
        $stmt->bindParam(":libro_id", $this->libro_relacionado_id);
        $stmt->bindParam(":fecha_limite", $this->fecha_limite);
        $stmt->bindParam(":estado", $this->estado);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Leer todas las tareas (para el admin)
    public function readAll() {
        $query = "SELECT
                    t.id, t.titulo, t.descripcion, t.fecha_limite, t.estado,
                    u.nombre_usuario as estudiante_nombre,
                    l.titulo as libro_titulo
                  FROM " . $this->table_name . " t
                  LEFT JOIN usuarios u ON t.usuario_asignado_id = u.id
                  LEFT JOIN libros l ON t.libro_relacionado_id = l.id
                  ORDER BY t.fecha_asignacion DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Leer todas las tareas asignadas a un estudiante específico
    public function readByUserId($user_id) {
        $query = "SELECT
                    t.id, t.titulo, t.descripcion, t.fecha_limite, t.estado, t.respuesta,
                    l.titulo as libro_titulo
                  FROM " . $this->table_name . " t
                  LEFT JOIN libros l ON t.libro_relacionado_id = l.id
                  WHERE t.usuario_asignado_id = ?
                  ORDER BY t.fecha_asignacion DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->execute();
        return $stmt;
    }

    // Completar una tarea (el estudiante envía una respuesta)
    public function complete() {
        $query = "UPDATE " . $this->table_name . "
                  SET
                    estado = 'completada',
                    respuesta = :respuesta
                  WHERE id = :id AND usuario_asignado_id = :usuario_id";

        $stmt = $this->conn->prepare($query);

        // Sanitizar datos
        $this->respuesta = htmlspecialchars(strip_tags($this->respuesta));
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->usuario_asignado_id = htmlspecialchars(strip_tags($this->usuario_asignado_id));

        // Vincular parámetros
        $stmt->bindParam(':respuesta', $this->respuesta);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':usuario_id', $this->usuario_asignado_id);

        if ($stmt->execute() && $stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }
}
?>
