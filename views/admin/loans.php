<?php
// views/admin/loans.php - Vista para la gestión de préstamos por el admin

$page_title = "Gestionar Préstamos";
include_once 'views/includes/header.php';
?>

<?php if (isset($_GET['success'])): ?>
    <div class="alert-card success">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
        Préstamo registrado exitosamente.
    </div>
<?php endif; ?>

<?php if (isset($_GET['returned'])): ?>
    <div class="alert-card success">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        Libro devuelto y stock actualizado.
    </div>
<?php endif; ?>

<div class="page-header">
    <h1>Gestión de Préstamos</h1>
    <div class="header-actions">
        <a href="<?php echo BASE_PATH; ?>/admin/createLoan" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Registrar Préstamo
        </a>
        <a href="<?php echo BASE_PATH; ?>/admin/exportLoansPDF" class="btn btn-info btn-sm" onclick="showToast('Generando PDF...', 'info')">PDF</a>
        <a href="<?php echo BASE_PATH; ?>/admin/exportLoansExcel" class="btn btn-success btn-sm" onclick="showToast('Generando Excel...', 'success')">Excel</a>
    </div>
</div>


<div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>Libro</th>
                <th>Estudiante (C.I.)</th>
                <th>Año / Ubicación</th>
                <th>Fecha Préstamo</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($stmt) && $stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $status_class = 'status-' . htmlspecialchars($row['estado']);

                    echo "<tr>";
                    echo "<td><strong>" . htmlspecialchars($row['libro_titulo']) . "</strong></td>";
                    echo "<td>" . htmlspecialchars($row['nombre_estudiante']) . "<br><small>C.I. " . htmlspecialchars($row['estudiante_cedula']) . "</small></td>";
                    echo "<td>" . htmlspecialchars($row['anio_estudiante']) . "<br><small>" . htmlspecialchars($row['ubicacion_lectura']) . "</small></td>";
                    echo "<td>" . date("d/m/Y", strtotime($row['fecha_prestamo'])) . "</td>";
                    echo "<td><span class='status " . $status_class . "'>" . htmlspecialchars($row['estado']) . "</span></td>";
                    echo "<td class='actions'>";
                    if ($estado == 'prestado' || $estado == 'retrasado') {
                        echo "<a href='" . BASE_PATH . "/admin/returnLoan/{$id}' class='btn btn-sm btn-success'>Marcar como Devuelto</a>";
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
