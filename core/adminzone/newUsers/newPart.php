<?php

$_POST = json_decode(file_get_contents("php://input"), true);

$newPartEmail = $_POST['newPartEmail'];
$newPartPassword = $_POST['newPartPassword'];
$newPartName = $_POST['newPartName'];
$newPartLastName = $_POST['newPartLastName'];
$newPartThirdName = $_POST['newPartThirdName'];
$newPartMobilePhone = $_POST['newPartMobilePhone'];
$newPartWorkPhone = $_POST['newPartWorkPhone'];
$newPartNomination = $_POST['newPartNomination'];
$newPartPass = $_POST['newPartPass'];
$newPartWorkPlace = $_POST['newPartWorkPlace'];
$newPartWorkExperience = $_POST['newPartWorkExperience'];
$newPartEducation = $_POST['newPartEducation'];
$newPartNameEducation = $_POST['newPartNameEducation'];
$newPartTraining = $_POST['newPartTraining'];
$newPartAdressIndex = $_POST['newPartAdressIndex'];
$newPartHomeAdress = $_POST['newPartHomeAdress'];
$newPartEmployerPhone = $_POST['newPartEmployerPhone'];
$newPartWorkEmail = $_POST['newPartWorkEmail'];
$newPartNameCoord = $_POST['newPartNameCoord'];
$newPartCheckbox = $_POST['newPartCheckbox'];

$email64 = base64_encode($newPartEmail);

$obj = (object) array();

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/core/other.php');
$query = "SELECT * from t_new_users where email ='".mb_convert_encoding($email64, "cp1251")."';";
if (mysqli_num_rows(mysqliQuery($query)) > 0) {
  $obj->error = "Пользователь с таким email уже есть";
  echo(json_encode($obj));
  return;
}

//Создаем БД для участников
$query = "CREATE TABLE IF NOT EXISTS t_new_parts (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255),
    name VARCHAR(100),
    last_name VARCHAR(100),
    mobile_phone VARCHAR(15),
    work_phone VARCHAR(15),
    nomination VARCHAR(10),
    pass VARCHAR(255),
    work_place VARCHAR(255),
    work_experience VARCHAR(50),
    education VARCHAR(255),
    name_education VARCHAR(255),
    training VARCHAR(255),
    adress_index VARCHAR(15),
    home_adress VARCHAR(255),
    employer_phone VARCHAR(50),
    work_email VARCHAR(255),
    subjectname VARCHAR(255),
    coord_name VARCHAR(255),
    reg_date DATETIME NOT NULL DEFAULT NOW(),
    status VARCHAR(10),
    third_name VARCHAR(100)
);";
mysqliQuery($query);

if ($newPartCheckbox == 'activ' && $newPartPassword == '') {
    $newPartPassword = randomString(15);
}

if ($newPartPassword != '') {
    $password64 = base64_encode($newPartPassword);
} else {
    $password64 = null;
}

//Заносим нового пользователя в таблицу t_users
$query = "INSERT INTO t_new_users VALUES (
  null,
  '".mb_convert_encoding($email64, "cp1251")."',
  '".mb_convert_encoding($password64, "cp1251")."',
  null,
  null,
  now(),
  now(),
  'part',
  null
)";
mysqliQuery($query);

//Узнаем субьект РФ координатора
$query = "SELECT last_name, first_name, subjectname from t_new_coords where id = '".mb_convert_encoding($newPartNameCoord, "cp1251")."';";
$row = mysqli_fetch_row(mysqliQuery($query));
$newPartNameCoord = mb_convert_encoding($row[0],"utf-8", "cp1251") . " "  . mb_convert_encoding($row[1],"utf-8", "cp1251");
$RFSubRus =  RFsubjects($row[2]);

//Заносим данный нового участника в таблицу t_parts
$query = "INSERT INTO t_new_parts VALUES (
    null,
    '".mb_convert_encoding($newPartEmail, "cp1251")."',
    '".mb_convert_encoding($newPartName, "cp1251")."',
    '".mb_convert_encoding($newPartLastName, "cp1251")."',
    '".mb_convert_encoding($newPartMobilePhone, "cp1251")."',
    '".mb_convert_encoding($newPartWorkPhone, "cp1251")."',
    '".mb_convert_encoding($newPartNomination, "cp1251")."',
    '".mb_convert_encoding($newPartPass, "cp1251")."',
    '".mb_convert_encoding($newPartWorkPlace, "cp1251")."',
    '".mb_convert_encoding($newPartWorkExperience, "cp1251")."',
    '".mb_convert_encoding($newPartEducation, "cp1251")."',
    '".mb_convert_encoding($newPartNameEducation, "cp1251")."',
    '".mb_convert_encoding($newPartTraining, "cp1251")."',
    '".mb_convert_encoding($newPartAdressIndex, "cp1251")."',
    '".mb_convert_encoding($newPartHomeAdress, "cp1251")."',
    '".mb_convert_encoding($newPartEmployerPhone, "cp1251")."',
    '".mb_convert_encoding($newPartWorkEmail, "cp1251")."',
    '".mb_convert_encoding($RFSubRus, "cp1251")."',
    '".mb_convert_encoding($newPartNameCoord, "cp1251")."',
    now(),
    '".mb_convert_encoding($newPartCheckbox, "cp1251")."',
    '".mb_convert_encoding($newPartThirdName, "cp1251")."'
);";
mysqliQuery($query);

$query = "SELECT id from t_new_parts where email = '".mb_convert_encoding($newPartEmail, "cp1251")."';";
$id_dir = mysqli_fetch_row(mysqliQuery($query))[0];

$catalogdir = $_SERVER['DOCUMENT_ROOT'] . "/files/parts/" . $id_dir;
//создаем каталог
if (!is_dir($catalogdir)) {
  mkdir($catalogdir, 0777);
}
//chmod($catalogdir, 0777);

//Создаем таблицу для тестов и результатов экспертов
$query = "CREATE TABLE IF NOT EXISTS t_new_parts_works (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255),
    tests_json TEXT,
    res_of_exp TEXT
);";
mysqliQuery($query);

$query = "INSERT INTO t_new_parts_works VALUES (
    null,
    '".mb_convert_encoding($newPartEmail, "cp1251")."',
    null,
    null
);";
mysqliQuery($query);

if ($newPartCheckbox == 'activ') {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/core/emailMessage/email.php');
    mailNewPart($newPartEmail,$newPartPassword);
    $obj->true = "Письмо с паролем было отправлено пользователю";
    $query = "UPDATE t_new_users SET emailpass = 'post' where email = '" . base64_encode(mb_convert_encoding($newPartEmail,"cp1251")) . "';";
    mysqliQuery($query);
    echo(json_encode($obj));
} else {
  $obj->true = "Пользователь был создан<br>";
  echo(json_encode($obj));
}

?>
