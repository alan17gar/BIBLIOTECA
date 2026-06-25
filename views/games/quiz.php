<?php
// views/games/quiz.php - Vista para el juego de Quiz

$page_title = "Quiz de Comprensión";
include_once 'views/includes/header.php';
?>

<div class="page-header">
    <h1>Quiz de Comprensión Lectora</h1>
    <a href="<?php echo BASE_PATH; ?>/student/games" class="btn btn-secondary">Volver al menú de juegos</a>
</div>

<div class="game-container" id="quiz-container">
    <div id="quiz-start-screen">
        <h2>¿Listo para empezar?</h2>
        <p>Selecciona un libro sobre el que quieras responder preguntas.</p>
        <!-- En una implementación real, aquí se cargarían los libros leídos por el usuario -->
        <select id="quiz-book-select" class="form-control">
            <option value="1">Don Quijote de la Mancha</option>
            <option value="2">Cien Años de Soledad</option>
            <option value="3">La Sombra del Viento</option>
        </select>
        <button id="start-quiz-btn" class="btn btn-primary">Comenzar Quiz</button>
    </div>

    <div id="quiz-game-screen" class="hidden">
        <div id="quiz-question-container">
            <h3 id="quiz-question"></h3>
            <div id="quiz-options" class="quiz-options-grid">
                <!-- Las opciones se generarán con JS -->
            </div>
        </div>
        <div id="quiz-feedback"></div>
        <button id="next-question-btn" class="btn btn-info hidden">Siguiente Pregunta</button>
    </div>

    <div id="quiz-end-screen" class="hidden">
        <h2>¡Quiz Completado!</h2>
        <p>Tu puntuación final es:</p>
        <p id="quiz-final-score" class="final-score"></p>
        <button id="restart-quiz-btn" class="btn btn-primary">Jugar de Nuevo</button>
    </div>
</div>

<!-- Estilos específicos para el Quiz -->
<style>
.game-container { background: var(--card-bg); padding: 2rem; border-radius: 8px; text-align: center; }
.hidden { display: none; }
.quiz-options-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin: 1.5rem 0; }
.quiz-option { background-color: var(--light-color); border: 2px solid var(--border-color); padding: 1rem; border-radius: 5px; cursor: pointer; transition: all 0.2s; }
.quiz-option:hover { background-color: #e2e6ea; }
.quiz-option.correct { background-color: #d4edda; border-color: #28a745; }
.quiz-option.incorrect { background-color: #f8d7da; border-color: #dc3545; }
#quiz-feedback { margin: 1rem 0; font-weight: bold; }
.final-score { font-size: 2.5rem; font-weight: bold; color: var(--primary-color); margin: 1rem 0; }
</style>

<?php
// Incluir el script del juego
$page_scripts = ['games/quiz.js'];
include_once 'views/includes/footer.php';
?>
