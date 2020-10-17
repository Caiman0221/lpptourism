<?php

$_POST = json_decode(file_get_contents("php://input"), true);
$email = $_POST['part_email'];

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
$query = "UPDATE t_new_parts_works SET tests_json = NULL where email = '".mb_convert_encoding($email, "cp1251")."';";
mysqliQuery($query);

echo('Результаты тестирования были удалены')

?>
