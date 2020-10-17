<?php

//Редактируем данные пользователя
$id = $_POST['id']; //id
$email = $_POST['email']; //email
$name = $_POST['name']; //name
$last_name = $_POST['last_name']; //last_name
$third_name = $_POST['third_name'];
$mobile_phone = $_POST['mobile_phone']; //mobile_phone
$work_phone = $_POST['work_phone']; //work_phone
$nomination = $_POST['nomination']; //nomination
$pass = $_POST['pass']; //pass
$work_place = $_POST['work_place']; //work_place
$work_experience =$_POST['work_experience']; //work_experience
$education = $_POST['education']; //education
$name_education = $_POST['name_education']; //name_education
$training = $_POST['training']; //training
$adress_index = $_POST['adress_index']; //adress_index
$home_adress = $_POST['home_adress']; //home_adress
$employer_phone = $_POST['employer_phone']; //employer_phone
$work_email = $_POST['work_email']; //work_email

//подключаем модуль mysqli
require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');

//вытаскиваем старый email из таблицы участников
$query = "SELECT email from t_new_parts where id = '$id';";
$email_old = mysqli_fetch_row(mysqliQuery($query))[0];
$email_old = mb_convert_encoding($email_old, "utf-8", "cp1251");

//обновляем email в таблице t_parts_works
$query = "UPDATE t_new_parts_works SET email = '".mb_convert_encoding($email, "cp1251")."' WHERE email = '".mb_convert_encoding($email_old, "cp1251")."';";
mysqliQuery($query);

//обновляем таблицу t_parts
$query = " UPDATE t_new_parts SET
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
    third_name = '".mb_convert_encoding($third_name, "cp1251")."'
    WHERE email = '".mb_convert_encoding($email_old, "cp1251")."';";
mysqliQuery($query);

//обновляем email в таблице е_users
$email_old_64 = base64_encode($email_old);
$email_64 = base64_encode($email);
$query = "UPDATE t_new_users SET email = '$email_64' where email = '$email_old_64';";

$part_dir = $_SERVER['DOCUMENT_ROOT'] . "/files/parts/$id";

//создаем каталог
if (file_exists($_FILES['MemberProfile']['tmp_name'])) {
    //Формируем ссылку
    $ext = substr($_FILES['MemberProfile']['name'], 1 + strrpos($_FILES['MemberProfile']['name'], ".")); //png, jpg, doc ... расширение файла
    $member_profile_link = $part_dir . "/Анкета пользователя." . $ext; //полный путь к анкете участника
    //Сохраняем на сервере
    if (is_uploaded_file($_FILES['MemberProfile']['tmp_name'])) {
        if (move_uploaded_file($_FILES['MemberProfile']['tmp_name'],"$member_profile_link") == false) {
            echo ("Файл не был загружен на сервер<br>");
        }
    }
}

$url = "$part_dir/";

if (file_exists($_FILES['DocInput1']['tmp_name'])) {
    $url1 = $url . $_FILES['DocInput1']['name'];
    move_uploaded_file($_FILES['DocInput1']['tmp_name'],$url1);
}
/*
if (file_exists($_FILES['DocInput2']['tmp_name'])) {
    $url1 = $url . $_FILES['DocInput2']['name'];
    move_uploaded_file($_FILES['DocInput2']['tmp_name'],$url1);
}
if (file_exists($_FILES['DocInput3']['tmp_name'])) {
    $url1 = $url . $_FILES['DocInput3']['name'];
    move_uploaded_file($_FILES['DocInput3']['tmp_name'],$url1);
}
if (file_exists($_FILES['DocInput4']['tmp_name'])) {
    $url1 = $url . $_FILES['DocInput4']['name'];
    move_uploaded_file($_FILES['DocInput4']['tmp_name'],$url1);
}
if (file_exists($_FILES['DocInput5']['tmp_name'])) {
    $url1 = $url . $_FILES['DocInput5']['name'];
    move_uploaded_file($_FILES['DocInput5']['tmp_name'],$url1);
}
*/

//Добавление файлов в архив

if (($member_profile_link != '') || ($url1 != '')) {

  $zip = new ZipArchive();
  $zip->open($_SERVER['DOCUMENT_ROOT'] . "/files/parts/$id/docs.zip"); // открываем архив участника по id

  if ($member_profile_link != '') {
    $member_profile_link_name = str_ireplace($url,'',$member_profile_link);
    $zip->addFile($member_profile_link,$member_profile_link_name); // добавляем анкету участника
  };
  if ($url1 != '') {
    $url1_name = str_ireplace("$url","",$url1);
    $zip->addFile($url1,$url1_name); //добавляем дополнительный файл 
  };
}


echo('Данные пользователя были обновлены')
?>
