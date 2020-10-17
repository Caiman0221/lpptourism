<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
$_POST = json_decode(file_get_contents("php://input"), true);
$access_token = $_POST['access_token'];

$query = "SELECT email from t_new_users where access_token = '$access_token';";
$email = base64_decode(mysqli_fetch_row(mysqliQuery($query))[0]);

$query = "SELECT tests_json from t_new_parts_works where email = '".mb_convert_encoding($email,"cp1251")."';";
$tests_json = mysqliQuery($query);
$tests_json = mysqli_fetch_row($tests_json)[0];
$tests_json = mb_convert_encoding($tests_json, "utf-8", "cp1251");
$test = json_decode($tests_json);

for ($i = 0; $i < count($test); $i++) {
    $obj = $test[$i];
    if (property_exists($obj,'part_answer') == false) {
        $start_question_time = $obj->start_question_time;
        break;
    }
}

echo $start_question_time;

?>
