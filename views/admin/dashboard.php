<?php
// views/admin/dashboard.php - Vista del panel principal del administrador

$page_title = "Dashboard del Administrador";
include_once 'views/includes/header.php';
?>

<div class="page-header">
    <h1>Dashboard del Administrador</h1>
    <p>Resumen general del estado de la biblioteca.</p>
</div>

<div class="dashboard-stats">
    <div class="stat-card">
        <div class="stat-icon icon-books">
            <!-- Icono de libro -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book-open"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
        </div>
        <div class="stat-info">
            <p>Total de Libros</p>
            <span><?php echo isset($total_books) ? $total_books : '0'; ?></span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon icon-users">
            <!-- Icono de usuarios -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        </div>
        <div class="stat-info">
            <p>Total de Usuarios</p>
            <span><?php echo isset($total_users) ? $total_users : '0'; ?></span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon icon-loans">
            <!-- Icono de préstamo -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-up-right"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
        </div>
        <div class="stat-info">
            <p>Préstamos Activos</p>
            <span><?php echo isset($total_loans) ? $total_loans : '0'; ?></span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon icon-tasks">
             <!-- Icono de tareas -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
        </div>
        <div class="stat-info">
            <p>Tareas Pendientes</p>
            <span>_</span> <!-- Dato a implementar -->
        </div>
    </div>
</div>

<div class="quick-actions">
    <h2>Acciones Rápidas</h2>
    <a href="/biblioteca-app/admin/createBook" class="btn btn-primary">Añadir Nuevo Libro</a>
    <a href="/biblioteca-app/admin/createUser" class="btn btn-secondary">Añadir Nuevo Usuario</a>
    <a href="/biblioteca-app/admin/loans" class="btn btn-info">Ver Préstamos</a>
</div>


<?php
include_once 'views/includes/footer.php';
?>
