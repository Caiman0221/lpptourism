const newsPublishClick = function() {
    let diverr = document.getElementById('newsAddDivErr');
    diverr.style.color = 'red';

    let newsUrl = document.getElementById('newsAddInputUrl').value;
    let newsPhoto = document.getElementById('newsAddInputPhoto').files[0];
    let newsName = document.getElementById('newsAddInputName');
    if (newsName.value == '') {
        newsName.style.border = '1px solid red';
        diverr.innerHTML = 'Вы не ввели название новости';
        return;
    } else {
        newsName.style.border = 'none';
        newsName = newsName.value;
        diverr.innerHTML = '';
    }

    let newsDescription = $('#newsAddInputDescription').eq(0).summernote('code');
    let newsText = $('#newsAddInputText').eq(0).summernote('code');
    if (newsText == '<p><br></p>') diverr.innerHTML = 'Вы не ввели текст новости';
    else diverr.innerHTML = '';

    let newsData = new FormData();
    newsData.append('url', newsUrl);
    newsData.append('photo', newsPhoto);
    newsData.append('name', newsName);
    newsData.append('description', newsDescription);
    newsData.append('text', newsText);

    diverr.style.color = 'black';

    fetch('http://lpptourism.ru/core/adminzone/newsNew.php', {
            method: 'POST',
            body: newsData
        })
        .then(diverr.innerHTML = 'Дожидаемся ответа сервера')
        .then(responese => responese.text())
        .then(result => {
            diverr.innerHTML = result;
            NewsLoaderFunc();
        })
        .catch(error => console.log(error));
};

async function fetchNews(url, data) {
    try {
        const response = await fetch(url, {
            method: 'POST',
            body: data
        });
        const result = await response.text();
    } catch (error) {
        console.error(error);
    }
}
