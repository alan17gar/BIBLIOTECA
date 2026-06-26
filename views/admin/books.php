<?php
// views/admin/books.php - Vista para la gestión de libros

$page_title = "Gestionar Libros";
include_once 'views/includes/header.php';
?>

<?php if (isset($_GET['success'])): ?>
    <div class="alert-card success">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
        Operación realizada con éxito.
    </div>
<?php endif; ?>

<div class="page-header">
    <h1>Gestión de Libros</h1>
    <div class="header-actions">
        <a href="<?php echo BASE_PATH; ?>/admin/createBook" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Añadir Nuevo Libro
        </a>
        <a href="<?php echo BASE_PATH; ?>/admin/exportBooksPDF" class="btn btn-info btn-sm" onclick="showToast('Iniciando descarga de PDF...', 'info')">PDF</a>
        <a href="<?php echo BASE_PATH; ?>/admin/exportBooksExcel" class="btn btn-success btn-sm" onclick="showToast('Iniciando descarga de Excel...', 'success')">Excel</a>
    </div>
</div>


<div class="search-filters glass-card" style="padding: 1.5rem; margin-bottom: 2rem; display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
    <form action="<?php echo BASE_PATH; ?>/admin/books" method="get" style="display: flex; gap: 0.5rem; flex-grow: 1;">
        <input type="text" name="search" class="form-control" placeholder="Buscar por título, autor o ISBN..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit" class="btn btn-primary btn-sm">Buscar</button>
    </form>
    <a href="<?php echo BASE_PATH; ?>/admin/books?search=Colección Bicentenaria" class="btn btn-accent btn-sm">Colección Bicentenaria</a>
    <?php if(isset($_GET['search'])): ?>
        <a href="<?php echo BASE_PATH; ?>/admin/books" class="btn btn-secondary btn-sm">Limpiar</a>
    <?php endif; ?>
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
