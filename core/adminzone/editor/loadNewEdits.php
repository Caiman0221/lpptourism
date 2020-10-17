<?php

$InfoH2 = $_POST['InfoH2'];
$InfoH1 = $_POST['InfoH1'];
$InfoPhone = $_POST['InfoPhone'];
$InfoEmail = $_POST['InfoEmail'];
$PrivateTextPage = $_POST['PrivateTextPage'];
$StartTestPage = $_POST['StartTestPage'];
$CreativWorkPage = $_POST['CreativWorkPage'];
$checkbox = $_POST['checkbox'];

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');

if (file_exists($_FILES['LppLogo']['tmp_name'])) {
    $logoURL = $_SERVER['DOCUMENT_ROOT'] . "/files/logos/" . $_FILES['LppLogo']['name'];
    $logoURL_bd = $_FILES['LppLogo']['name'];
    if (is_uploaded_file($_FILES['LppLogo']['tmp_name'])) {
        move_uploaded_file($_FILES['LppLogo']['tmp_name'],$logoURL);
    }
}

if (file_exists($_FILES['PartnerLogo']['tmp_name'])) {
    $PartnerLogoURL = $_SERVER['DOCUMENT_ROOT'] . "/files/logos/" . $_FILES['PartnerLogo']['name'];
    $PartnerLogoURL_bd = $_FILES['PartnerLogo']['name'];
    if (is_uploaded_file($_FILES['PartnerLogo']['tmp_name'])) {
        move_uploaded_file($_FILES['PartnerLogo']['tmp_name'],$logoURL);
    }
}

$query = "CREATE TABLE IF NOT EXISTS t_new_pages_editor (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name TEXT,
    html TEXT
);";
mysqliQuery($query);


$query = "SELECT * from t_new_pages_editor where name = 'InfoH2';";
if (mysqli_fetch_row(mysqliQuery($query))[0] == null) {
    $query = "INSERT INTO t_new_pages_editor VALUES (null,'InfoH2','".mb_convert_encoding($InfoH2, "cp1251")."');";
} else {
    $query = "UPDATE t_new_pages_editor set html = '".mb_convert_encoding($InfoH2, "cp1251")."' where name = 'InfoH2';";
}
mysqliQuery($query);

$query = "SELECT * from t_new_pages_editor where name = 'InfoH1';";
if (mysqli_fetch_row(mysqliQuery($query))[0] == null) {
    $query = "INSERT INTO t_new_pages_editor VALUES (null,'InfoH1','".mb_convert_encoding($InfoH1, "cp1251")."');";
} else {
    $query = "UPDATE t_new_pages_editor SET html = '".mb_convert_encoding($InfoH1, "cp1251")."' WHERE name = 'InfoH1';";
}
mysqliQuery($query);

$query = "SELECT * from t_new_pages_editor where name = 'InfoPhone';";
if (mysqli_fetch_row(mysqliQuery($query))[0] == null) {
    $query = "INSERT INTO t_new_pages_editor VALUES (null,'InfoPhone','".mb_convert_encoding($InfoPhone, "cp1251")."');";
} else {
    $query = "UPDATE t_new_pages_editor SET html = '".mb_convert_encoding($InfoPhone, "cp1251")."' WHERE name = 'InfoPhone';";
}
mysqliQuery($query);

$query = "SELECT * from t_new_pages_editor where name = 'InfoEmail';";
if (mysqli_fetch_row(mysqliQuery($query))[0] == null) {
    $query = "INSERT INTO t_new_pages_editor VALUES (null,'InfoEmail','".mb_convert_encoding($InfoEmail, "cp1251")."');";
} else {
    $query = "UPDATE t_new_pages_editor SET html = '".mb_convert_encoding($InfoEmail, "cp1251")."' WHERE name = 'InfoEmail';";
}
mysqliQuery($query);

$query = "SELECT * from t_new_pages_editor where name = 'PrivateTextPage';";
if (mysqli_fetch_row(mysqliQuery($query))[0] == null) {
    $query = "INSERT INTO t_new_pages_editor VALUES (null,'PrivateTextPage','".mb_convert_encoding($PrivateTextPage, "cp1251")."');";
} else {
    $query = "UPDATE t_new_pages_editor SET html = '".mb_convert_encoding($PrivateTextPage, "cp1251")."' WHERE name = 'PrivateTextPage';";
}
mysqliQuery($query);

$query = "SELECT * from t_new_pages_editor where name = 'StartTestPage';";
if (mysqli_fetch_row(mysqliQuery($query))[0] == null) {
    $query = "INSERT INTO t_new_pages_editor VALUES (null,'StartTestPage','".mb_convert_encoding($StartTestPage, "cp1251")."');";
} else {
    $query = "UPDATE t_new_pages_editor SET html = '".mb_convert_encoding($StartTestPage, "cp1251")."' WHERE name = 'StartTestPage';";
}
mysqliQuery($query);

$query = "SELECT * from t_new_pages_editor where name = 'CreativWorkPage';";
if (mysqli_fetch_row(mysqliQuery($query))[0] == null) {
    $query = "INSERT INTO t_new_pages_editor VALUES (null,'CreativWorkPage','".mb_convert_encoding($CreativWorkPage, "cp1251")."');";
} else {
    $query = "UPDATE t_new_pages_editor SET html = '".mb_convert_encoding($CreativWorkPage, "cp1251")."' WHERE name = 'CreativWorkPage';";
}
mysqliQuery($query);

if ($logoURL_bd != '') {
    $query = "SELECT * from t_new_pages_editor where name = 'LppLogo';";
    if (mysqli_fetch_row(mysqliQuery($query))[0] == null) {
        $query = "INSERT INTO t_new_pages_editor VALUES (null,'LppLogo','".mb_convert_encoding($logoURL_bd, "cp1251")."');";
    } else {
        $query = "UPDATE t_new_pages_editor SET html = '".mb_convert_encoding($logoURL_bd, "cp1251")."' WHERE name = 'LppLogo';";
    }
    mysqliQuery($query);
}

if ($PartnerLogoURL_bd != '') {
    $query = "SELECT * from t_new_pages_editor where name = 'PartnerLogo';";
    if (mysqli_fetch_row(mysqliQuery($query))[0] == null) {
        $query = "INSERT INTO t_new_pages_editor VALUES (null,'PartnerLogo','".mb_convert_encoding($PartnerLogoURL_bd, "cp1251")."');";
    } else {
        $query = "UPDATE t_new_pages_editor SET html = '".mb_convert_encoding($PartnerLogoURL_bd, "cp1251")."' WHERE name = 'PartnerLogo';";
    }
    mysqliQuery($query);
}

if ($checkbox != '') {
    $query = "SELECT * from t_new_pages_editor where name = 'checkboxForCoordReg';";
    if (mysqli_fetch_row(mysqliQuery($query))[0] == null) {
        $query = "INSERT INTO t_new_pages_editor VALUES (null,'checkboxForCoordReg','" . mb_convert_encoding($checkbox,'utf-8', "cp1251") . "');";
    } else {
        $query = "UPDATE t_new_pages_editor SET html = '" . mb_convert_encoding($checkbox, "cp1251") . "' WHERE name = 'checkboxForCoordReg';";
    }
    mysqliQuery($query);
}
echo('Изменения сохранены');

?>
