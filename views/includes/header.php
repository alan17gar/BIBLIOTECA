<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca App</title>

    <link rel="stylesheet" href="/public/css/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Lato:wght@400;700&display=swap" rel="stylesheet">

</head>
<body>
    <header class="main-header">
        <div class="container">
            <a href="/" class="logo">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book-open"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                <span>Biblioteca App</span>
            </a>

            <nav class="main-nav">
                <?php
                // Cargar la navegación correspondiente según el rol del usuario
                if (isset($_SESSION['user_role'])) {
                    if ($_SESSION['user_role'] === 'admin') {
                        include 'nav_admin.php'; // Navegación para el administrador
                    } else if ($_SESSION['user_role'] === 'student') {
                        include 'nav_student.php'; // Navegación para el estudiante
                    }
                }
                ?>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            ```

---

### 🚀 Último paso:
Guarda el archivo en GitHub, espera a que Render ponga el estado en verde (**Live**) y recarga tu página web presionando **`Ctrl + F5`** (para limpiar los archivos viejos de la memoria del navegador). 

¡Con esto tu sistema va a cargar tu diseño original por completo y se va a ver hermoso! Me avisas en cuanto lo hagas para celebrar.
