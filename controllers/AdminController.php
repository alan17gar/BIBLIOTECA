<?php
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
        // Cargar datos para las estadísticas del dashboard
        $total_books = $this->book->readAll()->rowCount();
        $total_users = $this->user->readAll()->rowCount();
        $total_loans = $this->loan->readAll()->rowCount();

        // Cargar la vista del dashboard del admin
        require 'views/admin/dashboard.php';
    }

    // --- Gestión de Libros (CRUD) ---
    public function books() {
        $stmt = $this->book->readAll();
        require 'views/admin/books.php';
    }

    public function createBook() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Asignar datos del formulario al objeto libro
            $this->book->titulo = $_POST['titulo'];
            $this->book->autor = $_POST['autor'];
            $this->book->isbn = $_POST['isbn'];
            $this->book->categoria = $_POST['categoria'];
            $this->book->sinopsis = $_POST['sinopsis'];
            $this->book->cantidad_total = $_POST['cantidad_total'];
            $this->book->cantidad_disponible = $_POST['cantidad_total']; // Al crear, disponible = total
            $this->book->ubicacion_fisica = $_POST['ubicacion_fisica'];

            // Manejo de la subida de archivos (portada y PDF)
            $this->book->portada = $this->uploadFile('portada', 'uploads/covers/');
            $this->book->pdf_ruta = $this->uploadFile('pdf', 'uploads/pdfs/');

            if ($this->book->create()) {
                header("Location: " . BASE_PATH . "/admin/books");
                exit;
            }
        }
        require 'views/admin/book_form.php'; // Formulario para crear/editar
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
            $this->book->ubicacion_fisica = $_POST['ubicacion_fisica'];

            // Manejo opcional de archivos nuevos en la edición
            if (isset($_FILES['portada']) && $_FILES['portada']['error'] == 0) {
                $this->book->portada = $this->uploadFile('portada', 'uploads/covers/');
            }
            if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] == 0) {
                $this->book->pdf_ruta = $this->uploadFile('pdf', 'uploads/pdfs/');
            }

            if ($this->book->update()) {
                header("Location: " . BASE_PATH . "/admin/books");
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
            // Sincronizado con las variables exactas de user_form.php y tu tabla de la BD
            $this->user->nombre_completo = $_POST['nombre_completo']; 
            $this->user->nombre_usuario = $_POST['nombre_usuario']; 
            $this->user->correo = $_POST['correo'];           
            $this->user->password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $this->user->rol = $_POST['rol']; 

            if ($this->user->create()) {
                header("Location: " . BASE_PATH . "/admin/users");
                exit;
            }
        }
        require 'views/admin/user_form.php';
    }

    public function editUser($id) {
        $this->user->id = $id;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->user->nombre_completo = $_POST['nombre_completo'];
            $this->user->nombre_usuario = $_POST['nombre_usuario'];
            $this->user->correo = $_POST['correo'];
            $this->user->rol = $_POST['rol'];

            // Actualizar contraseña solo si el admin escribió una nueva
            if (!empty($_POST['password'])) {
                $this->user->password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            }

            if ($this->user->update()) {
                header("Location: " . BASE_PATH . "/admin/users");
                exit;
            }
        } else {
            $this->user->readOne();
            require 'views/admin/user_form.php';
        }
    }

    public function deleteUser($id) {
        // Protección extra: evitar que un admin borre su propia cuenta activa en la sesión
        if ($_SESSION['user_id'] != $id) {
            $this->user->id = $id;
            if ($this->user->delete()) {
                header("Location: " . BASE_PATH . "/admin/users");
                exit;
            }
        } else {
            header("Location: " . BASE_PATH . "/admin/users");
            exit;
        }
    }

    // --- Gestión de Préstamos ---
    public function loans() {
        $stmt = $this->loan->readAll();
        require 'views/admin/loans.php';
    }

    public function returnLoan($id) {
        $this->loan->id = $id;
        if ($this->loan->returnBook()) {
            header("Location: " . BASE_PATH . "/admin/loans");
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
            $students = $this->user->readAll(); 
            $books = $this->book->readAll();

            require 'views/admin/task_form.php';
        }
    }

    // --- Función auxiliar para subir archivos ---
    private function uploadFile($file_input_name, $target_dir) {
        if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] == 0) {
            
            // CONVERSIÓN A RUTA ABSOLUTA: Asegura que funcione perfectamente en Render (Linux)
            $absolute_target_dir = $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim($target_dir, '/');
            
            // SEGURIDAD AUTOMÁTICA: Si la carpeta no existe en el servidor, PHP la crea con permisos de escritura
            if (!file_exists($absolute_target_dir)) {
                mkdir($absolute_target_dir, 0777, true);
            }

            // Definir las rutas del archivo
            $file_name = basename($_FILES[$file_input_name]["name"]);
            $absolute_target_file = $absolute_target_dir . $file_name; 
            $db_saved_path = rtrim($target_dir, '/') . '/' . $file_name; 

            // Mover el archivo usando la ruta absoluta requerida por Linux
            if (move_uploaded_file($_FILES[$file_input_name]["tmp_name"], $absolute_target_file)) {
                return $db_saved_path; 
            }
        }
        return ""; 
    }
}
?>
