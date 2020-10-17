<?php

$_POST = json_decode(file_get_contents("php://input"), true);

$newAdminEmail = $_POST['newAdminEmail'];
$newAdminPassword = $_POST['newAdminPassword'];
$newAdminName = $_POST['newAdminName'];
$newAdminLastName =$_POST['newAdminLastName'];
$newAdminPhone = $_POST['newAdminPhone'];
$newAdminCheck =$_POST['newAdminCheck'];

$email64 = base64_encode($newAdminEmail);

$obj = (object) array();

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
$query = "SELECT * from t_new_users where email ='".mb_convert_encoding($email64, "cp1251")."';";
if (mysqli_num_rows(mysqliQuery($query)) > 0) {
  $obj->error = "Пользователь с таким email уже есть";
  echo(json_encode($obj));
  return;
}

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


if ($newAdminCheck == 'activ' && $newAdminPassword == '') {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/core/other.php');
    $newAdminPassword = randomString(15);
}

if ($newAdminPassword != '') {
    $password64 = base64_encode($newAdminPassword);
} else {
    $password64 = null;
}
//Заносим нового админа в таблицу t_users
$query = "INSERT INTO t_new_users VALUES (
  null,
  '".mb_convert_encoding($email64, "cp1251")."',
  '".mb_convert_encoding($password64, "cp1251")."',
  null,
  null,
  now(),
  now(),
  'admin',
  null
)";
mysqliQuery($query);

//Заносим данный нового админа в таблицу t_admins
$query = "INSERT INTO t_new_admins VALUES (
  null,
  '".mb_convert_encoding($newAdminEmail, "cp1251")."',
  '".mb_convert_encoding($newAdminName, "cp1251")."',
  '".mb_convert_encoding($newAdminLastName, "cp1251")."',
  '".mb_convert_encoding($newAdminPhone, "cp1251")."',
  now(),
  '".mb_convert_encoding($newAdminCheck, "cp1251")."'
);";
mysqliQuery($query);

if ($newAdminCheck == 'activ') {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/core/emailMessage/email.php');
    mailNewAdmin($newAdminEmail,$newAdminPassword);
    $query = "UPDATE t_new_users SET emailpass = 'post' where email = '" . base64_encode(mb_convert_encoding($newAdminEmail,"cp1251")) . "';";
    mysqliQuery($query);
    $obj->true = "Письмо с паролем было отправлено пользователю<br>";
    echo(json_encode($obj));
} else {
  $obj->true = "Пользователь был создан<br>";
  echo(json_encode($obj));
}
?>
