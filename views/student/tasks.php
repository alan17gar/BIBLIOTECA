<?php
// views/student/tasks.php - Vista de las tareas asignadas al estudiante

$page_title = "Mis Tareas";
include_once 'views/includes/header.php';
?>

<div class="page-header">
    <h1>Mis Tareas</h1>
    <p>Revisa y completa las tareas que te han asignado.</p>
</div>

<div class="task-list">
    <?php
    if (isset($stmt) && $stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            ?>
            <div class="task-card <?php echo $estado === 'completada' ? 'task-completed' : ''; ?>">
                <div class="task-header">
                    <h3 class="task-title"><?php echo htmlspecialchars($titulo); ?></h3>
                    <span class="status <?php echo $estado === 'completada' ? 'status-returned' : 'status-pending'; ?>">
                        <?php echo htmlspecialchars(ucfirst($estado)); ?>
                    </span>
                </div>
                <div class="task-meta">
                    <span><strong>Libro relacionado:</strong> <?php echo htmlspecialchars($libro_titulo); ?></span>
                    <span><strong>Fecha límite:</strong> <?php echo date("d/m/Y", strtotime($fecha_limite)); ?></span>
                </div>
                <p class="task-description"><?php echo htmlspecialchars($descripcion); ?></p>

                <?php if ($estado === 'pendiente'): ?>
                    <div class="task-response-form">
                        <form action="/biblioteca-app/student/completeTask/<?php echo $id; ?>" method="post">
                            <div class="form-group">
                                <label for="respuesta_<?php echo $id; ?>">Tu respuesta:</label>
                                <textarea name="respuesta" id="respuesta_<?php echo $id; ?>" class="form-control" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-sm btn-success">Completar Tarea</button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="task-response-submitted">
                        <h4>Tu respuesta:</h4>
                        <p><?php echo htmlspecialchars($respuesta); ?></p>
                    </div>
                <?php endif; ?>
            </div>
            <?php
        }
    } else {
        echo "<p>No tienes ninguna tarea asignada en este momento.</p>";
    }
    ?>
</div>

<?php
include_once 'views/includes/footer.php';
?>
