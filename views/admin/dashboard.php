<?php
// views/admin/dashboard.php - Vista del panel principal del administrador

$page_title = "Dashboard";
include_once 'views/includes/header.php';
?>

<div class="dashboard-header">
    <h1>Panel de Control</h1>
    <p>Bienvenido, aquí tienes el resumen del estado de tu biblioteca.</p>
</div>

<div class="dashboard-stats">
    <div class="stat-card glass-card">
        <div class="stat-icon icon-books">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
        </div>
        <div class="stat-info">
            <p>Total Libros</p>
            <span><?php echo isset($total_books) ? $total_books : '0'; ?></span>
        </div>
    </div>

    <div class="stat-card glass-card">
        <div class="stat-icon icon-users">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        </div>
        <div class="stat-info">
            <p>Usuarios</p>
            <span><?php echo isset($total_users) ? $total_users : '0'; ?></span>
        </div>
    </div>

    <div class="stat-card glass-card">
        <div class="stat-icon icon-loans">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
        </div>
        <div class="stat-info">
            <p>Préstamos</p>
            <span><?php echo isset($total_loans) ? $total_loans : '0'; ?></span>
        </div>
    </div>

</div>

<div class="quick-actions glass-card" style="padding: 2.5rem;">
    <h2>Acciones Rápidas</h2>
    <div class="actions-container">
        <a href="<?php echo BASE_PATH; ?>/admin/createBook" class="btn btn-primary">Añadir Libro</a>
        <a href="<?php echo BASE_PATH; ?>/admin/createUser" class="btn btn-primary btn-accent">Nuevo Usuario</a>
        <a href="<?php echo BASE_PATH; ?>/admin/loans" class="btn glass-card btn-glass">Ver Préstamos</a>
    </div>
</div>

<?php
include_once 'views/includes/footer.php';
?>
