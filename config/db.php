<?php
// config/db.php - Archivo de configuración de la base de datos en la nube

class Database {
    // Parámetros de conexión reales obtenidos de Clever Cloud
    private $host = 'btguvsqpmzkocvxuyht7-mysql.services.clever-cloud.com';
    private $db_name = 'btguvsqpmzkocvxuyht7';
    private $username = 'u98s78lxcviah7yi';
    private $password = 'szIssCSvmypbtG317rny';
    private $conn;

    // Método para obtener la conexión a la base de datos
    public function getConnection() {
        $this->conn = null;

        try {
            // Crear una nueva instancia de PDO usando la base de datos remota
            $this->conn = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->db_name . ';charset=utf8',
                $this->username,
                $this->password
            );

            // Establecer el modo de error de PDO a excepción
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Deshabilitar emulación de preparaciones para seguridad nativa
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        } catch(PDOException $exception) {
            // Si la conexión falla, mostrar el mensaje de error
            echo 'Error de conexión: ' . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
