<?php

$dir = $_SERVER['DOCUMENT_ROOT'] . "/files/docsForUsers/coords/";
if (file_exists($_FILES['newCoordDoc']['tmp_name'])) {
    $file_dir = $dir . $_FILES['newCoordDoc']['name'];
    if (is_uploaded_file($_FILES['newCoordDoc']['tmp_name'])) {
        move_uploaded_file($_FILES['newCoordDoc']['tmp_name'],$file_dir); // exemple = /news/Chto_novogo_v_Ubuntu_20.04/1.png
    }
}

echo('файл добавлен');


?>
