<?php

$_POST = json_decode(file_get_contents("php://input"), true);
$access_token = $_POST['access_token'];
$link = $_POST['link'];

$obj = (object) array();

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
$query = "SELECT email from t_new_users where access_token = '$access_token';";
$email = base64_decode(mysqli_fetch_row(mysqliQuery($query))[0]);

$obj->creative_task_link = $link;
$obj_json = json_encode($obj);

$query = "UPDATE t_new_parts_works SET res_of_exp ='".mb_convert_encoding($obj_json,"cp1251")."' where email = '".mb_convert_encoding($email,"cp1251")."';";
mysqliQuery($query);

echo('Работа была добавлена');

?>
