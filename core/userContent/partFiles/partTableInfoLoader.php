<?php

//Старница вывода данных для кабинета пользователя

$_POST = json_decode(file_get_contents("php://input"), true);
$access_token = $_POST['access_token'];

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');

//находим почту участника из таблицы t_new_users
$query = "SELECT email from t_new_users where access_token = '$access_token';";
$email64 = mysqli_fetch_row(mysqliQuery($query))[0];
$email = base64_decode($email64);

//достаем данные из таблицы о пользователе
$query = "SELECT * from t_new_parts where email = '".mb_convert_encoding($email,"cp1251")."';";
$res = mysqli_fetch_row(mysqliQuery($query));

$name = mb_convert_encoding($res[2],"utf-8","cp1251");
$last_name = mb_convert_encoding($res[3],"utf-8","cp1251");
$mobile_phone = mb_convert_encoding($res[4],"utf-8","cp1251");
$work_phone = mb_convert_encoding($res[5],"utf-8","cp1251");
$nomination = mb_convert_encoding($res[6],"utf-8","cp1251");
$pass = mb_convert_encoding($res[7],"utf-8","cp1251");
$work_place = mb_convert_encoding($res[8],"utf-8","cp1251");
$work_experience = mb_convert_encoding($res[9],"utf-8","cp1251");
$education = mb_convert_encoding($res[10],"utf-8","cp1251");
$name_education = mb_convert_encoding($res[11],"utf-8","cp1251");
$training = mb_convert_encoding($res[12],"utf-8","cp1251");
$adress_index = mb_convert_encoding($res[13],"utf-8","cp1251");
$home_adress = mb_convert_encoding($res[14],"utf-8","cp1251");
$employer_phone = mb_convert_encoding($res[15],"utf-8","cp1251");
$work_email = mb_convert_encoding($res[16],"utf-8","cp1251");

$query = "SELECT nomination from t_new_nominations where id = '".mb_convert_encoding($nomination,"cp1251")."';";
$nomination = mysqli_fetch_row(mysqliQuery($query))[0];
$nomination = mb_convert_encoding($nomination, "utf-8", "cp1251");

$html = "
<div class='participantTableInfo'>
    <div class='participantTableName'>Имя:</div>
    <div class='participantTableRes'>$name</div>
</div>
<div class='participantTableInfo'>
    <div class='participantTableName'>Фамилия:</div>
    <div class='participantTableRes'>$last_name</div>
</div>
<div class='participantTableInfo'>
    <div class='participantTableName'>Мобильный телефон:</div>
    <div class='participantTableRes'>$mobile_phone</div>
</div>
<div class='participantTableInfo'>
    <div class='participantTableName'>Рабочий телефон:</div>
    <div class='participantTableRes'>$work_phone</div>
</div>
<div class='participantTableInfo'>
    <div class='participantTableName'>Номинация:</div>
    <div class='participantTableRes'>$nomination</div>
</div>
<div class='participantTableInfo'>
    <div class='participantTableName'>Паспортные данные:</div>
    <div class='participantTableRes'>$pass</div>
</div>
<div class='participantTableInfo'>
    <div class='participantTableName'>Место работы:</div>
    <div class='participantTableRes'>$work_place</div>
</div>
<div class='participantTableInfo'>
    <div class='participantTableName'>Стаж работы:</div>
    <div class='participantTableRes'>$work_experience</div>
</div>
<div class='participantTableInfo'>
    <div class='participantTableName'>Образование:</div>
    <div class='participantTableRes'>$education</div>
</div>
<div class='participantTableInfo'>
    <div class='participantTableName'>Наименование учебного заведения:</div>
    <div class='participantTableRes'>$name_education</div>
</div>
<div class='participantTableInfo'>
    <div class='participantTableName'>Повышение квалификации:</div>
    <div class='participantTableRes'>$training</div>
</div>
<div class='participantTableInfo'>
    <div class='participantTableName'>Индекс места жительства:</div>
    <div class='participantTableRes'>$adress_index</div>
</div>
<div class='participantTableInfo'>
    <div class='participantTableName'>Адрес места жительства:</div>
    <div class='participantTableRes'>$home_adress</div>
</div>
";

echo $html;

?>
