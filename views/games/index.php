<?php
// views/games/index.php - Página principal de la sección de juegos

$page_title = "Juegos Interactivos";
include_once 'views/includes/header.php';
?>

<div class="page-header">
    <h1>Juegos Interactivos</h1>
    <p>¡Pon a prueba tus conocimientos y diviértete!</p>
</div>

<div class="game-menu">
    <div class="game-card">
        <div class="game-card-icon">
            <!-- Icono para Quiz -->
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-help-circle"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
        </div>
        <h3 class="game-card-title">Quiz de Comprensión</h3>
        <p class="game-card-description">Responde preguntas sobre los libros que has leído.</p>
        <a href="/biblioteca-app/student/games/quiz" class="btn btn-primary">Jugar Ahora</a>
    </div>

    <div class="game-card">
        <div class="game-card-icon">
            <!-- Icono para Juego de Memoria -->
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-grid"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
        </div>
        <h3 class="game-card-title">Memoria de Portadas</h3>
        <p class="game-card-description">Encuentra los pares de portadas de libros.</p>
        <a href="/biblioteca-app/student/games/memory" class="btn btn-primary">Jugar Ahora</a>
    </div>
</div>

<style>
/* Estilos específicos para la página de juegos */
.game-menu {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}
.game-card {
    background: var(--card-bg);
    border-radius: 8px;
    box-shadow: var(--box-shadow);
    padding: 2rem;
    text-align: center;
}
.game-card-icon {
    color: var(--primary-color);
    margin-bottom: 1rem;
}
.game-card-title {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}
.game-card-description {
    color: var(--secondary-color);
    margin-bottom: 1.5rem;
}
</style>

<?php
include_once 'views/includes/footer.php';
?>
