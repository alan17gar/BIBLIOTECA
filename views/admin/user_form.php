<?php
// views/admin/user_form.php - Formulario para crear o editar un usuario

$is_edit = isset($this->user) && !empty($this->user->id);
$page_title = $is_edit ? "Editar Usuario" : "Añadir Nuevo Usuario";

include_once 'views/includes/header.php';
?>

<div class="page-header">
    <h1><?php echo $page_title; ?></h1>
    <a href="<?php echo BASE_PATH; ?>/admin/users" class="btn btn-secondary">Volver a la lista</a>
</div>

<div class="form-container">
    <form action="<?php echo $is_edit ? BASE_PATH . '/admin/editUser/' . $this->user->id : BASE_PATH . '/admin/createUser'; ?>" method="post" id="user-form">

        <div class="form-group">
            <label for="nombre_completo">Nombre Completo</label>
            <input type="text" id="nombre_completo" name="nombre_completo" class="form-control" value="<?php echo $is_edit ? htmlspecialchars($this->user->nombre_completo) : ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="nombre_usuario">Nombre de Usuario</label>
            <input type="text" id="nombre_usuario" name="nombre_usuario" class="form-control" value="<?php echo $is_edit ? htmlspecialchars($this->user->nombre_usuario) : ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="correo">Correo Electrónico</label>
            <input type="email" id="correo" name="correo" class="form-control" value="<?php echo $is_edit ? htmlspecialchars($this->user->correo) : ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" class="form-control" <?php echo !$is_edit ? 'required' : ''; ?>>
            <?php if ($is_edit): ?>
                <small class="form-text">Dejar en blanco para no cambiar la contraseña.</small>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="rol">Rol</label>
            <select id="rol" name="rol" class="form-control" required>
                <option value="admin" selected>Administrador</option>
            </select>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary"><?php echo $is_edit ? 'Actualizar Usuario' : 'Guardar Usuario'; ?></button>
        </div>
    </form>
</div>

<?php
include_once 'views/includes/footer.php';
?>
