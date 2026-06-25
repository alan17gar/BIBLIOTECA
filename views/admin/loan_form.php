<?php
// views/admin/loan_form.php - Formulario para registrar un préstamo manualmente

$page_title = "Registrar Préstamo";
include_once 'views/includes/header.php';
?>

<div class="page-header">
    <h1>Registrar Nuevo Préstamo</h1>
    <a href="<?php echo BASE_PATH; ?>/admin/loans" class="btn btn-secondary">Volver a la lista</a>
</div>

<?php if (isset($error)): ?>
    <div class="alert-card error" style="background: rgba(255, 23, 68, 0.1); color: var(--danger-color); padding: 1rem; border-radius: 12px; margin-bottom: 2rem; border: 1px solid var(--danger-color);">
        <?php echo $error; ?>
    </div>
<?php endif; ?>

<div class="form-container glass-card" style="padding: 2rem; max-width: 800px; margin: 0 auto;">
    <form action="<?php echo BASE_PATH; ?>/admin/createLoan" method="post">

        <div class="form-group">
            <label for="libro_id">Libro a Prestar</label>
            <select name="libro_id" id="libro_id" class="form-control" required>
                <option value="">Seleccione un libro</option>
                <?php
                if (isset($books)) {
                    while ($row = $books->fetch(PDO::FETCH_ASSOC)) {
                        $disabled = ($row['cantidad_disponible'] <= 0) ? 'disabled' : '';
                        $label = htmlspecialchars($row['titulo']) . " (" . $row['cantidad_disponible'] . " disponibles)";
                        echo "<option value='{$row['id']}' {$disabled}>{$label}</option>";
                    }
                }
                ?>
            </select>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label for="nombre_estudiante">Nombre del Estudiante</label>
                <input type="text" id="nombre_estudiante" name="nombre_estudiante" class="form-control" required placeholder="Ej. Juan Pérez">
            </div>

            <div class="form-group">
                <label for="codigo_prestamo">Código de Préstamo (Opcional)</label>
                <input type="text" id="codigo_prestamo" name="codigo_prestamo" class="form-control" placeholder="Ej. PRE-001">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label for="anio_estudiante">Año/Grado</label>
                <select name="anio_estudiante" id="anio_estudiante" class="form-control" required>
                    <option value="">Seleccione el año</option>
                    <option value="1er Año">1er Año</option>
                    <option value="2do Año">2do Año</option>
                    <option value="3er Año">3er Año</option>
                    <option value="4to Año">4to Año</option>
                    <option value="5to Año">5to Año</option>
                    <option value="Docente/Personal">Docente/Personal</option>
                </select>
            </div>

            <div class="form-group">
                <label for="ubicacion_lectura">Ubicación de Lectura</label>
                <select name="ubicacion_lectura" id="ubicacion_lectura" class="form-control" required>
                    <option value="">Seleccione ubicación</option>
                    <option value="Aula">Aula</option>
                    <option value="Biblioteca">Biblioteca</option>
                    <option value="Hogar">Hogar (Préstamo Externo)</option>
                    <option value="Patio/Cancha">Patio/Cancha</option>
                </select>
            </div>
        </div>

        <div class="form-group" style="margin-top: 1rem;">
            <button type="submit" class="btn btn-primary btn-block">Registrar Préstamo</button>
        </div>
    </form>
</div>

<?php
include_once 'views/includes/footer.php';
?>
