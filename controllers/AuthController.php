<?php
// controllers/AuthController.php - Controlador para la autenticación de usuarios

// Incluir el modelo de Usuario para interactuar con la base de datos
require_once 'models/User.php';

class AuthController {
    private $db;
    private $user;

    // Constructor que recibe la conexión a la BD e instancia el modelo User
    public function __construct($db) {
        $this->db = $db;
        $this->user = new User($this->db);
    }

    // --- Acción de Login ---
    // Muestra el formulario de login y procesa los datos enviados
    public function login() {
        // Verificar si el formulario ha sido enviado (petición POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Asignar los datos del formulario al objeto User
            $this->user->nombre_usuario = $_POST['username'];
            $this->user->password = $_POST['password'];

            // Intentar hacer login
            $login_result = $this->user->login();

            if ($login_result === 0) { // 0 = Éxito
                // Si el login es exitoso, guardar datos en la sesión
                $_SESSION['user_id'] = $this->user->id;
                $_SESSION['user_role'] = $this->user->rol;
                $_SESSION['username'] = $this->user->nombre_usuario;

                // Redirigir según el rol del usuario
                if ($this->user->rol === 'admin') {
                    header("Location: " . BASE_PATH . "/admin");
                } else {
                    // Por defecto si hay otros roles (como ayudantes futuros) o error
                    header("Location: " . BASE_PATH . "/admin");
                }
                exit;
            } else {
                // Si el login falla, determinar el mensaje de error específico
                if ($login_result === 1) {
                    $error = "El nombre de usuario no se ha encontrado.";
                } else if ($login_result === 2) {
                    $error = "La contraseña es incorrecta.";
                } else {
                    $error = "Ha ocurrido un error inesperado durante el login.";
                }
                // Cargar la vista de login y pasarle el mensaje de error
                require 'views/auth/login.php';
            }
        } else {
            // Si no es una petición POST, simplemente mostrar el formulario de login
            require 'views/auth/login.php';
        }
    }

    // --- Acción de Logout ---
    // Cierra la sesión del usuario
    public function logout() {
        // Destruir todas las variables de sesión
        session_unset();
        session_destroy();

        // Redirigir al usuario a la página de login
        header("Location: " . BASE_PATH . "/auth/login");
        exit;
    }

    // --- Control de Acceso ---
    // Métodos estáticos para verificar si el usuario tiene el rol adecuado

    public static function requireAdmin() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            // Si no es admin, redirigir al login
            header("Location: " . BASE_PATH . "/auth/login");
            exit;
        }
    }


    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}
?>
