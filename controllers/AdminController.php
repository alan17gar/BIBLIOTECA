<?php
ob_start();
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

    public function createLoan() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->loan->libro_id = $_POST['libro_id'];
            $this->loan->estudiante_id = $_POST['usuario_id'];
            $this->loan->ubicacion_lectura = $_POST['ubicacion_lectura'];
            $this->loan->estado = 'prestado';
            $this->loan->multa = 0;

            if ($this->loan->create()) {
                header("Location: " . BASE_PATH . "/admin/loans?success=1");
                exit;
            } else {
                $error = "No se pudo registrar el préstamo. Verifique la disponibilidad del libro.";
            }
        }

        $books = $this->book->readAll();
        $students = $this->student->readAll();
        require 'views/admin/loan_form.php';
    }

    public function createStudentQuick() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->student->cedula = $_POST['cedula'];
            $this->student->nombre_completo = $_POST['nombre_completo'];
            $this->student->anio_secundaria = $_POST['anio_secundaria'];

            if ($this->student->create()) {
                $new_id = $this->db->lastInsertId();
                echo json_encode([
                    'success' => true,
                    'id' => $new_id,
                    'nombre_completo' => $this->student->nombre_completo
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al crear estudiante']);
            }
            exit;
        }
    }

    public function returnLoan($id) {
        $this->loan->id = $id;
        if ($this->loan->returnBook()) {
            header("Location: " . BASE_PATH . "/admin/loans?returned=1");
            exit;
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
        while (ob_get_level()) {
            ob_end_clean();
        }

        // Instanciar la clase FPDF integrada abajo
        $pdf = new LocalFPDF('L', 'mm', 'A4');
        $pdf->AddPage();

        // Encabezado Elegante
        $pdf->SetFillColor(98, 0, 234); 
        $pdf->Rect(0, 0, 297, 40, 'F');

        $pdf->SetFont('Arial', 'B', 22);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(0, 15, utf8_decode('SISTEMA DE BIBLIOTECA'), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(0, 10, utf8_decode('REPORTE DETALLADO DE PRÉSTAMOS'), 0, 1, 'C');

        $pdf->Ln(25);

        // Tabla Estilizada
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(26, 35, 126); 
        $pdf->SetTextColor(255, 255, 255);

        $pdf->Cell(30, 10, utf8_decode('Cédula'), 1, 0, 'C', true);
        $pdf->Cell(60, 10, utf8_decode('Estudiante'), 1, 0, 'C', true);
        $pdf->Cell(25, 10, utf8_decode('Año'), 1, 0, 'C', true);
        $pdf->Cell(85, 10, utf8_decode('Libro Prestado'), 1, 0, 'C', true);
        $pdf->Cell(35, 10, utf8_decode('Fecha'), 1, 0, 'C', true);
        $pdf->Cell(42, 10, utf8_decode('Ubicación'), 1, 1, 'C', true);

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(0, 0, 0);

        $stmt = $this->loan->readAll();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $pdf->Cell(30, 8, utf8_decode($row['estudiante_cedula'] ?? ''), 1, 0, 'C');
            $pdf->Cell(60, 8, utf8_decode($row['nombre_estudiante'] ?? ''), 1, 0, 'L');
            $pdf->Cell(25, 8, utf8_decode($row['anio_estudiante'] ?? ''), 1, 0, 'C');
            $pdf->Cell(85, 8, utf8_decode($row['libro_titulo'] ?? ''), 1, 0, 'L');
            $pdf->Cell(35, 8, date("d/m/Y", strtotime($row['fecha_prestamo'])), 1, 0, 'C');
            $pdf->Cell(42, 8, utf8_decode($row['ubicacion_lectura'] ?? ''), 1, 1, 'L');
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="reporte_prestamos_' . date('Ymd') . '.pdf"');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');

        echo $pdf->Output('S');
        exit;
    }

    public function exportLoansExcel() {
        if (ob_get_length()) ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="prestamos_historial.xls"');
        echo "Libro\tEstudiante\tCédula\tUbicación\tFecha\n";
        $stmt = $this->loan->readAll();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $nombre = isset($row['nombre_estudiante']) ? $row['nombre_estudiante'] : '-';
            $cedula = isset($row['estudiante_cedula']) ? $row['estudiante_cedula'] : '-';
            echo "{$row['libro_titulo']}\t{$nombre}\t{$cedula}\t{$row['ubicacion_lectura']}\t{$row['fecha_prestamo']}\n";
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

// --- CLASE FPDF EMBEBIDA PARA EVITAR ERRORES DE RUTAS ---
class LocalFPDF {
    protected $page; protected $n; protected $offsets; protected $Orientation; protected $wPt; protected $hPt;
    protected $w; protected $h; protected $wPl; protected $hPl; protected $LineWidth; protected $FontFamily;
    protected $FontStyle; protected $FontSizePt; protected $FontSize; protected $TextColor; protected $FillColor;
    protected $ColorFlag; protected $fonts; protected $images; protected $PageLinks; protected $links;
    protected $extgstates; protected $CurrentFont; protected $Buffer; protected $state;

    public function __construct($orientation='P', $unit='mm', $size='A4') {
        $this->page = 0; $this->n = 2; $this->Buffer = ''; $this->state = 0;
        $this->fonts = []; $this->images = []; $this->links = []; $this->extgstates = [];
        $this->LineWidth = 0.567 / 2.834645; $this->FontFamily = 'Arial'; $this->FontStyle = '';
        $this->FontSize = 12 / 2.834645; $this->TextColor = '0 g'; $this->FillColor = '0 g'; $this->ColorFlag = false;
        $this->w = 297; $this->h = 210;
    }
    public function AddPage() {
        $this->page++; $this->offsets[$this->page] = strlen($this->Buffer);
        $this->state = 2; $this->_out('2 0 obj << /Type /Page /Parent 1 0 R /Resources << /Font << /F1 3 0 R >> >> /Contents ' . ($this->page + 2) . ' 0 R >> endobj');
    }
    public function SetFont($family, $style='', $size=0) {
        $this->FontFamily = $family; $this->FontStyle = strtoupper($style); if($size > 0) $this->FontSize = $size / 2.834645;
    }
    public function SetTextColor($r, $g=null, $b=null) { $this->TextColor = sprintf('%.3F %.3F %.3F rg', $r/255, $g/255, $b/255); }
    public function SetFillColor($r, $g=null, $b=null) { $this->FillColor = sprintf('%.3F %.3F %.3F RG', $r/255, $g/255, $b/255); }
    public function Rect($x, $y, $w, $h, $style='') { $this->_out(sprintf('%.2F %.2F %.2F %.2F re %s', $x*2.83, (210-$y-$h)*2.83, $w*2.83, $h*2.83, $style=='F'?'f':'s')); }
    public function Ln($h=null) { $this->_out('1 0 0 1 0 -20 cm'); }
    public function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false) {
        $txt = str_replace(['(', ')', '\\'], ['\\(', '\\)', '\\\\'], $txt);
        $this->_out('BT /F1 ' . ($this->FontSize * 2.83) . ' Tf BC 10 Td (' . $txt . ') Tj ET');
    }
    public function Output($dest='') {
        $this->_out('%%EOF'); return "%PDF-1.4\n1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj\n" . $this->Buffer;
    }
    protected function _out($s) { $this->Buffer .= $s . "\n"; }
}
?>
