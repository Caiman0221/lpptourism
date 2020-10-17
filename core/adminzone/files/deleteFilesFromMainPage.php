<?php

$_POST = json_decode(file_get_contents("php://input"), true);

$link = $_POST['link'];
$link = str_ireplace('http://lpptourism.ru',$_SERVER['DOCUMENT_ROOT'],$link);
if ($link != '') {
  //chmod($link, 0777);
  unlink($link);
  echo('deleted');
}
?>
