<?php
// index.php - Punto de entrada principal de la aplicación

// Iniciar la sesión para gestionar variables de sesión (como el ID de usuario y el rol)
session_start();

// Incluir el archivo de configuración de la base de datos
require_once 'config/db.php';

// Incluir los controladores base para tener una referencia
require_once 'controllers/AuthController.php';
require_once 'controllers/AdminController.php';
require_once 'controllers/StudentController.php';

// Instanciar la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// --- Enrutador Básico ---
// Determinar la ruta solicitada a partir de la URL.
// Usamos `parse_url` para manejar la URL de forma segura.
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Intentar detectar dinámicamente el base_path
$script_name = $_SERVER['SCRIPT_NAME'];
$base_path = str_replace('/index.php', '', $script_name);

// Definir constante para uso global en vistas
if (!defined('BASE_PATH')) {
    define('BASE_PATH', $base_path);
}

// Eliminar el base_path del inicio de la URI de forma segura
if ($base_path !== '' && strpos($request_uri, $base_path) === 0) {
    $route = substr($request_uri, strlen($base_path));
} else {
    $route = $request_uri;
}
$route = trim($route, '/');

// Si la ruta está vacía, por defecto vamos al login.
if ($route === '') {
    $route = 'auth/login';
}

// Dividir la ruta en partes: controlador/acción/parámetro
$parts = explode('/', $route);
$controller_name = isset($parts[0]) ? ucfirst($parts[0]) . 'Controller' : 'AuthController';
$action = isset($parts[1]) ? $parts[1] : 'index';
$param = isset($parts[2]) ? $parts[2] : null;

// --- Lógica del Enrutador ---
// Verificar si el controlador solicitado existe.
if (file_exists('controllers/' . $controller_name . '.php')) {
    require_once 'controllers/' . $controller_name . '.php';

    // Verificar si la clase del controlador existe.
    if (class_exists($controller_name)) {
        $controller = new $controller_name($db);

        // Verificar si la acción (método) existe en el controlador.
        if (method_exists($controller, $action)) {
            // Llamar a la acción, pasando el parámetro si existe.
            $controller->$action($param);
        } else {
            // Error 404: Acción no encontrada
            echo "Error 404: Acción no encontrada.";
        }
    } else {
        // Error 404: Clase del controlador no encontrada
        echo "Error 404: Controlador no encontrado.";
    }
} else {
    // Si la ruta no coincide con un controlador, redirigir a la página de login.
    // Esto es útil para rutas como 'admin' o 'student' que actúan como alias.
    switch ($parts[0]) {
        case 'admin':
            $controller = new AdminController($db);
            $controller->index();
            break;
        case 'student':
            $controller = new StudentController($db);
            $controller->index();
            break;
        default:
            // Por defecto, ir al login si la ruta es desconocida.
            $controller = new AuthController($db);
            $controller->login();
            break;
    }
}
?>
