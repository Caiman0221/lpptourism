<?php

$_POST = json_decode(file_get_contents("php://input"), true);

$id = $_POST['id'];
$email = $_POST['email'];
$password = $_POST['password'];
$name = $_POST['name'];
$last_name = $_POST['lastName'];
$phone = $_POST['phone'];
$checkbox = $_POST['checkbox'];

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');

//Забираем старый email для поиска по t_users
$query = "SELECT email from t_new_admins where id = '$id';";
$email_old = mysqli_fetch_row(mysqliQuery($query))[0];
$email_old = mb_convert_encoding($email_old,"utf-8","cp1251");

//Обновляем информацию об админе в БД t_new_admins
$query = "UPDATE t_new_admins set email = '".mb_convert_encoding($email, "cp1251")."',
 first_name = '".mb_convert_encoding($name, "cp1251")."',
 last_name = '".mb_convert_encoding($last_name, "cp1251")."',
 mobilephone = '".mb_convert_encoding($phone, "cp1251")."',
 status = '".mb_convert_encoding($checkbox, "cp1251")."'
 where id = '$id';
";
mysqliQuery($query);

//Перекодируем данные в base64 для БД t_new_users
$email64 = base64_encode($email);
$email_old64 = base64_encode($email_old);

//Если админ ввел пароль в input'е, то забираем его значение
if ($password != '') {
    $password64 = base64_encode($password);
    $query_pass = ", password = '$password64'";
    $query_new_pass = "AND password = '$password64'";
} else {
    $query_pass = "";
}

//Если админ пытался активировать нового пользователя
if ($checkbox != 'disabled') {
  //проверяем не было ли пароля в базе данных (если нет, то необходимо отправить сообщение ему на почту)
    $query = "SELECT password, emailpass from t_new_users where email = '$email64';";
    $result = mysqli_fetch_row(mysqliQuery($query));
    $password64_old = $result[0]; //Старый пароль из бд
    $emailpass = $result[1]; //Информация об отправке почты
    //если в БД не было пароля или информации об отправки почты
    if ($password64_old == null || $emailpass == null) {
        require_once($_SERVER['DOCUMENT_ROOT'] . '/core/emailMessage/email.php');
        require_once($_SERVER['DOCUMENT_ROOT'] . '/core/other.php');
        //генерируем новый пароль для пользователя если не был введен в input'е
        if ($password == '') {
          if ($password64_old == null) {
            $password = randomString(15);
            $password64 = base64_encode($password);
          } else {
            $password64 = $password64_old;
            $password = base64_decode($password64);
          }
            $query_pass = ", password = '$password64'";
            $query_new_pass = "AND password = '$password64'";
        }
        $query = "UPDATE t_new_users set emailpass = 'post', email = '$email64' $query_pass where email = '$email_old64';";
        mysqliQuery($query);
        if (mailNewAdmin($email,$password) == true) {
            echo ('Пользователю был выслан новый пароль на почту<br>');
        } else {
            echo ('Произошла ошибка отправки email<br>');
        }
    }
}

$query = "UPDATE t_new_users set email = '$email64' $query_pass where email = '$email_old64';";
mysqliQuery($query);

//проверка изменения email
$query = "SELECT email, password from t_new_users where email = '$email64' $query_new_pass;";
$result = mysqli_fetch_row(mysqliQuery($query))[0];
if ($result != '') {
    if ($email != $email_old) {
        echo ("Email был изменен<br>");
    }
}

echo ('Данные были изменены');


?>
