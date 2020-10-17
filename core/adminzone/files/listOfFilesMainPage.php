<?php

$dir = $_SERVER['DOCUMENT_ROOT'] . "/files/filesformainpage";
$link = "http://lpptourism.ru/files/filesformainpage/";
$obj = (object) array();
$obj->links = [];
$obj->names = [];
$n = 0;
for ($i=2; $i < count(scandir($dir)); $i++) {
    $filedir = $dir . scandir($dir)[$i];
    //chmod($filedir, 0777);
    $obj->links[$n] = $link . scandir($dir)[$i];
    $obj->names[$n] = scandir($dir)[$i];
    $n++;
}
echo(json_encode($obj));

?>
