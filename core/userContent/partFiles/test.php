<?php

$_POST = json_decode(file_get_contents("php://input"), true);
$access_token = $_POST['access_token'];

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
$query = "SELECT email from t_new_users where access_token = '$access_token';";
$email = base64_decode(mysqli_fetch_row(mysqliQuery($query))[0]);

//Узнаем номинацию;
$query = "SELECT nomination from t_new_parts where email = '".mb_convert_encoding($email,"cp1251")."';";
$nomination_id = mysqli_fetch_row(mysqliQuery($query))[0];

//Узнаем id теста
$query = "SELECT test from t_new_nominations where id = '$nomination_id';";
$test_id = mysqli_fetch_row(mysqliQuery($query))[0];

//Копируем тест из общей базы в БД пользователя
$query = "SELECT questions_json from t_new_tests where id = '$test_id';"; // Копируем тест из БД тестов
$test_json = mysqli_fetch_row(mysqliQuery($query))[0];

$test_json = mb_convert_encoding($test_json,"utf-8","cp1251");

$test = json_decode($test_json); //Декодируем из json

//Перемешиваем тест для пользователя
for ($i = 0; $i < count($test); $i++) {
    $question_obj = $test[$i];
    $question_obj->question_num = $i;
    $answers_pos = [];
    for ($n = 0; $n < count($question_obj->answers); $n++) {
        $answers_pos[$n] = $n;
    }
    shuffle($answers_pos);
    $question_obj->answers_pos = $answers_pos;
    $test[$i]=$question_obj;
}
shuffle($test);


$test_json = json_encode($test,JSON_UNESCAPED_UNICODE);

$test_json = mb_convert_encoding($test_json, "cp1251", "utf-8");
$email = mb_convert_encoding($email, "cp1251", "utf-8");

$query = "UPDATE `t_new_parts_works` SET `tests_json` = '$test_json' WHERE `email` = '$email';"; //stavroolesia@mail.ru
mysqliQuery($query);

?>
