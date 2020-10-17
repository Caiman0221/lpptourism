<?php

$_POST = json_decode(file_get_contents("php://input"), true);
$id = $_POST['id'];
$name = $_POST['name'];
$nomination =  $_POST['nomination'];
$questions_arr = $_POST['questions_arr'];

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');

//создаем таблицу для тестов
$query = "CREATE TABLE IF NOT EXISTS t_new_tests (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    questions_json TEXT
);";
mysqliQuery($query);


$questions_arr_json = json_encode($questions_arr,JSON_UNESCAPED_UNICODE);
$questions_arr_json = mb_convert_encoding($questions_arr_json,"utf-8");

//если запись не имеет id, то добавляем новую, иначе редактируем старую

if ($id == '') {
    $query = "INSERT INTO t_new_tests VALUES (NULL, '".mb_convert_encoding($name, "cp1251")."', '".iconv("" . mb_detect_encoding($questions_arr_json), "cp1251", $questions_arr_json)."');";
    mysqliQuery($query);
    echo("Тест был добавлен.");
} else {
    $query = "UPDATE t_new_tests SET name = '".mb_convert_encoding($name, "cp1251")."', questions_json = '".iconv("" . mb_detect_encoding($questions_arr_json), "cp1251", $questions_arr_json)."' WHERE id = '$id';";
    mysqliQuery($query);
    echo("Тест был успешно отредактирован.");
}

if ($nomination != '') {
    $query = "SELECT id FROM t_new_tests WHERE name = '".mb_convert_encoding($name, "cp1251")."';";
    $res = mysqli_fetch_row(mysqliQuery($query))[0];
    $query = "UPDATE t_new_nominations SET test = NULL WHERE test = '$res';";
    mysqliQuery($query);
    if ($nomination == '0') {
        $res = null;
    }
    $query = "UPDATE t_new_nominations SET test = '$res' WHERE id = '$nomination';";
    mysqliQuery($query);
    echo('Тест был успешно прикреплен к номинации');
}

?>
