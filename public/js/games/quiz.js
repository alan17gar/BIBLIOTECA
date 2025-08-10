// public/js/games/quiz.js

document.addEventListener('DOMContentLoaded', () => {
    // Datos de ejemplo del quiz. En una app real, esto vendría de la BD.
    const quizData = {
        "1": [ // ID del libro "Don Quijote"
            { question: "¿Quién es el autor de Don Quijote?", options: ["Miguel de Cervantes", "García Márquez", "Shakespeare", "Homero"], answer: "Miguel de Cervantes" },
            { question: "¿Cómo se llama el caballo de Don Quijote?", options: ["Rocinante", "Bucéfalo", "Pegaso", "Babieca"], answer: "Rocinante" },
            { question: "¿Quién es el fiel escudero de Don Quijote?", options: ["Sancho Panza", "Lazarillo de Tormes", "Celestina", "Guzmán de Alfarache"], answer: "Sancho Panza" }
        ],
        "2": [ // ID del libro "Cien Años de Soledad"
            { question: "¿En qué pueblo ficticio transcurre 'Cien años de soledad'?", options: ["Macondo", "Comala", "Santa María", "Yoknapatawpha"], answer: "Macondo" },
            { question: "¿Qué familia es la protagonista de la novela?", options: ["Los Buendía", "Los Trueba", "Los Corleone", "Los Stark"], answer: "Los Buendía" }
        ],
        // Añadir más preguntas para otros libros...
    };

    const startScreen = document.getElementById('quiz-start-screen');
    const gameScreen = document.getElementById('quiz-game-screen');
    const endScreen = document.getElementById('quiz-end-screen');

    const startBtn = document.getElementById('start-quiz-btn');
    const bookSelect = document.getElementById('quiz-book-select');

    const questionEl = document.getElementById('quiz-question');
    const optionsEl = document.getElementById('quiz-options');
    const feedbackEl = document.getElementById('quiz-feedback');
    const nextBtn = document.getElementById('next-question-btn');

    const finalScoreEl = document.getElementById('quiz-final-score');
    const restartBtn = document.getElementById('restart-quiz-btn');

    let currentQuestions = [];
    let currentQuestionIndex = 0;
    let score = 0;

    startBtn.addEventListener('click', () => {
        const selectedBookId = bookSelect.value;
        currentQuestions = quizData[selectedBookId] || [];
        if (currentQuestions.length === 0) {
            alert('No hay preguntas para este libro.');
            return;
        }

        currentQuestionIndex = 0;
        score = 0;

        startScreen.classList.add('hidden');
        endScreen.classList.add('hidden');
        gameScreen.classList.remove('hidden');

        showQuestion();
    });

    nextBtn.addEventListener('click', () => {
        currentQuestionIndex++;
        if (currentQuestionIndex < currentQuestions.length) {
            showQuestion();
        } else {
            showEndScreen();
        }
    });

    restartBtn.addEventListener('click', () => {
        endScreen.classList.add('hidden');
        startScreen.classList.remove('hidden');
    });

    function showQuestion() {
        feedbackEl.textContent = '';
        nextBtn.classList.add('hidden');
        optionsEl.innerHTML = '';

        const question = currentQuestions[currentQuestionIndex];
        questionEl.textContent = question.question;

        question.options.forEach(optionText => {
            const optionDiv = document.createElement('div');
            optionDiv.textContent = optionText;
            optionDiv.classList.add('quiz-option');
            optionDiv.addEventListener('click', () => selectAnswer(optionDiv, optionText, question.answer));
            optionsEl.appendChild(optionDiv);
        });
    }

    function selectAnswer(optionDiv, selectedAnswer, correctAnswer) {
        // Deshabilitar más clics
        const allOptions = optionsEl.querySelectorAll('.quiz-option');
        allOptions.forEach(opt => opt.style.pointerEvents = 'none');

        if (selectedAnswer === correctAnswer) {
            score++;
            optionDiv.classList.add('correct');
            feedbackEl.textContent = "¡Correcto!";
            feedbackEl.style.color = '#28a745';
        } else {
            optionDiv.classList.add('incorrect');
            feedbackEl.textContent = `Incorrecto. La respuesta era: ${correctAnswer}`;
            feedbackEl.style.color = '#dc3545';
        }

        nextBtn.classList.remove('hidden');
    }

    function showEndScreen() {
        gameScreen.classList.add('hidden');
        endScreen.classList.remove('hidden');
        finalScoreEl.textContent = `${score} / ${currentQuestions.length}`;
    }
});
