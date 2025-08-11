<?php
// sql/seed.php - Script para poblar la base de datos con datos de prueba funcionales.

// --- CONFIGURACIÓN ---
// Incluir la configuración de la base de datos y el modelo de usuario.
// Usamos `__DIR__` para asegurarnos de que las rutas funcionen sin importar desde dónde se ejecute el script.
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/User.php';

echo "<!DOCTYPE html><html lang='es'><head><meta charset='UTF-8'><title>Seeder de Base de Datos</title>";
echo "<style>body { font-family: sans-serif; line-height: 1.6; padding: 20px; } .success { color: green; } .error { color: red; }</style>";
echo "</head><body>";
echo "<h1>Iniciando el proceso de siembra de datos...</h1>";

// --- CONEXIÓN A LA BD ---
try {
    $database = new Database();
    $db = $database->getConnection();
    $user = new User($db);
    echo "<p class='success'>Conexión a la base de datos exitosa.</p>";
} catch (Exception $e) {
    echo "<p class='error'>Error de conexión a la base de datos: " . $e->getMessage() . "</p>";
    echo "</body></html>";
    exit;
}

// --- DATOS DE PRUEBA ---
$users_to_create = [
    [
        'nombre_usuario' => 'admin',
        'password' => 'admin123',
        'rol' => 'admin',
        'nombre_completo' => 'Administrador del Sistema',
        'correo' => 'admin@biblioteca.app'
    ],
    [
        'nombre_usuario' => 'estudiante1',
        'password' => 'estudiante123',
        'rol' => 'student',
        'nombre_completo' => 'Juan Pérez',
        'correo' => 'juan.perez@email.com'
    ],
    [
        'nombre_usuario' => 'estudiante2',
        'password' => 'estudiante123',
        'rol' => 'student',
        'nombre_completo' => 'María García',
        'correo' => 'maria.garcia@email.com'
    ]
];

// --- LÓGICA DEL SEEDER ---
try {
    // 1. Limpiar la tabla de usuarios para evitar duplicados
    $db->exec("SET FOREIGN_KEY_CHECKS = 0;"); // Desactivar revisión de claves foráneas
    $db->exec("TRUNCATE TABLE usuarios;");
    $db->exec("SET FOREIGN_KEY_CHECKS = 1;"); // Reactivar revisión
    echo "<p>Tabla 'usuarios' limpiada con éxito.</p>";

    // 2. Insertar cada usuario
    echo "<h2>Insertando usuarios...</h2>";
    foreach ($users_to_create as $userData) {
        $user->nombre_usuario = $userData['nombre_usuario'];
        $user->password = $userData['password']; // La contraseña en texto plano
        $user->rol = $userData['rol'];
        $user->nombre_completo = $userData['nombre_completo'];
        $user->correo = $userData['correo'];

        // El método `create()` del modelo se encarga de hashear la contraseña
        if ($user->create()) {
            echo "<p class='success'>Usuario '{$userData['nombre_usuario']}' creado con éxito. Contraseña: '{$userData['password']}'</p>";
        } else {
            echo "<p class='error'>Error al crear el usuario '{$userData['nombre_usuario']}'.</p>";
        }
    }

    echo "<h2>Proceso de siembra completado.</h2>";
    echo "<p>Ahora puedes iniciar sesión con las credenciales mencionadas arriba.</p>";

} catch (PDOException $e) {
    echo "<p class='error'>Ha ocurrido un error durante la siembra: " . $e->getMessage() . "</p>";
}

echo "</body></html>";
?>
