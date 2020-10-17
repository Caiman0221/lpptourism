<?php

$access_token = $_POST['token'];

require($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/core/other.php');

//достаем email по access_token из таблицы пользователей
$query = "SELECT email FROM t_new_users where access_token = '$access_token';";
$row = mysqli_fetch_row(mysqliQuery($query))[0];
$email = base64_decode($row);

//достаем данные о координаторе из t_coords
$query = "SELECT subjectname, last_name, first_name FROM t_new_coords where email = '$email';";
$res = mysqliQuery($query);
$row = mysqli_fetch_row($res);
$RFsubNum = mb_convert_encoding($row[0],'utf-8', "cp1251");

$RFsubTranslit = rus2translit(RFsubjects($RFsubNum));
$lastName = mb_convert_encoding($row[1],'utf-8', "cp1251");
$lastName = rus2translit($lastName);
$firstName = mb_convert_encoding($row[2],'utf-8', "cp1251");
$firstName = rus2translit($firstName);

//Создаем  путь для папки
$catalogdir = $_SERVER['DOCUMENT_ROOT'] . "/files/normFiles/".$RFsubTranslit;
$timedate = date('H_i_d_m_Y');
//создаем каталог
if (!is_dir($catalogdir)) {
  mkdir($catalogdir, 0777);
}
//chmod($catalogdir, 0777);

if (file_exists($_FILES['normDoc']['tmp_name'])) {
    //Формируем ссылку
    $ext = substr($_FILES['normDoc']['name'], 1 + strrpos($_FILES['normDoc']['name'], ".")); //png, jpg ...
    $photoURL = $catalogdir . "/" . $lastName . "_" . $firstName . "_" . $timedate . "." . $ext;
    //Сохраняем на сервере
    if (is_uploaded_file($_FILES['normDoc']['tmp_name'])) {
        move_uploaded_file($_FILES['normDoc']['tmp_name'],"$photoURL"); //$photoURL exemple = /news/Chto_novogo_v_Ubuntu_20.04/1.png
        // работа с архивом
        //создаем архив (если его нет) и формируем путь для него
        $zip = new ZipArchive();
        $zip->open($_SERVER['DOCUMENT_ROOT'] . '/files/NormFiles.zip',ZipArchive::CREATE);
        $zip_dir = $_SERVER['DOCUMENT_ROOT'] . '/files/NormFiles.zip';
        //chmod($zip_dir,0777);
        $zip->addEmptyDir($RFsubTranslit);
        $dir_for_zip = str_ireplace($_SERVER['DOCUMENT_ROOT'] . '/files/normFiles/','',$photoURL);
        $zip->addFile($photoURL,$dir_for_zip);
        $zip->close();

        echo('Документ был успешно добавлен');
    }
} else {
    echo("документ отсутствует\n");
    echo($_FILES['normDoc']['error']);
}
?>
