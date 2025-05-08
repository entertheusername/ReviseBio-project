// <!-- Woon Wei Jie TP076183 -->
function updateSummary(questionId) {
    const selectedInput = document.querySelector(`input[name="answers[${questionId}]"]:checked`);
    const summaryItem = document.getElementById(`summary-${questionId}`);
    summaryItem.textContent = selectedInput 
        ? `Answered` 
        : `Not Answered`;
}

function updateTextareaSummary(questionId) {
    const textarea = document.querySelector(`textarea[name="answers[${questionId}]"]`);
    const summaryItem = document.getElementById(`summary-${questionId}`);
    summaryItem.textContent = textarea.value.trim()
        ? 'Answered'
        : 'Not Answered';
}

function hideForm() {
    const quizform = document.getElementsByName('quizform')[0];
    const quizformbtn = document.getElementsByName('quizform')[1]
    const queslist = document.getElementsByName('ansum')[0];
    quizform.style.display = 'none';
    quizformbtn.style.display = 'none';
    queslist.style.display = 'block';
}

function showForm() {
    const quizform = document.getElementsByName('quizform')[0];
    const quizformbtn = document.getElementsByName('quizform')[1]
    const queslist = document.getElementsByName('ansum')[0];
    quizform.style.display = 'block';
    quizformbtn.style.display = 'block';
    queslist.style.display = 'none';
}

function submitForm() {
    const form = document.forms['quizform'];
    if (form) {
        if (validateFormBeforeSubmission()) {
            form.submit();
        }
    } else {
        console.error("Quiz form not found.");
    }
}

function validateFormBeforeSubmission() {
    let allAnswered = true;

    const radioGroups = document.querySelectorAll('input[type="radio"][required]');
    const uniqueGroups = new Set();

    for (let radio of radioGroups) {
        const groupName = radio.name;
        if (!uniqueGroups.has(groupName)) {
            const isChecked = document.querySelector(`input[name="${groupName}"]:checked`);
            if (!isChecked) {
                alert("Please answer all required multiple-choice questions.");
                allAnswered = false;
                break;
            }
            uniqueGroups.add(groupName);
        }
    }

    const textareas = document.querySelectorAll('textarea[required]');
    for (let textarea of textareas) {
        if (!textarea.value.trim()) { 
            alert("Please fill in all required text response fields.");
            allAnswered = false;
            break;
        }
    }

    return allAnswered;
}
