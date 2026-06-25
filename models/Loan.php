<?php
// models/Loan.php - Modelo para la gestión de préstamos

require_once 'Book.php';

class Loan {
    private $conn;
    private $table_name = "prestamos";

    // Propiedades del objeto Préstamo
    public $id;
    public $libro_id;
    public $usuario_id;
    public $nombre_estudiante;
    public $codigo_prestamo;
    public $anio_estudiante;
    public $ubicacion_lectura;
    public $fecha_prestamo;
    public $fecha_devolucion_estimada;
    public $fecha_devolucion_real;
    public $estado; // 'prestado', 'devuelto', 'retrasado'
    public $multa;

    public function __construct($db) {
        $this->conn = $db;
    }

    // --- Métodos del Modelo ---

    // Crear un nuevo préstamo
    public function create() {
        // Primero, verificar si hay libros disponibles
        $book = new Book($this->conn);
        $book->id = $this->libro_id;
        if (!$book->readOne() || $book->cantidad_disponible <= 0) {
            return false; // No hay libros disponibles
        }

        // Si hay libros, proceder con el préstamo
        $query = "INSERT INTO " . $this->table_name . "
                  SET
                    libro_id=:libro_id, usuario_id=:usuario_id,
                    nombre_estudiante=:nombre_estudiante, codigo_prestamo=:codigo_prestamo,
                    anio_estudiante=:anio_estudiante, ubicacion_lectura=:ubicacion_lectura,
                    fecha_prestamo=:fecha_prestamo, fecha_devolucion_estimada=:fecha_devolucion_estimada,
                    estado=:estado, multa=:multa";

        $stmt = $this->conn->prepare($query);

        // Sanitizar datos
        $this->libro_id = htmlspecialchars(strip_tags($this->libro_id));
        $this->usuario_id = !empty($this->usuario_id) ? htmlspecialchars(strip_tags($this->usuario_id)) : null;
        $this->nombre_estudiante = htmlspecialchars(strip_tags($this->nombre_estudiante));
        $this->codigo_prestamo = htmlspecialchars(strip_tags($this->codigo_prestamo));
        $this->anio_estudiante = htmlspecialchars(strip_tags($this->anio_estudiante));
        $this->ubicacion_lectura = htmlspecialchars(strip_tags($this->ubicacion_lectura));
        $this->estado = htmlspecialchars(strip_tags($this->estado));
        $this->multa = htmlspecialchars(strip_tags($this->multa));

        // Asignar fechas
        $this->fecha_prestamo = date('Y-m-d H:i:s');
        // Por defecto, 15 días para devolver
        $this->fecha_devolucion_estimada = date('Y-m-d H:i:s', strtotime('+15 days'));

        // Vincular parámetros
        $stmt->bindParam(":libro_id", $this->libro_id);
        $stmt->bindParam(":usuario_id", $this->usuario_id);
        $stmt->bindParam(":nombre_estudiante", $this->nombre_estudiante);
        $stmt->bindParam(":codigo_prestamo", $this->codigo_prestamo);
        $stmt->bindParam(":anio_estudiante", $this->anio_estudiante);
        $stmt->bindParam(":ubicacion_lectura", $this->ubicacion_lectura);
        $stmt->bindParam(":fecha_prestamo", $this->fecha_prestamo);
        $stmt->bindParam(":fecha_devolucion_estimada", $this->fecha_devolucion_estimada);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":multa", $this->multa);

        // Ejecutar y actualizar la disponibilidad del libro
        if ($stmt->execute()) {
            // Reducir la cantidad disponible del libro
            $book->descontarStock($this->libro_id);
            return true;
        }
        return false;
    }

    // Leer todos los préstamos (con información del libro y usuario)
   // Leer todos los préstamos (con información del libro y usuario)
    public function readAll() {
        $query = "SELECT
                    p.id, p.libro_id, p.usuario_id, 
                    p.fecha_prestamo, p.fecha_devolucion_estimada, p.fecha_devolucion_real, p.estado, p.multa,
                    l.titulo as libro_titulo, 
                    u.nombre_usuario as sistema_usuario_nombre,
                    u.nombre_completo as nombre_estudiante
                  FROM " . $this->table_name . " p
                  LEFT JOIN libros l ON p.libro_id = l.id
                  LEFT JOIN usuarios u ON p.usuario_id = u.id
                  ORDER BY p.fecha_prestamo DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Leer préstamos de un usuario específico
    public function readByUserId($user_id) {
        $query = "SELECT
                    p.id, p.libro_id, p.fecha_prestamo, p.fecha_devolucion_estimada, p.estado, p.multa,
                    l.titulo as libro_titulo, l.autor as libro_autor
                  FROM " . $this->table_name . " p
                  JOIN libros l ON p.libro_id = l.id
                  WHERE p.usuario_id = ?
                  ORDER BY p.fecha_prestamo DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->execute();
        return $stmt;
    }

    // Marcar un préstamo como devuelto
    public function returnBook() {
        $query = "UPDATE " . $this->table_name . "
                  SET
                    estado = 'devuelto',
                    fecha_devolucion_real = :fecha_devolucion_real
                  WHERE id = :id AND estado = 'prestado'";

        $stmt = $this->conn->prepare($query);

        // Sanitizar y vincular datos
        $this->id = htmlspecialchars(strip_tags($this->id));
        $fecha_actual = date('Y-m-d H:i:s');

        $stmt->bindParam(':fecha_devolucion_real', $fecha_actual);
        $stmt->bindParam(':id', $this->id);

        // Ejecutar y actualizar disponibilidad
        if ($stmt->execute() && $stmt->rowCount() > 0) {
            // Necesitamos el ID del libro para actualizar su disponibilidad
            $this->readOne(); // Cargar datos del préstamo
            $book = new Book($this->conn);
            $book->reintegrarStock($this->libro_id); // Aumentar cantidad disponible
            return true;
        }
        return false;
    }

    // Leer un solo préstamo por ID para obtener el libro_id
    private function readOne() {
        $query = "SELECT libro_id FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->libro_id = $row['libro_id'];
        }
    }

    // Renovar un préstamo (extender la fecha de devolución)
    public function renew() {
        $query = "UPDATE " . $this->table_name . "
                  SET fecha_devolucion_estimada = :nueva_fecha
                  WHERE id = :id AND estado = 'prestado'";

        $stmt = $this->conn->prepare($query);

        // Sanitizar y calcular nueva fecha (ej: 7 días más)
        $this->id = htmlspecialchars(strip_tags($this->id));
        $nueva_fecha = date('Y-m-d H:i:s', strtotime('+7 days'));

        $stmt->bindParam(':nueva_fecha', $nueva_fecha);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
