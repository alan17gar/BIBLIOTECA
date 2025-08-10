<?php
// views/admin/tasks.php - Vista para la gestión de tareas por el admin

$page_title = "Gestionar Tareas";
include_once 'views/includes/header.php';
?>

<div class="page-header">
    <h1>Gestión de Tareas</h1>
    <!-- Este enlace requerirá una nueva acción y vista de formulario -->
    <a href="/biblioteca-app/admin/createTask" class="btn btn-primary">Asignar Nueva Tarea</a>
</div>

<div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>Título</th>
                <th>Estudiante Asignado</th>
                <th>Libro Relacionado</th>
                <th>Fecha Límite</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($stmt) && $stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                     $status_class = 'status-' . ($estado === 'completada' ? 'returned' : 'pending');

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($titulo) . "</td>";
                    echo "<td>" . htmlspecialchars($estudiante_nombre) . "</td>";
                    echo "<td>" . htmlspecialchars($libro_titulo) . "</td>";
                    echo "<td>" . date("d/m/Y", strtotime($fecha_limite)) . "</td>";
                    echo "<td><span class='status " . $status_class . "'>" . htmlspecialchars(ucfirst($estado)) . "</span></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No hay tareas asignadas.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php
include_once 'views/includes/footer.php';
?>
