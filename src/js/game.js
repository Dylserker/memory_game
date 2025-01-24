function generateCards() {
    const images = [];
    for (let i = 1; i <= 18; i++) {
        images.push(`src/images/fruit${i}.png`);
    }
    const cards = images.map((fruit, i) => ({ id: i, fruit }))
        .concat(images.map((fruit, i) => ({ id: i + 18, fruit })));
    return cards;
}

let cards = generateCards();
let flippedCards = [];
let timer = 300;
let interval;
let matchedPairs = 0;
const TOTAL_PAIRS = 18;

document.getElementById('startGame').addEventListener('click', startGame);

function startGame() {
    matchedPairs = 0;
    flippedCards = [];
    timer = 300;

    document.getElementById('startGame').style.display = 'none';

    const board = document.getElementById('gameBoard');
    board.innerHTML = '';

    document.getElementById('timer').innerText = `Temps restant: ${timer}s`;
    document.getElementById('progress').style.width = '100%';

    shuffle(cards);
    displayCards(cards);
    interval = setInterval(updateTimer, 1000);

    const restartGameBtn = document.getElementById('restartGame');
    if (restartGameBtn) {
        restartGameBtn.style.display = 'none';
    }
}

function shuffle(arr) {
    for (let i = arr.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [arr[i], arr[j]] = [arr[j], arr[i]];
    }
}

function displayCards(cards) {
    const board = document.getElementById('gameBoard');
    board.innerHTML = '';
    cards.forEach(card => {
        const cardElement = document.createElement('div');
        cardElement.classList.add('card');
        cardElement.dataset.id = card.id;
        cardElement.dataset.fruit = card.fruit;
        cardElement.innerHTML = `<img src="src/images/fruit_back.png" alt="Fruit">`;
        cardElement.addEventListener('click', flipCard);
        board.appendChild(cardElement);
    });
}

function flipCard(event) {
    const card = event.currentTarget;
    if (flippedCards.length < 2 && !card.classList.contains('flipped')) {
        card.classList.add('flipped');
        card.innerHTML = `<img src="${card.dataset.fruit}" alt="Fruit">`;
        flippedCards.push(card);
        if (flippedCards.length === 2) {
            setTimeout(checkMatch, 500);
        }
    }
}

function checkMatch() {
    const [card1, card2] = flippedCards;

    if (card1.dataset.fruit === card2.dataset.fruit) {
        card1.classList.add('matched');
        card2.classList.add('matched');

        matchedPairs++;
        flippedCards = [];

        if (matchedPairs === TOTAL_PAIRS) {
            clearInterval(interval);
            showGameResultModal('victoire');
        }
    } else {
        setTimeout(() => {
            card1.classList.remove('flipped');
            card2.classList.remove('flipped');
            card1.innerHTML = `<img src="src/images/fruit_back.png" alt="Fruit">`;
            card2.innerHTML = `<img src="src/images/fruit_back.png" alt="Fruit">`;
            flippedCards = [];
        }, 700);
    }
}

function updateTimer() {
    if (timer <= 0) {
        clearInterval(interval);
        showGameResultModal('défaite');
    } else {
        timer--;
        document.getElementById('timer').innerText = `Temps restant: ${timer}s`;

        const percentage = (timer / 300) * 100;
        document.getElementById('progress').style.width = `${percentage}%`;

        if (percentage <= 10) {
            document.getElementById('progress').style.backgroundColor = 'red';
        } else if (percentage <= 50) {
            document.getElementById('progress').style.backgroundColor = 'orange';
        } else {
            document.getElementById('progress').style.backgroundColor = '#4caf50';
        }
    }
}

function saveScore(time) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "MVC/controllers/save_score.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (xhr.status === 200) {
            console.log("Score sauvegardé avec succès");
        } else {
            console.error("Erreur lors de la sauvegarde du score");
        }
    };
    xhr.send(`time=${time}`);
}

function showGameResultModal(result) {
    if (result === 'victoire') {
        alert(`🏆 Félicitations ! Vous avez gagné la partie ! 🏆\nTemps restant : ${timer} secondes`);
        saveScore(timer);
    } else {
        alert(`⏰ Dommage ! Temps écoulé - Partie terminée ⏰\nVous n'avez pas réussi à trouver toutes les paires à temps.`);
    }

    const globalRestartBtn = document.getElementById('restartGame');
    if (globalRestartBtn) {
        globalRestartBtn.style.display = 'block';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const startGameBtn = document.getElementById('startGame');
    const restartGameBtn = document.getElementById('restartGame');

    if (restartGameBtn) {
        restartGameBtn.addEventListener('click', () => {
            startGame();
        });
    }
});