function showHiddenButtonsAdmin(data) {
    let numId = showNumById(data);
    document.getElementById('hiddenAdminButtonPart' + numId).style.display = 'block';
}

var showNumById = function(data) {
    str = data.id;
    let num = str.replace(/\D+/g, '');
    return (num);
};

function closeEditorAdminButtonsClick(data) {
    let numId = showNumById(data);
    document.getElementById('infoAdminEditorPage' + numId).style.display = 'none';
    document.getElementById('editAdminEditorPage' + numId).style.display = 'none';
    document.getElementById('hiddenAdminButtonPart' + numId).style.display = 'none';
}

function infoEditorAdminButtonsClick(data) {
    let numId = showNumById(data);
    document.getElementById('infoAdminEditorPage' + numId).style.display = 'block';
    document.getElementById('editAdminEditorPage' + numId).style.display = 'none';
}

function EditEditorAdminButtonsClick(data) {
    let numId = showNumById(data);
    document.getElementById('infoAdminEditorPage' + numId).style.display = 'none';
    document.getElementById('editAdminEditorPage' + numId).style.display = 'block';
}

function adminListMenu() {
    let select = document.getElementById('adminSortSelect').value;
    let search = document.getElementById('adminSearchInput').value;
    let NumOfPages = document.getElementById('adminNumOfPages').value;

    let data = {
        select: select,
        search: search,
        NumOfPages: NumOfPages
    };

    fetch('http://lpptourism.ru/core/adminzone/usersLists/adminsList.php', {
            method: 'POST',
            headers: {
                'Conten-Type': 'application/json;'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.text())
        .then(result => {
            document.getElementById('loaderForAdmins').innerHTML = result;
        })
        .catch(error => {
            document.getElementById('loaderForAdmins').innerHTML = error;
        });
}

function adminListNumPages() {
    let adminListNum = document.getElementById('adminListNum').value;

    data = {
        adminListNum: adminListNum
    };
    fetch('http://lpptourism.ru/core/adminzone/usersLists/adminsListNumPages.php', {
            method: 'POST',
            headers: {
                'Conten-Type': 'application/json;'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.text())
        .then(result => {
            document.getElementById('adminNumOfPages').innerHTML = result;
            adminListMenu();
        })
        .catch(error => console.log(error));
}

function adminsListAddAll(button) {
    let checkboxs = document.getElementsByName('AdminEditorCheck');
    for (let i = 0; i < checkboxs.length; i++) {
        checkboxs[i].checked = true;
    }
    button.innerHTML = 'Убрать всех';
    button.onclick = function() {adminsListCheckDellAll(this);};
}

function adminsListCheckDellAll(button) {
    let checkboxs = document.getElementsByName('AdminEditorCheck');
    for (let i = 0; i < checkboxs.length; i++) {
        checkboxs[i].checked = false;
    }
    button.innerHTML = 'Выбрать всех';
    button.onclick = function() {adminsListAddAll(this);};
}

function adminsListActivChecked() {
    let checkboxs = document.getElementsByName('AdminEditorCheck');
    let adminsID = [];
    let n = 0;
    for (let i = 0; i < checkboxs.length; i++) {
        if (checkboxs[i].checked == true) {
            adminsID[n] = showNumById(checkboxs[i]);
            n++;
        }
    }

    let data = {
        adminsID: adminsID,
        user: 'admin',
        status: 'activ'
    };

    fetch('http://lpptourism.ru/core/adminzone/usersLists/chengeAllUserStatus.php', {
            method: "POST",
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(document.getElementById('adminListDivResults').innerHTML = 'Дожидаюсь ответа от сервера')
        .then(response => response.text())
        .then(result => {
            document.getElementById('adminListDivResults').innerHTML = result;
            adminListMenu();
        })
        .catch(error => {
            document.getElementById('adminListDivResults').innerHTML = 'Произошла ошибка';
            console.log(error);
        });
}

function adminsListDisableChecked() {
    let checkboxs = document.getElementsByName('AdminEditorCheck');
    let adminsID = [];
    let n = 0;
    for (let i = 0; i < checkboxs.length; i++) {
        if (checkboxs[i].checked == true) {
            adminsID[n] = showNumById(checkboxs[i]);
            n++;
        }
    }

    let data = {
        adminsID: adminsID,
        user: 'admin',
        status: 'disabled'
    };

    fetch('http://lpptourism.ru/core/adminzone/usersLists/chengeAllUserStatus.php', {
            method: 'POST',
            headers: {
                'Conten-Type': 'application/json;'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.text())
        .then(result => {
            document.getElementById('adminListDivResults').innerHTML = result;
            adminListMenu();
        })
        .catch(error => document.getElementById('adminListDivResults').innerHTML = error);
}

function adminsListDelete() {
    document.getElementById('adminLiastConfirmDelete').style.display = 'block';
}

function adminsListNoDeleteButton() {
    document.getElementById('adminLiastConfirmDelete').style.display = 'none';
}

function adminsListDeleteButton() {
    let checkboxs = document.getElementsByName('AdminEditorCheck');
    let adminsID = [];
    let n = 0;
    for (let i = 0; i < checkboxs.length; i++) {
        if (checkboxs[i].checked == true) {
            adminsID[n] = showNumById(checkboxs[i]);
            n++;
        }
    }

    let data = {
        adminsID: adminsID,
    };

    fetch('http://lpptourism.ru/core/adminzone/usersLists/adminsListDelete.php', {
            method: 'POST',
            headers: {
                'Conten-Type': 'application/json;'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.text())
        .then(result => {
            document.getElementById('adminListDivResults').innerHTML = result;
            adminListMenu();
        })
        .catch(error => document.getElementById('adminListDivResults').innerHTML = error);
}


function adminListEditOneClick(e) {
    if (e.target.getAttribute('name') != 'adminListEditOneClick') return;
    else {
        let divErr = e.currentTarget.getElementsByClassName('adminListEditOneError')[0];
        divErr.style.color = 'red';

        let id = e.currentTarget.querySelector("input[name=editAdminID]");
        if (id.value == '') {
            divErr.innerHTML = 'произошла ошибка, id админа пуст';
            return;
        } else {
            divErr.innerHTML = '';
            id = id.value;
        }
        let email = e.currentTarget.querySelector('input[name=editAdminEmail');
        if (email.value == '') {
            email.style.border = '1px solid red';
            divErr.innerHTML = 'Вы не ввели email пользователя';
            return;
        } else {
            email.style.border = 'none';
            email = email.value;
            divErr.innerHTML = '';
        }
        let password = e.currentTarget.querySelector('input[name=editAdminPassword').value;
        let name = e.currentTarget.querySelector('input[name=editAdminName');
        if (name.value == '') {
            name.style.border = '1px solid red';
            divErr.innerHTML = 'Вы не ввели имя пользователя';
            return;
        } else {
            name.style.border = 'none';
            name = name.value;
            divErr.innerHTML = '';
        }

        let lastName = e.currentTarget.querySelector('input[name=editAdminLastName');
        if (lastName.value == '') {
            lastName.style.border = '1px solid red';
            divErr.innerHTML = 'вы не ввели фамилию пользователя';
            return;
        } else {
            lastName.style.border = 'none';
            lastName = lastName.value;
            divErr.innerHTML = '';
        }

        let phone = e.currentTarget.querySelector('input[name=editAdminPhone');
        if (phone.value == '') {
            phone.style.border = '1px solid red';
            divErr.innerHTML = 'Вы не ввели телефон пользователя';
            return;
        } else {
            phone.style.border = 'none';
            phone = phone.value;
            divErr.innerHTML = '';
        }

        let checkbox = e.currentTarget.querySelector('input[name=editAdminCheckbox');
        if (checkbox.checked == true) checkbox = 'activ';
        else checkbox = 'disabled';

        let data = {
            id: id,
            email: email,
            password: password,
            name: name,
            lastName: lastName,
            phone: phone,
            checkbox: checkbox
        };

        divErr.style.color = 'black';
        fetch('http://lpptourism.ru/core/adminzone/usersLists/admin/editAdminList.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(divErr.innerHTML = 'Дожидаюсь ответа от сервера')
            .then(response => response.text())
            .then(result => divErr.innerHTML = result)
            .catch(error => {
                console.log(error);
                divErr.style.color = 'red';
                divErr.innerHTML = 'Произошла ошибка';
            });
    }
}



//COORDS
function CoordListMenu() {
    let select = document.getElementById('coordSortSelect').value;
    let search = document.getElementById('coordSearchInput').value;
    let NumOfPages = document.getElementById('coordNumOfPages').value;

    let data = {
        select: select,
        search: search,
        NumOfPages: NumOfPages
    };

    fetch('http://lpptourism.ru/core/adminzone/usersLists/coord/coordList.php', {
            method: 'POST',
            headers: {
                'Conten-Type': 'application/json;'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.text())
        .then(result => {
            document.getElementById('loaderForCoords').innerHTML = result;
        })
        .catch(error => {
            document.getElementById('loaderForCoords').innerHTML = error;
        });
}

function coordListNumPages() {
    let coordListNum = document.getElementById('coordListNum').value;

    data = {
        coordListNum: coordListNum
    };
    fetch('http://lpptourism.ru/core/adminzone/usersLists/coord/coordListNumPages.php', {
            method: 'POST',
            headers: {
                'Conten-Type': 'application/json;'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.text())
        .then(result => {
            document.getElementById('coordNumOfPages').innerHTML = result;
            adminListMenu();
        })
        .catch(error => console.log(error));
}

function coordsListAddAll(button) {
    let checkboxs = document.getElementsByName('CoordEditorCheck');
    for (let i = 0; i < checkboxs.length; i++) {
        checkboxs[i].checked = true;
    }
    button.innerHTML = 'Убрать всех';
    button.onclick = function() {coordsListCheckDellAll(this);};
}
function coordsListCheckDellAll(button) {
    let checkboxs = document.getElementsByName('CoordEditorCheck');
    for (let i = 0; i < checkboxs.length; i++) {
        checkboxs[i].checked = false;
    }
    button.innerHTML = 'Выбрать всех';
    button.onclick = function() {coordsListAddAll(this);};
}

function coordsListActivChecked() {
    let checkboxs = document.getElementsByName('CoordEditorCheck');
    let coordsID = [];
    let n = 0;
    for (let i = 0; i < checkboxs.length; i++) {
        if (checkboxs[i].checked == true) {
            coordsID[n] = showNumById(checkboxs[i]);
            n++;
        }
    }

    let data = {
        adminsID: coordsID,
        user: 'coord',
        status: 'activ'
    };

    fetch('http://lpptourism.ru/core/adminzone/usersLists/chengeAllUserStatus.php', {
            method: 'POST',
            headers: {
                'Conten-Type': 'application/json;'
            },
            body: JSON.stringify(data)
        })
        .then(document.getElementById('coordListDivResults').innerHTML = 'Дожидаюсь ответа от сервера')
        .then(response => response.text())
        .then(result => {
            document.getElementById('coordListDivResults').innerHTML = result;
            CoordListMenu();
        })
        .catch(error => document.getElementById('coordListDivResults').innerHTML = error);
}

function coordsListDisableChecked() {
    let checkboxs = document.getElementsByName('CoordEditorCheck');
    let coordsID = [];
    let n = 0;
    for (let i = 0; i < checkboxs.length; i++) {
        if (checkboxs[i].checked == true) {
            coordsID[n] = showNumById(checkboxs[i]);
            n++;
        }
    }

    let data = {
        adminsID: coordsID,
        user: 'coord',
        status: 'disabled'
    };

    fetch('http://lpptourism.ru/core/adminzone/usersLists/chengeAllUserStatus.php', {
            method: 'POST',
            headers: {
                'Conten-Type': 'application/json;'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.text())
        .then(result => {
            document.getElementById('coordListDivResults').innerHTML = result;
            CoordListMenu();
        })
        .catch(error => document.getElementById('coordListDivResults').innerHTML = error);
}

function coordsListDelete() {
    document.getElementById('coordLiastConfirmDelete').style.display = 'block';
}

function coordsListNoDeleteButton() {
    document.getElementById('coordLiastConfirmDelete').style.display = 'none';
}

function coordsListDeleteButton() {
    let checkboxs = document.getElementsByName('CoordEditorCheck');
    let coordsID = [];
    let n = 0;
    for (let i = 0; i < checkboxs.length; i++) {
        if (checkboxs[i].checked == true) {
            coordsID[n] = showNumById(checkboxs[i]);
            n++;
        }
    }

    let data = {
        coordsID: coordsID,
    };

    fetch('http://lpptourism.ru/core/adminzone/usersLists/coord/coordsListDelete.php', {
            method: 'POST',
            headers: {
                'Conten-Type': 'application/json;'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.text())
        .then(result => {
            document.getElementById('coordListDivResults').innerHTML = result;
            CoordListMenu();
        })
        .catch(error => document.getElementById('coordListDivResults').innerHTML = error);
}

function showHiddenButtonsCoord(data) {
    let numId = showNumById(data);
    document.getElementById('hiddenCoordButtonPart' + numId).style.display = 'block';
}

function infoEditorCoordButtonsClick(data) {
    let numId = showNumById(data);
    document.getElementById('infoCoordEditorPage' + numId).style.display = 'block';
    document.getElementById('editCoordEditorPage' + numId).style.display = 'none';
}

function closeEditorCoordButtonsClick(data) {
    let numId = showNumById(data);
    document.getElementById('infoCoordEditorPage' + numId).style.display = 'none';
    document.getElementById('editCoordEditorPage' + numId).style.display = 'none';
    document.getElementById('hiddenCoordButtonPart' + numId).style.display = 'none';
}

function infoEditorCoordButtonsClick(data) {
    let numId = showNumById(data);
    document.getElementById('infoCoordEditorPage' + numId).style.display = 'block';
    document.getElementById('editCoordEditorPage' + numId).style.display = 'none';
}

function EditEditorCoordButtonsClick(data) {
    let numId = showNumById(data);
    document.getElementById('infoCoordEditorPage' + numId).style.display = 'none';
    document.getElementById('editCoordEditorPage' + numId).style.display = 'block';
}

function СoordListEditOneClick(e) {
    if (e.target.getAttribute('name') != 'CoordListEditOneClick') {
        return;
    } else {
        let divErr = e.currentTarget.getElementsByClassName('CoordListEditOneError')[0];
        divErr.style.color = 'red';

        let id = e.currentTarget.querySelector("input[name=editCoordID]");
        if (id.value == '') {
            divErr.innerHTML = 'произошла ошибка, id админа пуст';
            return;
        } else {
            divErr.innerHTML = '';
            id = id.value;
        }
        let email = e.currentTarget.querySelector('input[name=editCoordEmail');
        if (email.value == '') {
            email.style.border = '1px solid red';
            divErr.innerHTML = 'Вы не ввели email пользователя';
            return;
        } else {
            email.style.border = 'none';
            email = email.value;
            divErr.innerHTML = '';
        }
        let password = e.currentTarget.querySelector('input[name=editCoordPassword').value;
        let name = e.currentTarget.querySelector('input[name=editCoordName');
        if (name.value == '') {
            name.style.border = '1px solid red';
            divErr.innerHTML = 'Вы не ввели имя пользователя';
            return;
        } else {
            name.style.border = 'none';
            name = name.value;
            divErr.innerHTML = '';
        }

        let lastName = e.currentTarget.querySelector('input[name=editCoordLastName');
        if (lastName.value == '') {
            lastName.style.border = '1px solid red';
            divErr.innerHTML = 'вы не ввели фамилию пользователя';
            return;
        } else {
            lastName.style.border = 'none';
            lastName = lastName.value;
            divErr.innerHTML = '';
        }

        let phone = e.currentTarget.querySelector('input[name=editCoordPhone');
        if (phone.value == '') {
            phone.style.border = '1px solid red';
            divErr.innerHTML = 'Вы не ввели телефон пользователя';
            return;
        } else {
            phone.style.border = 'none';
            phone = phone.value;
            divErr.innerHTML = '';
        }

        let checkbox = e.currentTarget.querySelector('input[name=editCoordCheckbox');
        if (checkbox.checked == true) checkbox = 'activ';
        else checkbox = 'disabled';

        let data = {
            id: id,
            email: email,
            password: password,
            name: name,
            lastName: lastName,
            phone: phone,
            checkbox: checkbox
        };

        divErr.style.color = 'black';
        fetch('http://lpptourism.ru/core/adminzone/usersLists/coord/editCoordList.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(divErr.innerHTML = 'Дожидаюсь ответа от сервера')
            .then(response => response.text())
            .then(result => {
                divErr.innerHTML = result;
            })
            .catch(error => divErr.innerHTML = error);
    }
}

//Parts
function PartListMenu() {
    let select = document.getElementById('partSortSelect').value;
    let search = document.getElementById('partSearchInput').value;
    let NumOfPages = document.getElementById('partNumOfPages').value;

    let data = {
        select: select,
        search: search,
        NumOfPages: NumOfPages
    };

    fetch('http://lpptourism.ru/core/adminzone/usersLists/parts/PartList.php', {
            method: 'POST',
            headers: {
                'Conten-Type': 'application/json;'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.text())
        .then(result => {
            document.getElementById('loaderForParts').innerHTML = result;
        })
        .catch(error => {
            document.getElementById('loaderForParts').innerHTML = error;
        });
}

function partListNumPages() {
    let partListNum = document.getElementById('partListNum').value;

    data = {
        partListNum: partListNum
    };
    fetch('http://lpptourism.ru/core/adminzone/usersLists/parts/PartListNumPages.php', {
            method: 'POST',
            headers: {
                'Conten-Type': 'application/json;'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.text())
        .then(result => {
            document.getElementById('partNumOfPages').innerHTML = result;
            adminListMenu();
        })
        .catch(error => console.log(error));
}

function partsListAddAll(button) {
    let checkboxs = document.getElementsByName('partEditorCheck');
    for (let i = 0; i < checkboxs.length; i++) {
        checkboxs[i].checked = true;
    }
    button.innerHTML = 'Убрать всех';
    button.onclick = function() {partsListCheckDellAll(this);};
}

function partsListCheckDellAll(button) {
    let checkboxs = document.getElementsByName('partEditorCheck');
    for (let i = 0; i < checkboxs.length; i++) {
        checkboxs[i].checked = false;
    }
    button.innerHTML = 'Выбрать всех';
    button.onclick = function() {partsListAddAll(this);};
}

function partsListActivChecked() {
    let checkboxs = document.getElementsByName('partEditorCheck');
    let partsID = [];
    let n = 0;
    for (let i = 0; i < checkboxs.length; i++) {
        if (checkboxs[i].checked == true) {
            partsID[n] = showNumById(checkboxs[i]);
            n++;
        }
    }

    let data = {
        adminsID: partsID,
        user: 'part',
        status: 'activ'
    };

    fetch('http://lpptourism.ru/core/adminzone/usersLists/chengeAllUserStatus.php', {
            method: 'POST',
            headers: {
                'Conten-Type': 'application/json;'
            },
            body: JSON.stringify(data)
        })
        .then(document.getElementById('partListDivResults').innerHTML = 'Дожидаюсь ответа от сервера')
        .then(response => response.text())
        .then(result => {
            document.getElementById('partListDivResults').innerHTML = result;
            PartListMenu();
        })
        .catch(error => document.getElementById('partListDivResults').innerHTML = error);
}

function partsListDisableChecked() {
    let checkboxs = document.getElementsByName('partEditorCheck');
    let partsID = [];
    let n = 0;
    for (let i = 0; i < checkboxs.length; i++) {
        if (checkboxs[i].checked == true) {
            partsID[n] = showNumById(checkboxs[i]);
            n++;
        }
    }

    let data = {
        adminsID: partsID,
        user: 'part',
        status: 'disabled'
    };

    fetch('http://lpptourism.ru/core/adminzone/usersLists/chengeAllUserStatus.php', {
            method: 'POST',
            headers: {
                'Conten-Type': 'application/json;'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.text())
        .then(result => {
            document.getElementById('partListDivResults').innerHTML = result;
            PartListMenu();
        })
        .catch(error => document.getElementById('partListDivResults').innerHTML = error);
}

function partsListDelete() {
    document.getElementById('partLiastConfirmDelete').style.display = 'block';
}

function partsListNoDeleteButton() {
    document.getElementById('partLiastConfirmDelete').style.display = 'none';
}

function partsListDeleteButton() {
    let checkboxs = document.getElementsByName('partEditorCheck');
    let partsID = [];
    let n = 0;
    for (let i = 0; i < checkboxs.length; i++) {
        if (checkboxs[i].checked == true) {
            partsID[n] = showNumById(checkboxs[i]);
            n++;
        }
    }

    let data = {
        partsID: partsID,
    };

    fetch('http://lpptourism.ru/core/adminzone/usersLists/parts/PartListDelete.php', {
            method: 'POST',
            headers: {
                'Conten-Type': 'application/json;'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.text())
        .then(result => {
            document.getElementById('partListDivResults').innerHTML = result;
            PartListMenu();
        })
        .catch(error => document.getElementById('partListDivResults').innerHTML = error);
}

function showHiddenButtonsPart(data) {
    let numId = showNumById(data);
    document.getElementById('hiddenPartButtonPart' + numId).style.display = 'block';
}

function infoEditorPartButtonsClick(data) {
    let numId = showNumById(data);
    document.getElementById('infoPartEditorPage' + numId).style.display = 'block';
    document.getElementById('editPartEditorPage' + numId).style.display = 'none';
}

function closeEditorPartButtonsClick(data) {
    let numId = showNumById(data);
    document.getElementById('infoPartEditorPage' + numId).style.display = 'none';
    document.getElementById('editPartEditorPage' + numId).style.display = 'none';
    document.getElementById('hiddenPartButtonPart' + numId).style.display = 'none';
    document.getElementById('editPartResults' + numId).style.display = 'none';
}

function infoEditorPartButtonsClick(data) {
    let numId = showNumById(data);
    document.getElementById('infoPartEditorPage' + numId).style.display = 'block';
    document.getElementById('editPartEditorPage' + numId).style.display = 'none';
    document.getElementById('editPartResults' + numId).style.display = 'none';
}

function EditEditorPartButtonsClick(data) {
    let numId = showNumById(data);
    document.getElementById('infoPartEditorPage' + numId).style.display = 'none';
    document.getElementById('editPartEditorPage' + numId).style.display = 'block';
    document.getElementById('editPartResults' + numId).style.display = 'none';
}

function ResultsEditorPartButtonsClick(data) {
    let numId = showNumById(data);
    document.getElementById('infoPartEditorPage' + numId).style.display = 'none';
    document.getElementById('editPartEditorPage' + numId).style.display = 'none';
    document.getElementById('editPartResults' + numId).style.display = 'block';
}

function PartListEditOneClick(e) {
    if (e.target.getAttribute('name') != 'PartListEditOneClick') {
        return;
    } else {
        let divErr = e.currentTarget.getElementsByClassName('PartListEditOneError')[0];
        divErr.style.color = 'red';

        let id = e.currentTarget.querySelector("input[name=editPartID]");
        if (id.value == '') {
            divErr.innerHTML = 'Произошла ошибка';
            return;
        } else id = id.value;

        let email = e.currentTarget.querySelector("input[name=editPartEmail]");
        if (email.value == '') {
            email.style.border = '1px solid red';
            divErr.innerHTML = 'Вы не ввели email';
            return;
        } else {
            email.style.border = 'none';
            divErr.innerHTML = '';
            email = email.value;
        }

        let password = e.currentTarget.querySelector("input[name=editPartPassword]").value;
        let name = e.currentTarget.querySelector("input[name=editPartName]");
        if (name.value == '') {
            name.style.border = '1px solid red';
            divErr.innerHTML = 'Вы не ввели имя';
            return;
        } else {
            name.style.border = 'none';
            divErr.innerHTML = '';
            name = name.value;
        }

        let last_name = e.currentTarget.querySelector("input[name=editPartLastName]");
        if (last_name.value == '') {
            last_name.style.border = '1px solid red';
            divErr.innerHTML = 'Вы не ввели фамилию';
            return;
        } else {
            last_name.style.border = 'none';
            divErr.innerHTML = '';
            last_name = last_name.value;
        }

        let third_name = e.currentTarget.querySelector("input[name=editPartThirdName]");
        if (third_name.value == '') {
            third_name.style.border = '1px solid red';
            divErr.innerHTML = 'Вы не ввели отчество';
            return;
        } else {
            third_name.style.border = 'none';
            third_name = third_name.value;
            divErr.innerHTML = '';
        }

        let mobile_phone = e.currentTarget.querySelector("input[name=editPartPhone]");
        if (mobile_phone.value == '') {
            mobile_phone.style.border = '1px solid red';
            divErr.innerHTML = 'Вы не ввели мобильный телефон';
            return;
        } else {
            mobile_phone.style.border = 'none';
            divErr.innerHTML = '';
            mobile_phone = mobile_phone.value;
        }

        let work_phone = e.currentTarget.querySelector("input[name=editPartWorkPhone]");
        if (work_phone.value == '') {
            work_phone.style.border = '1px solid red';
            divErr.innerHTML = 'Вы не ввели рабочий телефон';
            return;
        } else {
            work_phone.style.border = 'none';
            divErr.innerHTML = '';
            work_phone = work_phone.value;
        }

        let nomination = e.currentTarget.getElementsByClassName('editPartNomination')[0].value;
        let pass = e.currentTarget.querySelector("input[name=editPartPass]").value;
        let work_place = e.currentTarget.querySelector("input[name=editPartWorkPlace]").value;
        let work_experience = e.currentTarget.querySelector("input[name=editPartWorkExperience]");
        if (work_experience.value < 3) {
            work_experience.style.border = '1px solid red';
            divErr.innerHTML = 'Стаж работы должен быть больше 3';
            return;
        } else {
            work_experience.style.border = 'none';
            divErr.innerHTML = '';
            work_experience = work_experience.value;
        }

        let education = e.currentTarget.querySelector("input[name=editPartEducation]").value;
        let name_education = e.currentTarget.querySelector("input[name=editPartNameEducation]").value;
        let training = e.currentTarget.querySelector("input[name=editPartTraining]").value;
        let adress_index = e.currentTarget.querySelector("input[name=editPartAdressIndex]").value;
        let home_adress = e.currentTarget.querySelector("input[name=editPartHomeAdress]");
        if (home_adress.value == '') {
            home_adress.style.border = '1px solid red';
            divErr.innerHTML = 'Вы не ввели адрес места жительства';
            return;
        } else {
            home_adress.style.border = 'none';
            divErr.innerHTML = '';
            home_adress = home_adress.value;
        }

        let employer_phone = e.currentTarget.querySelector("input[name=editPartEmployerPhone]").value;
        let work_email = e.currentTarget.querySelector("input[name=editPartWorkEmail]").value;
        let subject = e.currentTarget.getElementsByClassName("editPartSubject")[0].value;
        let part_coord = e.currentTarget.getElementsByClassName("editPartCoord")[0].value;

        let checkbox = e.currentTarget.querySelector('input[name=editPartCheckbox');
        if (checkbox.checked == true) checkbox = 'activ';
        else checkbox = 'disabled';

        let data = {
            id: id,
            email: email,
            password: password,
            name: name,
            last_name: last_name,
            third_name: third_name,
            mobile_phone: mobile_phone,
            work_phone: work_phone,
            work_experience: work_experience,
            nomination: nomination,
            pass: pass,
            work_place: work_place,
            education: education,
            name_education: name_education,
            training: training,
            adress_index: adress_index,
            home_adress: home_adress,
            employer_phone: employer_phone,
            work_email: work_email,
            subject: subject,
            part_coord: part_coord,
            checkbox: checkbox
        };
        fetch('http://lpptourism.ru/core/adminzone/usersLists/parts/editPartList.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.text())
            .then(result => {
                divErr.style.color = 'green';
                divErr.innerHTML = result;
                PartListMenu();
            })
            .catch(error => divErr.innerHTML = error);
    }
}


//Experts
function ExpertListMenu() {
    let select = document.getElementById('expertSortSelect').value;
    let search = document.getElementById('expertSearchInput').value;
    let NumOfPages = document.getElementById('expertNumOfPages').value;

    let data = {
        select: select,
        search: search,
        NumOfPages: NumOfPages
    };

    fetch('http://lpptourism.ru/core/adminzone/usersLists/experts/expertList.php', {
            method: 'POST',
            headers: {
                'Conten-Type': 'application/json;'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.text())
        .then(result => {
            document.getElementById('loaderForExperts').innerHTML = result;
        })
        .catch(error => {
            document.getElementById('loaderForExperts').innerHTML = error;
        });
}

function expertListNumPages() {
    let expertListNum = document.getElementById('expertListNum').value;

    data = {
        expertListNum: expertListNum
    };
    fetch('http://lpptourism.ru/core/adminzone/usersLists/experts/expertListNumPages.php', {
            method: 'POST',
            headers: {
                'Conten-Type': 'application/json;'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.text())
        .then(result => {
            document.getElementById('expertNumOfPages').innerHTML = result;
            adminListMenu();
        })
        .catch(error => console.log(error));
}

function expertsListAddAll(button) {
    let checkboxs = document.getElementsByName('ExpertEditorCheck');
    for (let i = 0; i < checkboxs.length; i++) {
        checkboxs[i].checked = true;
    }
    button.innerHTML = 'Убрать всех';
    button.onclick = function() {expertsListCheckDellAll(this);};
}
function expertsListCheckDellAll(button) {
    let checkboxs = document.getElementsByName('ExpertEditorCheck');
    for (let i = 0; i < checkboxs.length; i++) {
        checkboxs[i].checked = false;
    }
    button.innerHTML = 'Выбрать всех';
    button.onclick = function() {expertsListAddAll(this);};
}

function expertsListActivChecked() {
    let checkboxs = document.getElementsByName('ExpertEditorCheck');
    let expertsID = [];
    let n = 0;
    for (let i = 0; i < checkboxs.length; i++) {
        if (checkboxs[i].checked == true) {
            expertsID[n] = showNumById(checkboxs[i]);
            n++;
        }
    }

    let data = {
        adminsID: expertsID,
        user: 'expert',
        status: 'activ'
    };

    fetch('http://lpptourism.ru/core/adminzone/usersLists/chengeAllUserStatus.php', {
            method: 'POST',
            headers: {
                'Conten-Type': 'application/json;'
            },
            body: JSON.stringify(data)
        })
        .then(document.getElementById('expertListDivResults').innerHTML = 'Дожидаюсь ответа от сервера')
        .then(response => response.text())
        .then(result => {
            document.getElementById('expertListDivResults').innerHTML = result;
            ExpertListMenu();
        })
        .catch(error => document.getElementById('expertListDivResults').innerHTML = error);
}

function expertsListDisableChecked() {
    let checkboxs = document.getElementsByName('ExpertEditorCheck');
    let expertsID = [];
    let n = 0;
    for (let i = 0; i < checkboxs.length; i++) {
        if (checkboxs[i].checked == true) {
            expertsID[n] = showNumById(checkboxs[i]);
            n++;
        }
    }

    let data = {
        adminsID: expertsID,
        user: 'expert',
        status: 'disabled'
    };

    fetch('http://lpptourism.ru/core/adminzone/usersLists/chengeAllUserStatus.php', {
            method: 'POST',
            headers: {
                'Conten-Type': 'application/json;'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.text())
        .then(result => {
            document.getElementById('expertListDivResults').innerHTML = result;
            ExpertListMenu();
        })
        .catch(error => document.getElementById('expertListDivResults').innerHTML = error);
}

function expertsListDelete() {
    document.getElementById('expertLiastConfirmDelete').style.display = 'block';
}

function expertsListNoDeleteButton() {
    document.getElementById('expertLiastConfirmDelete').style.display = 'none';
}

function expertsListDeleteButton() {
    let checkboxs = document.getElementsByName('ExpertEditorCheck');
    let expertsID = [];
    let n = 0;
    for (let i = 0; i < checkboxs.length; i++) {
        if (checkboxs[i].checked == true) {
            expertsID[n] = showNumById(checkboxs[i]);
            n++;
        }
    }

    let data = {
        expertsID: expertsID,
    };

    fetch('http://lpptourism.ru/core/adminzone/usersLists/experts/expertsListDelete.php', {
            method: 'POST',
            headers: {
                'Conten-Type': 'application/json;'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.text())
        .then(result => {
            document.getElementById('expertListDivResults').innerHTML = result;
            ExpertListMenu();
        })
        .catch(error => document.getElementById('expertListDivResults').innerHTML = error);
}

function showHiddenButtonsExpert(data) {
    let numId = showNumById(data);
    document.getElementById('hiddenExpertButtonPart' + numId).style.display = 'block';
}

function infoEditorExpertButtonsClick(data) {
    let numId = showNumById(data);
    document.getElementById('infoExpertEditorPage' + numId).style.display = 'block';
    document.getElementById('editExpertEditorPage' + numId).style.display = 'none';
}

function closeEditorExpertButtonsClick(data) {
    let numId = showNumById(data);
    document.getElementById('infoExpertEditorPage' + numId).style.display = 'none';
    document.getElementById('editExpertEditorPage' + numId).style.display = 'none';
    document.getElementById('hiddenExpertButtonPart' + numId).style.display = 'none';
}

function infoEditorExpertButtonsClick(data) {
    let numId = showNumById(data);
    document.getElementById('infoExpertEditorPage' + numId).style.display = 'block';
    document.getElementById('editExpertEditorPage' + numId).style.display = 'none';
}

function EditEditorExpertButtonsClick(data) {
    let numId = showNumById(data);
    document.getElementById('infoExpertEditorPage' + numId).style.display = 'none';
    document.getElementById('editExpertEditorPage' + numId).style.display = 'block';
}

function ExpertListEditOneClick(e) {
    if (e.target.getAttribute('name') != 'ExpertListEditOneClick') {
        return;
    } else {
        let divErr = e.currentTarget.getElementsByClassName('ExpertListEditOneError')[0];
        divErr.style.color = 'red';

        let id = e.currentTarget.querySelector("input[name=editExpertID]");
        if (id.value == '') {
            divErr.innerHTML = 'произошла ошибка, id админа пуст';
            return;
        } else {
            divErr.innerHTML = '';
            id = id.value;
        }
        let email = e.currentTarget.querySelector('input[name=editExpertEmail');
        if (email.value == '') {
            email.style.border = '1px solid red';
            divErr.innerHTML = 'Вы не ввели email пользователя';
            return;
        } else {
            email.style.border = 'none';
            email = email.value;
            divErr.innerHTML = '';
        }
        let password = e.currentTarget.querySelector('input[name=editExpertPassword').value;
        let name = e.currentTarget.querySelector('input[name=editExpertName');
        if (name.value == '') {
            name.style.border = '1px solid red';
            divErr.innerHTML = 'Вы не ввели имя пользователя';
            return;
        } else {
            name.style.border = 'none';
            name = name.value;
            divErr.innerHTML = '';
        }

        let lastName = e.currentTarget.querySelector('input[name=editExpertLastName');
        if (lastName.value == '') {
            lastName.style.border = '1px solid red';
            divErr.innerHTML = 'вы не ввели фамилию пользователя';
            return;
        } else {
            lastName.style.border = 'none';
            lastName = lastName.value;
            divErr.innerHTML = '';
        }

        let phone = e.currentTarget.querySelector('input[name=editExpertPhone');
        if (phone.value == '') {
            phone.style.border = '1px solid red';
            divErr.innerHTML = 'Вы не ввели телефон пользователя';
            return;
        } else {
            phone.style.border = 'none';
            phone = phone.value;
            divErr.innerHTML = '';
        }

        let checkbox = e.currentTarget.querySelector('input[name=editExpertCheckbox');
        if (checkbox.checked == true) checkbox = 'activ';
        else checkbox = 'disabled';

        let data = {
            id: id,
            email: email,
            password: password,
            name: name,
            lastName: lastName,
            phone: phone,
            checkbox: checkbox
        };
        fetch('http://lpptourism.ru/core/adminzone/usersLists/experts/editExpertList.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.text())
            .then(result => {
                divErr.style.color = 'green';
                divErr.innerHTML = result;
                ExpertListMenu();
            })
            .catch(error => divErr.innerHTML = error);
    }
}

function dellPartTestResults(e) {
    if (e.target.getAttribute('name') == 'partTestResultsTableHeader') {
        let part_email = e.currentTarget.querySelector('input[name=deletedTestPartEmail]').value;
        let data = {
            part_email: part_email
        };

        var errDiv = e.currentTarget.getElementsByClassName('errDivDeletedTest')[0];

        let test_content = e.currentTarget.parentNode.getElementsByClassName('testContainer');

        let i = 0;
        let n = test_content.length;
        while (i < n) {
            test_content[0].remove();
            i++;
        }

        fetch ('http://lpptourism.ru/core/adminzone/usersLists/parts/dellPartTestRes.php',{
            method: "POST",
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.text())
        .then(result => {
            errDiv.innerHTML = result;
        })
        .catch(error => console.log(error));
    }
}

function loadCoordFileNormDocs(e) {
    if (e.target.getElementsByTagName('a')) {
        let container = e.currentTarget.getElementsByTagName('input')[0].value;
        var errDiv = e.currentTarget.getElementsByTagName('div')[0];
        let data = {
            coord_email: container
        };

        fetch('http://lpptourism.ru/core/adminzone/usersLists/coord/coordNormDocsLoadForOne.php',{
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result[`error`]) {
                errDiv.innerHTML = result[`error`];
            } else {
                let link = document.createElement('a');
                link.setAttribute("href", 'http://lpptourism.ru/core/adminzone/usersLists/coord/loadNormDocsFromCoord.php');
                link.setAttribute("download", result);
                link.click();
            }
        });

    }
}
