function visibleNewsClick(e) {
    if (e.target.closest('.visibleNewsPart')) {
        e.currentTarget.getElementsByClassName('hiddenNewsPart')[0].style.display = 'block';
    }
}

function oneNewsEditClick(e) {

    if (e.target.getAttribute('name') == 'deleteNewsButton') {
        let buttonData = {
            id: e.currentTarget.querySelector("input[name=editInputID]").value,
            buttonFunc: "delete"
        };
        DeleteButtonsFetch(buttonData);
    } else if (e.target.getAttribute('name') == 'closeNewsButton') {
        e.currentTarget.style.display = 'none';
    } else if (e.target.getAttribute('name') == 'saveNewsButton') {
        let saveData = new FormData();

        let description = e.currentTarget.getElementsByClassName('hiddenDescriptionDiv')[0].getElementsByClassName('note-editable')[0].innerHTML;
        let text = e.currentTarget.getElementsByClassName('hiddenTextDiv')[0].getElementsByClassName('note-editable')[0].innerHTML;

        saveData.append('id', e.currentTarget.querySelector("input[name=editInputID]").value);
        saveData.append('url', e.currentTarget.querySelector('input[name=editInputURl]').value);
        saveData.append('photo', e.currentTarget.querySelector('input[name=editInputPhoto]').files[0]);
        saveData.append('name', e.currentTarget.querySelector('input[name=editInputName]').value);
        saveData.append('description', description);
        saveData.append('text', text);

        var diverr = e.currentTarget.getElementsByClassName('saveNewsDivErr')[0];

        fetch('http://lpptourism.ru/core/adminzone/editNews.php', {
                method: 'POST',
                body: saveData
            })
            .then(diverr.innerHTML = 'Ожидаем ответа сервера')
            .then(responese => responese.text())
            .then(result => diverr.innerHTML = result)
            .catch(error => {
                diverr.innerHTML = 'Произошла ошибка';
                console.log(error);
            });
    }
}

const DeleteButtonsFetch = function(data) {
    fetch('http://lpptourism.ru/core/adminzone/deleteNews.php', {
            method: 'POST',
            headers: {
                'Conten-Type': 'application/json;'
            },
            body: JSON.stringify(data)
        })
        .then(document.getElementById('errDivForNewsEditor').innerHTML = 'Ожидаем ответа от сервера')
        .then(responese => responese.text())
        .then(result => {
            document.getElementById('errDivForNewsEditor').innerHTML = result;
            NewsLoaderFunc();
        })
        .catch(err => {
            document.getElementById('errDivForNewsEditor').innerHTML = 'Произошла ошибка';
            console.log(err);
        });
};
