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
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.6rem;">
                <label for="usuario_id" style="margin-bottom: 0;">Estudiante</label>
                <button type="button" id="openStudentModal" class="btn btn-sm btn-accent" style="padding: 0.4rem 0.8rem; font-size: 0.75rem;">+ Registrar Estudiante</button>
            </div>
            <select name="usuario_id" id="usuario_id" class="form-control" required>
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

<!-- Modal para Registro Rápido de Estudiante -->
<div id="studentModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 2000; justify-content: center; align-items: center; backdrop-filter: blur(4px);">
    <div class="glass-card" style="width: 90%; max-width: 500px; padding: 2.5rem; position: relative; animation: modalIn 0.3s ease;">
        <h3 style="margin-bottom: 1.5rem; font-weight: 800;">Registrar Estudiante</h3>

        <form id="quickStudentForm">
            <div class="form-group">
                <label for="m_cedula">Cédula</label>
                <input type="text" id="m_cedula" name="cedula" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="m_nombre">Nombre Completo</label>
                <input type="text" id="m_nombre" name="nombre_completo" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="m_anio">Año/Grado</label>
                <select id="m_anio" name="anio_secundaria" class="form-control" required>
                    <option value="1er Año">1er Año</option>
                    <option value="2do Año">2do Año</option>
                    <option value="3er Año">3er Año</option>
                    <option value="4to Año">4to Año</option>
                    <option value="5to Año">5to Año</option>
                </select>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary btn-sm btn-block">Guardar</button>
                <button type="button" id="closeStudentModal" class="btn btn-secondary btn-sm" style="width: auto;">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<style>
@keyframes modalIn {
    from { transform: scale(0.9); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('studentModal');
    const openBtn = document.getElementById('openStudentModal');
    const closeBtn = document.getElementById('closeStudentModal');
    const form = document.getElementById('quickStudentForm');
    const studentSelect = document.getElementById('usuario_id');

    openBtn.onclick = () => modal.style.display = 'flex';
    closeBtn.onclick = () => modal.style.display = 'none';

    form.onsubmit = async (e) => {
        e.preventDefault();
        const formData = new FormData(form);

        try {
            const response = await fetch('<?php echo BASE_PATH; ?>/admin/createStudentQuick', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();

            if(data.success) {
                // Agregar al select y seleccionar
                const option = new Option(data.nombre_completo, data.id, true, true);
                studentSelect.add(option);

                // Cerrar y limpiar
                modal.style.display = 'none';
                form.reset();
                showToast('Estudiante registrado y seleccionado.');
            } else {
                showToast(data.message || 'Error al guardar', 'error');
            }
        } catch (err) {
            showToast('Error de conexión', 'error');
        }
    };
});
</script>

<?php
include_once 'views/includes/footer.php';
?>
