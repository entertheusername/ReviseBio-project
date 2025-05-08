// <!-- Sum Chun Hoe TP076270 -->
// Flashcard - Start
// Flashcards Data
let flashcards = [];
let FCGrpID = 0;
let currentCardIndex = 0;

document.addEventListener('DOMContentLoaded', () => {
    if (window.flashcardsData) {
        flashcards = window.flashcardsData;
        setFlashcardData(flashcards);
    }
});

document.addEventListener('DOMContentLoaded', () => {
    if (window.FCGrpID) {
        FCGrpID = window.FCGrpID;
    }
});

function setFlashcardData(array) {
    flashcards = array;
    if (flashcards.length === 0) {
        console.error("No flashcards available.");
        return;
    }

    updateFlashcard();
    updateProgressBar();
}

function flipCard(card) {
    card.classList.toggle("is-flipped");
}

// Show the next card
function showNextCard() {
    if (currentCardIndex < flashcards.length - 1) {
        currentCardIndex++;
        updateFlashcard();
        updateProgressBar();
    } else {
        window.location.href = "stu-flashcard-end.php?FCGrpID=" + FCGrpID;
    }
}

// Show the previous card
function showPreviousCard() {
    if (currentCardIndex > 0) {
        currentCardIndex--;
        updateFlashcard();
        updateProgressBar();
    }
}

// Update flashcard content
function updateFlashcard() {
    const front = document.getElementById("flashcard-front");
    const back = document.getElementById("flashcard-back");

    front.textContent = flashcards[currentCardIndex].front;
    back.textContent = flashcards[currentCardIndex].back;

    // Reset flipped state
    const flashcard = document.querySelector(".flashcard");
    flashcard.classList.remove("is-flipped");
}

function updateProgressBar() {
    const progressFill = document.querySelector(".progress-fill");
    const progressPercentage = ((currentCardIndex + 1) / flashcards.length) * 100;
    progressFill.style.width = `${progressPercentage}%`;
}
// Flashcard - End
