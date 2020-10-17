<?php

$_POST = json_decode(file_get_contents("php://input"), true);
$access_token = $_POST['access_token'];
$part_id = $_POST['part_id'];
$K1 = $_POST['K1'];
$K2 = $_POST['K2'];
$K3 = $_POST['K3'];
$K4 = $_POST['K4'];
$K5 = $_POST['K5'];

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
//Узнаем email эксперта из БД t_new_users
$query = "SELECT email FROM t_new_users where access_token = '$access_token';";
$email = base64_decode(mysqli_fetch_row(mysqliQuery($query))[0]);

//Вытаскиваем объект с проставленными результатами данного эксперта
$query = "SELECT results from t_new_experts where email = '".mb_convert_encoding($email,"cp1251")."';";
$results_obj = mysqliQuery($query);
$results_obj = mysqli_fetch_row($results_obj)[0];
$results_obj = mb_convert_encoding($results_obj,'utf-8','cp1251');
$results_obj = json_decode($results_obj);

//Создаем новую запись с результатами участника у данного эксперта (ключ - id участника, k1..k5 результаты у данного эксперта)
$results_obj->$part_id = [$K1,$K2,$K3,$K4,$K5];
$results_obj_json = json_encode($results_obj);

//Обновляем запись с результатами у данного эксперта
$query = "UPDATE t_new_experts set results = '$results_obj_json' where email = '".mb_convert_encoding($email,"cp1251")."';";
mysqliQuery($query);

//Достаем email участника из таблицы t_new_parts
$query = "SELECT email from t_new_parts where id = '$part_id';";
$part_email = mysqli_fetch_row(mysqliQuery($query))[0];
$part_email = mb_convert_encoding($part_email,"utf-8","cp1251");

//Достаем результаты данного участника из таблицы t_new_parts_works
$query = "SELECT res_of_exp from t_new_parts_works where email = '".mb_convert_encoding($part_email,"cp1251")."';";
$res_of_exp_obj = mysqliQuery($query);
$res_of_exp_obj = mysqli_fetch_row($res_of_exp_obj)[0];
$res_of_exp_obj = mb_convert_encoding($res_of_exp_obj, 'utf-8', 'cp1251');
$res_of_exp_obj = json_decode($res_of_exp_obj);

//Считываем результаты всех экспертов
$query = "SELECT results from t_new_experts";
$res = mysqliQuery($query);
$rows = mysqli_num_rows($res);
//Создаем массив k1..k5 для конкретного участника
$part_result_arr = [];
$n = 0;
for ($i = 0; $i < $rows; $i++) {
    $row = json_decode(mysqli_fetch_row($res)[0]);
    //Проверяем у каждого эксперта если ли результаты по данному участнику и считаем ср арифметическое
    $res_arr = $row->$part_id;
    if ($res_arr != null) {
        $n++;
    }
    $part_result_arr[0] = ($part_result_arr[0] + $res_arr[0]);
    $part_result_arr[1] = ($part_result_arr[1] + $res_arr[1]);
    $part_result_arr[2] = ($part_result_arr[2] + $res_arr[2]);
    $part_result_arr[3] = ($part_result_arr[3] + $res_arr[3]);
    $part_result_arr[4] = ($part_result_arr[4] + $res_arr[4]);
}
$part_result_arr[0] = round(($part_result_arr[0] / $n),2);
$part_result_arr[1] = round(($part_result_arr[1] / $n),2);
$part_result_arr[2] = round(($part_result_arr[2] / $n),2);
$part_result_arr[3] = round(($part_result_arr[3] / $n),2);
$part_result_arr[4] = round(($part_result_arr[4] / $n),2);

$res_of_exp_obj->results = $part_result_arr;
$res_of_exp_obj = json_encode($res_of_exp_obj);
echo($res_of_exp_obj);

$query = "UPDATE t_new_parts_works set res_of_exp = '".mb_convert_encoding($res_of_exp_obj,"cp1251")."' where email = '".mb_convert_encoding($part_email,"cp1251")."';";
mysqliQuery($query);
?>
