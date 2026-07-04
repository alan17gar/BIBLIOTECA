<?php
// views/admin/loan_form.php - Formulario para registrar un préstamo

$page_title = "Registrar Préstamo";
include_once 'views/includes/header.php';
?>

<?php include_once 'views/includes/alerts.php'; ?>

<div class="page-header">
    <h1>Registrar Nuevo Préstamo</h1>
    <a href="<?php echo BASE_PATH; ?>/admin/loans" class="btn btn-secondary">Volver a la lista</a>
</div>


<div class="form-container glass-card" style="padding: 2rem; max-width: 600px; margin: 0 auto;">
    <form action="<?php echo BASE_PATH; ?>/admin/createLoan" method="POST">
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <!-- Datos del Préstamo -->
            <div class="loan-info">
                <h3>Detalles del Préstamo Interno</h3>
                <div class="form-group">
                    <label for="libro_id">Libro Seleccionado</label>
                    <select id="libro_id" name="libro_id" class="form-control" required>
                        <option value="">-- Selecciona un libro --</option>
                        <?php
                        while ($book = $books->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='{$book['id']}'>{$book['titulo']} (ISBN: {$book['isbn']}) - Disp: {$book['cantidad_disponible']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="ubicacion_lectura">Ubicación de Lectura</label>
                    <select id="ubicacion_lectura" name="ubicacion_lectura" class="form-control" required>
                        <option value="Biblioteca">Biblioteca</option>
                        <option value="Aula con Profesor">Aula con Profesor</option>
                        <option value="Hogar">Hogar</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="fecha_devolucion_estimada">Fecha Estimada de Devolución</label>
                    <input type="date" id="fecha_devolucion_estimada" name="fecha_devolucion_estimada" class="form-control" required value="<?php echo date('Y-m-d', strtotime('+15 days')); ?>">
                </div>
            </div>
        </div>

        <div class="form-actions" style="margin-top: 2rem; text-align: right;">
            <button type="submit" class="btn btn-primary btn-lg">Registrar Préstamo</button>
        </div>
    </form>
</div>

<?php
include_once 'views/includes/footer.php';
?>
