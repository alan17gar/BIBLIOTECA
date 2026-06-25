<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Biblioteca App</title>

    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/public/css/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

</head>
<body>
    <!-- Elementos geométricos decorativos -->
    <div class="bg-glow-1"></div>
    <div class="bg-glow-2"></div>

    <header class="main-header">
        <div class="container">
            <a href="<?php echo BASE_PATH; ?>/" class="logo">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                <span>BIBLIOTECA</span>
            </a>

            <button class="menu-toggle" id="menu-toggle">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
            </button>

            <nav class="main-nav" id="main-nav">
                <?php
                // Cargar la navegación correspondiente según el rol del usuario
                if (isset($_SESSION['user_role'])) {
                    if ($_SESSION['user_role'] === 'admin') {
                        include 'nav_admin.php';
                    } else if ($_SESSION['user_role'] === 'student') {
                        include 'nav_student.php';
                    }
                }
                ?>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
