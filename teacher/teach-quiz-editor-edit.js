// <!-- Chan Zyanne TP075156 -->
function addInitialQuestionBlock(iQuestion, iFile, iSelType, iExplaination, iAnswers, iCorrect, iQuesID) {
    index++;
    const container = document.getElementById('form');
    const formContainer = document.createElement('div');
    formContainer.className = 'form-container';
    formContainer.setAttribute('name', 'form-container');
    formContainer.id = `form-container-${index}`;
    formContainer.innerHTML = `            
        <input type="text" name="questions[${index}]" class="question-input" maxlength="100" placeholder="Enter your question here" value="${iQuestion}" required>
        <input type="file" name="file[${index}]" class="file-input" accept="image/*" value="${iFile}">
        <input type="hidden" name="quesid[${index}]" value="${iQuesID}">

        <div class="questiontype">
            <button type="button" class="option-btn ${iSelType == 'Mcq' ? 'selected': ''}" onclick="updateAnswerOptions('mcq', ${index})">MCQ</button>
            <button type="button" class="option-btn ${iSelType == 'Truefalse' ? 'selected': ''}" onclick="updateAnswerOptions('truefalse', ${index})">True or False</button>
            <button type="button" class="option-btn ${iSelType == 'Short' ? 'selected': ''}" onclick="updateAnswerOptions('shortanswer', ${index})">Short Answer</button>
        </div>

        <div id="answer-options-${index}">
            <!-- Content goes here -->
            ${(() => {
                switch (iSelType) {
                    case 'Mcq':
                        return generateInitialMCQOptionsHTML(index, iAnswers, iCorrect);
                    case 'Truefalse':
                        return generateInitialTruefalseOptionsHTML(index, iCorrect);
                    case 'Short':
                        return generateInitialShortAnswerHTML(index, iAnswers);
                    default:
                        return '';
                }
            })()}
        </div>

        <textarea name="explanations[${index}]" maxlength="200" placeholder="Explanation" class="explanation" style="resize: none;" required>${iExplaination}</textarea>

        <div style="display: flex; justify-content: center;">
            <button type="button" class="toolbar-button" onclick="openPopup(${index})">
                <span class="material-symbols-outlined">delete</span>
            </button>
        </div>
    `;
    container.appendChild(formContainer);
}

function generateInitialMCQOptionsHTML(questionIndex, iAnswers, iCorrect) {
    return `
        <input name="question_types[${questionIndex}]" type="hidden" value="mcq">
        <div class="answer-option">
            <label class="answer-choicemcq">
                <input name="correct_answers[${questionIndex}]" type="radio" value="0" ${iCorrect[0] == '1' ? 'checked': ''} required> 
                <input name="options[${questionIndex}][]" class="answer" type="text" maxlength="50" placeholder="Enter Option 1" value="${iAnswers[0]}" required> 
            </label>
            <label class="answer-choicemcq">
                <input name="correct_answers[${questionIndex}]" type="radio" value="1" ${iCorrect[1] == '1' ? 'checked': ''} required> 
                <input name="options[${questionIndex}][]" class="answer" type="text" maxlength="50" placeholder="Enter Option 2" value="${iAnswers[1]}" required> 
            </label>
            <label class="answer-choicemcq">
                <input name="correct_answers[${questionIndex}]" type="radio" value="2" ${iCorrect[2] == '1' ? 'checked': ''} required> 
                <input name="options[${questionIndex}][]" class="answer" type="text" maxlength="50" placeholder="Enter Option 3" value="${iAnswers[2]}" required> 
            </label>
            <label class="answer-choicemcq">
                <input name="correct_answers[${questionIndex}]" type="radio" value="3" ${iCorrect[3] == '1' ? 'checked': ''} required> 
                <input name="options[${questionIndex}][]" class="answer" type="text" maxlength="50" placeholder="Enter Option 4" value="${iAnswers[3]}" required> 
            </label>
        </div>
    `;
}

function generateInitialTruefalseOptionsHTML(questionIndex, iCorrect) {
    return `
        <input name="question_types[${questionIndex}]" type="hidden" value="truefalse">
            <div class="answer-option">
                <label class="answer-choicetruefalse truefalse-option">
                    <input name="correct_answers[${questionIndex}]" type="radio" value="True" ${iCorrect[0] == '1' ? 'checked': ''} required> 
                    True
                </label>
                <label class="answer-choicetruefalse truefalse-option">
                    <input name="correct_answers[${questionIndex}]" type="radio" value="False" ${iCorrect[1] == '1' ? 'checked': ''} required> 
                    False
                </label>
            </div>
    `;
}

function generateInitialShortAnswerHTML(questionIndex, iAnswers) {
    return `
                <input name="question_types[${questionIndex}]" type="hidden" value="short">
                <div class="answer-option">
                    <textarea name="answers[${questionIndex}]" maxlength="200" placeholder="Enter short answer here" class="short-answer" style="resize: none;" required>${iAnswers[0]}</textarea>
                </div>
            `;
}

function getQuizTitle() {
    const form = document.querySelectorAll('[name="nq-title"]');
    return form.value;
}