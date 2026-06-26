<?php
// views/admin/users.php - Vista para la gestión de usuarios

$page_title = "Gestionar Usuarios";
include_once 'views/includes/header.php';
?>

<?php if (isset($_GET['success'])): ?>
    <div class="alert-card success">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
        Usuario actualizado correctamente.
    </div>
<?php endif; ?>

<div class="page-header">
    <h1>Gestión de Usuarios</h1>
    <!-- El enlace para crear usuario apuntará a una acción que aún no he implementado en el controlador -->
    <a href="<?php echo BASE_PATH; ?>/admin/createUser" class="btn btn-primary">
        Añadir Nuevo Usuario
    </a>
</div>

<div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre de Usuario</th>
                <th>Nombre Completo</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Fecha de Creación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($stmt) && $stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($id) . "</td>";
                    echo "<td>" . htmlspecialchars($nombre_usuario) . "</td>";
                    echo "<td>" . htmlspecialchars($nombre_completo) . "</td>";
                    echo "<td>" . htmlspecialchars($correo) . "</td>";
                    echo "<td>" . htmlspecialchars($rol) . "</td>";
                    echo "<td>" . date("d/m/Y", strtotime($fecha_creacion)) . "</td>";
                    echo "<td class='actions'>";
                    echo "<a href='" . BASE_PATH . "/admin/editUser/{$id}' class='btn btn-sm btn-warning'>Editar</a>";
                    // Evitar que el admin se borre a sí mismo
                    if ($_SESSION['user_id'] != $id) {
                        echo "<a href='" . BASE_PATH . "/admin/deleteUser/{$id}' class='btn btn-sm btn-danger' onclick='return confirm(\"¿Estás seguro?\");'>Eliminar</a>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No se encontraron usuarios.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php
include_once 'views/includes/footer.php';
?>
