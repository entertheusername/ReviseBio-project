// <!-- Chan Zyanne TP075156 -->
index = -1;

function addQuestionBlock() {
    index++;
    const container = document.getElementById('form');
    const formContainer = document.createElement('div');
    formContainer.className = 'form-container';
    formContainer.setAttribute('name', 'form-container');
    formContainer.id = `form-container-${index}`;
    formContainer.innerHTML = `            
        <input type="text" name="questions[${index}]" class="question-input" maxlength="100" placeholder="Enter your question here" required>
        <input type="file" name="file[${index}]" class="file-input" accept="image/*">

        <div class="questiontype">
            <button type="button" class="option-btn selected" onclick="updateAnswerOptions('mcq', ${index})">MCQ</button>
            <button type="button" class="option-btn" onclick="updateAnswerOptions('truefalse', ${index})">True or False</button>
            <button type="button" class="option-btn" onclick="updateAnswerOptions('shortanswer', ${index})">Short Answer</button>
        </div>

        <div id="answer-options-${index}">
            <!-- Content goes here -->
            ${generateMCQOptionsHTML(index)}
        </div>

        <textarea name="explanations[${index}]" maxlength="200" placeholder="Explanation" class="explanation" style="resize: none;" required></textarea>

        <div style="display: flex; justify-content: center;">
            <button type="button" class="toolbar-button" onclick="openPopup(${index})">
                <span class="material-symbols-outlined">delete</span>
            </button>
        </div>
    `;
    container.appendChild(formContainer);
}

function removeFormContainer(index) {
    const formContainer = document.getElementById(`form-container-${index}`);

    if (formContainer) {
        formContainer.remove();
    }
}

function updateAnswerOptions(optionType, questionIndex) {
    const formContainer = document.getElementById(`form-container-${questionIndex}`);

    const buttons = formContainer.querySelectorAll('.option-btn');
    buttons.forEach(btn => btn.classList.remove('selected'));

    const selectedButton = formContainer.querySelector(`.option-btn[onclick*="'${optionType}'"]`);
    selectedButton.classList.add('selected');

    answerContainer = document.getElementById(`answer-options-${questionIndex}`);
    switch (optionType) {
        case 'mcq':
            answerContainer.innerHTML = generateMCQOptionsHTML(questionIndex);
            break;
        case 'truefalse':
            answerContainer.innerHTML = `
                <input name="question_types[${questionIndex}]" type="hidden" value="truefalse">
                <div class="answer-option">
                    <label class="answer-choicetruefalse truefalse-option">
                        <input name="correct_answers[${questionIndex}]" type="radio" value="True" required> 
                        True
                    </label>
                    <label class="answer-choicetruefalse truefalse-option">
                        <input name="correct_answers[${questionIndex}]" type="radio" value="False" required> 
                        False
                    </label>
                </div>
                `;
            break;
        case 'shortanswer':
            answerContainer.innerHTML = `
                    <input name="question_types[${questionIndex}]" type="hidden" value="short">
                    <div class="answer-option">
                        <textarea name="answers[${questionIndex}]" maxlength="200" placeholder="Enter short answer here" class="short-answer" style="resize: none;" required></textarea>
                    </div>
                `;
            break;
    }
}

function generateMCQOptionsHTML(questionIndex) {
    return `
            <input name="question_types[${questionIndex}]" type="hidden" value="mcq">
            <div class="answer-option">
                <label class="answer-choicemcq">
                    <input name="correct_answers[${questionIndex}]" type="radio" value="0" required> 
                    <input name="options[${questionIndex}][]" class="answer" type="text" maxlength="50" placeholder="Enter Option 1"  required> 
                </label>
                <label class="answer-choicemcq">
                    <input name="correct_answers[${questionIndex}]" type="radio" value="1" required> 
                    <input name="options[${questionIndex}][]" class="answer" type="text" maxlength="50" placeholder="Enter Option 2"  required> 
                </label>
                <label class="answer-choicemcq">
                    <input name="correct_answers[${questionIndex}]" type="radio" value="2" required> 
                    <input name="options[${questionIndex}][]" class="answer" type="text" maxlength="50" placeholder="Enter Option 3"  required> 
                </label>
                <label class="answer-choicemcq">
                    <input name="correct_answers[${questionIndex}]" type="radio" value="3" required> 
                    <input name="options[${questionIndex}][]" class="answer" type="text" maxlength="50" placeholder="Enter Option 4"  required> 
                </label>
            </div>
        `;
}

function openPopup(index) {
    const popup = document.getElementById('popup');
    popup.style.display = 'block';

    document.querySelector('#popup .general-popup-content button').onclick = function () {
        removeFormContainer(index);
        closePopup('popup');
    };
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

    // if (document.getElementById("form").getElementsByTagName("div").length == 0) {
    //     alert("Quiz atleast need one question to be filled.");
    //     allAnswered = false;
    // }

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

    const texts = document.querySelectorAll('input[type="text"][required]');
    for (let text of texts) {
        if (!text.value.trim()) {
            alert("Please fill in all required text response fields.");
            allAnswered = false;
            break;
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

function hideForm() {
    const form = document.getElementById('form');
    const formbtn = document.getElementById('form-btn');
    const queslist = document.getElementById('ansum');
    form.style.display = 'none';
    formbtn.style.display = 'none';
    queslist.style.display = 'flex';
}

function showForm() {
    const form = document.getElementById('form');
    const formbtn = document.getElementById('form-btn');
    const queslist = document.getElementById('ansum');
    form.style.display = 'flex';
    formbtn.style.display = 'flex';
    queslist.style.display = 'none';
}

function printQues() {
    const quesList = document.getElementById('ques-list');
    quesList.innerHTML = ``;
    getQuesInputValues().forEach(i => {
        const ques = document.createElement('p');
        ques.textContent = i;
        quesList.appendChild(ques);
    });

}

function getQuesInputValues() {
    const form = document.querySelectorAll('[name="form-container"]');
    const value = [];
    form.forEach(i => {
        const ques = i.querySelector('input[type="text"]');
        value.push(ques.value);
    });

    return value;
}