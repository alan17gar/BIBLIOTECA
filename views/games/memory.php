<?php
// views/games/memory.php - Vista para el juego de Memoria de Portadas

$page_title = "Memoria de Portadas";
include_once 'views/includes/header.php';
?>

<div class="page-header">
    <h1>Juego de Memoria con Portadas</h1>
    <a href="/biblioteca-app/student/games" class="btn btn-secondary">Volver al menú de juegos</a>
</div>

<div class="game-container" id="memory-container">
    <div class="memory-info">
        <div class="memory-stat">Movimientos: <span id="memory-moves">0</span></div>
        <div class="memory-stat">Tiempo: <span id="memory-timer">0s</span></div>
    </div>
    <div id="memory-board" class="memory-board">
        <!-- Las tarjetas se generarán con JS -->
    </div>
    <button id="restart-memory-btn" class="btn btn-primary" style="margin-top: 1.5rem;">Reiniciar Juego</button>
</div>

<!-- Estilos específicos para el Juego de Memoria -->
<style>
.memory-info { display: flex; justify-content: space-around; margin-bottom: 1.5rem; font-size: 1.2rem; }
.memory-board { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; perspective: 1000px; }
.memory-card { width: 100%; aspect-ratio: 2/3; position: relative; transform-style: preserve-3d; transition: transform 0.6s; cursor: pointer; }
.memory-card.is-flipped { transform: rotateY(180deg); }
.memory-card-face { position: absolute; width: 100%; height: 100%; backface-visibility: hidden; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
.memory-card-front { background-color: var(--secondary-color); display: flex; justify-content: center; align-items: center; }
.memory-card-front svg { width: 50%; height: 50%; color: white; } /* Icono de libro */
.memory-card-back { background-color: var(--light-color); transform: rotateY(180deg); }
.memory-card-back img { width: 100%; height: 100%; object-fit: cover; border-radius: 8px; }
.memory-card.is-matched {
    transform: scale(0.95);
    opacity: 0.5;
    cursor: default;
}
</style>

<?php
// Incluir el script del juego
$page_scripts = ['games/memory.js'];
include_once 'views/includes/footer.php';
?>
