<?php

$_POST = json_decode(file_get_contents("php://input"), true);

$newExperEmail = $_POST['newExperEmail'];
$newExpertPassword = $_POST['newExpertPassword'];
$newExpertName = $_POST['newExpertName'];
$newExpertLastName = $_POST['newExpertLastName'];
$newExpertMobilePhone = $_POST['newExpertMobilePhone'];
$newExpertWorkPhone = $_POST['newExpertWorkPhone'];
$newExpertCheckbox = $_POST['newExpertCheckbox'];

$email64 = base64_encode($newExperEmail);

$obj = (object) array();

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
$query = "SELECT * from t_new_users where email ='".mb_convert_encoding($email64, "cp1251")."';";
if (mysqli_num_rows(mysqliQuery($query)) > 0) {
  $obj->error = "Пользователь с таким email уже есть";
    echo(json_encode($obj));
    return;
}

$query = "CREATE TABLE IF NOT EXISTS t_new_experts (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255),
    workphone VARCHAR(20),
    mobilephone VARCHAR(20),
    last_name VARCHAR(100),
    first_name VARCHAR(100),
    reg_date DATETIME NOT NULL DEFAULT NOW(),
    status CHAR(15),
    results TEXT
);";
mysqliQuery($query);

if ($newExpertCheckbox == 'activ' && $newExpertPassword == '') {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/core/other.php');
    $newExpertPassword = randomString(15);
}

if ($newExpertPassword != '') {
    $password64 = base64_encode($newExpertPassword);
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
  'expert',
  null
)";
mysqliQuery($query);

//Заносим данный нового координатора в таблицу t_coords
$query = "INSERT INTO t_new_experts VALUES (
    null,
    '".mb_convert_encoding($newExperEmail, "cp1251")."',
    '".mb_convert_encoding($newExpertWorkPhone, "cp1251")."',
    '".mb_convert_encoding($newExpertMobilePhone, "cp1251")."',
    '".mb_convert_encoding($newExpertLastName, "cp1251")."',
    '".mb_convert_encoding($newExpertName, "cp1251")."',
    now(),
    '".mb_convert_encoding($newExpertCheckbox, "cp1251")."',
    null
);";
mysqliQuery($query);

if ($newExpertCheckbox == 'activ') {
  require_once($_SERVER['DOCUMENT_ROOT'] . '/core/emailMessage/email.php');
  mailNewExpert($newExperEmail,$newExpertPassword);
  $obj->true = 'Письмо с паролем было отправлено пользователю';
  $query = "UPDATE t_new_users SET emailpass = 'post' where email = '" . base64_encode(mb_convert_encoding($newExperEmail,"cp1251")) . "';";
  mysqliQuery($query);
  echo(json_encode($obj));
} else {
  $obj->true = "Пользователь был создан<br>";
  echo(json_encode($obj));
}
?>
