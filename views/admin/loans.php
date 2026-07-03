<?php
// views/admin/loans.php - Vista para la gestión de préstamos por el admin

$page_title = "Gestionar Préstamos";
include_once 'views/includes/header.php';
?>

<div class="page-header">
    <h1>Gestión de Préstamos</h1>
    <div class="header-actions">
        <a href="<?php echo BASE_PATH; ?>/admin/exportLoansPDF" class="btn btn-secondary btn-sm">PDF</a>
        <a href="<?php echo BASE_PATH; ?>/admin/exportLoansExcel" class="btn btn-secondary btn-sm">Excel</a>
        <a href="<?php echo BASE_PATH; ?>/admin/createLoan" class="btn btn-primary">Registrar Préstamo</a>
    </div>
</div>

<div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>Estudiante</th>
                <th>Cédula</th>
                <th>Año</th>
                <th>Libro</th>
                <th>ISBN</th>
                <th>Ubicación Lectura</th>
                <th>Fecha Préstamo</th>
                <th>Fecha Dev.</th>
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
                    echo "<td>" . htmlspecialchars($estudiante_nombre) . "</td>";
                    echo "<td>" . htmlspecialchars($estudiante_cedula) . "</td>";
                    echo "<td>" . htmlspecialchars($estudiante_anio) . "</td>";
                    echo "<td>" . htmlspecialchars($libro_titulo) . "</td>";
                    echo "<td>" . htmlspecialchars($libro_isbn) . "</td>";
                    echo "<td>" . htmlspecialchars($ubicacion_lectura) . "</td>";
                    echo "<td>" . date("d/m/Y", strtotime($fecha_prestamo)) . "</td>";
                    echo "<td>" . date("d/m/Y", strtotime($fecha_devolucion_estimada)) . "</td>";
                    echo "<td><span class='status " . $status_class . "'>" . htmlspecialchars($estado) . "</span></td>";
                    echo "<td class='actions'>";
                    if ($estado == 'prestado' || $estado == 'retrasado') {
                        echo "<a href='" . BASE_PATH . "/admin/returnLoan/{$id}' class='btn btn-sm btn-success'>Devolver</a>";
                    } else {
                        echo "N/A";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='10'>No hay préstamos registrados.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php
include_once 'views/includes/footer.php';
?>
