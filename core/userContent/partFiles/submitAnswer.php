<?php

//забираем значения от участника
$_POST = json_decode(file_get_contents("php://input"), true);
$access_token = $_POST['access_token'];
$part_answers_arr = $_POST['part_answers_arr'];
$question_part_num = $_POST['question_num'];

//подключаем необходимые модули
require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/core/other.php');

//достаем email участника из БД пользователей
$query = "SELECT email from t_new_users where access_token = '$access_token';";
$email = base64_decode(mysqli_fetch_row(mysqliQuery($query))[0]);
$email = mb_convert_encoding($email,"utf-8","cp1251");

//по email нахидим результаты тестирования участника
$query = "SELECT tests_json from t_new_parts_works where email = '".mb_convert_encoding($email,"cp1251")."';";
$part_test_arr_json = mysqli_fetch_row(mysqliQuery($query))[0];
$part_test_arr_json = mb_convert_encoding($part_test_arr_json,"utf-8","cp1251");
$part_test_arr = json_decode($part_test_arr_json);

//цикл для нахождения нужного вопроса
for ($i = 0; $i < count($part_test_arr); $i++) {
    $part_test_obj = $part_test_arr[$i];

    if ($question_part_num == $part_test_obj->question_num) {


        $answer_time = unixRealTime();

        /*
        $if_time = $part_test_obj->start_question_time + $part_test_obj->time + 5 - $answer_time;
        if ($if_time < 0) {
            for ($n = 0; $n < count($part_answers_arr); $n++) {
                $part_answers_arr[$n] = 'time';
            }
        }
        */
        $part_test_obj->stop_question_time = $answer_time;
        $part_test_obj->part_answer = $part_answers_arr;
        $part_test_arr[$i] = $part_test_obj;
    break;
    }
}

$part_test_arr_json = json_encode($part_test_arr,JSON_UNESCAPED_UNICODE);
$query = "UPDATE t_new_parts_works set tests_json = '".mb_convert_encoding($part_test_arr_json,"cp1251")."' where email = '".mb_convert_encoding($email,"cp1251")."';";
mysqliQuery($query);

?>
