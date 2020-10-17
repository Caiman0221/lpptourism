<?php

//перегружает таблицы lpptourism
$sql ="drop table t_new_users";
mysqliQuery($sql);

$sql = "CREATE TABLE IF NOT EXISTS t_new_users (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email CHAR(255),
    password CHAR(255),
    access_token CHAR(255),
    refresh_token CHAR(255),
    access_token_date DATETIME NOT NULL DEFAULT NOW(),
    refresh_token_date DATETIME NOT NULL DEFAULT NOW(),
    access CHAR(40),
    emailpass VARCHAR(15)
);";

mysqliQuery($sql);

//admin
$email = base64_encode('admin@test.ru');
$password = base64_encode('adminpass');
$access_token = randomString(45);
$refresh_token = randomString(60);
$datetime = realTime();

$sql = "INSERT INTO t_new_users VALUES (null, '$email','$password','$access_token','$refresh_token','$datetime','$datetime','admin',null)";
mysqliQuery($sql);

$query = "CREATE TABLE IF NOT EXISTS t_new_admins (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255),
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    mobilephone VARCHAR(20),
    reg_date DATETIME NOT NULL DEFAULT NOW(),
    status VARCHAR(15)
);";
mysqliQuery($query);

$newAdminEmail = 'admin@test.ru';
$newAdminName = 'Админ';
//$newAdminName = mb_convert_encoding($newAdminName, "cp1251");
$newAdminLastName = 'Админович';
//$newAdminLastName = mb_convert_encoding($newAdminLastName, "cp1251");
$newAdminPhone = '+70000000000';
$newAdminCheck = 'activ';

echo(mb_detect_encoding($newAdminName));
//Заносим данный нового админа в таблицу t_new_admins
$query = "INSERT INTO t_new_admins VALUES (null,'$newAdminEmail','$newAdminName','$newAdminLastName','$newAdminPhone',now(),'$newAdminCheck');";
mysqliQuery($query);

function mysqliConnect() {
    $mysqli = new mysqli('h812193481.mysql','h812193481_mysql','v6dipV_S','h812193481_db');
    return($mysqli);
}

function mysqliClose() {
    mysqliConnect()->close;
}

function mysqliQuery($query) {
  $query = mb_convert_encoding($query, 'cp1251');
    if ($result = mysqliConnect()->query($query)) {
        $result = mb_convert_encoding($result, "utf8");
        return($result);
        $result->close();
    }
    mysqliClose();
}

function randomString($int) {
    $array = array (0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
    $string = "";
    for ($i = 0; $i < $int; $i++) {
        $string .= $array[mt_rand(0, (count($array) - 1))];
    }
    return($string);
}

function realTime() {
    date_default_timezone_set('UTC');
    $datetime = date('Y\-m\-d H:i:s');
    return $datetime;
}

?>
