<?php
// views/auth/login.php - Vista del formulario de inicio de sesión

$page_title = "Inicio de Sesión";
include_once 'views/includes/header.php';
?>

<div class="login-container">
    <div class="login-box glass-card">
        <div class="login-logo">
            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--primary-color);"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
            <h2>BIBLIOTECA</h2>
        </div>

        <p class="login-description">Inicia sesión para acceder al sistema</p>

        <?php
        if (isset($error)) {
            echo '<div class="alert alert-danger" style="margin-bottom: 1.5rem; padding: 1rem; border-radius: 12px; background: rgba(255, 23, 68, 0.1); color: var(--danger-color); font-weight: 600;">' . htmlspecialchars($error) . '</div>';
        }
        ?>

        <form action="<?php echo BASE_PATH; ?>/auth/login" method="post" id="login-form">
            <div class="form-group">
                <label for="username">Nombre de Usuario</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="ej: admin" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <div class="form-group" style="margin-top: 2rem;">
                <button type="submit" class="btn btn-primary btn-block">Entrar al Sistema</button>
            </div>
        </form>

    </div>
</div>

<?php
include_once 'views/includes/footer.php';
?>
