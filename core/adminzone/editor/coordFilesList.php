<?php

$dir = $_SERVER['DOCUMENT_ROOT'] . "/files/docsForUsers/coords";
for ($i = 2; $i < count(scandir($dir)); $i++) {
    $file_name = scandir($dir)[$i];
    $file_link = "http://lpptourism.ru/files/docsForUsers/coords/$file_name";
    $html .= "<div>
                <div class='coordsDocsContainerLink'><a href='$file_link'>$file_name</a></div>
                <div class='coordsDocsContainerButton'><button value='$file_link' onclick='dellCoordButtonClick(this)' class='dellDocButton'>X</button></div>
            </div>";
}
echo ($html);
?>
