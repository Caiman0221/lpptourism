<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');

$obj = (object) array();

$query = "SELECT html from t_new_pages_editor where name = 'InfoH2';";
$res = mysqliQuery($query);
if ($res == null) {
  $obj->InfoH2 = null;
} else {
  $obj->InfoH2 = mysqli_fetch_row($res)[0];
  $obj->InfoH2 = mb_convert_encoding($obj->InfoH2, "utf-8", "cp1251");
}

$query = "SELECT html from t_new_pages_editor where name = 'InfoH1';";
$res = mysqliQuery($query);
if ($res == null) {
  $obj->InfoH1 = null;
} else {
  $obj->InfoH1 = mysqli_fetch_row($res)[0];
  $obj->InfoH1 = mb_convert_encoding($obj->InfoH1, "utf-8", "cp1251");
}

$query = "SELECT html from t_new_pages_editor where name = 'InfoPhone';";
$res = mysqliQuery($query);
if ($res == null) {
  $obj->InfoPhone = null;
} else {
  $obj->InfoPhone = mysqli_fetch_row($res)[0];
  $obj->InfoPhone = mb_convert_encoding($obj->InfoPhone, "utf-8", "cp1251");
}

$query = "SELECT html from t_new_pages_editor where name = 'InfoEmail';";
$res = mysqliQuery($query);
if ($res == null) {
  $obj->InfoEmail = null;
} else {
  $obj->InfoEmail = mysqli_fetch_row($res)[0];
  $obj->InfoEmail = mb_convert_encoding($obj->InfoEmail, "utf-8", "cp1251");
}

$query = "SELECT html from t_new_pages_editor where name = 'PrivateTextPage';";
$res = mysqliQuery($query);
if ($res == null) {
  $obj->PrivateTextPage = null;
} else {
  $obj->PrivateTextPage = mysqli_fetch_row($res)[0];
  //$obj->PrivateTextPage = "hello";
  $obj->PrivateTextPage = mb_convert_encoding($obj->PrivateTextPage, "utf-8", "cp1251");
}

$query = "SELECT html from t_new_pages_editor where name = 'StartTestPage';";
$res = mysqliQuery($query);
if ($res == null) {
  $obj->StartTestPage = null;
} else {
  $obj->StartTestPage = mysqli_fetch_row($res)[0];
  $obj->StartTestPage = mb_convert_encoding($obj->StartTestPage, "utf-8", "cp1251");
}

$query = "SELECT html from t_new_pages_editor where name = 'CreativWorkPage';";
$res = mysqliQuery($query);
if ($res == null) {
  $obj->CreativWorkPage = null;
} else {
  $obj->CreativWorkPage = mysqli_fetch_row($res)[0];
  $obj->CreativWorkPage = mb_convert_encoding($obj->CreativWorkPage, "utf-8", "cp1251");
}


$query = "SELECT html from t_new_pages_editor where name = 'checkboxForCoordReg';";
$res = mysqliQuery($query);
if ($res == null) {
  $obj->checkboxForCoordReg = null;
} else {
  $obj->checkboxForCoordReg = mysqli_fetch_row($res)[0];
  $obj->checkboxForCoordReg = mb_convert_encoding($obj->checkboxForCoordReg, "utf-8", "cp1251");
}

$query = "SELECT html from t_new_pages_editor where name = 'LppLogo';";
$res = mysqliQuery($query);
if ($res == null) {
  $obj->logoURL_bd = '';
} else {
  $logoURL_bd = mysqli_fetch_row($res)[0];
  $logoURL_bd = mb_convert_encoding($logoURL_bd,"utf-8","cp1251");
  $obj->logoURL_bd = "http://lpptourism.ru/files/logos/$logoURL_bd";
}

$query = "SELECT html from t_new_pages_editor where name = 'PartnerLogo';";
$res = mysqliQuery($query);
if ($res == null) {
  $obj->PartnerLogoURL_bd = '';
} else {
  $PartnerLogoURL_bd = mysqli_fetch_row($res)[0];
  $PartnerLogoURL_bd = mb_convert_encoding($PartnerLogoURL_bd,"utf-8","cp1251");
  $obj->PartnerLogoURL_bd = "http://lpptourism.ru/files/logos/$PartnerLogoURL_bd";
}

echo (json_encode($obj))

?>
