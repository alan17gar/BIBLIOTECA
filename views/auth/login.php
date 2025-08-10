<?php
// views/auth/login.php - Vista del formulario de inicio de sesión

// No incluir el header principal porque no debe mostrar la navegación de admin/student
// En su lugar, podríamos tener un header_login.php simple o manejarlo aquí.
// Por simplicidad y para reutilizar el CSS, usaremos el header principal,
// el cual no mostrará la navegación si el usuario no ha iniciado sesión.
$page_title = "Inicio de Sesión";
// El header no mostrará nav si no hay sesión
include_once 'views/includes/header.php';
?>

<div class="login-container">
    <div class="login-box">
        <div class="login-logo">
            <!-- Icono SVG de un libro -->
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
            <h2>Biblioteca App</h2>
        </div>

        <p class="login-description">Inicia sesión para acceder al sistema</p>

        <?php
        // Mostrar mensaje de error si existe
        if (isset($error)) {
            echo '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>';
        }
        ?>

        <form action="/biblioteca-app/auth/login" method="post" id="login-form">
            <div class="form-group">
                <label for="username">Nombre de Usuario</label>
                <input type="text" id="username" name="username" class="form-control" required>
                <div class="form-error"></div>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control" required>
                <div class="form-error"></div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Entrar</button>
            </div>
        </form>

        <div class="login-footer">
            <p><strong>Credenciales de prueba:</strong></p>
            <p>Admin: `admin` / `admin123`</p>
            <p>Estudiante: `estudiante1` / `estudiante123`</p>
        </div>
    </div>
</div>

<?php
// Incluir el footer
include_once 'views/includes/footer.php';
?>
