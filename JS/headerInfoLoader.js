fetch ('http://lpptourism.ru/core/userContent/headerLoader.php')
.then(response => response.json())
.then(result => {
    document.getElementById('headerInfoEdited').innerHTML = result[`headerInfoEdited`];
    document.getElementById('blackHeaderInfoEdited').innerHTML = result[`blackHeaderInfoEdited`];
});
