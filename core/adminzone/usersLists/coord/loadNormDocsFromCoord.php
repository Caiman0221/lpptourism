<?php

$zip_dir = $_SERVER['DOCUMENT_ROOT'] . "/files/normFiles/CoordNormfiles.zip";

header('Content-Type: application/zip');
header('Content-disposition: attachment; filename='.$zip_dir);
header("Content-Length: ".filesize($zip_dir)."");

readfile($zip_dir);

unlink($zip_dir);

?>
