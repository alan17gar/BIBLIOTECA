<?php
// views/admin/task_form.php - Formulario para asignar una nueva tarea

$page_title = "Asignar Nueva Tarea";
include_once 'views/includes/header.php';
?>

<div class="page-header">
    <h1><?php echo $page_title; ?></h1>
    <a href="<?php echo BASE_PATH; ?>/admin/tasks" class="btn btn-secondary">Volver a la lista de tareas</a>
</div>

<div class="form-container">
    <form action="<?php echo BASE_PATH; ?>/admin/createTask" method="post" id="task-form">

        <div class="form-group">
            <label for="titulo">Título de la Tarea</label>
            <input type="text" id="titulo" name="titulo" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" class="form-control" rows="4" required></textarea>
        </div>

        <div class="form-group">
            <label for="usuario_asignado_id">Asignar a Usuario</label>
            <select id="usuario_asignado_id" name="usuario_asignado_id" class="form-control" required>
                <option value="">-- Selecciona un usuario --</option>
                <?php
                // Iterar sobre los usuarios pasados desde el controlador
                if (isset($students) && $students->rowCount() > 0) {
                    while ($row = $students->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['nombre_completo']) . " (" . htmlspecialchars($row['nombre_usuario']) . ")</option>";
                    }
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="libro_relacionado_id">Libro Relacionado (Opcional)</label>
            <select id="libro_relacionado_id" name="libro_relacionado_id" class="form-control">
                <option value="">-- Selecciona un libro --</option>
                 <?php
                // Iterar sobre los libros pasados desde el controlador
                if (isset($books) && $books->rowCount() > 0) {
                    while ($row = $books->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['titulo']) . "</option>";
                    }
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="fecha_limite">Fecha Límite</label>
            <input type="date" id="fecha_limite" name="fecha_limite" class="form-control" required>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Asignar Tarea</button>
        </div>
    </form>
</div>

<?php
include_once 'views/includes/footer.php';
?>
