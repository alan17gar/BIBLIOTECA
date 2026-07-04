<?php
// views/admin/books.php - Vista para la gestión de libros

$page_title = "Gestionar Libros";
include_once 'views/includes/header.php';
?>

<?php include_once 'views/includes/alerts.php'; ?>

<div class="page-header">
    <h1>Gestión de Libros</h1>
    <div class="header-actions">
        <a href="<?php echo BASE_PATH; ?>/admin/exportBooksPDF" class="btn btn-secondary btn-sm">PDF</a>
        <a href="<?php echo BASE_PATH; ?>/admin/exportBooksExcel" class="btn btn-secondary btn-sm">Excel</a>
        <a href="<?php echo BASE_PATH; ?>/admin/createBook" class="btn btn-primary">
            <!-- Icono de añadir -->
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Añadir Nuevo Libro
        </a>
    </div>
</div>

<div class="filter-container glass-card" style="margin-bottom: 2rem; padding: 1.5rem;">
    <form action="<?php echo BASE_PATH; ?>/admin/books" method="GET" class="filter-form" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
        <div class="form-group" style="flex: 1; min-width: 200px; margin-bottom: 0;">
            <label>Buscar</label>
            <input type="text" name="keyword" class="form-control" placeholder="Título, autor o ISBN..." value="<?php echo htmlspecialchars($_GET['keyword'] ?? ''); ?>">
        </div>
        <div class="form-group" style="width: 180px; margin-bottom: 0;">
            <label>Categoría</label>
            <select name="categoria" class="form-control">
                <option value="">Todas</option>
                <?php
                $cats = ['Ciencia Ficción', 'Fantasía', 'Novela Negra / Thriller', 'Romántico', 'Historia', 'Terror / Horror', 'Realismo Contemporáneo', 'Biografías y Memorias', 'Ensayo / Divulgación', 'Desarrollo Personal', 'Historia y Política', 'Cocina y Estilo de Vid.', 'Poesía', 'Novela Gráfica / Cómic', 'Clásicos', 'Colección Bicentenaria'];
                foreach ($cats as $cat) {
                    $sel = (isset($_GET['categoria']) && $_GET['categoria'] == $cat) ? 'selected' : '';
                    echo "<option value='$cat' $sel>$cat</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group" style="width: 150px; margin-bottom: 0;">
            <label>Ubicación</label>
            <select name="ubicacion_fisica" class="form-control">
                <option value="">Todas</option>
                <?php
                for($i=1; $i<=7; $i++) {
                    $ub = "Estante $i";
                    $sel = (isset($_GET['ubicacion_fisica']) && $_GET['ubicacion_fisica'] == $ub) ? 'selected' : '';
                    echo "<option value='$ub' $sel>$ub</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group" style="margin-bottom: 0; display: flex; align-items: center; gap: 0.5rem;">
            <input type="checkbox" name="bicentenaria" value="1" <?php echo isset($_GET['bicentenaria']) ? 'checked' : ''; ?>>
            <label style="margin-bottom: 0;">Col. Bicentenaria</label>
        </div>
        <button type="submit" class="btn btn-primary">Filtrar</button>
        <a href="<?php echo BASE_PATH; ?>/admin/books" class="btn btn-secondary">Limpiar</a>
    </form>
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
