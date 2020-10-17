<?php

$_POST = json_decode(file_get_contents("php://input"), true);

$access_token = $_POST['access_token'];
$refresh_token = $_POST['refresh_token'];
$page = $_POST['page'];

$object = (object) array();

//Проверяем access_token
if ($access_token != null) {
    if (accessTokenCheck($access_token) == true) {
        $object->access_token_check = 'true';
    } else {
        $object->access_token_check = 'false';
    }
} else {
    $object->access_token_check = 'empty';
}

//Проверяем refresh_token
if ($refresh_token != null) {
    if (refreshTokenCheck($refresh_token) == true) {
        $object->refresh_token_check = 'true';

        require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
        require_once($_SERVER['DOCUMENT_ROOT'] . '/core/other.php');

        $access_token = randomString(45);
        $datetime = realTime();

        $query = "UPDATE t_new_users SET access_token = '$access_token', access_token_date = '$datetime' WHERE refresh_token = '$refresh_token';";
        mysqliQuery($query);

        $object->access_token = $access_token;
        $object->token_time = strtotime($datetime);
    } else {
        $object->refresh_token_check = 'false';
    }
} else {
    $object->refresh_token_check = 'empty';
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/pageLoader.php');

$object->page = pageLoader($access_token,$page);

echo(json_encode($object));

//Проверяет не устарел ли access_token
function accessTokenCheck($access_token) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/core/other.php');
    $query = "SELECT access_token_date FROM t_new_users WHERE access_token = '$access_token';";
    date_default_timezone_set('UTC'); //Смотрим время сейчас
    $datetime = strtotime(date('Y\-m\-d H:i:s')); //Время сейчас UTC, переведенное в unix
    $datetimeToken = strtotime(mysqliSelectQuery($query)); //Время создания токена UTC, переведенное в unix
    //Если токен жив больше 15 минут, то он устарел (60сек * 10 = 15 минут)
    if (($datetime - $datetimeToken) <= 60*15) {
        return(true);
    } else {
        return(false);
    }
}

//Проверяет не устарел ли refresh_token
function refreshTokenCheck($refresh_token) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/core/other.php');
    $query = "SELECT refresh_token_date FROM t_new_users WHERE refresh_token = '$refresh_token';";
    date_default_timezone_set('UTC'); //Смотрим время сейчас
    $datetime = strtotime(date('Y\-m\-d H:i:s')); //Время сейчас UTC, переведенное в unix
    $datetimeToken = strtotime(mysqliSelectQuery($query)); //Время создания токена UTC, переведенное в unix
    //Если токен жив больше 12 часов, то он устарел
    if (($datetime - $datetimeToken) <= 43200) {
        return(true);
    } else {
        return(false);
    }
}

?>
