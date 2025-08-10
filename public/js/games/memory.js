// public/js/games/memory.js

document.addEventListener('DOMContentLoaded', () => {
    const board = document.getElementById('memory-board');
    const movesEl = document.getElementById('memory-moves');
    const timerEl = document.getElementById('memory-timer');
    const restartBtn = document.getElementById('restart-memory-btn');

    // Rutas a las imágenes de portada. En una app real, se cargarían desde la BD.
    // Usaremos 6 imágenes, que se duplicarán para formar 12 tarjetas.
    const imageUrls = [
        '/biblioteca-app/public/images/covers/cover1.jpg',
        '/biblioteca-app/public/images/covers/cover2.jpg',
        '/biblioteca-app/public/images/covers/cover3.jpg',
        '/biblioteca-app/public/images/covers/cover4.jpg',
        '/biblioteca-app/public/images/covers/cover5.jpg',
        '/biblioteca-app/public/images/covers/cover1.jpg', // Placeholder, se reemplazará
    ];
    // Crear el set de tarjetas duplicando las imágenes
    let cardsArray = [...imageUrls, ...imageUrls];

    let hasFlippedCard = false;
    let lockBoard = false;
    let firstCard, secondCard;
    let moves = 0;
    let timer = 0;
    let timerInterval = null;

    // Función para barajar las tarjetas
    function shuffle(array) {
        array.sort(() => Math.random() - 0.5);
    }

    // Función para crear el tablero
    function createBoard() {
        board.innerHTML = '';
        shuffle(cardsArray);
        cardsArray.forEach(imageUrl => {
            const card = document.createElement('div');
            card.classList.add('memory-card');
            card.dataset.image = imageUrl;

            card.innerHTML = `
                <div class="memory-card-face memory-card-front">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                </div>
                <div class="memory-card-face memory-card-back">
                    <img src="${imageUrl}" alt="Portada de libro">
                </div>
            `;
            card.addEventListener('click', flipCard);
            board.appendChild(card);
        });
    }

    function flipCard() {
        if (lockBoard) return;
        if (this === firstCard) return;

        this.classList.add('is-flipped');

        if (!hasFlippedCard) {
            // Primer clic
            hasFlippedCard = true;
            firstCard = this;
            startTimer();
        } else {
            // Segundo clic
            secondCard = this;
            incrementMoves();
            checkForMatch();
        }
    }

    function checkForMatch() {
        let isMatch = firstCard.dataset.image === secondCard.dataset.image;
        isMatch ? disableCards() : unflipCards();
    }

    function disableCards() {
        firstCard.removeEventListener('click', flipCard);
        secondCard.removeEventListener('click', flipCard);
        firstCard.classList.add('is-matched');
        secondCard.classList.add('is-matched');
        resetBoard();
        checkWin();
    }

    function unflipCards() {
        lockBoard = true;
        setTimeout(() => {
            firstCard.classList.remove('is-flipped');
            secondCard.classList.remove('is-flipped');
            resetBoard();
        }, 1200);
    }

    function resetBoard() {
        [hasFlippedCard, lockBoard] = [false, false];
        [firstCard, secondCard] = [null, null];
    }

    function incrementMoves() {
        moves++;
        movesEl.textContent = moves;
    }

    function startTimer() {
        if (timerInterval) return;
        timerInterval = setInterval(() => {
            timer++;
            timerEl.textContent = `${timer}s`;
        }, 1000);
    }

    function stopTimer() {
        clearInterval(timerInterval);
        timerInterval = null;
    }

    function checkWin() {
        const matchedCards = document.querySelectorAll('.is-matched');
        if (matchedCards.length === cardsArray.length) {
            stopTimer();
            setTimeout(() => alert(`¡Ganaste! Lo hiciste en ${moves} movimientos y ${timer} segundos.`), 500);
        }
    }

    function restartGame() {
        stopTimer();
        moves = 0;
        timer = 0;
        movesEl.textContent = moves;
        timerEl.textContent = `${timer}s`;
        resetBoard();
        createBoard();
    }

    // Event Listeners
    restartBtn.addEventListener('click', restartGame);

    // Iniciar el juego
    createBoard();
});
