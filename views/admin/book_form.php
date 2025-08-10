<?php
// views/admin/book_form.php - Formulario para crear o editar un libro

// Determinar si es una operación de edición o creación
$is_edit = isset($this->book) && !empty($this->book->id);
$page_title = $is_edit ? "Editar Libro" : "Añadir Nuevo Libro";

include_once 'views/includes/header.php';
?>

<div class="page-header">
    <h1><?php echo $page_title; ?></h1>
    <a href="/biblioteca-app/admin/books" class="btn btn-secondary">Volver a la lista</a>
</div>

<div class="form-container">
    <form action="<?php echo $is_edit ? '/biblioteca-app/admin/editBook/' . $this->book->id : '/biblioteca-app/admin/createBook'; ?>" method="post" enctype="multipart/form-data" id="book-form">

        <div class="form-group">
            <label for="titulo">Título</label>
            <input type="text" id="titulo" name="titulo" class="form-control" value="<?php echo $is_edit ? htmlspecialchars($this->book->titulo) : ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="autor">Autor</label>
            <input type="text" id="autor" name="autor" class="form-control" value="<?php echo $is_edit ? htmlspecialchars($this->book->autor) : ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="isbn">ISBN</label>
            <input type="text" id="isbn" name="isbn" class="form-control" value="<?php echo $is_edit ? htmlspecialchars($this->book->isbn) : ''; ?>">
        </div>

        <div class="form-group">
            <label for="categoria">Categoría</label>
            <input type="text" id="categoria" name="categoria" class="form-control" value="<?php echo $is_edit ? htmlspecialchars($this->book->categoria) : ''; ?>">
        </div>

        <div class="form-group">
            <label for="sinopsis">Sinopsis</label>
            <textarea id="sinopsis" name="sinopsis" class="form-control" rows="4"><?php echo $is_edit ? htmlspecialchars($this->book->sinopsis) : ''; ?></textarea>
        </div>

        <div class="form-group">
            <label for="cantidad_total">Cantidad Total</label>
            <input type="number" id="cantidad_total" name="cantidad_total" class="form-control" value="<?php echo $is_edit ? htmlspecialchars($this->book->cantidad_total) : '1'; ?>" required min="0">
        </div>

        <div class="form-group">
            <label for="ubicacion_fisica">Ubicación Física</label>
            <input type="text" id="ubicacion_fisica" name="ubicacion_fisica" class="form-control" value="<?php echo $is_edit ? htmlspecialchars($this->book->ubicacion_fisica) : ''; ?>">
        </div>

        <div class="form-group">
            <label for="portada">Imagen de Portada</label>
            <input type="file" id="portada" name="portada" class="form-control" accept="image/*">
            <?php if ($is_edit && !empty($this->book->portada)): ?>
                <p>Portada actual: <img src="/<?php echo htmlspecialchars($this->book->portada); ?>" alt="Portada" style="max-width: 100px; margin-top: 10px;"></p>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="pdf">Archivo PDF (Opcional)</label>
            <input type="file" id="pdf" name="pdf" class="form-control" accept=".pdf">
             <?php if ($is_edit && !empty($this->book->pdf_ruta)): ?>
                <p>PDF actual: <a href="/<?php echo htmlspecialchars($this->book->pdf_ruta); ?>" target="_blank">Ver PDF</a></p>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary"><?php echo $is_edit ? 'Actualizar Libro' : 'Guardar Libro'; ?></button>
        </div>
    </form>
</div>

<?php
include_once 'views/includes/footer.php';
?>
