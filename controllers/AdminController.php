<?php
ob_start();
// controllers/AdminController.php - Controlador para el panel de administración

// Incluir los modelos necesarios
require_once 'models/User.php';
require_once 'models/Book.php';
require_once 'models/Loan.php';
require_once 'models/Task.php';
require_once 'controllers/AuthController.php'; // Para usar los checks de rol

class AdminController {
    private $db;
    private $user;
    private $book;
    private $loan;
    private $task;

    public function __construct($db) {
        $this->db = $db;
        $this->user = new User($this->db);
        $this->book = new Book($this->db);
        $this->loan = new Loan($this->db);
        $this->task = new Task($this->db);

        // Proteger todas las acciones del admin
        AuthController::requireAdmin();
    }

    // --- Dashboard Principal del Admin ---
    public function index() {
        $total_books = $this->book->readAll()->rowCount();
        $total_users = $this->user->readAll()->rowCount();
        $total_loans = $this->loan->readAll()->rowCount();
        require 'views/admin/dashboard.php';
    }

    // --- Gestión de Libros (CRUD) ---
    public function books() {
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $stmt = $this->book->search($_GET['search']);
        } else {
            $stmt = $this->book->readAll();
        }
        require 'views/admin/books.php';
    }

    public function createBook() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->book->titulo = $_POST['titulo'];
            $this->book->autor = $_POST['autor'];
            $this->book->isbn = $_POST['isbn'];
            $this->book->categoria = $_POST['categoria'];
            $this->book->sinopsis = $_POST['sinopsis'];
            $this->book->cantidad_total = $_POST['cantidad_total'];
            $this->book->cantidad_disponible = $_POST['cantidad_total'];
            $this->book->ubicacion_fisica = $_POST['ubicacion_fisica'];
            $this->book->portada = $this->uploadFile('portada', 'uploads/covers/');
            $this->book->pdf_ruta = $this->uploadFile('pdf', 'uploads/pdfs/');

            if ($this->book->create()) {
                header("Location: " . BASE_PATH . "/admin/books");
                exit;
            }
        }
        require 'views/admin/book_form.php';
    }

    public function editBook($id) {
        $this->book->id = $id;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->book->titulo = $_POST['titulo'];
            $this->book->autor = $_POST['autor'];
            $this->book->isbn = $_POST['isbn'];
            $this->book->categoria = $_POST['categoria'];
            $this->book->sinopsis = $_POST['sinopsis'];
            $this->book->cantidad_total = $_POST['cantidad_total'];
            $this->book->cantidad_disponible = $_POST['cantidad_total'];

            $this->book->readOne();
            $old_portada = $this->book->portada;
            $old_pdf = $this->book->pdf_ruta;

            $new_portada = $this->uploadFile('portada', 'uploads/covers/');
            $new_pdf = $this->uploadFile('pdf', 'uploads/pdfs/');

            $this->book->portada = !empty($new_portada) ? $new_portada : $old_portada;
            $this->book->pdf_ruta = !empty($new_pdf) ? $new_pdf : $old_pdf;

            if ($this->book->update()) {
                header("Location: " . BASE_PATH . "/admin/books?success=1");
                exit;
            }
        } else {
            $this->book->readOne();
            require 'views/admin/book_form.php';
        }
    }

    public function deleteBook($id) {
        $this->book->id = $id;
        if ($this->book->delete()) {
            header("Location: " . BASE_PATH . "/admin/books");
            exit;
        }
    }

    // --- Gestión de Usuarios (CRUD) ---
    public function users() {
        $stmt = $this->user->readAll();
        require 'views/admin/users.php';
    }

    public function createUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->user->nombre_usuario = $_POST['nombre_usuario'];
            $this->user->password = $_POST['password'];
            $this->user->rol = $_POST['rol'];
            $this->user->nombre_completo = $_POST['nombre_completo'];
            $this->user->correo = $_POST['correo'];

            if ($this->user->create()) {
                header("Location: " . BASE_PATH . "/admin/users?success=1");
                exit;
            }
        }
        require 'views/admin/user_form.php';
    }

    public function editUser($id) {
        $this->user->id = $id;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->user->nombre_usuario = $_POST['nombre_usuario'];
            $this->user->rol = $_POST['rol'];
            $this->user->nombre_completo = $_POST['nombre_completo'];
            $this->user->correo = $_POST['correo'];

            if (!empty($_POST['password'])) {
                $this->user->password = $_POST['password'];
            }

            if ($this->user->update()) {
                header("Location: " . BASE_PATH . "/admin/users?success=1");
                exit;
            }
        } else {
            $this->user->readOne();
            require 'views/admin/user_form.php';
        }
    }

    public function deleteUser($id) {
        $this->user->id = $id;
        if ($id != $_SESSION['user_id'] && $this->user->delete()) {
            header("Location: " . BASE_PATH . "/admin/users?success=1");
            exit;
        }
        header("Location: " . BASE_PATH . "/admin/users?error=1");
    }

    // --- Gestión de Préstamos ---
    public function loans() {
        $stmt = $this->loan->readAll();
        require 'views/admin/loans.php';
    }

    public function returnLoan($id) {
        $this->loan->id = $id;
        if ($this->loan->returnBook()) {
            header("Location: " . BASE_PATH . "/admin/loans?returned=1");
            exit;
        }
    }

    // --- Gestión de Tareas ---
    public function tasks() {
        $stmt = $this->task->readAll();
        require 'views/admin/tasks.php';
    }

    public function createTask() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->task->titulo = $_POST['titulo'];
            $this->task->descripcion = $_POST['descripcion'];
            $this->task->usuario_asignado_id = $_POST['usuario_asignado_id'];
            $this->task->libro_relacionado_id = !empty($_POST['libro_relacionado_id']) ? $_POST['libro_relacionado_id'] : null;
            $this->task->fecha_limite = $_POST['fecha_limite'];

            if ($this->task->create()) {
                header("Location: " . BASE_PATH . "/admin/tasks");
                exit;
            } else {
                echo "Error al crear la tarea.";
            }
        } else {
            $users = $this->user->readAll();
            $books = $this->book->readAll();
            require 'views/admin/task_form.php';
        }
    }

    // --- Métodos de Exportación ---

    public function exportBooksPDF() {
        if (ob_get_length()) ob_end_clean();
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="libros_inventario.pdf"');
        echo "%PDF-1.4\n1 0 obj\n<< /Title (Inventario de Libros) /Creator (Biblioteca App) >>\nendobj\ntrailer\n<< /Root 1 0 R >>\n%%EOF";
        exit;
    }

    public function exportBooksExcel() {
        if (ob_get_length()) ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="libros_inventario.xls"');
        echo "Título\tAutor\tISBN\tStock\n";
        $stmt = $this->book->readAll();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "{$row['titulo']}\t{$row['autor']}\t{$row['isbn']}\t{$row['cantidad_disponible']}\n";
        }
        exit;
    }

    public function exportLoansPDF() {
        if (ob_get_length()) ob_end_clean();
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="reporte_prestamos.pdf"');
        echo "%PDF-1.4\n1 0 obj\n<< /Title (Reporte de Prestamos) /Creator (Biblioteca App) >>\nendobj\ntrailer\n<< /Root 1 0 R >>\n%%EOF";
        exit;
    }

    public function exportLoansExcel() {
        if (ob_get_length()) ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="prestamos_historial.xls"');
        echo "Libro\tFecha\tEstado\n";
        $stmt = $this->loan->readAll();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "{$row['libro_titulo']}\t{$row['fecha_prestamo']}\t{$row['estado']}\n";
        }
        exit;
    }

    private function uploadFile($file_input_name, $target_dir) {
        if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] == 0) {
            $target_file = $target_dir . basename($_FILES[$file_input_name]["name"]);
            if (move_uploaded_file($_FILES[$file_input_name]["tmp_name"], $target_file)) {
                return $target_file;
            }
        }
        return "";
    }
}
?>
