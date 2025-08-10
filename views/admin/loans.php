<?php
// views/admin/loans.php - Vista para la gestión de préstamos por el admin

$page_title = "Gestionar Préstamos";
include_once 'views/includes/header.php';
?>

<div class="page-header">
    <h1>Gestión de Préstamos</h1>
    <p>Supervisa todos los préstamos y registra las devoluciones.</p>
</div>

<div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>Libro</th>
                <th>Estudiante</th>
                <th>Fecha de Préstamo</th>
                <th>Fecha de Devolución</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($stmt) && $stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $status_class = 'status-' . htmlspecialchars($estado);

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($libro_titulo) . "</td>";
                    echo "<td>" . htmlspecialchars($estudiante_nombre) . "</td>";
                    echo "<td>" . date("d/m/Y", strtotime($fecha_prestamo)) . "</td>";
                    echo "<td>" . date("d/m/Y", strtotime($fecha_devolucion_estimada)) . "</td>";
                    echo "<td><span class='status " . $status_class . "'>" . htmlspecialchars($estado) . "</span></td>";
                    echo "<td class='actions'>";
                    if ($estado == 'prestado' || $estado == 'retrasado') {
                        echo "<a href='/biblioteca-app/admin/returnLoan/{$id}' class='btn btn-sm btn-success'>Marcar como Devuelto</a>";
                        // Botón de renovar podría añadirse aquí
                    } else {
                        echo "N/A";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No hay préstamos registrados.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php
include_once 'views/includes/footer.php';
?>
