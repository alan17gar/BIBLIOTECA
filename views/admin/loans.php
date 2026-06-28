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
                <th>Cédula</th>
                <th>Año</th>
                <th>Ubicación</th>
                <th>Fecha de Préstamo</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($stmt) && $stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // Evitamos extract() y leemos de forma segura protegiendo contra nulos o variables no definidas
                    $id_prestamo = $row['id'] ?? 0;
                    $libro = $row['libro_titulo'] ?? '-';
                    $estudiante = $row['nombre_estudiante'] ?? '-';
                    $cedula = $row['estudiante_cedula'] ?? '-';
                    $anio = $row['anio_estudiante'] ?? '-';
                    $ubicacion = $row['ubicacion_lectura'] ?? '-';
                    $fecha = isset($row['fecha_prestamo']) ? date("d/m/Y", strtotime($row['fecha_prestamo'])) : '-';
                    $estado_actual = $row['estado'] ?? 'prestado';
                    
                    // Clase CSS para el diseño visual del estado
                    $status_class = 'status-' . htmlspecialchars($estado_actual);

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($libro) . "</td>";
                    echo "<td>" . htmlspecialchars($estudiante) . "</td>";
                    echo "<td>" . htmlspecialchars($cedula) . "</td>";
                    echo "<td>" . htmlspecialchars($anio) . "</td>";
                    echo "<td>" . htmlspecialchars($ubicacion) . "</td>";
                    echo "<td>" . htmlspecialchars($fecha) . "</td>";
                    echo "<td><span class='status " . $status_class . "'>" . ucfirst(htmlspecialchars($estado_actual)) . "</span></td>";
                    echo "<td class='actions'>";
                    
                    if ($estado_actual === 'prestado' || $estado_actual === 'retrasado') {
                        echo "<a href='" . BASE_PATH . "/admin/returnLoan/{$id_prestamo}' class='btn btn-sm btn-success'>Marcar como Devuelto</a>";
                    } else {
                        echo "<span class='text-muted'>N/A</span>";
                    }
                    
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No hay préstamos registrados.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php
include_once 'views/includes/footer.php';
?>
