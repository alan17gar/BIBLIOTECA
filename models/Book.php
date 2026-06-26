<?php
// models/Book.php - Modelo para la gestión de libros

class Book {
    private $conn;
    private $table_name = "libros";

    // Propiedades del objeto Libro
    public $id;
    public $titulo;
    public $autor;
    public $isbn;
    public $categoria;
    public $sinopsis;
    public $portada; // Ruta a la imagen de la portada
    public $cantidad_total;
    public $cantidad_disponible;
    public $ubicacion_fisica;
    public $pdf_ruta; // Ruta al archivo PDF (opcional)

    public function __construct($db) {
        $this->conn = $db;
    }

    // --- Métodos CRUD ---

    // Crear un nuevo libro
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET
                    titulo=:titulo, autor=:autor, isbn=:isbn, categoria=:categoria,
                    sinopsis=:sinopsis, portada=:portada, cantidad_total=:cantidad_total,
                    cantidad_disponible=:cantidad_disponible, ubicacion_fisica=:ubicacion_fisica, pdf_ruta=:pdf_ruta";

        $stmt = $this->conn->prepare($query);

        // Sanitizar datos
        $this->titulo = htmlspecialchars(strip_tags($this->titulo));
        $this->autor = htmlspecialchars(strip_tags($this->autor));
        $this->isbn = htmlspecialchars(strip_tags($this->isbn));
        $this->categoria = htmlspecialchars(strip_tags($this->categoria));
        $this->sinopsis = htmlspecialchars(strip_tags($this->sinopsis));
        $this->portada = htmlspecialchars(strip_tags($this->portada));
        $this->cantidad_total = htmlspecialchars(strip_tags($this->cantidad_total));
        $this->cantidad_disponible = htmlspecialchars(strip_tags($this->cantidad_disponible));
        $this->ubicacion_fisica = htmlspecialchars(strip_tags($this->ubicacion_fisica));
        $this->pdf_ruta = htmlspecialchars(strip_tags($this->pdf_ruta));

        // Vincular parámetros
        $stmt->bindParam(":titulo", $this->titulo);
        $stmt->bindParam(":autor", $this->autor);
        $stmt->bindParam(":isbn", $this->isbn);
        $stmt->bindParam(":categoria", $this->categoria);
        $stmt->bindParam(":sinopsis", $this->sinopsis);
        $stmt->bindParam(":portada", $this->portada);
        $stmt->bindParam(":cantidad_total", $this->cantidad_total);
        $stmt->bindParam(":cantidad_disponible", $this->cantidad_disponible);
        $stmt->bindParam(":ubicacion_fisica", $this->ubicacion_fisica);
        $stmt->bindParam(":pdf_ruta", $this->pdf_ruta);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Leer todos los libros
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY titulo ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Leer un solo libro por ID
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->titulo = $row['titulo'];
            $this->autor = $row['autor'];
            $this->isbn = $row['isbn'];
            $this->categoria = $row['categoria'];
            $this->sinopsis = $row['sinopsis'];
            $this->portada = $row['portada'];
            $this->cantidad_total = $row['cantidad_total'];
            $this->cantidad_disponible = $row['cantidad_disponible'];
            $this->ubicacion_fisica = $row['ubicacion_fisica'];
            $this->pdf_ruta = $row['pdf_ruta'];
            return true;
        }
        return false;
    }

    // Actualizar un libro
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                  SET
                    titulo = :titulo, autor = :autor, isbn = :isbn, categoria = :categoria,
                    sinopsis = :sinopsis, portada = :portada, cantidad_total = :cantidad_total,
                    cantidad_disponible = :cantidad_disponible, ubicacion_fisica = :ubicacion_fisica, pdf_ruta = :pdf_ruta
                  WHERE
                    id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitizar datos
        $this->titulo=htmlspecialchars(strip_tags($this->titulo));
        $this->autor=htmlspecialchars(strip_tags($this->autor));
        $this->isbn=htmlspecialchars(strip_tags($this->isbn));
        $this->categoria=htmlspecialchars(strip_tags($this->categoria));
        $this->sinopsis=htmlspecialchars(strip_tags($this->sinopsis));
        $this->portada=htmlspecialchars(strip_tags($this->portada));
        $this->cantidad_total=htmlspecialchars(strip_tags($this->cantidad_total));
        $this->cantidad_disponible=htmlspecialchars(strip_tags($this->cantidad_disponible));
        $this->ubicacion_fisica=htmlspecialchars(strip_tags($this->ubicacion_fisica));
        $this->pdf_ruta=htmlspecialchars(strip_tags($this->pdf_ruta));
        $this->id=htmlspecialchars(strip_tags($this->id));

        // Vincular parámetros
        $stmt->bindParam(':titulo', $this->titulo);
        $stmt->bindParam(':autor', $this->autor);
        $stmt->bindParam(':isbn', $this->isbn);
        $stmt->bindParam(':categoria', $this->categoria);
        $stmt->bindParam(':sinopsis', $this->sinopsis);
        $stmt->bindParam(':portada', $this->portada);
        $stmt->bindParam(':cantidad_total', $this->cantidad_total);
        $stmt->bindParam(':cantidad_disponible', $this->cantidad_disponible);
        $stmt->bindParam(':ubicacion_fisica', $this->ubicacion_fisica);
        $stmt->bindParam(':pdf_ruta', $this->pdf_ruta);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Eliminar un libro
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // --- Métodos Adicionales ---

    // Buscar libros (por título, autor, categoría)
    public function search($keywords) {
        $query = "SELECT * FROM " . $this->table_name . "
                  WHERE
                    titulo LIKE ? OR autor LIKE ? OR categoria LIKE ?
                  ORDER BY
                    titulo ASC";

        $stmt = $this->conn->prepare($query);

        // Sanitizar y vincular el término de búsqueda
        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%"; // Para búsquedas parciales

        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);

        $stmt->execute();
        return $stmt;
    }

    // Actualizar la cantidad disponible de un libro (al prestar o devolver)
    public function updateAvailability($book_id, $change) {
        // $change puede ser +1 (devolver) o -1 (prestar)
        $query = "UPDATE " . $this->table_name . "
                  SET cantidad_disponible = cantidad_disponible + (?)
                  WHERE id = ? AND cantidad_disponible + (?) >= 0";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $change, PDO::PARAM_INT);
        $stmt->bindParam(2, $book_id, PDO::PARAM_INT);
        $stmt->bindParam(3, $change, PDO::PARAM_INT);

        if ($stmt->execute() && $stmt->rowCount() > 0) {
            return true;
        }
        return false; // No se pudo actualizar o no hay suficientes libros
    }

    // Método explícito para descontar stock
    public function descontarStock($id) {
        return $this->updateAvailability($id, -1);
    }

    // Método explícito para reintegrar stock
    public function reintegrarStock($id) {
        return $this->updateAvailability($id, 1);
    }
}
?>
