//Кнопка верхнего меню
function allUsersListClick() {
    document.getElementById('allUsersList').style.display = 'block';
    document.getElementById('adminsList').style.display = 'none';
    document.getElementById('coordsList').style.display = 'none';
    document.getElementById('partsList').style.display = 'none';
    document.getElementById('expertsList').style.display = 'none';
}
//Кнопка верхнего меню
function adminListClick() {
    document.getElementById('allUsersList').style.display = 'none';
    document.getElementById('adminsList').style.display = 'block';
    document.getElementById('coordsList').style.display = 'none';
    document.getElementById('partsList').style.display = 'none';
    document.getElementById('expertsList').style.display = 'none';
}

//Кнопка верхнего меню
function coordsListClick() {
    document.getElementById('allUsersList').style.display = 'none';
    document.getElementById('adminsList').style.display = 'none';
    document.getElementById('coordsList').style.display = 'block';
    document.getElementById('partsList').style.display = 'none';
    document.getElementById('expertsList').style.display = 'none';
}

//Кнопка верхнего меню
function partsListClick() {
    document.getElementById('allUsersList').style.display = 'none';
    document.getElementById('adminsList').style.display = 'none';
    document.getElementById('coordsList').style.display = 'none';
    document.getElementById('partsList').style.display = 'block';
    document.getElementById('expertsList').style.display = 'none';
}

//Кнопка верхнего меню
function expertsListClick() {
    document.getElementById('allUsersList').style.display = 'none';
    document.getElementById('adminsList').style.display = 'none';
    document.getElementById('coordsList').style.display = 'none';
    document.getElementById('partsList').style.display = 'none';
    document.getElementById('expertsList').style.display = 'block';
}

//Выбор какого пользователя создать
function newUserOptionClick(a) {
    let user = a.value;
    if (user == 1) {
        document.getElementById("newAdminCreate").style.display = "block";
        document.getElementById("newCoordCreate").style.display = "none";
        document.getElementById("newPartCreate").style.display = "none";
        document.getElementById("newExpertCreate").style.display = "none";
    } else if (user == 2) {
        document.getElementById("newAdminCreate").style.display = "none";
        document.getElementById("newCoordCreate").style.display = "block";
        document.getElementById("newPartCreate").style.display = "none";
        document.getElementById("newExpertCreate").style.display = "none";
    } else if (user == 3) {
        document.getElementById("newAdminCreate").style.display = "none";
        document.getElementById("newCoordCreate").style.display = "none";
        document.getElementById("newPartCreate").style.display = "block";
        document.getElementById("newExpertCreate").style.display = "none";
    } else if (user == 4) {
        document.getElementById("newAdminCreate").style.display = "none";
        document.getElementById("newCoordCreate").style.display = "none";
        document.getElementById("newPartCreate").style.display = "none";
        document.getElementById("newExpertCreate").style.display = "block";
    } else if (user == 0) {
        document.getElementById("newAdminCreate").style.display = "none";
        document.getElementById("newCoordCreate").style.display = "none";
        document.getElementById("newPartCreate").style.display = "none";
        document.getElementById("newExpertCreate").style.display = "none";
    }
}

//Создает нового администратора
function newAdminSubmitClick() {
    let err = document.getElementById('newAdminSubmitErr');
    err.style.color = 'red';

    let newAdminEmail = document.getElementById('newAdminEmail');
    if (newAdminEmail.value == '') {
        newAdminEmail.style.border = '1px solid red';
        err.innerHTML = 'Вы не ввели Email';
        return;
    } else {
        newAdminEmail.style.border = 'none';
        err.innerHTML = '';
        newAdminEmail = newAdminEmail.value;
    }

    let newAdminPassword = document.getElementById('newAdminPassword').value;

    let newAdminName = document.getElementById('newAdminName');
    if (newAdminName.value == '') {
        newAdminName.style.border = '1px solid red';
        err.innerHTML = 'Вы не ввели имя администратора';
        return;
    } else {
        newAdminName.style.border = 'none';
        err.innerHTML = '';
        newAdminName = newAdminName.value;
    }

    let newAdminLastName = document.getElementById('newAdminLastName');
    if (newAdminLastName.value == '') {
        newAdminLastName.style.border = '1px solid red';
        err.innerHTML = 'Вы не ввели фамилию администратора';
        return;
    } else {
        newAdminLastName.style.border = 'none';
        err.innerHTML = '';
        newAdminLastName = newAdminLastName.value;
    }

    let newAdminPhone = document.getElementById('newAdminPhone');
    if (newAdminPhone.value == '') {
        newAdminPhone.style.border = '1px solid red';
        err.innerHTML = 'Вы не ввели мобильный телефон';
        return;
    } else {
        newAdminPhone.style.border = 'none';
        err.innerHTML = '';
        newAdminPhone = newAdminPhone.value;
    }
    let newAdminCheck = document.getElementById('newAdminCheckbox');
    if (newAdminCheck.checked == true) newAdminCheck = 'activ';
    else newAdminCheck = 'disabled';

    let newAdminData = {
        newAdminEmail: newAdminEmail,
        newAdminPassword: newAdminPassword,
        newAdminName: newAdminName,
        newAdminLastName: newAdminLastName,
        newAdminPhone: newAdminPhone,
        newAdminCheck: newAdminCheck
    };

    err.style.color = 'black';
    fetch('http://lpptourism.ru/core/adminzone/newUsers/newAdmin.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json;'
            },
            body: JSON.stringify(newAdminData)
        })
        .then(err.innerHTML = 'Ожидаем ответа от сервера')
        .then(response => response.json())
        .then(res => {
          if (res[`error`]) {
            err.style.color = 'red';
            err.innerHTML = res[`error`];
          } else {
            err.style.color = 'black';
            err.innerHTML = res[`true`];
            adminListMenu();
            document.getElementById('newAdminEmail').value = '';
            document.getElementById('newAdminPassword').value = '';
            document.getElementById('newAdminName').value = '';
            document.getElementById('newAdminLastName').value = '';
            document.getElementById('newAdminPhone').value = '';
            document.getElementById('newAdminCheckbox').checked = false;
          }
        })
        .catch(error => {
            console.log(error);
            err.style.color = 'red';
            err.innerHTML = 'Произошла ошибка';
        });
}

//Создает нового координатора
function newCoordSubmitClick() {
    let errDiv = document.getElementById('newCoordSubmitErr');
    errDiv.style.color = 'red';

    let newCoordEmail = document.getElementById('newCoordEmail');
    if (newCoordEmail.value == '') {
        newCoordEmail.style.border = '1px solid red';
        errDiv.innerHTML = 'Вы не ввели email';
        return;
    } else {
        newCoordEmail.style.border = 'none';
        newCoordEmail = newCoordEmail.value;
        errDiv.innerHTML = '';
    }

    let newCoordPassword = document.getElementById('newCoordPassword').value;

    let newCoordName = document.getElementById('newCoordName');
    if (newCoordName.value == '') {
        newCoordName.style.border = '1px solid red';
        errDiv.innerHTML = 'Вы не ввели имя';
        return;
    } else {
        newCoordName.style.border = 'none';
        newCoordName = newCoordName.value;
        errDiv.innerHTML = '';
    }

    let newCoordLastName = document.getElementById('newCoordLastName');
    if (newCoordLastName.value == '') {
        newCoordLastName.style.border = '1px solid red';
        errDiv.innerHTML = 'Вы не ввели фамилию';
        return;
    } else {
        newCoordLastName.style.border = 'none';
        newCoordLastName = newCoordLastName.value;
        errDiv.innerHTML = '';
    }

    let newCoordMobilePhone = document.getElementById('newCoordMobilePhone');
    if (newCoordMobilePhone.value == '') {
        newCoordMobilePhone.style.border = '1px solid red';
        errDiv.innerHTML = 'Вы не ввели мобильный телефон';
        return;
    } else {
        newCoordMobilePhone.style.border = 'none';
        newCoordMobilePhone = newCoordMobilePhone.value;
        errDiv.innerHTML = '';
    }

    let newCoordWorkPhone = document.getElementById('newCoordWorkPhone');
    if (newCoordWorkPhone.value == '') {
        newCoordWorkPhone.style.border = '1px solid red';
        errDiv.innerHTML = 'Вы не ввели рабочий телефон';
        return;
    } else {
        newCoordWorkPhone.style.border = 'none';
        newCoordWorkPhone = newCoordWorkPhone.value;
        errDiv.innerHTML = '';
    }

    let newCoordSubjectName = document.getElementById('newCoordSubjectName');
    if (newCoordSubjectName.value == '') {
        newCoordSubjectName.style.border = '1px solid red';
        errDiv.innerHTML = 'Вы не выбрали название субьекта';
        return;
    } else {
        newCoordSubjectName.style.border = 'none';
        newCoordSubjectName = newCoordSubjectName.value;
        errDiv.innerHTML = '';
    }

    let newCoordCheckbox = document.getElementById('newCoordCheckbox');
    if (newCoordCheckbox.checked == true) newCoordCheckbox = 'activ';
    else newCoordCheckbox = 'disabled';

    let newCoordData = {
        newCoordEmail: newCoordEmail,
        newCoordPassword: newCoordPassword,
        newCoordName: newCoordName,
        newCoordLastName: newCoordLastName,
        newCoordMobilePhone: newCoordMobilePhone,
        newCoordWorkPhone: newCoordWorkPhone,
        newCoordSubjectName: newCoordSubjectName,
        newCoordCheckbox: newCoordCheckbox
    };

    errDiv.style.color = 'black';
    fetch('http://lpptourism.ru/core/adminzone/newUsers/newCoord.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json;'
            },
            body: JSON.stringify(newCoordData)
        })
        .then(errDiv.innerHTML = 'Ожидаем ответа от сервера')
        .then(response => response.json())
        .then(res => {
          if (res[`error`]) {
            errDiv.style.color = 'red';
            errDiv.innerHTML = res[`error`];
          } else if (res[`true`]) {
            errDiv.style.color = 'black';
            errDiv.innerHTML = res[`true`];
            newPartNameCoord();
            CoordListMenu();
            document.getElementById('newCoordEmail').value = '';
            document.getElementById('newCoordPassword').value = '';
            document.getElementById('newCoordName').value = '';
            document.getElementById('newCoordLastName').value = '';
            document.getElementById('newCoordMobilePhone').value = '';
            document.getElementById('newCoordWorkPhone').value = '';
            document.getElementById('newCoordSubjectName').value = '';
            document.getElementById('newCoordCheckbox').checked = false;
          }
        })
        .catch(error => {
            console.log(error);
            errDiv.style.color = 'red';
            errDiv.innerHTML = 'Произошла ошибка';
        });
}

//Создает нового участника
function newPartSubmitClick() {
    let errDiv = document.getElementById('newPartSubmitErr');
    errDiv.style.color = 'red';

    let newPartEmail = document.getElementById('newPartEmail');
    if (newPartEmail.value == '') {
        newPartEmail.style.border = '1px solid red';
        errDiv.innerHTML = 'Вы не ввели email';
        return;
    } else {
        newPartEmail.style.border = 'none';
        newPartEmail = newPartEmail.value;
        errDiv.innerHTML = '';
    }

    let newPartPassword = document.getElementById('newPartPassword').value;

    let newPartName = document.getElementById('newPartName');
    if (newPartName.value == '') {
        newPartName.style.border = '1px solid red';
        errDiv.innerHTML = 'Вы не ввели имя участника';
        return;
    } else {
        newPartName.style.border = 'none';
        newPartName = newPartName.value;
        errDiv.innerHTML = '';
    }

    let newPartLastName = document.getElementById('newPartLastName');
    if (newPartLastName.value == '') {
        newPartLastName.style.border = '1px solid red';
        errDiv.innerHTML = 'Вы не ввели фамилию участника';
        return;
    } else {
        newPartLastName.style.border = 'none';
        newPartLastName = newPartLastName.value;
        errDiv.innerHTML = '';
    }

    let newPartThirdName = document.getElementById('newPartThirdName');
    if (newPartThirdName.value == '') {
        newPartThirdName.style.border = '1px solid red';
        errDiv.innerHTML = 'Вы не ввели отчество участника';
        return;
    } else {
        newPartThirdName.style.border = 'none';
        newPartThirdName = newPartThirdName.value;
        errDiv.innerHTML = '';
    }

    let newPartMobilePhone = document.getElementById('newPartMobilePhone');
    if (newPartMobilePhone.value == '') {
        newPartMobilePhone.style.border = '1px solid red';
        errDiv.innerHTML = 'Вы не ввели мобильный телефон';
        return;
    } else {
        newPartMobilePhone.style.border = 'none';
        newPartMobilePhone = newPartMobilePhone.value;
        errDiv.innerHTML = '';
    }

    let newPartWorkPhone = document.getElementById('newPartWorkPhone');
    if (newPartWorkPhone.value == '') {
        newPartWorkPhone.style.border = '1px solid red';
        errDiv.innerHTML = 'Вы не ввели рабочий телефон';
        return;
    } else {
        newPartWorkPhone.style.border = 'none';
        newPartWorkPhone = newPartWorkPhone.value;
        errDiv.innerHTML = '';
    }

    let newPartNomination = document.getElementById('newPartNomination');
    if (newPartNomination.value == '') {
        newPartNomination.style.border = '1px solid red';
        errDiv.innerHTML = 'Вы не указали номинацию';
        return;
    } else {
        newPartNomination.style.border = 'none';
        newPartNomination = newPartNomination.value;
        errDiv.innerHTML = '';
    }

    let newPartWorkExperience = document.getElementById('newPartWorkExperience');
    if (newPartWorkExperience.value == '') {
        newPartWorkExperience.style.border = '1px solid red';
        errDiv.innerHTML = 'Вы не ввели стаж работы';
        return;
    } else {
        newPartWorkExperience.style.border = 'none';
        newPartWorkExperience = newPartWorkExperience.value;
        errDiv.innerHTML = '';
    }

    let newPartHomeAdress = document.getElementById('newPartHomeAdress');
    if (newPartHomeAdress.value == '') {
        newPartHomeAdress.style.border = '1px solid red';
        errDiv.innerHTML = 'Вы не ввели адрес';
        return;
    } else {
        newPartHomeAdress.style.border = 'none';
        newPartHomeAdress = newPartHomeAdress.value;
        errDiv.innerHTML = '';
    }

    let newPartNameCoord = document.getElementById('newPartNameCoord');
    if (newPartNameCoord.value == '') {
        newPartNameCoord.style.border = '1px solid red';
        errDiv.innerHTML = 'Вы не указали имя координатора';
        return;
    } else {
        newPartNameCoord.style.border = 'none';
        newPartNameCoord = newPartNameCoord.value;
        errDiv.innerHTML = '';
    }

    let newPartPass = document.getElementById('newPartPass').value;
    let newPartWorkPlace = document.getElementById('newPartWorkPlace').value;
    let newPartEducation = document.getElementById('newPartEducation').value;
    let newPartNameEducation = document.getElementById('newPartNameEducation').value;
    let newPartTraining = document.getElementById('newPartTraining').value;
    let newPartAdressIndex = document.getElementById('newPartAdressIndex').value;
    let newPartEmployerPhone = document.getElementById('newPartEmployerPhone').value;
    let newPartWorkEmail = document.getElementById('newPartWorkEmail').value;
    let newPartCheckbox = document.getElementById('newPartCheckbox');
    if (newPartCheckbox.checked == true) newPartCheckbox = 'activ';
    else newPartCheckbox = 'disabled';

    let newPartData = {
        newPartEmail: newPartEmail,
        newPartPassword: newPartPassword,
        newPartName: newPartName,
        newPartLastName: newPartLastName,
        newPartThirdName: newPartThirdName,
        newPartMobilePhone: newPartMobilePhone,
        newPartWorkPhone: newPartWorkPhone,
        newPartNomination: newPartNomination,
        newPartPass: newPartPass,
        newPartWorkPlace: newPartWorkPlace,
        newPartWorkExperience: newPartWorkExperience,
        newPartEducation: newPartEducation,
        newPartNameEducation: newPartNameEducation,
        newPartTraining: newPartTraining,
        newPartAdressIndex: newPartAdressIndex,
        newPartHomeAdress: newPartHomeAdress,
        newPartEmployerPhone: newPartEmployerPhone,
        newPartWorkEmail: newPartWorkEmail,
        newPartNameCoord: newPartNameCoord,
        newPartCheckbox: newPartCheckbox
    };

    errDiv.style.color = 'black';
    fetch('http://lpptourism.ru/core/adminzone/newUsers/newPart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json;'
            },
            body: JSON.stringify(newPartData)
        })
        .then(errDiv.innerHTML = 'Ожидаем ответа от сервера')
        .then(response => response.json())
        .then(res => {
          if(res[`error`]) {
            errDiv.style.color = 'red';
            errDiv.innerHTML = res[`error`];
          } else if (res[`true`]) {
            errDiv.style.color = 'black';
            errDiv.innerHTML = res[`true`];

            PartListMenu();

            document.getElementById('newPartEmail').value = '';
            document.getElementById('newPartPassword').value = '';
            document.getElementById('newPartName').value = '';
            document.getElementById('newPartLastName').value = '';
            document.getElementById('newPartThirdName').value = '';
            document.getElementById('newPartMobilePhone').value = '';
            document.getElementById('newPartWorkPhone').value = '';
            document.getElementById('newPartNomination').value = '';
            document.getElementById('newPartWorkExperience').value = '';
            document.getElementById('newPartHomeAdress').value = '';
            document.getElementById('newPartNameCoord').value = '';
            document.getElementById('newPartPass').value = '';
            document.getElementById('newPartWorkPlace').value = '';
            document.getElementById('newPartEducation').value = '';
            document.getElementById('newPartNameEducation').value = '';
            document.getElementById('newPartTraining').value = '';
            document.getElementById('newPartAdressIndex').value = '';
            document.getElementById('newPartEmployerPhone').value = '';
            document.getElementById('newPartWorkEmail').value = '';
            document.getElementById('newPartCheckbox').checked = false;
          }
        })
        .catch(error => {
            console.log(error);
            errDiv.style.color = 'red';
            errDiv.innerHTML = 'Произошла ошибка';
        });
}

//Создает нового эксперта
function newExpertSubmitClick() {
    let errDiv = document.getElementById('newExpertSubmitErr');
    errDiv.style.color = 'red';

    let newExperEmail = document.getElementById('newExperEmail');
    if (newExperEmail.value == '') {
        newExperEmail.style.border = '1px solid red';
        errDiv.innerHTML = 'Вы не ввели email';
        return;
    } else {
        newExperEmail.style.border = 'none';
        errDiv.innerHTML = '';
        newExperEmail = newExperEmail.value;
    }

    let newExpertPassword = document.getElementById('newExpertPassword').value;

    let newExpertName = document.getElementById('newExpertName');
    if (newExpertName.value == '') {
        newExpertName.style.border = '1px solid red';
        errDiv.innerHTML = 'Вы не ввели email';
        return;
    } else {
        newExpertName.style.border = 'none';
        errDiv.innerHTML = '';
        newExpertName = newExpertName.value;
    }

    let newExpertLastName = document.getElementById('newExpertLastName');
    if (newExpertLastName.value == '') {
        newExpertLastName.style.border = '1px solid red';
        errDiv.innerHTML = 'Вы не ввели email';
        return;
    } else {
        newExpertLastName.style.border = 'none';
        errDiv.innerHTML = '';
        newExpertLastName = newExpertLastName.value;
    }

    let newExpertMobilePhone = document.getElementById('newExpertMobilePhone');
    if (newExpertMobilePhone.value == '') {
        newExpertMobilePhone.style.border = '1px solid red';
        errDiv.innerHTML = 'Вы не ввели email';
        return;
    } else {
        newExpertMobilePhone.style.border = 'none';
        errDiv.innerHTML = '';
        newExpertMobilePhone = newExpertMobilePhone.value;
    }

    let newExpertWorkPhone = document.getElementById('newExpertWorkPhone');
    if (newExpertWorkPhone.value == '') {
        newExpertWorkPhone.style.border = '1px solid red';
        errDiv.innerHTML = 'Вы не ввели email';
        return;
    } else {
        newExpertWorkPhone.style.border = 'none';
        errDiv.innerHTML = '';
        newExpertWorkPhone = newExpertWorkPhone.value;
    }

    let newExpertCheckbox = document.getElementById('newExpertCheckbox');
    if (newExpertCheckbox.checked == true) newExpertCheckbox = 'activ';
    else newExpertCheckbox = 'disabled';

    let newExpertData = {
        newExperEmail: newExperEmail,
        newExpertPassword: newExpertPassword,
        newExpertName: newExpertName,
        newExpertLastName: newExpertLastName,
        newExpertMobilePhone: newExpertMobilePhone,
        newExpertWorkPhone: newExpertWorkPhone,
        newExpertCheckbox: newExpertCheckbox,
    };

    errDiv.style.color = 'black';
    fetch('http://lpptourism.ru/core/adminzone/newUsers/newExpert.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json;'
            },
            body: JSON.stringify(newExpertData)
        })
        .then(errDiv.innerHTML = 'Ожидаем ответа от сервера')
        .then(response => response.json())
        .then(res => {
          if(res[`error`]) {
            errDiv.style.color = 'red';
            errDiv.innerHTML = res[`error`];
          } else if (res[`true`]) {
            errDiv.style.color = 'black';
            errDiv.innerHTML = res[`true`];

            ExpertListMenu();

            document.getElementById('newExperEmail').value = '';
            document.getElementById('newExpertPassword').value = '';
            document.getElementById('newExpertName').value = '';
            document.getElementById('newExpertLastName').value = '';
            document.getElementById('newExpertMobilePhone').value = '';
            document.getElementById('newExpertWorkPhone').value = '';
            document.getElementById('newExpertCheckbox').checked = false;
          }
        })
        .catch(error => {
            console.log(error);
            errDiv.style.color = 'red';
            errDiv.innerHTML = 'Произошла ошибка';
        });

}

//Создает рандомную строку
function radnomStr(num) {
    var text = "";
    var possible = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

    for (var i = 0; i < num; i++)
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}
