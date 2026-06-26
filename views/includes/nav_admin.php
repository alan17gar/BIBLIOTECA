<!-- views/includes/nav_admin.php - Menú de navegación para el Administrador -->

<ul>
    <li><a href="<?php echo BASE_PATH; ?>/admin">Dashboard</a></li>
    <li><a href="<?php echo BASE_PATH; ?>/admin/books">Gestionar Libros</a></li>
    <li><a href="<?php echo BASE_PATH; ?>/admin/users">Gestionar Usuarios</a></li>
    <li><a href="<?php echo BASE_PATH; ?>/admin/loans">Gestionar Préstamos</a></li>
    <li class="user-menu">
        <span>
            <!-- Icono de usuario -->
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            <?php echo htmlspecialchars($_SESSION['username']); ?>
        </span>
        <a href="<?php echo BASE_PATH; ?>/auth/logout" class="logout-button">
            <!-- Icono de logout -->
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
            Salir
        </a>
    </li>
</ul>
