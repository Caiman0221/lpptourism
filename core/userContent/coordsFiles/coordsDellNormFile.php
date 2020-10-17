<?php

$_POST = json_decode(file_get_contents("php://input"), true);

$href = $_POST['href'];

$dir = str_ireplace('http://lpptourism.ru/',$_SERVER['DOCUMENT_ROOT'] . '/',$href);



//chmod($dir,0777);
if(unlink($dir) == false) {
    echo("Возникла ошибка, файл не был удален");
    return;
}

$zip_dir = $_SERVER['DOCUMENT_ROOT'] . "/files/NormFiles.zip";
//chmod($zip_dir,0777);
$zip_dell_dir = str_ireplace($_SERVER['DOCUMENT_ROOT'] . '/files/normFiles/','',$dir);
$zip = new ZipArchive;
if ($zip->open($zip_dir) === true) {
    $zip->deleteName($zip_dell_dir);
    $zip->close();
    echo("Файл был удален");
} else {
    echo ("Возникла ошибка, файл не был удален");
    return;
}

?>
