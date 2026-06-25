<?php
// views/student/dashboard.php - Vista del panel principal del estudiante

$page_title = "Mi Dashboard";
include_once 'views/includes/header.php';
?>

<div class="page-header">
    <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <p>Aquí tienes un resumen de tu actividad en la biblioteca.</p>
</div>

<div class="dashboard-stats">
    <div class="stat-card">
        <div class="stat-icon icon-loans">
            <!-- Icono de préstamo -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-up-right"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
        </div>
        <div class="stat-info">
            <p>Préstamos Activos</p>
            <span><?php echo isset($active_loans_count) ? $active_loans_count : '0'; ?></span>
        </div>
        <a href="<?php echo BASE_PATH; ?>/student/loans" class="stat-link">Ver mis préstamos</a>
    </div>

    <div class="stat-card">
        <div class="stat-icon icon-tasks">
             <!-- Icono de tareas -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
        </div>
        <div class="stat-info">
            <p>Tareas Pendientes</p>
            <span><?php echo isset($pending_tasks_count) ? $pending_tasks_count : '0'; ?></span>
        </div>
        <a href="<?php echo BASE_PATH; ?>/student/tasks" class="stat-link">Ver mis tareas</a>
    </div>

    <div class="stat-card">
        <div class="stat-icon icon-books">
            <!-- Icono de libro -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book-open"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
        </div>
        <div class="stat-info">
            <p>Explorar Catálogo</p>
            <span>Buscar y solicitar</span>
        </div>
        <a href="<?php echo BASE_PATH; ?>/student/books" class="stat-link">Buscar libros</a>
    </div>
</div>

<div class="recent-activity">
    <h2>Actividad Reciente</h2>

    <h3>Mis Préstamos Actuales</h3>
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Libro</th>
                    <th>Fecha de Préstamo</th>
                    <th>Fecha de Devolución</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($loans) && $loans->rowCount() > 0) {
                    while ($row = $loans->fetch(PDO::FETCH_ASSOC)) {
                        if ($row['estado'] == 'prestado') {
                             echo "<tr>";
                             echo "<td>" . htmlspecialchars($row['libro_titulo']) . "</td>";
                             echo "<td>" . date("d/m/Y", strtotime($row['fecha_prestamo'])) . "</td>";
                             echo "<td>" . date("d/m/Y", strtotime($row['fecha_devolucion_estimada'])) . "</td>";
                             echo "<td><span class='status status-" . htmlspecialchars($row['estado']) . "'>" . htmlspecialchars($row['estado']) . "</span></td>";
                             echo "</tr>";
                        }
                    }
                } else {
                    echo "<tr><td colspan='4'>No tienes préstamos activos.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>


<?php
include_once 'views/includes/footer.php';
?>
