<?php

$_POST = json_decode(file_get_contents("php://input"), true);

$selector = $_POST['selector'];
$lastname = $_POST['lastname'];
$firstname = $_POST['firstname'];
$workphone = $_POST['workphone'];
$mobilephone = $_POST['mobilephone'];
$email = $_POST['email'];

$obj = (object) array();
//$email = 'admin@test.ru';
require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
//ищем нет ли такого email уже
$email64 = base64_encode($email);
$query = "SELECT id from t_new_users where email = '$email64';";
$res = mysqli_fetch_row(mysqliQuery($query))[0];

if ($res != null) {
    $obj->err = 'Такой email уже есть';
    echojs($obj);
    return;
}


$query = "INSERT INTO t_new_users VALUES (
  null,
  '$email64',
  null,
  null,
  null,
  now(),
  now(),
  'coord',
  null
)";

if(mysqliQuery($query)) {
    $true = 'true';
} else {
    $obj->err = "Ошибка на сервере №1";
    echojs($obj);
    return;
}

$query = "CREATE TABLE IF NOT EXISTS t_new_coords (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255),
    workphone VARCHAR(20),
    mobilephone VARCHAR(20),
    last_name VARCHAR(100),
    first_name VARCHAR(100),
    subjectname VARCHAR(255),
    reg_date DATETIME NOT NULL DEFAULT NOW(),
    status CHAR(15)
);";
mysqliQuery($query);

$query = "INSERT into t_new_coords VALUES (
  null,
  '".mb_convert_encoding($email,'cp1251')."',
  '".mb_convert_encoding($workphone,'cp1251')."',
  '".mb_convert_encoding($mobilephone,'cp1251')."',
  '".mb_convert_encoding($lastname,'cp1251')."',
  '".mb_convert_encoding($firstname,'cp1251')."',
  '".mb_convert_encoding($selector,'cp1251')."',
  now(),
  'disabled'
);";
if(mysqliQuery($query)) {
    $true = 'true';
} else {
    $obj->err = "Ошибка на сервере №2";
    echojs($obj);
    return;
}

$obj->true = 'true';

echojs($obj);
function echojs($obj) {
    echo (json_encode($obj));
}

?>
