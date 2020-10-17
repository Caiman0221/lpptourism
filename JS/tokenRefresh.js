const refTokenCheck = function(url, page) {
    var realTime = Math.round(Date.now() / 1000);
    if (realTime - (localStorage.getItem('token_time')) < 60 * 15) {
        refAccessTokenCheck(url, page);
    } else {
        refRefreshTokenCheck(url, page);
    }
};

const refAccessTokenCheck = function(url, page) {
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
            if (result[`access_token_check`] == 'false') {
              refRefreshTokenCheck(url,page);
            }
        });
};

const refRefreshTokenCheck = function(url, page) {
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
            if (result[`refresh_token_check`] == 'false') {
              if (result[`page`]) {
                res = result[`page`];
                if (res[`link`]) document.location.href = res[`link`];
              }
            }
        });
};
