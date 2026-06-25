<?php
// views/admin/books.php - Vista para la gestión de libros

$page_title = "Gestionar Libros";
include_once 'views/includes/header.php';
?>

<div class="page-header">
    <h1>Gestión de Libros</h1>
    <a href="<?php echo BASE_PATH; ?>/admin/createBook" class="btn btn-primary">
        <!-- Icono de añadir -->
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        Añadir Nuevo Libro
    </a>
</div>

<div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>Portada</th>
                <th>Título</th>
                <th>Autor</th>
                <th>ISBN</th>
                <th>Categoría</th>
                <th>Disponibles</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Iterar sobre los libros obtenidos del controlador
            if (isset($stmt) && $stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    echo "<tr>";
                    echo "<td><img src='/{$portada}' alt='Portada de " . htmlspecialchars($titulo) . "' class='book-cover-thumbnail' /></td>";
                    echo "<td>" . htmlspecialchars($titulo) . "</td>";
                    echo "<td>" . htmlspecialchars($autor) . "</td>";
                    echo "<td>" . htmlspecialchars($isbn) . "</td>";
                    echo "<td>" . htmlspecialchars($categoria) . "</td>";
                    echo "<td>" . htmlspecialchars($cantidad_disponible) . "</td>";
                    echo "<td>" . htmlspecialchars($cantidad_total) . "</td>";
                    echo "<td class='actions'>";
                    // Botones de acción (editar, eliminar)
                    echo "<a href='" . BASE_PATH . "/admin/editBook/{$id}' class='btn btn-sm btn-warning'>Editar</a>";
                    echo "<a href='" . BASE_PATH . "/admin/deleteBook/{$id}' class='btn btn-sm btn-danger' onclick='return confirm(\"¿Estás seguro de que quieres eliminar este libro?\");'>Eliminar</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No se encontraron libros.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php
include_once 'views/includes/footer.php';
?>
