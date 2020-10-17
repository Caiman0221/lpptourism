//НОМИНАЦИИ - добавляем/удаляем
function testNominations() {
    document.getElementById('NominationsContainer').style.display = 'block';
    document.getElementById('addNewTestContainer').style.display = 'none';
    document.getElementById('listOfTests').style.display = 'none';
}

function testAddClick() {
    document.getElementById('NominationsContainer').style.display = 'none';
    document.getElementById('addNewTestContainer').style.display = 'block';
    document.getElementById('listOfTests').style.display = 'none';
}

function testListClick() {
    document.getElementById('NominationsContainer').style.display = 'none';
    document.getElementById('addNewTestContainer').style.display = 'none';
    document.getElementById('listOfTests').style.display = 'block';
}

function listTestLoader() {
    document.getElementById('TestsContainer').innerHTML = '';
    let data = "1";
    fetch('http://lpptourism.ru/core/adminzone/tests/testsList.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/text'
        },
        body: data
    })
        .then(response => response.text())
        .then(result => document.getElementById('TestsContainer').innerHTML = result)
        .catch(error => document.getElementById('TestsContainer').innerHTML = error)
}

function newNominationClick() {
    let newNomination = document.getElementById('newNomination');
    if (newNomination.value == '') {
        newNomination.style.border = '1px solid red';
        let errorNominationsDiv = document.getElementById('errorNominationsDiv');
        errorNominationsDiv.style.color = 'red';
        errorNominationsDiv.innerHTML = 'Вы не ввели номинацию';
        return;
    }
    newNomination.style.border = 'none';
    errorNominationsDiv.innerHTML = '';
    newNomination = newNomination.value;
    let data = {
        newNomination: newNomination
    };

    fetch('http://lpptourism.ru/core/adminzone/tests/newNomination.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json;'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.text())
        .then(result => {
            if (result) {
                errorNominationsDiv.style.color = 'black';
                errorNominationsDiv.innerHTML = result;
                loadNominationsList();
            } else {
                errorNominationsDiv.style.color = 'red';
                errorNominationsDiv.innerHTML = 'что-то пошло не так';
            }
        })
        .catch(error => console.log(error));

}

function loadNominationsList() {
    fetch('http://lpptourism.ru/core/adminzone/tests/nominationsList.php')
        .then(response => response.text())
        .then(result => document.getElementById('nominationsContainer').innerHTML = result)
        .catch(error => document.getElementById('nominationsContainer').innerHTML = error);
        //console.log('Список тестов обновился');
}

function dellTestFromNominationClick(num) {
    let id = showNumById(num);
    let data = {
        idtest: id
    };
    fetch('http://lpptourism.ru/core/adminzone/tests/deleteNomination.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.text())
        .then(result => {
            document.getElementById('errorNominationsDiv').style.color = 'green';
            document.getElementById('errorNominationsDiv').innerHTML = result;
            loadNominationsList();
            listTestLoader();
        });
}

function dellNominationClick(num) {
    let id = showNumById(num);
    let data = {
        id: id
    };
    fetch('http://lpptourism.ru/core/adminzone/tests/deleteNomination.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.text())
        .then(result => {
            document.getElementById('errorNominationsDiv').style.color = 'green';
            document.getElementById('errorNominationsDiv').innerHTML = result;
            loadNominationsList();
            listTestLoader();
            //listTestLoader();
        });
}

function NominationSelect(id) {
    fetch('http://lpptourism.ru/core/adminzone/tests/nominationsListForSelect.php')
        .then(response => response.text())
        .then(result => document.getElementById(id).innerHTML = result);
}


//ANSWERS

function addNewQuestionClick(e) {
    if (e.target.getAttribute('name') != 'addNewQuestionClick') {
        return;
    } else {
        let N = e.currentTarget.getElementsByClassName('Questions').length + 1;
        let newQuestion = e.currentTarget.getElementsByClassName('QuestionsContainer')[0];
        newQuestion.insertAdjacentHTML('beforeend', `<div class="OneQuestionContainer" onclick="DellQuestionClick(event)">
                                                        <div class="QuestionContainer">
                                                            <div class="QuestionContent">
                                                                <div><input type="number" name="QuestionNumber" class="QuestionNumber" style="display: none;" value="` + N + `"></div>
                                                                <div>` + N + `) Вопрос:</div>
                                                                <div><input type="text" name="Question" class="Questions" placeholder="Вопрос" onchange='inputCheck(event)'></div>
                                                            </div>
                                                            <div class="AnswersContainer" onclick="AddNewAnsewerClick(event)">
                                                                <div class="OneAnswerContainer">
                                                                    <div class="AnswerContent" onclick="DellAnswerClick(event)">
                                                                        <span><input type="checkbox" class="AnsewerCheckboxes" name="TrueAnswer"></span>
                                                                        <span><input type="text" name="AnswerInput" class="AnswerInputClass" placeholder="Ответ" onchange='inputCheck(event)'></span>
                                                                        <span><button name="DeleteAnswerButton" class="dellDocButton">X</button></span>
                                                                    </div>
                                                                </div>
                                                                <div class="addNewAnswerButtonContainer">
                                                                    <button name="addNewAnswerClick" class="greenButton">Добавить ответ</button>
                                                                </div>
                                                            </div>
                                                            <div class="OtherInfoOfAnswerContainer">
                                                                <span>
                                                                    <div>Время на ответ</div>
                                                                    <div><input type="number" name="TimeForAnswer" value="40"></div>
                                                                </span>
                                                                <span>
                                                                    <div>Баллы за ответ</div>
                                                                    <div><input type="number" name="PointsForAnswer" value="1"></div>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="DellQuestionButtonContainer">
                                                            <button name="DellQuestionClick" class="dellDocButton">X</button>
                                                        </div>
                                                    </div>`);
    }
}

function AddNewAnsewerClick(e) {
    if (e.target.getAttribute('name') != 'addNewAnswerClick') {
        return;
    } else {
        let newAnswer = e.currentTarget.getElementsByClassName('OneAnswerContainer')[0];
        newAnswer.insertAdjacentHTML('beforeend', `<div class="AnswerContent" onclick="DellAnswerClick(event)">
                                                        <span><input type="checkbox" class="AnsewerCheckboxes" name="TrueAnswer"></span>
                                                        <span><input type="text" name="AnswerInput" class="AnswerInputClass" placeholder="Ответ" onchange='inputCheck(event)'></span>
                                                        <span><button name="DeleteAnswerButton" class="dellDocButton">X</button></span>
                                                    </div>`);
    }
}

function DellAnswerClick(e) {
    if (e.target.getAttribute('name') != 'DeleteAnswerButton') {
        return;
    } else {
        e.currentTarget.remove();
    }
}

function DellQuestionClick(e) {
    if (e.target.getAttribute('name') != 'DellQuestionClick') {
        return;
    } else {
        e.currentTarget.remove();
    }
}

function addTestInDBClick(e) {
    if (e.target.getAttribute('name') != 'addTestInDBClick') {
        return;
    } else {
        var buttonID = e.target.id;
        //console.log(buttonID);

        var errDiv = e.currentTarget.getElementsByClassName('errorDivContainer')[0];
        errDiv.style.color = 'red';

        var id = e.currentTarget.querySelector("input[name=OneTestIDnum]").value;
        let name = e.currentTarget.querySelector("input[name=OneTestName]").value;
        if (name == '') {
            e.currentTarget.querySelector("input[name=OneTestName]").style.border = '1px solid red';
            errDiv.innerHTML = 'Вы не ввели название теста';
            return;
        } else {
            e.currentTarget.querySelector("input[name=OneTestName]").style.border = 'none';
            errDiv.innerHTML = '';
        }

        let nomination = e.currentTarget.querySelector("select[name=Nomination]").value;
        let question_html_class = e.currentTarget.getElementsByClassName('QuestionContainer');
        let questions_arr = [];
        for (let i = 0; i < question_html_class.length; i++) {
            let question = question_html_class[i].querySelector("input[name=Question]").value;
            if (question == '') {
                question_html_class[i].querySelector("input[name=Question]").style.border = '1px solid red';
                errDiv.innerHTML = 'Вы не ввели вопрос №' + (i + 1);
                return;
            } else {
                question_html_class[i].querySelector("input[name=Question]").style.border = 'none';
                errDiv.innerHTML = '';
            }

            let time = question_html_class[i].querySelector("input[name=TimeForAnswer]").value;
            if (time == '') {
                question_html_class[i].querySelector("input[name=TimeForAnswer]").style.border = '1px solid red';
                errDiv.innerHTML = 'Вы не указали время отведенное на вопрос в ' + (i + 1) + ' вопросе';
                return;
            } else {
                question_html_class[i].querySelector("input[name=TimeForAnswer]").style.border = 'none';
                errDiv.innerHTML = '';
            }

            let points = question_html_class[i].querySelector("input[name=PointsForAnswer]").value;
            if (points == '') {
                question_html_class[i].querySelector("input[name=PointsForAnswer]").style.border = '1px solid red';
                errDiv.innerHTML = 'Вы не указали колличество баллов за вопрос в ' + (i + 1) + ' вопросе';
                return;
            } else {
                question_html_class[i].querySelector("input[name=PointsForAnswer]").style.border = 'none';
                errDiv.innerHTML = '';
            }

            let checkbox_html_class = question_html_class[i].getElementsByClassName('AnsewerCheckboxes');
            let answers_html_class = question_html_class[i].getElementsByClassName('AnswerInputClass');
            let checkboxs = [];
            let answers = [];
            let checks_results = 0;
            for (let n = 0; n < checkbox_html_class.length; n++) {
                if (checkbox_html_class[n].checked) {
                    checkboxs[n] = 'true';
                    checks_results = checks_results + 1;
                } else checkboxs[n] = 'false';

                answers[n] = answers_html_class[n].value;
            }

            if (checks_results != 1) {
                for (let n = 0; n < checkbox_html_class.length; n++) {
                    answers_html_class[n].style.border = '1px solid red';
                }
                if (checks_results == 0) {
                    errDiv.innerHTML = "В " + (i + 1) + " вопросе не отмечено ни одного правильного ответа.";
                    return;
                } else if (checks_results > 1) {
                    errDiv.innerHTML = "В " + (i + 1) + " вопросе больше одного правильного ответа.";
                    return;
                }
            } else {
                for (let n = 0; n < checkbox_html_class.length; n++) {
                    answers_html_class[n].style.border = 'none';
                }
            }

            let question_obj = {
                question: question,
                checkboxs: checkboxs,
                answers: answers,
                time: time,
                points: points
            };
            questions_arr[i] = question_obj;
        }
        let test_obj = {
            id: id,
            name: name,
            nomination: nomination,
            questions_arr: questions_arr
        };

        fetch('http://lpptourism.ru/core/adminzone/tests/editTest.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(test_obj)
            })
            .then(response => response.text())
            .then(result => {
                errDiv.style.color = 'green';
                errDiv.innerHTML = result;
                //if (id != '') listTestLoader();
                if (buttonID == "addNewTestButton") {
                    //console.log(buttonID);
                    //console.log('Сейчас сработает таймер');
                    setTimeout(() => {
                        listTestLoader();
                        //console.log('Список тестов обновился');
                    }, 500);
                };
                loadNominationsList();
            })
            .catch(error => {
                errDiv.style.color = 'red';
                errDiv.innerHTML = error;
            });
    }
}

function hiddenListTestClick(div) {
    div.getElementsByClassName('HiddenTest')[0].style.display = 'block';
}

function closeHiddenTest(e) {
    if (e.target.getAttribute('name') != 'closeEditTest') {
        return;
    } else {
        e.currentTarget.getElementsByClassName('HiddenTest')[0].style.display = 'none';
    }
}


function remove_no_printed(str) {
  return str.replace(/[^A-Za-z 0-9 А-Яа-я\.,\?""''!@#\$%\^&\*\(\)-_=\+;:<>\/\\\|\}\{\[\]`~]*/g, '');
}

function inputCheck(e) {
    e.target.value = remove_no_printed(e.target.value);
    e.target.value = e.target.value.replace('"','``');
    e.target.value = e.target.value.replace("'","`");
}
