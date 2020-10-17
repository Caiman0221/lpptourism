async function fetchData(url, data) {
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

function normDocsLoader() {
    let token = localStorage.getItem('access_token');
    let normDoc = document.getElementById('normDocInput').files[0];

    if (!normDoc) {
      document.getElementById('nomrDocsErrDiv').style.color = 'red';
      document.getElementById('nomrDocsErrDiv').innerHTML = 'Вы не добавили файл';
      return;
    }
    if (normDoc.size > 10*1024*1024) {
      document.getElementById('nomrDocsErrDiv').style.color = 'red';
      document.getElementById('nomrDocsErrDiv').innerHTML = 'Размер файла не должен превышать 10Мб';
      return;
    }

    let coordDocData = new FormData();
    coordDocData.append('token', token);
    coordDocData.append('normDoc', normDoc);

    fetchProtocolsData('http://lpptourism.ru/core/userContent/coordsFiles/normDocsLoader.php', coordDocData);

    async function fetchProtocolsData(url, data) {
        try {
            const response = await fetch(url, {
                method: 'POST',
                body: data
            });
            const result = await response.text();
            document.getElementById('nomrDocsErrDiv').style.color = 'black';
            document.getElementById('nomrDocsErrDiv').innerHTML = result;
            coordFilesLoadedList();
            let timerId = setTimeout(() => {
              document.getElementById('nomrDocsErrDiv').innerHTML = '';
            }, 5000);
        } catch (error) {
            document.getElementById('nomrDocsErrDiv').style.color = 'red';
            document.getElementById('nomrDocsErrDiv').innerHTML = error;
            coordFilesLoadedList();
        }
    }
}

function submitUserProfile() {
    let coordNewPartErr = document.getElementById('coordNewPartErr');
    coordNewPartErr.style.color = 'red';

    let ctuEmail = document.getElementById('ctuEmail');
    if (ctuEmail.value == '') {
        ctuEmail.style.border = '1px solid red';
        coordNewPartErr.innerHTML = 'Вы не ввели email';
        return;
    } else {
        ctuEmail.style.border = 'none';
        coordNewPartErr.innerHTML = '';
        ctuEmail = ctuEmail.value;
    }

    let ctuLastName = document.getElementById('ctuLastName');
    if (ctuLastName.value == '') {
        ctuLastName.style.border = '1px solid red';
        coordNewPartErr.innerHTML = 'Вы не ввели фамилию';
        return;
    } else {
        ctuLastName.style.border = 'none';
        coordNewPartErr.innerHTML = '';
        ctuLastName = ctuLastName.value;
    }

    let ctuName = document.getElementById('ctuName');
    if (ctuName.value == '') {
        ctuName.style.border = '1px solid red';
        coordNewPartErr.innerHTML = 'Вы не ввели имя';
        return;
    } else {
        ctuName.style.border = 'none';
        coordNewPartErr.innerHTML = '';
        ctuName = ctuName.value;
    }

    let ctuThirdName = document.getElementById('ctuThirdName');
    if (ctuThirdName.value == '') {
        ctuThirdName.style.border = '1px solid red';
        coordNewPartErr.innerHTML = 'Вы не ввели отчество';
        return;
    } else {
        ctuThirdName.style.border = 'none';
        coordNewPartErr.innerHTML = '';
        ctuThirdName = ctuThirdName.value;
    }


    let ctuMobilePhone = document.getElementById('ctuMobilePhone');
    if (ctuMobilePhone.value == '') {
        ctuMobilePhone.style.border = '1px solid red';
        coordNewPartErr.innerHTML = 'Вы не ввели мобильный телефон';
        return;
    } else {
        ctuMobilePhone.style.border = 'none';
        coordNewPartErr.innerHTML = '';
        ctuMobilePhone = ctuMobilePhone.value;
    }

    let ctuWorkPhone = document.getElementById('ctuWorkPhone');
    if (ctuWorkPhone.value == '') {
        ctuWorkPhone.style.border = '1px solid red';
        coordNewPartErr.innerHTML = 'Вы не ввели рабочий телефон';
        return;
    } else {
        ctuWorkPhone.style.border = 'none';
        coordNewPartErr.innerHTML = '';
        ctuWorkPhone = ctuWorkPhone.value;
    }

    let ctuNomination = document.getElementById('ctuNomination');
    if (ctuNomination.value == '') {
        ctuNomination.style.border = '1px solid red';
        coordNewPartErr.innerHTML = 'Вы не выбрали номинацию';
        return;
    } else {
        ctuNomination.style.border = 'none';
        coordNewPartErr.innerHTML = '';
        ctuNomination = ctuNomination.value;
    }

    let ctuPass = document.getElementById('ctuPass').value;
    let ctuWorkPlace = document.getElementById('ctuWorkPlace').value;

    let ctuWorkExperience = document.getElementById('ctuWorkExperience');
    if (ctuWorkExperience.value == '') {
        ctuWorkExperience.style.border = '1px solid red';
        coordNewPartErr.innerHTML = 'Вы не заполнили стаж работы';
        return;
    } else if (ctuWorkExperience.value < 3) {
        ctuWorkExperience.style.border = '1px solid red';
        coordNewPartErr.innerHTML = 'Стаж работы должен быть не менее 3';
        return;
    } else {
        ctuWorkExperience.style.border = 'none';
        coordNewPartErr.innerHTML = '';
        ctuWorkExperience = ctuWorkExperience.value;
    }

    let ctuEducation = document.getElementById('ctuEducation').value;
    let ctuNameEducation = document.getElementById('ctuNameEducation').value;
    let ctuTraining = document.getElementById('ctuTraining').value;
    let ctuIndex = document.getElementById('ctuIndex').value;
    let ctuHomeAdress = document.getElementById('ctuHomeAdress');
    if (ctuHomeAdress.value == '') {
        ctuHomeAdress.style.border = '1px solid red';
        coordNewPartErr.innerHTML = 'Вы не заполнили адрес места жительства';
        return;
    } else {
        ctuHomeAdress.style.border = 'none';
        coordNewPartErr.innerHTML = '';
        ctuHomeAdress = ctuHomeAdress.value;
    }

    let ctuEmployerPhone = document.getElementById('ctuEmployerPhone').value;
    let ctuWorkEmail = document.getElementById('ctuWorkEmail').value;

    let ctuMemberProfile = document.getElementById('ctuMemberProfile');
    if (ctuMemberProfile.files.length == 0) {
      coordNewPartErr.style.color = 'red';
      coordNewPartErr.innerHTML = "Вы не прикрепили анкету участника";
      return;
    } else if (ctuMemberProfile.files[0].size > 10*1024*1024) {
      coordNewPartErr.style.color = 'red';
      coordNewPartErr.innerHTML = "Размер файла анкеты участника не должен превышать 10Мб";
      return;
    } else {
      coordNewPartErr.style.color = '#666666';
      ctuMemberProfile = document.getElementById('ctuMemberProfile').files[0];
      coordNewPartErr.innerHTML = '';
    }

    let ctuOtherDocs1 = document.getElementById('ctuOtherDocs1');
    if (ctuOtherDocs1.files.length != 0) {
      if (ctuOtherDocs1.files[0].size > 10*1024*1024) {
        coordNewPartErr.style.color = 'red';
        coordNewPartErr.innerHTML = 'Первый файл превышает допустимый размер 10Мю';
      }
    }

    let ctuOtherDocs2 = document.getElementById('ctuOtherDocs2');
    if (ctuOtherDocs2.files.length != 0) {
      if (ctuOtherDocs2.files[0].size > 10*1024*1024) {
        coordNewPartErr.style.color = 'red';
        coordNewPartErr.innerHTML = 'Второй файл превышает допустимый размер 10Мю';
      }
    }

    let ctuOtherDocs3 = document.getElementById('ctuOtherDocs3');
    if (ctuOtherDocs3.files.length != 0) {
      if (ctuOtherDocs3.files[0].size > 10*1024*1024) {
        coordNewPartErr.style.color = 'red';
        coordNewPartErr.innerHTML = 'Третий файл превышает допустимый размер 10Мю';
      }
    }

    let ctuOtherDocs4 = document.getElementById('ctuOtherDocs4');
    if (ctuOtherDocs4.files.length != 0) {
      if (ctuOtherDocs4.files[0].size > 10*1024*1024) {
        coordNewPartErr.style.color = 'red';
        coordNewPartErr.innerHTML = 'Четвертый файл превышает допустимый размер 10Мю';
      }
    }
    let ctuOtherDocs5 = document.getElementById('ctuOtherDocs5');
    if (ctuOtherDocs5.files.length != 0) {
      if (ctuOtherDocs5.files[0].size > 10*1024*1024) {
        coordNewPartErr.style.color = 'red';
        coordNewPartErr.innerHTML = 'Пятый файл превышает допустимый размер 10Мю';
      }
    }

    let ctuNewPart = new FormData();
    ctuNewPart.append('access_token', localStorage.getItem('access_token'));
    ctuNewPart.append('Email', ctuEmail);
    ctuNewPart.append('Name', ctuName);
    ctuNewPart.append('LastName', ctuLastName);
    ctuNewPart.append('ThirdName', ctuThirdName);
    ctuNewPart.append('MobilePhone', ctuMobilePhone);
    ctuNewPart.append('WorkPhone', ctuWorkPhone);
    ctuNewPart.append('Nomination', ctuNomination);
    ctuNewPart.append('Pass', ctuPass);
    ctuNewPart.append('WorkPlace', ctuWorkPlace);
    ctuNewPart.append('WorkExperience', ctuWorkExperience);
    ctuNewPart.append('Education', ctuEducation);
    ctuNewPart.append('NameEducation', ctuNameEducation);
    ctuNewPart.append('Training', ctuTraining);
    ctuNewPart.append('Index', ctuIndex);
    ctuNewPart.append('HomeAdress', ctuHomeAdress);
    ctuNewPart.append('EmployerPhone', ctuEmployerPhone);
    ctuNewPart.append('WorkEmail', ctuWorkEmail);
    ctuNewPart.append('MemberProfile', ctuMemberProfile);

    if (ctuOtherDocs1.files.length != 0) ctuNewPart.append('ctuOtherDocs1', ctuOtherDocs1.files[0]);
    if (ctuOtherDocs2.files.length != 0) ctuNewPart.append('ctuOtherDocs2', ctuOtherDocs2.files[0]);
    if (ctuOtherDocs3.files.length != 0) ctuNewPart.append('ctuOtherDocs3', ctuOtherDocs3.files[0]);
    if (ctuOtherDocs4.files.length != 0) ctuNewPart.append('ctuOtherDocs4', ctuOtherDocs4.files[0]);
    if (ctuOtherDocs5.files.length != 0) ctuNewPart.append('ctuOtherDocs5', ctuOtherDocs5.files[0]);

    fetchCoordData('http://lpptourism.ru/core/userContent/coordsFiles/coordNewPart.php', ctuNewPart); ///var/www/html/core/userContent/coordsFiles/coordNewPart.php

    async function fetchCoordData(url, data) {
        try {
            const response = await fetch(url, {
                method: 'POST',
                body: data
            });
            const result = await response.text();
            if (result != 'true') {
                coordNewPartErr.innerHTML = result;
            } else {
                coordNewPartErr.innerHTML = "Анкета была успешно создана";
                coordNewPartErr.style.color = 'green';
                coordNewPartErr.style.fontSize = '18px';
                coordPartsListLoader();

                //inputs clear
                document.getElementById('ctuEmail').value = '';
                document.getElementById('ctuName').value = '';
                document.getElementById('ctuLastName').value = '';
                document.getElementById('ctuThirdName').value = '';
                document.getElementById('ctuMobilePhone').value = '';
                document.getElementById('ctuWorkPhone').value = '';
                document.getElementById('ctuNomination').value = '';
                document.getElementById('ctuPass').value = '';
                document.getElementById('ctuWorkPlace').value = '';
                document.getElementById('ctuWorkExperience').value = '';
                document.getElementById('ctuEducation').value = '';
                document.getElementById('ctuNameEducation').value = '';
                document.getElementById('ctuTraining').value = '';
                document.getElementById('ctuIndex').value = '';
                document.getElementById('ctuHomeAdress').value = '';
                document.getElementById('ctuEmployerPhone').value = '';
                document.getElementById('ctuWorkEmail').value = '';
                document.getElementById('ctuMemberProfile').value = '';
                document.getElementById('ctuOtherDocs1').value = '';
                document.getElementById('ctuOtherDocs2').value = '';
                document.getElementById('ctuOtherDocs3').value = '';
                document.getElementById('ctuOtherDocs4').value = '';
                document.getElementById('ctuOtherDocs5').value = '';


                document.getElementById('ctuMemberProfileContainer').innerHTML = document.getElementById('ctuMemberProfileContainer').innerHTML;
                document.getElementById('ctuMemberProfileLabel').innerHTML = "Выберите файл";
                document.getElementById('ctuOtherDocs1Container').innerHTML = document.getElementById('ctuOtherDocs1Container').innerHTML;
                document.getElementById('ctuOtherDocs1Label').innerHTML = "Выберите файл";
                document.getElementById('ctuOtherDocs2Container').innerHTML = document.getElementById('ctuOtherDocs2Container').innerHTML;
                document.getElementById('ctuOtherDocs2Label').innerHTML = "Выберите файл";
                document.getElementById('ctuOtherDocs3Container').innerHTML = document.getElementById('ctuOtherDocs3Container').innerHTML;
                document.getElementById('ctuOtherDocs3Label').innerHTML = "Выберите файл";
                document.getElementById('ctuOtherDocs4Container').innerHTML = document.getElementById('ctuOtherDocs4Container').innerHTML;
                document.getElementById('ctuOtherDocs4Label').innerHTML = "Выберите файл";
                document.getElementById('ctuOtherDocs5Container').innerHTML = document.getElementById('ctuOtherDocs5Container').innerHTML;
                document.getElementById('ctuOtherDocs5Label').innerHTML = "Выберите файл";
            }
        } catch (error) {
            console.error(error);
        }
    }
}

function getTextFromInput(e) {
    e.currentTarget.getElementsByTagName('label')[0].innerHTML = e.currentTarget.querySelector("input[name=DocInput]").files[0].name;
}

function getTextFromEditInput(e) {
    e.currentTarget.getElementsByClassName('editPartLabel')[0].innerHTML = e.currentTarget.querySelector("input[name=DocEditInput]").files[0].name;
}
