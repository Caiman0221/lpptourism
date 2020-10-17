<?php

$coord_access_token = $_POST['access_token'];
$Email = $_POST['Email']; //Email
$Name = $_POST['Name']; //Name
$LastName = $_POST['LastName']; //LastName
$ThirdName = $_POST['ThirdName'];
$MobilePhone = $_POST['MobilePhone']; //MobilePhone
$WorkPhone = $_POST['WorkPhone']; //WorkPhone
$Nomination = $_POST['Nomination']; //Nomination
$Pass = $_POST['Pass']; //Pass
$WorkPlace = $_POST['WorkPlace']; //WorkPlace
$WorkExperience = $_POST['WorkExperience']; //WorkExperience
$Education = $_POST['Education']; //Education
$NameEducation = $_POST['NameEducation']; //NameEducation
$Training = $_POST['Training']; //Training
$Index = $_POST['Index']; //Index
$HomeAdress = $_POST['HomeAdress']; //HomeAdress
$EmployerPhone = $_POST['EmployerPhone']; //EmployerPhone
$WorkEmail = $_POST['WorkEmail']; //WorkEmail

require_once($_SERVER['DOCUMENT_ROOT'] . "/core/mysqli.php");
$query = "SELECT email from t_new_users where access_token = '$coord_access_token';";
$coordEmail = base64_decode(mysqli_fetch_row(mysqliQuery($query))[0]);
$coordEmail = mb_convert_encoding($coordEmail,"utf-8","cp1251");

//узнаем субьект РФ координатора
$query = "SELECT subjectname from t_new_coords where email = '".mb_convert_encoding($coordEmail, "cp1251")."';";
require_once($_SERVER['DOCUMENT_ROOT'] . '/core/other.php');
$RFSubRus = RFsubjects(mysqli_fetch_row(mysqliQuery($query))[0]);

//Проверяем нет ли участника с таким email
$Email64 = base64_encode($Email);
$query = "SELECT * from t_new_users where email = '$Email64';";
if (mysqli_fetch_row(mysqliQuery($query))[0] != '') {
    $obj = 'Пользователь с таким Email уже существует';
    echo $obj;
    return;
}

//БД для участников
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

$query = "SELECT * from t_new_parts where email = '$Email';";
if (mysqli_fetch_row(mysqliQuery($query))[0] != '') {
    $obj = "Пользователь с таким email уже существует";
    echo $obj;
    return;
}


$query = "SELECT last_name, first_name from t_new_coords where email = '$coordEmail'";
$row = mysqli_fetch_row(mysqliQuery($query));
$coordName = mb_convert_encoding($row[0],"utf-8","cp1251") . ' ' . mb_convert_encoding($row[1],"utf-8","cp1251");

$query = "INSERT INTO t_new_users VALUES (
  null,
  '$Email64',
  null,
  null,
  null,
  now(),
  now(),
  'part',
  null
);";
mysqliQuery($query);

$query = "INSERT INTO t_new_parts VALUES (
    null,
    '".mb_convert_encoding($Email,"cp1251")."',
    '".mb_convert_encoding($Name,"cp1251")."',
    '".mb_convert_encoding($LastName,"cp1251")."',
    '".mb_convert_encoding($MobilePhone,"cp1251")."',
    '".mb_convert_encoding($WorkPhone,"cp1251")."',
    '".mb_convert_encoding($Nomination,"cp1251")."',
    '".mb_convert_encoding($Pass,"cp1251")."',
    '".mb_convert_encoding($WorkPlace,"cp1251")."',
    '".mb_convert_encoding($WorkExperience,"cp1251")."',
    '".mb_convert_encoding($Education,"cp1251")."',
    '".mb_convert_encoding($NameEducation,"cp1251")."',
    '".mb_convert_encoding($Training,"cp1251")."',
    '".mb_convert_encoding($Index,"cp1251")."',
    '".mb_convert_encoding($HomeAdress,"cp1251")."',
    '".mb_convert_encoding($EmployerPhone,"cp1251")."',
    '".mb_convert_encoding($WorkEmail,"cp1251")."',
    '".mb_convert_encoding($RFSubRus,"cp1251")."',
    '".mb_convert_encoding($coordName,"cp1251")."',
    now(),
    'disabled',
    '".mb_convert_encoding($ThirdName,"cp1251")."'
);";
mysqliQuery($query);

$query = "CREATE TABLE IF NOT EXISTS t_new_parts_works (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255),
    tests_json TEXT,
    res_of_exp TEXT
);";
mysqliQuery($query);

$query = "INSERT INTO t_new_parts_works VALUES (
    null,
    '".mb_convert_encoding($Email,"cp1251")."',
    null,
    null
);";
mysqliQuery($query);

$query = "SELECT id from t_new_parts where email = '".mb_convert_encoding($Email,"cp1251")."';";
$partID = mysqli_fetch_row(mysqliQuery($query))[0];
$catalogdir = $_SERVER['DOCUMENT_ROOT'] . "/files/parts/".$partID; // /files/parts/1
//создаем каталог
if (!is_dir($catalogdir)) {
  mkdir($catalogdir, 0777);
}
//chmod($catalogdir, 0777);

if (file_exists($_FILES['MemberProfile']['tmp_name'])) {
    //Формируем ссылку
    $ext = substr($_FILES['MemberProfile']['name'], 1 + strrpos($_FILES['MemberProfile']['name'], ".")); //png, jpg ...
    $ProfileURL = $catalogdir . "/Анкета пользователя." . $ext; // /files/parts/1/Анкета пользователя.doc
    //Сохраняем на сервере
    if (is_uploaded_file($_FILES['MemberProfile']['tmp_name'])) {
        move_uploaded_file($_FILES['MemberProfile']['tmp_name'],"$ProfileURL"); //$photoURL exemple = /news/Chto_novogo_v_Ubuntu_20.04/1.png
    }
}

$url = $catalogdir . "/";   //  /files/parts/1/

if (file_exists($_FILES['ctuOtherDocs1']['tmp_name'])) {
    $url1 = $url . $_FILES['ctuOtherDocs1']['name'];   //  /files/parts/1/имя файла.doc
    move_uploaded_file($_FILES['ctuOtherDocs1']['tmp_name'],$url1);
}
if (file_exists($_FILES['ctuOtherDocs2']['tmp_name'])) {
    $url2 = $url . $_FILES['ctuOtherDocs2']['name'];
    move_uploaded_file($_FILES['ctuOtherDocs2']['tmp_name'],$url2);
}
if (file_exists($_FILES['ctuOtherDocs3']['tmp_name'])) {
    $url3 = $url . $_FILES['ctuOtherDocs3']['name'];
    move_uploaded_file($_FILES['ctuOtherDocs3']['tmp_name'],$url3);
}
if (file_exists($_FILES['ctuOtherDocs4']['tmp_name'])) {
    $url4 = $url . $_FILES['ctuOtherDocs4']['name'];
    move_uploaded_file($_FILES['ctuOtherDocs4']['tmp_name'],$url4);
}
if (file_exists($_FILES['ctuOtherDocs5']['tmp_name'])) {
    $url5 = $url . $_FILES['ctuOtherDocs5']['name'];
    move_uploaded_file($_FILES['ctuOtherDocs5']['tmp_name'],$url5);
}

$zip = new ZipArchive();
$zip->open($_SERVER['DOCUMENT_ROOT'] . "/files/parts/$partID/docs.zip",ZipArchive::CREATE); // /files/parts
$zip_dir = $_SERVER['DOCUMENT_ROOT'] . "/files/parts/$partID/docs.zip";
//chmod($zip_dir,0777);
$dir_for_zip = str_ireplace("$url",'',$ProfileURL);  // /files/parts/1    Анкета пользователя.doc
$zip->addFile($ProfileURL,$dir_for_zip);
if ($url1 != '') {
    $dir_for_zip = str_ireplace("$url",'',$url1);
    $zip->addFile($url1,$dir_for_zip);
}
if ($url2 != '') {
    $dir_for_zip = str_ireplace("$url",'',$url2);
    $zip->addFile($url2,$dir_for_zip);
}
if ($url3 != '') {
    $dir_for_zip = str_ireplace("$url",'',$url3);
    $zip->addFile($url3,$dir_for_zip);
}
if ($url4 != '') {
    $dir_for_zip = str_ireplace("$url",'',$url4);
    $zip->addFile($url4,$dir_for_zip);
}
if ($url5 != '') {
    $dir_for_zip = str_ireplace("$url",'',$url5);
    $zip->addFile($url5,$dir_for_zip);
}
$zip->close();

echo $obj = "true";

?>
