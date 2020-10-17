<?php

$_POST = json_decode(file_get_contents("php://input"), true);

$id = $_POST['id'];
$email = $_POST['email'];
$password = $_POST['password'];
$name = $_POST['name'];
$last_name = $_POST['last_name'];
$third_name = $_POST['third_name'];
$mobile_phone = $_POST['mobile_phone'];
$work_phone = $_POST['work_phone'];
$nomination = $_POST['nomination'];
$work_experience = $_POST['work_experience'];
$pass = $_POST['pass'];
$work_place = $_POST['work_place'];
$education = $_POST['education'];
$name_education = $_POST['name_education'];
$training = $_POST['training'];
$adress_index = $_POST['adress_index'];
$home_adress = $_POST['home_adress'];
$employer_phone = $_POST['employer_phone'];
$work_email = $_POST['work_email'];
$subject = $_POST['subject'];
$part_coord = $_POST['part_coord'];
$checkbox = $_POST['checkbox'];

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
$query = "SELECT email from t_new_parts where id = '$id';";
$email_old = mysqli_fetch_row(mysqliQuery($query))[0];
$email_old = mb_convert_encoding($email_old,"utf-8","cp1251");

//Изменяем информацию об участнике в БД t_new_parts
$query = "UPDATE t_new_parts set
  email = '".mb_convert_encoding($email, "cp1251")."',
  name = '".mb_convert_encoding($name, "cp1251")."',
  last_name = '".mb_convert_encoding($last_name, "cp1251")."',
  mobile_phone = '".mb_convert_encoding($mobile_phone, "cp1251")."',
  work_phone = '".mb_convert_encoding($work_phone, "cp1251")."',
  nomination = '".mb_convert_encoding($nomination, "cp1251")."',
  pass = '".mb_convert_encoding($pass, "cp1251")."',
  work_place = '".mb_convert_encoding($work_place, "cp1251")."',
  work_experience = '".mb_convert_encoding($work_experience, "cp1251")."',
  education = '".mb_convert_encoding($education, "cp1251")."',
  name_education = '".mb_convert_encoding($name_education, "cp1251")."',
  training = '".mb_convert_encoding($training, "cp1251")."',
  adress_index = '".mb_convert_encoding($adress_index, "cp1251")."',
  home_adress = '".mb_convert_encoding($home_adress, "cp1251")."',
  employer_phone = '".mb_convert_encoding($employer_phone, "cp1251")."',
  work_email = '".mb_convert_encoding($work_email, "cp1251")."',
  subjectname = '".mb_convert_encoding($subject, "cp1251")."',
  coord_name = '".mb_convert_encoding($part_coord, "cp1251")."',
  status = '".mb_convert_encoding($checkbox, "cp1251")."',
  third_name = '".mb_convert_encoding($third_name, "cp1251")."'
  where id = '$id';";
mysqliQuery($query);

$query_works = "UPDATE t_new_parts_works set email = '".mb_convert_encoding($email, "cp1251")."' where id = '$id';";
mysqliQuery($query_works);
//Перекодируем информацию в base64 для БД t_new_users
$email64 = base64_encode($email);
$email_old64 = base64_encode($email_old);

//Если админ ввел пароль в input, то забираем его значение
if ($password != '') {
    $password64 = base64_encode($password);
    $query_pass = ", password = '$password64'";
    $query_new_pass = "AND password = '$password64'";
} else {
    $query_pass = "";
}

//если админ пытался активировать нового пользователя
if ($checkbox != 'disabled') {
  //проверяем не было ли пароля в базе данных (если нет, то отправляем новый ему на почту)
    $query = "SELECT password, emailpass from t_new_users where email = '$email64';";
    $result = mysqli_fetch_row(mysqliQuery($result));
    $password64_old = $result[0]; //Старый пароль из БД
    $emailpass = $result[1]; //Информация об отправке почты
    //Если в БД не было пароля или информации об отправке почты
    if ($password64_old == null) {
        require_once($_SERVER['DOCUMENT_ROOT'] . '/core/emailMessage/email.php');
        require_once($_SERVER['DOCUMENT_ROOT'] . '/core/other.php');
        //генерируем новый паоль для пользователя если не был введен в input или не было в БД
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
        if (mailNewPart($email,$password) == true) {
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
