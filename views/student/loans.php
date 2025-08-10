<?php
// views/student/loans.php - Vista del historial de préstamos del estudiante

$page_title = "Mis Préstamos";
include_once 'views/includes/header.php';
?>

<div class="page-header">
    <h1>Mis Préstamos</h1>
    <p>Aquí puedes ver tu historial de préstamos y el estado de tus libros actuales.</p>
</div>

<div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>Libro</th>
                <th>Autor</th>
                <th>Fecha de Préstamo</th>
                <th>Fecha de Devolución Estimada</th>
                <th>Estado</th>
                <th>Multa</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($stmt) && $stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    // Determinar la clase CSS para el estado
                    $status_class = '';
                    switch ($estado) {
                        case 'prestado':
                            $status_class = 'status-active';
                            break;
                        case 'devuelto':
                            $status_class = 'status-returned';
                            break;
                        case 'retrasado':
                            $status_class = 'status-delayed';
                            break;
                    }

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($libro_titulo) . "</td>";
                    echo "<td>" . htmlspecialchars($libro_autor) . "</td>";
                    echo "<td>" . date("d/m/Y", strtotime($fecha_prestamo)) . "</td>";
                    echo "<td>" . date("d/m/Y", strtotime($fecha_devolucion_estimada)) . "</td>";
                    echo "<td><span class='status " . $status_class . "'>" . htmlspecialchars($estado) . "</span></td>";
                    echo "<td>$" . number_format($multa, 2) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No tienes ningún préstamo registrado.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php
include_once 'views/includes/footer.php';
?>
