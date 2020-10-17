<?php

$_POST = json_decode(file_get_contents("php://input"), true);

$newCoordEmail = $_POST['newCoordEmail'];
$newCoordPassword = $_POST['newCoordPassword'];
$newCoordName = $_POST['newCoordName'];
$newCoordLastName = $_POST['newCoordLastName'];
$newCoordMobilePhone = $_POST['newCoordMobilePhone'];
$newCoordWorkPhone = $_POST['newCoordWorkPhone'];
$newCoordSubjectName = $_POST['newCoordSubjectName'];
$newCoordCheckbox = $_POST['newCoordCheckbox'];

$email64 = base64_encode($newCoordEmail);

$obj = (object) array();

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
$query = "SELECT * from t_new_users where email ='".mb_convert_encoding($email64, "cp1251")."';";
if (mysqli_num_rows(mysqliQuery($query)) > 0) {
  $obj->error = "Пользователь с таким email уже есть";
  echo(json_encode($obj));
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

if ($newCoordCheckbox == 'activ' && $newCoordPassword == '') {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/core/other.php');
    $newCoordPassword = randomString(15);
}

if ($newCoordPassword != '') {
    $password64 = base64_encode(($newCoordPassword));
} else {
    $password64 = null;
}

//Заносим нового координатора в таблицу t_users
$query = "INSERT INTO t_new_users VALUES (
  null,
  '".mb_convert_encoding($email64, "cp1251")."',
  '".mb_convert_encoding($password64, "cp1251")."',
  null,
  null,
  now(),
  now(),
  'coord',
  null
)";
mysqliQuery($query);

//Заносим данный нового координатора в таблицу t_coords
$query = "INSERT INTO t_new_coords VALUES (
    null,
    '".mb_convert_encoding($newCoordEmail, "cp1251")."',
    '".mb_convert_encoding($newCoordWorkPhone, "cp1251")."',
    '".mb_convert_encoding($newCoordMobilePhone, "cp1251")."',
    '".mb_convert_encoding($newCoordLastName, "cp1251")."',
    '".mb_convert_encoding($newCoordName, "cp1251")."',
    '".mb_convert_encoding($newCoordSubjectName, "cp1251")."',
    now(),
    '".mb_convert_encoding($newCoordCheckbox, "cp1251")."'
);";
mysqliQuery($query);

if ($newCoordCheckbox == 'activ') {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/core/emailMessage/email.php');
    mailNewCoord($newCoordEmail,$newCoordPassword);
    $query = "UPDATE t_new_users SET emailpass = 'post' where email = '" . base64_encode(mb_convert_encoding($newCoordEmail,"cp1251")) . "';";
    mysqliQuery($query);
    $obj->true = "Письмо с паролем было отправлено пользователю";
    echo(json_encode($obj));
} else {
  $obj->true = "Пользователь был создан<br>";
  echo(json_encode($obj));
}

?>
