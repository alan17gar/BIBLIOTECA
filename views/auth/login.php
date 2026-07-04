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

        <?php include_once 'views/includes/alerts.php'; ?>

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

        <div class="login-footer" style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--glass-border);">
            <p style="font-size: 0.85rem; font-weight: 600; color: var(--secondary-color); opacity: 0.6;">CREDENCIALES DE PRUEBA</p>
            <div style="display: flex; justify-content: center; gap: 1rem; margin-top: 0.5rem; font-size: 0.8rem;">
                <span>Admin: <code style="color: var(--primary-color);">admin</code></span>
                <span>Pass: <code style="color: var(--primary-color);">admin123</code></span>
            </div>
        </div>
    </div>
</div>

<?php
include_once 'views/includes/footer.php';
?>
