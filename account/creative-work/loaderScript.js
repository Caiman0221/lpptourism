function uploadCreativWord() {
  let errorDiv = document.getElementById('errorLink');
  errorDiv.style.color = 'red';
  let link = document.getElementById('linkOnCreativWork');
  if (link.value == '') {
    link.style.border = '1px solid red';
    errorDiv.innerHTML = 'вы не ввели ссылку';
    return;
  } else {
    link.style.border = 'none';
    link = link.value;
    errorDiv.innerHTML = '';
  }

  let data = {
    access_token: localStorage.getItem('access_token'),
    link: link
  };

  fetch('http://lpptourism.ru/core/userContent/partFiles/partLoaderLink.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
    })
    .then(response => response.text())
    .then(result => {
      errorDiv.style.color = 'black';
      errorDiv.innerHTML = result;
    })
    .catch(error => errorDiv.innerHTML = error);
}
