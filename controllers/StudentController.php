<?php
// controllers/StudentController.php - Controlador para el panel del estudiante

// Incluir los modelos y controladores necesarios
require_once 'models/Book.php';
require_once 'models/Loan.php';
require_once 'controllers/AuthController.php'; // Para usar los checks de rol

class StudentController {
    private $db;
    private $book;
    private $loan;

    public function __construct($db) {
        $this->db = $db;
        $this->book = new Book($this->db);
        $this->loan = new Loan($this->db);

        // Proteger todas las acciones del estudiante
        AuthController::requireStudent();
    }

    // --- Dashboard Principal del Estudiante ---
    public function index() {
        // Cargar datos para el dashboard del estudiante
        $user_id = $_SESSION['user_id'];

        // Obtener préstamos del estudiante
        $stmt = $this->loan->readByUserId($user_id);
        $active_loans_count = 0;
        $loans_data = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $loans_data[] = $row;
            if ($row['estado'] == 'prestado') {
                $active_loans_count++;
            }
        }

        // Pasar datos a la vista
        require 'views/student/dashboard.php';
    }

    // --- Búsqueda y visualización de libros ---
    public function books() {
        // Verificar si hay una búsqueda
        if (isset($_GET['search'])) {
            $keywords = $_GET['search'];
            $stmt = $this->book->search($keywords);
        } else {
            $stmt = $this->book->readAll();
        }
        require 'views/student/books.php';
    }

    // --- Visualización del historial de préstamos ---
    public function loans() {
        $user_id = $_SESSION['user_id'];
        $stmt = $this->loan->readByUserId($user_id);
        require 'views/student/loans.php';
    }

    // --- Solicitar un préstamo ---
    public function requestLoan($book_id) {
        $this->loan->libro_id = $book_id;
        $this->loan->usuario_id = $_SESSION['user_id'];
        $this->loan->estado = 'prestado'; // Estado inicial
        $this->loan->multa = 0;

        if ($this->loan->create()) {
            // Préstamo exitoso
            header("Location: " . BASE_PATH . "/student/loans");
        } else {
            // Error, probablemente no hay libros disponibles
            // Idealmente, manejar este error de forma más elegante
            echo "Error: No se pudo solicitar el préstamo. Es posible que no haya ejemplares disponibles.";
        }
        exit;
    }


    // --- Juegos Interactivos ---
    public function games($game_name = 'index') {
        // Cargar la vista del juego solicitado
        $game_file = 'views/games/' . $game_name . '.php';
        if (file_exists($game_file)) {
            require $game_file;
        } else {
            // Vista por defecto o de error si el juego no existe
            require 'views/games/index.php';
        }
    }
}
?>
