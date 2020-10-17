const userLogin = function(page) {
    let email = document.getElementById('email');
    let password = document.getElementById('password');

    let adminemail = document.getElementById('adminemail');
    let adminpassword = document.getElementById('adminpassword');

    if (email) email = email.value;
    if (password) password = password.value;
    if (adminemail) email = adminemail.value;
    if (adminpassword) password = adminpassword.value;

    userData = {
        email: email,
        password: password,
        page: page
    };

    let url = 'http://lpptourism.ru/core/login.php';
    let options = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json;' //  charset=utf-8
        },
        body: JSON.stringify(userData)
    };

    fetch(url, options)
        .then(response => response.json())
        .then(result => {
            if (result[`access_token`]) localStorage.setItem('access_token', result[`access_token`]);
            if (result[`refresh_token`]) localStorage.setItem('refresh_token', result[`refresh_token`]);
            if (result[`token_time`]) localStorage.setItem('token_time', result[`token_time`]);
            if (result[`link`]) document.location.href = result[`link`];
            if (result[`answer`]) document.getElementById('brokenPass').innerHTML = result[`answer`];
            else document.getElementById('brokenPass').innerHTML = '';
        });
};

const TokenCheck = function(url, page) {
    var realTime = Math.round(Date.now() / 1000);
    if (realTime - (localStorage.getItem('token_time')) < 60 * 15) {
        accessTokenCheck(url, page);
    } else {
        refreshTokenCheck(url, page);
    }
};

const accessTokenCheck = function(url, page) {
    let access_token = localStorage.getItem('access_token');

    userData = {
        access_token: access_token,
        refresh_token: null,
        page: page
    };

    let options = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json;' // charset=utf-8
        },
        body: JSON.stringify(userData)
    };
    fetch(url, options)
        .then(response => response.json())
        .then(result => {
            if (result[`access_token`]) localStorage.setItem('access_token', result[`access_token`]);
            if (result[`token_time`]) localStorage.setItem('token_time', result[`token_time`]);
            if (result[`page`]) pageContainerLoader(result);
        });
};

const refreshTokenCheck = function(url, page) {
    userData = {
        access_token: null,
        refresh_token: localStorage.getItem('refresh_token'),
        page: page
    };

    let options = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json;'
        },
        body: JSON.stringify(userData)
    };
    fetch(url, options)
        .then(response => response.json())
        .then(result => {
            if (result[`access_token`]) localStorage.setItem('access_token', result[`access_token`]);
            if (result[`token_time`]) localStorage.setItem('token_time', result[`token_time`]);
            if (result[`page`]) pageContainerLoader(result);
        });
};

const pageContainerLoader = function(result) {
    res = result[`page`];
    if (res[`menuNav`]) {
        let menuNav = document.getElementById('menuNav');
        let menuNav2 = document.getElementById('menuNav2');
        if (menuNav) menuNav.innerHTML = res[`menuNav`];
        if (menuNav2) menuNav2.innerHTML = res[`menuNav`];
    }
    if (res[`mainPartOfPage`]) {
      if (document.getElementById('mainPartOfPage')) {
        document.getElementById('mainPartOfPage').innerHTML = res[`mainPartOfPage`];
      } else {
        setTimeout(() => {
          document.getElementById('mainPartOfPage').innerHTML = res[`mainPartOfPage`];
        }, 1000);
      }
    }
    if (res[`link`]) document.location.href = res[`link`];
    if (document.getElementById('coordAllCreatedPartsList')) coordPartsListLoader();
    if (document.getElementById('normFilesContainer')) coordFilesLoadedList();
    if (document.getElementById('participantTableData')) participantTableDataLoader();
    if (document.getElementById('questionTime')) testTime();
};

function exitClick() {
    localStorage.clear();
    document.location.href = 'http://lpptourism.ru/private';
}
