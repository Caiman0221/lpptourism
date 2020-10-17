<?php

$zip = new ZipArchive();
$zip->open($_SERVER['DOCUMENT_ROOT'] . '/files/NormFiles.zip',ZipArchive::CREATE);
$zip_dir = $_SERVER['DOCUMENT_ROOT'] . '/files/NormFiles.zip';
$direct = $_SERVER['DOCUMENT_ROOT'] . "/files/normFiles";
for ($i=2; $i < count(scandir($direct)); $i++) {
    $dir = scandir($direct)[$i];
    $fulldir = $direct."/$dir";
    $ext = pathinfo($fulldir)['extension'];
    if ($ext == '') {
        for ($n=2; $n<count(scandir($fulldir));$n++) {
            $dir_down = scandir($fulldir)[$n];
            $dir_for_zip = $dir . "/$dir_down";
            $dir_down_full = $fulldir . "/$dir_down";
            $zip->addFile("$dir_down_full",$dir_for_zip);
        }
    } else {
        $zip->addFile($fulldir,$dir);
    }
}
echo('Архив был успешно создан');
$zip->close();

?>
