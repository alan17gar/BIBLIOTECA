<?php
// views/student/books.php - Vista para buscar y ver libros

$page_title = "Catálogo de Libros";
include_once 'views/includes/header.php';
?>

<div class="page-header">
    <h1>Catálogo de Libros</h1>
    <p>Busca tu próxima lectura y solicita un préstamo.</p>
</div>

<!-- Barra de búsqueda -->
<div class="search-bar-container">
    <form action="/biblioteca-app/student/books" method="get" class="search-form">
        <input type="text" name="search" placeholder="Buscar por título, autor o categoría..." class="search-input" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>
</div>

<!-- Listado de libros en formato de tarjetas -->
<div class="book-grid">
    <?php
    if (isset($stmt) && $stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            ?>
            <div class="book-card">
                <img src="/<?php echo htmlspecialchars($portada); ?>" alt="Portada de <?php echo htmlspecialchars($titulo); ?>" class="book-card-img">
                <div class="book-card-body">
                    <h3 class="book-card-title"><?php echo htmlspecialchars($titulo); ?></h3>
                    <p class="book-card-author">por <?php echo htmlspecialchars($autor); ?></p>
                    <p class="book-card-category"><?php echo htmlspecialchars($categoria); ?></p>
                    <div class="book-card-availability">
                        <?php if ($cantidad_disponible > 0): ?>
                            <span class="status status-available">Disponible (<?php echo $cantidad_disponible; ?>)</span>
                        <?php else: ?>
                            <span class="status status-unavailable">No disponible</span>
                        <?php endif; ?>
                    </div>
                    <p class="book-card-synopsis"><?php echo substr(htmlspecialchars($sinopsis), 0, 100) . '...'; ?></p>
                    <div class="book-card-actions">
                        <!-- El botón de solicitar podría llevar a una página de detalles o solicitar directamente -->
                        <?php if ($cantidad_disponible > 0): ?>
                            <a href="/biblioteca-app/student/requestLoan/<?php echo $id; ?>" class="btn btn-sm btn-success" onclick="return confirm('¿Confirmas que quieres solicitar este libro?');">Solicitar Préstamo</a>
                        <?php endif; ?>
                        <!-- Un botón para ver más detalles podría ser útil -->
                        <!-- <a href="/biblioteca-app/books/details/<?php echo $id; ?>" class="btn btn-sm btn-info">Ver Detalles</a> -->
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<p>No se encontraron libros que coincidan con la búsqueda.</p>";
    }
    ?>
</div>

<?php
include_once 'views/includes/footer.php';
?>
