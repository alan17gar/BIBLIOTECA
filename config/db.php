<?php
// config/db.php - Archivo de configuración de la base de datos

class Database {
    // Parámetros de conexión a la base de datos
    // Usar las credenciales estándar de XAMPP/WAMP
    private $host = 'localhost';
    private $db_name = 'biblioteca_app';
    private $username = 'root';
    private 'password' = ''; // Por defecto, la contraseña de root en XAMPP/WAMP es vacía
    private $conn;

    // Método para obtener la conexión a la base de datos
    public function getConnection() {
        $this->conn = null; // Reiniciar la conexión

        try {
            // Crear una nueva instancia de PDO
            $this->conn = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->db_name . ';charset=utf8',
                $this->username,
                $this->password
            );

            // Establecer el modo de error de PDO a excepción para un mejor manejo de errores
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Deshabilitar emulación de preparaciones para usar preparaciones nativas de MySQL
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        } catch(PDOException $exception) {
            // Si la conexión falla, mostrar un mensaje de error
            echo 'Error de conexión: ' . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
