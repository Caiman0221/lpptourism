function expertSelectNomination(select) {
    for (let i = 1; i < select.options.length; i++) {
        document.getElementById('tableForNomination' + i).style.display = 'none';
    }
    if (select.value != '') document.getElementById('tableForNomination' + select.value).style.display = 'block';
}

function expertTableClick(e) {
    let target = e.target.getAttribute('name');
    if (target == 'partK1Button' || target == 'partK2Button' || target == 'partK3Button' || target == 'partK4Button' || target == 'partcheck') {
        var valK1 = e.currentTarget.querySelector('input[name=partK1]'); //K1
        if (valK1.value > 5) {valK1.value = '5';}
        else if (valK1.value < 0) {valK1.value = 0;}

        var K1;
        if (valK1.value == '') K1 = 0;
        else K1 = valK1.value;

        var valK2 = e.currentTarget.querySelector('input[name=partK2]'); //K2
        if (valK2.value > 5) valK2.value = 5;
        else if (valK2.value < 0) valK2.value = 0;

        var K2;
        if (valK2.value == '') K2 = 0;
        else K2 = valK2.value;

        var valK3 = e.currentTarget.querySelector('input[name=partK3]'); //K3
        if (valK3.value > 5) valK3.value = 5;
        else if (valK3.value < 0) valK3.value = 0;

        var K3;
        if (valK3.value == '') K3 = 0;
        else K3 = valK3.value;

        var valK4 = e.currentTarget.querySelector('input[name=partK4]'); //K4
        if (valK4.value > 5) valK4.value = 5;
        else if (valK4.value < 0) valK4.value = 0;

        var K4;
        if (valK4.value == '') K4 = 0;
        else K4 = valK4.value;

        var valK5 = e.currentTarget.querySelector('input[name=partcheck]').checked; //CHECK
        var K5;
        if (valK5 == true) K5 = 1;
        else  K5 = 0;
        var valAll = e.currentTarget.querySelector('input[name=partRes]');
        valAll.value = Number(K1) + Number(K2) + Number(K3) + Number(K4) + Number(K5);

        //Подготовка данных для отправки в БД
        let data = {
            access_token: localStorage.getItem('access_token'),
            part_id: e.currentTarget.querySelector('input[name=partID]').value,
            K1: e.currentTarget.querySelector('input[name=partK1]').value,
            K2: e.currentTarget.querySelector('input[name=partK2]').value,
            K3: e.currentTarget.querySelector('input[name=partK3]').value,
            K4: e.currentTarget.querySelector('input[name=partK4]').value,
            K5: K5
        };

        fetch ('http://lpptourism.ru/core/userContent/expertsFiles/insertRes.php',{
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.text())
        .then(result => console.log(result));
    } else {
        return;
    }
}
