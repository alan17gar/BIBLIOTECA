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

        <div class="form-group">
            <label for="estudiante_id">Estudiante</label>
            <select name="estudiante_id" id="estudiante_id" class="form-control" required>
                <option value="">Seleccione un estudiante</option>
                <?php
                if (isset($students)) {
                    while ($s = $students->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$s['id']}'>" . htmlspecialchars($s['nombre_completo']) . " (C.I. " . htmlspecialchars($s['cedula']) . ")</option>";
                    }
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="ubicacion_lectura">Ubicación de Lectura</label>
            <select name="ubicacion_lectura" id="ubicacion_lectura" class="form-control" required>
                <option value="">Seleccione ubicación</option>
                <option value="Biblioteca">Biblioteca</option>
                <option value="Aula con Profesor">Aula con Profesor</option>
                <option value="Hogar">Hogar</option>
            </select>
        </div>

        <div class="form-group" style="margin-top: 1rem;">
            <button type="submit" class="btn btn-primary btn-block">Registrar Préstamo</button>
        </div>
    </form>
</div>

<?php
include_once 'views/includes/footer.php';
?>
