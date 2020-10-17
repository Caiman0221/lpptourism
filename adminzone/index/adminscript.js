
function labelChange(e) {
    e.currentTarget.getElementsByClassName('editNewsLabel')[0].innerHTML = e.currentTarget.querySelector("input[name=NewsNewInput]").files[0].name;
}

function adminMainPartClick() {
    document.getElementById('adminMainPart').style.display = 'block';
    document.getElementById('adminNewsContainer').style.display = 'none';
    document.getElementById('adminTestsContainer').style.display = 'none';
    document.getElementById('adminUsersContainer').style.display = 'none';
    document.getElementById('adminPageEditor').style.display = 'none';
}

function adminNewsClick() {
    document.getElementById('adminMainPart').style.display = 'none';
    document.getElementById('adminNewsContainer').style.display = 'block';
    document.getElementById('adminTestsContainer').style.display = 'none';
    document.getElementById('adminUsersContainer').style.display = 'none';
    document.getElementById('adminPageEditor').style.display = 'none';
}

function adminTestClick() {
    document.getElementById('adminMainPart').style.display = 'none';
    document.getElementById('adminNewsContainer').style.display = 'none';
    document.getElementById('adminTestsContainer').style.display = 'block';
    document.getElementById('adminUsersContainer').style.display = 'none';
    document.getElementById('adminPageEditor').style.display = 'none';
}

function adminUsersClick() {
    document.getElementById('adminMainPart').style.display = 'none';
    document.getElementById('adminNewsContainer').style.display = 'none';
    document.getElementById('adminTestsContainer').style.display = 'none';
    document.getElementById('adminUsersContainer').style.display = 'block';
    document.getElementById('adminPageEditor').style.display = 'none';
}

function adminPageEditorClick() {
    document.getElementById('adminMainPart').style.display = 'none';
    document.getElementById('adminNewsContainer').style.display = 'none';
    document.getElementById('adminTestsContainer').style.display = 'none';
    document.getElementById('adminUsersContainer').style.display = 'none';
    document.getElementById('adminPageEditor').style.display = 'block';
}

function newsAddClick() {
    document.getElementById('newsAddArticle').style.display = 'block';
    document.getElementById('newsListArticle').style.display = 'none';
}

function newsListClick() {
    document.getElementById('newsAddArticle').style.display = 'none';
    document.getElementById('newsListArticle').style.display = 'block';
}
