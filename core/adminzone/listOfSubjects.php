<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/other.php');

$subjectsList = [];
for ($i = 1; $i <= 85; $i++) {
    $subjectname = RFsubjects($i);
    $subjectsList[] = $subjectname;
}
echo(json_encode($subjectsList));

?>
