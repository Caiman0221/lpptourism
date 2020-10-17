<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
$query = "SELECT html from t_new_pages_editor where name = 'InfoH2';";
$res = mysqliQuery($query);
if ($res == null) {
    $InfoH2 = '';
} else {
    $InfoH2 = mysqli_fetch_row($res)[0];
    $InfoH2 = mb_convert_encoding($InfoH2,"utf-8","cp1251");
}

$query = "SELECT html from t_new_pages_editor where name = 'InfoH1';";
$res = mysqliQuery($query);
if ($res == null) {
    $InfoH1 = '';
} else {
    $InfoH1 = mysqli_fetch_row($res)[0];
    $InfoH1 = mb_convert_encoding($InfoH1,"utf-8","cp1251");
}

$query = "SELECT html from t_new_pages_editor where name = 'InfoPhone';";
$res = mysqliQuery($query);
if ($res == null) {
    $InfoPhone = '';
} else {
    $InfoPhone = mysqli_fetch_row($res)[0];
    $InfoPhone = mb_convert_encoding($InfoPhone,"utf-8","cp1251");
}

$query = "SELECT html from t_new_pages_editor where name = 'InfoEmail';";
$res = mysqliQuery($query);
if ($res == null) {
    $InfoEmail = '';
} else {
    $InfoEmail = mysqli_fetch_row($res)[0];
    $InfoEmail = mb_convert_encoding($InfoEmail,"utf-8","cp1251");
}

$query = "SELECT html from t_new_pages_editor where name = 'LppLogo';";
$res = mysqliQuery($query);
if ($res == null) {
  $logoURL_bd = '';
} else {
  $logoURL_bd = mysqli_fetch_row($res)[0];
  $logoURL_bd = mb_convert_encoding($logoURL_bd,"utf-8","cp1251");
  $logoURL_bd = "http://lpptourism.ru/files/logos/$logoURL_bd";
}

$query = "SELECT html from t_new_pages_editor where name = 'PartnerLogo';";
$res = mysqliQuery($query);
if ($res == null) {
  $PartnerLogoURL_bd = '';
} else {
  $PartnerLogoURL_bd = mysqli_fetch_row($res)[0];
  $PartnerLogoURL_bd = mb_convert_encoding($PartnerLogoURL_bd,"utf-8","cp1251");
  $PartnerLogoURL_bd = "http://lpptourism.ru/files/logos/$PartnerLogoURL_bd";
}

$html = "<div class='inlineContainer logoContainer'>
            <a href='http://lpptourism.ru'><img src='$logoURL_bd' alt=''></a>
            </div>
            <div class='inlineContainer aboutInfo'>
            <h2>$InfoH2</h2>
            <h1>$InfoH1</h1>
            </div>
            <div class='inlineContainer contacts'>
            <div class='contactsContainer'>
                <div>
                    Оргкомитет:<br>
                    <a href='tel:$InfoPhone'>$InfoPhone</a><br>
                    <a href='mailto:$InfoEmail'>$InfoEmail</a><br>
                </div>
            </div>
            </div>
            <div class='inlineContainer tourismLogo'>
            <img src='$PartnerLogoURL_bd'>
            </div>";

$obj = (object) array();
$obj->headerInfoEdited = $html;

$html = "<div class='inlineContainer logoContainer'>
            <img src='http://lpptourism.ru/files/logos/lpit_logo_light.png'>
            </div>
            <div class='inlineContainer contacts'>
            <div class='contactsContainer'>
                <div>
                    Оргкомитет:<br>
                    <a href='tel:$InfoPhone'>$InfoPhone</a><br>
                    <a href='mailto:$InfoEmail'>$InfoEmail</a><br>
                </div>
            </div>
            </div>";

$obj->blackHeaderInfoEdited = $html;

echo (json_encode($obj,JSON_UNESCAPED_UNICODE));
?>
