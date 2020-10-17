<?php

$dir = $_SERVER['DOCUMENT_ROOT'] . "/../../../../var/log/";
//$dir = "./";
echo($dir);

for ($i = 0; $i < count(scandir($dir)); $i++) {
  echo(scandir($dir)[$i]."<br />");
};

$fd = fopen($_SERVER['DOCUMENT_ROOT'] . "/../../../../var/log/lpptourism.ru.error_log", 'r') or die("не удалось открыть файл");
while(!feof($fd))
{
    $str = htmlentities(fgets($fd));
    echo $str."<br />";
}
fclose($fd);
?>
