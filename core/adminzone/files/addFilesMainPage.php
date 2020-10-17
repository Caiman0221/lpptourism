<?php

$catalogdir = $_SERVER['DOCUMENT_ROOT'] . '/files/filesformainpage';
//chmod($catalogdir,0777);

if (file_exists($_FILES['newfile']['tmp_name'])) {
    $url = $catalogdir . "/" . $_FILES['newfile']['name'];
    if (is_uploaded_file($_FILES['newfile']['tmp_name'])) {
        if(move_uploaded_file($_FILES['newfile']['tmp_name'],$url) != false) {
            echo ("Файл был успешно добавлен");
        } else {
            echo("Файл не был добавен с кодом ошибки:" . $_FILES['newfile']['error']);
        }
    }
}

?>
