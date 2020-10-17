window.onload =  function() {
    //testTime()
};

let timerId = setInterval(() => {
    let token_time = localStorage.getItem('token_time');
    let time = Math.round((new Date()).getTime() / 1000);
    if ((time - token_time) > 60 * 15) {
        refTokenCheck('http://lpptourism.ru/core/token.php', 'test');
    }
}, 5000);

function inputCheckClick(e) {
    if (e.target.closest('label')) {
        let labels = e.currentTarget.getElementsByTagName('label');
        for (let i = 0; i < labels.length; i++) {
            labels[i].style.background = '#dfdfdf';
            e.currentTarget.getElementsByClassName('testCheckboxInput')[i].checked = false;
        }
        e.target.style.background = 'green';
        e.target.getElementsByClassName('testCheckboxInput')[0].checked = true;
    }

}

function startTestButtonClick() {
    let data = {
        access_token: localStorage.getItem('access_token')
    };
    fetch('http://lpptourism.ru/core/userContent/partFiles/test.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.text())
        .then(result => {
            TokenCheck('http://lpptourism.ru/core/token.php', 'test');
        });
}

function testButtonsClick(e) {
    var part_inputs_arr;
    var part_answers_arr;
    if (e.target.getAttribute('name') == 'testButtons') {
        part_inputs_arr = e.currentTarget.querySelectorAll('.testCheckbox');
        part_answers_arr = [];
        i = 0;
        while (i < part_inputs_arr.length) {
            part_answers_arr[i] = 'empty';
            i++;
        }

    } else if (e.target.getAttribute('name') == 'testButtonConfirm') {
        part_inputs_arr = e.currentTarget.querySelectorAll('.testCheckbox'); //все input checkbox к вопросу
        part_answers_arr = []; //массив ответов
        i = 0;

        while (i < part_inputs_arr.length) {
            let num = part_inputs_arr[i].querySelector('input[name=answerid]').value;
            let check = part_inputs_arr[i].querySelector('input[name=answecheck]').checked;
            if (check == true) check = 'true';
            else check = 'false';
            part_answers_arr[num] = check; //записывает в массив к вопросу данные по каждому ответу
            i++;
        }

        i = 0;
        res_empty = 0;
        while (i < part_answers_arr.length) {
            if (part_answers_arr[i] === 'true') {
                res_empty++;
            }
            i++;
        }

        if (res_empty == 0 && testTimerForButtonClick() == 1) {
            return;
            console.log(timer);
        }

        if (res_empty == 0 && testTimerForButtonClick() == 0) {
            i = 0;
            while(i < part_answers_arr.length) {
                part_answers_arr[i] = 'time';
                i++;
            }
        }
    } else {
        return;
    }
    let question_num = e.currentTarget.querySelector('input[name=questionID]').value;
    let data = {
        access_token: localStorage.getItem('access_token'),
        question_num: question_num,
        part_answers_arr: part_answers_arr
    };
    funcForTimer(0);
    fetch('http://lpptourism.ru/core/userContent/partFiles/submitAnswer.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.text())
    .then(result => {
        TokenCheck('http://lpptourism.ru/core/token.php', 'test');
    })
    .catch(error => console.log(error));
}

function testTime() {
    let question_id = Number(document.getElementById('questionID').value);
    let question_time = Number(document.getElementById('questionTime').value);

    let userTime = Math.round(new Date().getTime()/1000);
    localStorage.setItem('question_time',question_time + userTime);

    if (question_id != localStorage.getItem('question_id')) {
        localStorage.setItem('question_id',question_id);
    }

    funcForTimer(1);
}

function testTimerForButtonClick() {
    if (document.getElementById('timerID').innerHTML > 0) {
        return 1;
    } else {
        return 0;
    }
}

function funcForTimer(start) {
    let userTime = Math.round(new Date().getTime()/1000);

    if (localStorage.getItem('timerForQuestion') && localStorage.getItem('timerForQuestion') > 0) {
        let question_time = userTime + Number(localStorage.getItem('timerForQuestion'));
        localStorage.setItem('question_time',question_time);
    }

    if (start == 1) {
        var timerForDiv = setInterval(() => {
            userTime = Math.round(new Date().getTime()/1000);
            question_time = localStorage.getItem('question_time');
            if (document.getElementById('timerID')) {
                document.getElementById('timerID').innerHTML = question_time - userTime;
                localStorage.setItem('timerForQuestion',question_time - userTime);
            }
            if (document.getElementById('timerID').innerHTML <= 0) {
                document.getElementById('testButtonConfirm').click();
                clearInterval(timerForDiv);
            }
        }, 1000);
    } else if (start == 0){
        clearInterval(timerForDiv);
        localStorage.removeItem('timerForQuestion');
        localStorage.removeItem('question_time');
        document.getElementById('timerID').innerHTML = 0;
    }
}
