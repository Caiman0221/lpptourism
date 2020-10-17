<?php

$_POST = json_decode(file_get_contents("php://input"), true);

$file_link = $_POST['link'];
$file_link = str_ireplace('http://lpptourism.ru',$_SERVER['DOCUMENT_ROOT'],$file_link);
if ($file_link != '') {
  //chmod($file_link,0777);
  unlink($file_link);
}
?>
