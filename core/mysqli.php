<?php

//Открывает соединение с mysql
function mysqliConnect() {
    $mysqli = new mysqli('server', 'user','password','db');
    return($mysqli);
}

//Закрывает соединение с mysql
function mysqliClose() {
    mysqliConnect()->close;
}

//Обычный query запрос в mysql, при правильном запросе возращает $result, если нет, то false
function mysqliQuery($query) {
    if ($result = mysqliConnect()->query($query)) {
        return($result);
        $result->close();
    } else {
        return(false);
    }
    mysqliClose();
}

//Возвращает строку или false
function mysqliSelectQuery($query) {
    if ($result = mysqliConnect()->query($query)) {
        if ($result->num_rows >= 1) {
            return(mysqli_fetch_array($result)[0]);
        } else {
            return(false);
        }
        $result->close();
    }
    mysqliClose();
}

?>
