<?php

$zip = new ZipArchive();
$zip->open($_SERVER['DOCUMENT_ROOT'] . '/files/NormFiles.zip',ZipArchive::CREATE);
$zip_dir = $_SERVER['DOCUMENT_ROOT'] . '/files/NormFiles.zip';
//chmod($zip_dir,0777);
$direct = $_SERVER['DOCUMENT_ROOT'] . "/files/normFiles";
for ($i=2; $i < count(scandir($direct)); $i++) {
    $dir = scandir($direct)[$i];
    $zip->addEmptyDir($dir);
    $fulldir = $direct."/$dir";
    //chmod($fulldir, 0777);
    $ext = pathinfo($fulldir)['extension'];
    if ($ext == '') {
        for ($n=2; $n<count(scandir($fulldir));$n++) {
            $dir_down = scandir($fulldir)[$n];
            $dir_for_zip = $dir . "/$dir_down";
            $dir_down_full = $fulldir . "/$dir_down";
            //chmod($dir_down_full, 0777);
            $zip->addFile("/$dir_down_full",$dir_for_zip);
        }
    }
    $zip->close();
}

echo ('Архив собран');
?>
