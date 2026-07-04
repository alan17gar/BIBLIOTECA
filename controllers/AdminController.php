<?php
// controllers/AdminController.php - Controlador para el panel de administración

// Incluir los modelos necesarios
require_once 'models/User.php';
require_once 'models/Book.php';
require_once 'models/Loan.php';
require_once 'models/Student.php';
require_once 'controllers/AuthController.php'; // Para usar los checks de rol

class AdminController {
    private $db;
    private $user;
    private $book;
    private $loan;
    private $student;

    public function __construct($db) {
        $this->db = $db;
        $this->user = new User($this->db);
        $this->book = new Book($this->db);
        $this->loan = new Loan($this->db);
        $this->student = new Student($this->db);

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
        $filters = [
            'keyword' => $_GET['keyword'] ?? '',
            'categoria' => $_GET['categoria'] ?? '',
            'ubicacion_fisica' => $_GET['ubicacion_fisica'] ?? '',
            'bicentenaria' => $_GET['bicentenaria'] ?? ''
        ];
        $stmt = $this->book->search($filters);
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
            $this->book->readOne();
            $borrowed = $this->book->cantidad_total - $this->book->cantidad_disponible;

            $this->book->titulo = $_POST['titulo'];
            $this->book->autor = $_POST['autor'];
            $this->book->isbn = $_POST['isbn'];
            $this->book->categoria = $_POST['categoria'];
            $this->book->sinopsis = $_POST['sinopsis'];
            $this->book->cantidad_total = $_POST['cantidad_total'];
            $this->book->cantidad_disponible = $_POST['cantidad_total'] - $borrowed;
            $this->book->ubicacion_fisica = $_POST['ubicacion_fisica'];

            // Mantener portadas/pdfs anteriores si no se suben nuevos
            $nueva_portada = $this->uploadFile('portada', 'uploads/covers/');
            if (!empty($nueva_portada)) $this->book->portada = $nueva_portada;

            $nuevo_pdf = $this->uploadFile('pdf', 'uploads/pdfs/');
            if (!empty($nuevo_pdf)) $this->book->pdf_ruta = $nuevo_pdf;

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
            $this->user->nombre_usuario = $_POST['nombre_usuario'];
            $this->user->password = $_POST['password'];
            $this->user->rol = 'admin'; // Solo admin permitido
            $this->user->nombre_completo = $_POST['nombre_completo'];
            $this->user->correo = $_POST['correo'];

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
            $this->user->nombre_usuario = $_POST['nombre_usuario'];
            if (!empty($_POST['password'])) {
                $this->user->password = $_POST['password'];
            }
            $this->user->rol = 'admin'; // Solo admin
            $this->user->nombre_completo = $_POST['nombre_completo'];
            $this->user->correo = $_POST['correo'];

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
        $this->user->id = $id;
        if ($this->user->delete()) {
            header("Location: " . BASE_PATH . "/admin/users");
            exit;
        }
    }

    // --- Gestión de Préstamos ---
    public function loans() {
        $stmt = $this->loan->readAll();
        require 'views/admin/loans.php';
    }

    public function createLoan() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Manejar estudiante
            $this->student->cedula = $_POST['cedula'];
            $this->student->nombre_completo = $_POST['nombre_completo'];
            $this->student->anio_secundaria = $_POST['anio_secundaria'];
            $student_id = $this->student->findOrCreate();

            if ($student_id) {
                $this->loan->libro_id = $_POST['libro_id'];
                $this->loan->estudiante_id = $student_id;
                $this->loan->ubicacion_lectura = $_POST['ubicacion_lectura'];
                $this->loan->fecha_devolucion_estimada = $_POST['fecha_devolucion_estimada'];
                $this->loan->estado = 'prestado';
                $this->loan->multa = 0;

                if ($this->loan->create()) {
                    header("Location: " . BASE_PATH . "/admin/loans");
                    exit;
                } else {
                    $error = "No hay ejemplares disponibles para este libro.";
                }
            }
        }
        $books = $this->book->readAll();
        require 'views/admin/loan_form.php';
    }

    public function returnLoan($id) {
        $this->loan->id = $id;
        if ($this->loan->returnBook()) {
            header("Location: " . BASE_PATH . "/admin/loans");
            exit;
        }
    }

    // --- Exportación de Datos ---
    public function exportBooksPDF() {
        ob_start();
        while (ob_get_level()) { ob_end_clean(); }
        require_once __DIR__ . '/../libs/fpdf/fpdf.php';
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, utf8_decode('Listado de Libros'), 0, 1, 'C');
        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(80, 7, 'Titulo', 1);
        $pdf->Cell(40, 7, 'Autor', 1);
        $pdf->Cell(40, 7, 'ISBN', 1);
        $pdf->Cell(30, 7, 'Stock', 1);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 9);
        $stmt = $this->book->readAll();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $pdf->Cell(80, 6, utf8_decode(substr($row['titulo'], 0, 40)), 1);
            $pdf->Cell(40, 6, utf8_decode($row['autor']), 1);
            $pdf->Cell(40, 6, $row['isbn'], 1);
            $pdf->Cell(30, 6, $row['cantidad_disponible'] . '/' . $row['cantidad_total'], 1);
            $pdf->Ln();
        }
        $pdf->Output('D', 'libros.pdf');
        exit;
    }

    public function exportBooksExcel() {
        while (ob_get_level()) { ob_end_clean(); }
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=libros.csv');
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Titulo', 'Autor', 'ISBN', 'Categoria', 'Stock Total', 'Stock Disponible', 'Ubicacion']);
        $stmt = $this->book->readAll();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            fputcsv($output, [$row['id'], $row['titulo'], $row['autor'], $row['isbn'], $row['categoria'], $row['cantidad_total'], $row['cantidad_disponible'], $row['ubicacion_fisica']]);
        }
        fclose($output);
        exit;
    }

    public function exportLoansPDF() {
        ob_start();
        while (ob_get_level()) { ob_end_clean(); }
        require_once __DIR__ . '/../libs/fpdf/fpdf.php';
        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, utf8_decode('Reporte de Préstamos'), 0, 1, 'C');
        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(80, 7, 'Libro', 1);
        $pdf->Cell(40, 7, 'ISBN', 1);
        $pdf->Cell(30, 7, utf8_decode('Fecha Prést.'), 1);
        $pdf->Cell(30, 7, 'Fecha Dev.', 1);
        $pdf->Cell(40, 7, 'Ubicacion', 1);
        $pdf->Cell(30, 7, 'Estado', 1);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $stmt = $this->loan->readAll();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $pdf->Cell(80, 6, utf8_decode(substr($row['libro_titulo'], 0, 45)), 1);
            $pdf->Cell(40, 6, $row['libro_isbn'], 1);
            $pdf->Cell(30, 6, date('d/m/Y', strtotime($row['fecha_prestamo'])), 1);
            $pdf->Cell(30, 6, date('d/m/Y', strtotime($row['fecha_devolucion_estimada'])), 1);
            $pdf->Cell(40, 6, utf8_decode($row['ubicacion_lectura']), 1);
            $pdf->Cell(30, 6, $row['estado'], 1);
            $pdf->Ln();
        }
        $pdf->Output('D', 'prestamos.pdf');
        exit;
    }

    public function exportLoansExcel() {
        while (ob_get_level()) { ob_end_clean(); }
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=prestamos.csv');
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Libro', 'ISBN', 'Ubicacion Lectura', 'Fecha Prestamo', 'Fecha Dev. Estimada', 'Estado']);
        $stmt = $this->loan->readAll();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            fputcsv($output, [
                $row['libro_titulo'],
                $row['libro_isbn'],
                $row['ubicacion_lectura'],
                $row['fecha_prestamo'],
                $row['fecha_devolucion_estimada'],
                $row['estado']
            ]);
        }
        fclose($output);
        exit;
    }

    // --- Función auxiliar para subir archivos ---
    private function uploadFile($file_input_name, $target_dir) {
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] == 0) {
            $target_file = $target_dir . basename($_FILES[$file_input_name]["name"]);
            $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Validaciones (tamaño, tipo, etc.)
            // ...

            if (move_uploaded_file($_FILES[$file_input_name]["tmp_name"], $target_file)) {
                return $target_file; // Devolver la ruta del archivo
            }
        }
        return ""; // Devolver cadena vacía si no se subió archivo
    }
}
?>
