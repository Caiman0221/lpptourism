function accountOnClick() {
    userLogin('private');
}

function coordRegClick() {
    let coordErr = document.getElementById('coordRegErr');
    coordErr.style.color = 'red';
    let selector = document.getElementById('coordRegSelector'); //субьект
    if (selector.value == '') {
        selector.style.border = '1px solid red';
        coordErr.innerHTML = 'Вы не выбрали субьект';
        return;
    } else {
        selector.style.border = 'none';
        coordErr.innerHTML = '';
        selector = selector.value;
    }

    let lastName = document.getElementById('coordLastName'); //фамилия
    if (lastName.value == '') {
        lastName.style.border = '1px solid red';
        coordErr.innerHTML = 'Вы не ввели фамилию';
        return;
    } else {
        lastName.style.border = 'none';
        coordErr.innerHTML = '';
        lastName = lastName.value;
    }

    let firstName = document.getElementById('coordFirstName'); //имя
    if (firstName.value == '') {
        firstName.style.border = '1px solid red';
        coordErr.innerHTML = 'Вы не ввели имя';
        return;
    } else {
        firstName.style.border = 'none';
        coordErr.innerHTML = '';
        firstName = firstName.value;
    }

    let workPhone = document.getElementById('coordWorkPhone'); //рабочий телефон
    if (workPhone.value == '') {
        workPhone.style.border = '1px solid red';
        coordErr.innerHTML = 'Вы не указали рабочий телефон';
        return;
    } else {
        workPhone.style.border = 'none';
        coordErr.innerHTML = '';
        workPhone = workPhone.value;
    }

    let mobilePhone = document.getElementById('coordMobilePhone'); //мобильный телефон
    if (mobilePhone.value == '') {
        mobilePhone.style.border = '1px solid red';
        coordErr.innerHTML = 'Вы не указали мобильный телефон';
        return;
    } else {
        mobilePhone.style.border = 'none';
        coordErr.innerHTML = '';
        mobilePhone = mobilePhone.value;
    }

    let email = document.getElementById('coordEmail'); //email
    if (email.value == '') {
        email.style.border = '1px solid red';
        coordErr.innerHTML = 'Вы не указали мобильный телефон';
        return;
    } else {
        email.style.border = 'none';
        coordErr.innerHTML = '';
        email = email.value;
    }

    let checkbox = document.getElementById('coordCheckbox'); //согласие
    if (checkbox.checked == false) {
        coordErr.innerHTML = 'Вы не согласились на обработку персональных данных';
        return;
    }

    coordData = {
        selector: selector,
        lastname: lastName,
        firstname: firstName,
        workphone: workPhone,
        mobilephone: mobilePhone,
        email: email
    };

    fetch('http://lpptourism.ru/core/userContent/coordsFiles/regforcoords.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json;'
            },
            body: JSON.stringify(coordData)
        })
        .then(response => response.json())
        .then(res => {
            if (res[`err`]) {
                coordErr.innerHTML = res[`err`];
                document.getElementById('coordEmail').style.border = 'red';
                return;
            } else {
                document.getElementById('coordEmail').style.border = 'none';
                coordErr.innerHTML = '';
            }
            if (res['true']) {
                document.getElementById('hiddenMassegeAfterCoordReg').style.display = 'block';
                disableScrolling();
            }
        })
        .catch(err => {
          console.log(err);
          coordErr.innerHTML = 'Произошла ошибка';
        });
}

function closePopUpCoordRegClick() {
    document.getElementById('hiddenMassegeAfterCoordReg').style.display = 'none';
    enableScrolling();
}


function disableScrolling() {
    var x = window.scrollX;
    var y = window.scrollY;
    window.onscroll = function() {
        window.scrollTo(x, y);
    };
}

function enableScrolling() {
    window.onscroll = function() {};
}

function forgotPasswordClick() {
    document.getElementById('hiddenPasswordUpdateContainer').style.display = 'block';
    disableScrolling();
}

function closePopupForgotPassword() {
    document.getElementById('hiddenPasswordUpdateContainer').style.display = 'none';
    enableScrolling();
}
function updatePasswordButtonCLick() {
    let data = {
        email: document.getElementById('userEmailForPassUpdate').value
    };
    fetch ('http://lpptourism.ru/core/emailMessage/forgotpassword.php',{
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.text())
    .then(result => {
        document.getElementById('errorDivForPasswordUpdate').innerHTML = result;
    });
}
