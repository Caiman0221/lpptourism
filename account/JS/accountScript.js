function creativeWorkButtonClick() {
    document.location.href = 'creative-work/';
}

function coordCabinet() {
    document.getElementById('coordPageCabinet').style.display = 'block';
    document.getElementById('coordPageRegulations').style.display = 'none';
    document.getElementById('coordPageDownloadWorkLog').style.display = 'none';
    document.getElementById('coordPageCreateProfile').style.display = 'none';
}

function coordRegulations() {
    document.getElementById('coordPageCabinet').style.display = 'none';
    document.getElementById('coordPageRegulations').style.display = 'block';
    document.getElementById('coordPageDownloadWorkLog').style.display = 'none';
    document.getElementById('coordPageCreateProfile').style.display = 'none';
}

function coordDownloadWorkLog() {
    document.getElementById('coordPageCabinet').style.display = 'none';
    document.getElementById('coordPageRegulations').style.display = 'none';
    document.getElementById('coordPageDownloadWorkLog').style.display = 'block';
    document.getElementById('coordPageCreateProfile').style.display = 'none';
}

function coordCreateProfile() {
    document.getElementById('coordPageCabinet').style.display = 'none';
    document.getElementById('coordPageRegulations').style.display = 'none';
    document.getElementById('coordPageDownloadWorkLog').style.display = 'none';
    document.getElementById('coordPageCreateProfile').style.display = 'block';
}

function participantTableDataLoader() {
    let data = {
        access_token: localStorage.getItem('access_token')
    };

    fetch('http://lpptourism.ru/core/userContent/partFiles/partTableInfoLoader.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.text())
        .then(result => {
            document.getElementById('participantTableData').innerHTML = result;
        })
        .catch(error => document.getElementById('participantTableData').innerHTML = error);

}

function coordFilesLoadedList() {
    let data = {
        access_token: localStorage.getItem('access_token')
    };

    fetch('http://lpptourism.ru/core/userContent/coordsFiles/coordFilesLoaded.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.text())
        .then(result => {
            document.getElementById('normFilesContainer').innerHTML = result;
        })
        .catch(error => document.getElementById('normFilesContainer').innerHTML = error);
}

function dellNormFileCoordClick(e) {
    if (e.target.getAttribute('name') != 'dellDocButton') {
        return;
    } else {
        let data = {
            href: e.currentTarget.getElementsByTagName('a')[0].href
        };
        fetch('http://lpptourism.ru/core/userContent/coordsFiles/coordsDellNormFile.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.text())
            .then(result => {
                coordFilesLoadedList();
                document.getElementById('dellNormDocsErrDiv').innerHTML = result;
                let timerId = setTimeout(() => {
                  document.getElementById('dellNormDocsErrDiv').innerHTML = '';
                }, 5000);
            })
            .catch(error => document.getElementById('dellNormDocsErrDiv').innerHTML = error);
    }
}

function coordPartsListLoader() {
    let data = {
        access_token: localStorage.getItem('access_token')
    };
    fetch('http://lpptourism.ru/core/userContent/coordsFiles/partsListLoader.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.text())
        .then(result => {
            document.getElementById('coordAllCreatedPartsList').innerHTML = result;
        })
        .catch(err => {
            console.log(err);
            document.getElementById('coordAllCreatedPartsList').innerHTML = err;
        });
}

function coordPartsListClick(e) {
    if (e.target.classList.contains("coordPartListName")) {
        let partID = e.currentTarget.getElementsByTagName('input')[0].value;
        let data = {
            partID: partID
        };

        fetch('http://lpptourism.ru/core/userContent/coordsFiles/editUserTable.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                let table = document.getElementById('coordEditPartTable');
                table.querySelector('input[name=ctuID]').value = result[`id`]; //ctuID
                table.querySelector('input[name=ctuEmail]').value = result[`email`]; //ctuEmail
                table.querySelector('input[name=ctuName]').value = result[`name`]; //ctuName
                table.querySelector('input[name=ctuLastName]').value = result[`last_name`]; //ctuLastName
                table.querySelector('input[name=ctuThirdName]').value = result[`third_name`]; //ctuThirdName
                table.querySelector('input[name=ctuMobilePhone]').value = result[`mobile_phone`]; //ctuMobilePhone
                table.querySelector('input[name=ctuWorkPhone]').value = result[`work_phone`]; //ctuWorkPhone
                table.querySelector('select[name=ctuNomination]').innerHTML = result[`nomination`]; //ctuNomination
                table.querySelector('input[name=ctuPass]').value = result[`pass`]; //ctuPass
                table.querySelector('input[name=ctuWorkPlace]').value = result[`work_place`]; //ctuWorkPlace
                table.querySelector('input[name=ctuWorkExperience]').value = result[`work_experience`]; //ctuWorkExperience
                table.querySelector('input[name=ctuEducation]').value = result[`education`]; //ctuEducation
                table.querySelector('input[name=ctuNameEducation]').value = result[`name_education`]; //ctuNameEducation
                table.querySelector('input[name=ctuTraining]').value = result[`training`]; //ctuTraining
                table.querySelector('input[name=ctuIndex]').value = result[`adress_index`]; //ctuIndex
                table.querySelector('input[name=ctuHomeAdress]').value = result[`home_adress`]; //ctuHomeAdress
                table.querySelector('input[name=ctuEmployerPhone]').value = result[`employer_phone`]; //ctuEmployerPhone
                table.querySelector('input[name=ctuWorkEmail]').value = result[`work_email`]; //ctuWorkEmail
                document.getElementById('containerLoadedFilesForPart').innerHTML = result[`part_files`];
                document.getElementById('coordEditPartErr').innerHTML = '';

                document.getElementById('ctuEditMemberProfile').value = '';
                document.getElementById('ctuEditOtherDocs1').value = '';

                document.getElementsByClassName('editPartLabel')[0].innerHTML = 'Выберете файл';
                document.getElementsByClassName('editPartLabel')[1].innerHTML = 'Выберете файл';

                document.getElementById('visiblePartOfCoordMainPage').style.display = 'none';
                document.getElementById('hiddenPartOfCoordMainPage').style.display = 'block';
            })
            .catch(err => console.log(err));
    } else if (e.target.classList.contains('dellDocButton')) {
        let partID = e.currentTarget.getElementsByTagName('input')[0].value;
        //document.getElementById('errDivForPartsList').innerHTML = partID;
        //return;
        let data = {
            partID: partID
        };
        fetch('http://lpptourism.ru/core/userContent/coordsFiles/dellPartClick.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.text())
            .then(result => {
                document.getElementById('errDivForPartsList').innerHTML = result;
                coordPartsListLoader();
            })
            .catch(err => document.getElementById('errDivForPartsList').innerHTML = err);
    } else {
        return;
    }
}

function coordCloseEditPartClick() {
    document.getElementById('visiblePartOfCoordMainPage').style.display = 'block';
    document.getElementById('hiddenPartOfCoordMainPage').style.display = 'none';
    coordPartsListLoader();
}

function submitEditPartProfile(e) {
    if (e.target.getAttribute('name') != 'submitEditPartProfile') {
        return;
    } else {
        let table = e.currentTarget;
        let errDiv = table.querySelector('#coordEditPartErr');
        errDiv.style.color = 'red';

        let id = table.querySelector('input[name=ctuID]').value; //ctuID
        let email = table.querySelector('input[name=ctuEmail]'); //ctuEmail
        if (email.value == '') {
            email.style.border = '1px solid red';
            errDiv.innerHTML = 'Вы не ввели email';
            return;
        } else {
            email.style.border = 'none';
            errDiv.innerHTML = '';
            email = email.value;
        }

        let name = table.querySelector('input[name=ctuName]'); //ctuName
        if (name.value == '') {
            name.style.border = '1px solid red';
            errDiv.innerHTML = 'Вы не ввели имя';
            return;
        } else {
            name.style.border = 'none';
            errDiv.innerHTML = '';
            name = name.value;
        }

        let last_name = table.querySelector('input[name=ctuLastName]'); //ctuLastName
        if (last_name.value == '') {
            last_name.style.border = '1px solid red';
            errDiv.innerHTML = 'Вы не ввели фамилию';
            return;
        } else {
            last_name.style.border = 'none';
            errDiv.innerHTML = '';
            last_name = last_name.value;
        }
        let third_name = table.querySelector('input[name=ctuThirdName]'); //ctuLastName
        if (third_name.value == '') {
            third_name.style.border = '1px solid red';
            errDiv.innerHTML = 'Вы не ввели фамилию';
            return;
        } else {
            third_name.style.border = 'none';
            errDiv.innerHTML = '';
            third_name = third_name.value;
        }

        let mobile_phone = table.querySelector('input[name=ctuMobilePhone]'); //ctuMobilePhone
        if (mobile_phone.value == '') {
            mobile_phone.style.border = '1px solid red';
            errDiv.innerHTML = 'Вы не ввели мобильный телефон';
            return;
        } else {
            mobile_phone.style.border = 'none';
            errDiv.innerHTML = '';
            mobile_phone = mobile_phone.value;
        }

        let work_phone = table.querySelector('input[name=ctuWorkPhone]'); //ctuWorkPhone
        if (work_phone.value == '') {
            work_phone.style.border = '1px solid red';
            errDiv.innerHTML = 'Вы не ввели рабочий телефон';
            return;
        } else {
            work_phone.style.border = 'none';
            errDiv.innerHTML = '';
            work_phone = work_phone.value;
        }

        let nomination = table.querySelector('select[name=ctuNomination]'); //ctuNomination
        if (nomination.value == '') {
            nomination.style.border = '1px solid red';
            errDiv.innerHTML = 'Вы не выбрали номинацию';
            return;
        } else {
            nomination.style.border = 'none';
            errDiv.innerHTML = '';
            nomination = nomination.value;
        }

        let pass = table.querySelector('input[name=ctuPass]').value; //ctuPass
        let work_place = table.querySelector('input[name=ctuWorkPlace]').value; //ctuWorkPlace
        let work_experience = table.querySelector('input[name=ctuWorkExperience]'); //ctuWorkExperience
        if (work_experience.value == '') {
            work_experience.style.border = '1px solid red';
            errDiv.innerHTML = 'Вы не заполнили стаж работы';
            return;
        } else if (work_experience.value < 3) {
            work_experience.style.border = '1px solid red';
            errDiv.innerHTML = 'Стаж работы должен быть не менее 3';
            return;
        } else {
            work_experience.style.border = 'none';
            errDiv.innerHTML = '';
            work_experience = work_experience.value;
        }

        let education = table.querySelector('input[name=ctuEducation]').value; //ctuEducation
        let name_education = table.querySelector('input[name=ctuNameEducation]').value; //ctuNameEducation
        let training = table.querySelector('input[name=ctuTraining]').value; //ctuTraining
        let adress_index = table.querySelector('input[name=ctuIndex]').value; //ctuIndex
        let home_adress = table.querySelector('input[name=ctuHomeAdress]'); //ctuHomeAdress
        if (home_adress.value == '') {
            home_adress.style.border = '1px solid red';
            errDiv.innerHTML = 'Вы не заполнили адрес места жительства';
            return;
        } else {
            home_adress.style.border = 'none';
            errDiv.innerHTML = '';
            home_adress = home_adress.value;
        }

        let employer_phone = table.querySelector('input[name=ctuEmployerPhone]').value; //ctuEmployerPhone
        let work_email = table.querySelector('input[name=ctuWorkEmail]').value; //ctuWorkEmail
        let MemberProfile = table.querySelector('#ctuEditMemberProfile').files[0];
        let DocInput1 = table.querySelector('#ctuEditOtherDocs1').files[0];
        //let DocInput2 = table.querySelector('#ctuEditOtherDocs2').files[0];
        //let DocInput3 = table.querySelector('#ctuEditOtherDocs3').files[0];
        //let DocInput4 = table.querySelector('#ctuEditOtherDocs4').files[0];
        //let DocInput5 = table.querySelector('#ctuEditOtherDocs5').files[0];

        let editPartData = new FormData();
        editPartData.append('id', id);
        editPartData.append('email', email);
        editPartData.append('name', name);
        editPartData.append('last_name', last_name);
        editPartData.append('third_name', third_name);
        editPartData.append('mobile_phone', mobile_phone);
        editPartData.append('work_phone', work_phone);
        editPartData.append('nomination', nomination);
        editPartData.append('pass', pass);
        editPartData.append('work_place', work_place);
        editPartData.append('work_experience', work_experience);
        editPartData.append('education', education);
        editPartData.append('name_education', name_education);
        editPartData.append('training', training);
        editPartData.append('adress_index', adress_index);
        editPartData.append('home_adress', home_adress);
        editPartData.append('employer_phone', employer_phone);
        editPartData.append('work_email', work_email);
        editPartData.append('MemberProfile', MemberProfile);
        editPartData.append('DocInput1', DocInput1);
        //editPartData.append('DocInput2', DocInput2);
        //editPartData.append('DocInput3', DocInput3);
        //editPartData.append('DocInput4', DocInput4);
        //editPartData.append('DocInput5', DocInput5);

        fetch('http://lpptourism.ru/core/userContent/coordsFiles/EditPartData.php', {
                method: 'POST',
                body: editPartData
            })
            .then(response => response.text())
            .then(result => {
                errDiv.style.color = 'green';
                errDiv.innerHTML = result;
                fetchNewDocs();
            })
            .catch(err => errDiv.innerHTML = err);

        //Обновляем данные таблицы

        let fetchNewDocs = function() {

          let partID = document.querySelector('input[name=ctuID]').value;

          let data = {
              partID: partID
          };
          fetch('http://lpptourism.ru/core/userContent/coordsFiles/editUserTable.php', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json'
                  },
                  body: JSON.stringify(data)
              })
              .then(response => response.json())
              .then(result => {
                  document.getElementById('containerLoadedFilesForPart').innerHTML = result[`part_files`];
                  document.getElementById('ctuEditMemberProfile').value = '';
                  document.getElementById('ctuEditOtherDocs1').value = '';
                  document.getElementsByClassName('editPartLabel')[0].innerHTML = 'Выберете файл';
                  document.getElementsByClassName('editPartLabel')[1].innerHTML = 'Выберете файл';
                  document.getElementById('visiblePartOfCoordMainPage').style.display = 'none';
                  document.getElementById('hiddenPartOfCoordMainPage').style.display = 'block';
              })
              .catch(err => console.log(err));
        }


    }
}

function passTheTestButton() {
    window.location.href = '/account/test/index.html';
}
let timerId = setInterval(() => refTokenCheck('http://lpptourism.ru/core/token.php', 'account'), 30000);

function partDataLoaderFunc() {
    let data  = {
        access_token: localStorage.getItem('access_token')
    };

}
