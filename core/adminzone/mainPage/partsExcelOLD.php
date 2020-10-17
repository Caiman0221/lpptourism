<?php
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=Список участников.xls");  //File name extension was wrong
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
/*
header('Content-Type: application/ms-excel; format=attachment;');
header('Content-Disposition: attachment; filename=Список участников.xls');

header('Expires: Mon, 18 Jul 1998 01:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');
*/
?>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/core/other.php');
$query = "SELECT * from t_new_parts;";
$parts_res = mysqliQuery($query);
if ($parts_res == false) {
  $tr = null;
} else {
  $parts_num = mysqli_num_rows($parts_res);

  for ($i = 0; $i < $parts_num; $i++) {
      $part = mysqli_fetch_row($parts_res);
      $email = $part[1];
      $name = $part[2];
      $last_name = $part[3];
      $mobile_phone = $part[4];
      $work_phone = $part[5];
      $nomination = $part[6];
      $query = "SELECT nomination from t_new_nominations where id = '".mb_convert_encoding($nomination, "cp1251")."';";
      $nomination = mysqli_fetch_row(mysqliQuery($query))[0];
      $pass = $part[7];
      $work_place = $part[8];
      $work_experience = $part[9];
      $education = $part[10];
      $name_education = $part[11];
      $training = $part[12];
      $adress_index = $part[13];
      $home_adress = $part[14];
      $employer_phone = $part[15];
      $work_email = $part[16];
      $subjectname = $part[17];
      $coord_name = $part[18];
      $reg_date = $part[19];
      $third_name = $part[21];

      $query = "SELECT tests_json, res_of_exp from t_new_parts_works where email = '".mb_convert_encoding($email, "cp1251")."';";
      $result = mysqli_fetch_row(mysqliQuery($query));
      $test_arr = mb_convert_encoding($result[0],'utf-8','cp1251');
      $test_arr = json_decode($test_arr);

      $test_res = 0;
      if ($test_arr != null) {
          for ($n = 0; $n < count($test_arr); $n++) {
              $obj = $test_arr[$n];
              $checkboxs = $obj->checkboxs;
              $part_answer = $obj->part_answer;
              $points = $obj->points;
              if ($checkboxs === $part_answer) {
                  $test_res += (int) $points;
              }
          }
      } else {
          $test_res = 'Тест не пройден';
      }

      $creative_task = json_decode($result[1]);
      $creative_task_res = 0;
      $creative_task = $creative_task->results;
      if ($creative_task != null) {
          for ($n = 0; $n < count($creative_task); $n++) {
              $creative_task_res += (int) $creative_task[$n];
          }
      } else {
          $creative_task_res = 'Нет оценок';
      }
      if ($test_arr != null && $creative_task != null) {
          $full_res = $test_res + $creative_task_res;
      } else if ($test_arr != null && $creative_task == null) {
        $full_res = (int) $test_res;
      } else if ($test_arr == null && $creative_task != null) {
        $full_res = (int) $creative_task_res;
      } else {
        $full_res = 0;
      }


      $tr .= "
          <tr>
              <td>".mb_convert_encoding($email, "utf-8", "cp1251")."</td>
              <td>".mb_convert_encoding($last_name, "utf-8", "cp1251")."</td>
              <td>".mb_convert_encoding($name, "utf-8", "cp1251")."</td>
              <td>".mb_convert_encoding($third_name, "utf-8", "cp1251")."</td>
              <td>".mb_convert_encoding($mobile_phone, "utf-8", "cp1251")."</td>
              <td>".mb_convert_encoding($work_phone, "utf-8", "cp1251")."</td>
              <td>".mb_convert_encoding($nomination, "utf-8", "cp1251")."</td>
              <td>".mb_convert_encoding($pass, "utf-8", "cp1251")."</td>
              <td>".mb_convert_encoding($work_place, "utf-8", "cp1251")."</td>
              <td>".mb_convert_encoding($work_experience, "utf-8", "cp1251")."</td>
              <td>".mb_convert_encoding($education, "utf-8", "cp1251")."</td>
              <td>".mb_convert_encoding($name_education, "utf-8", "cp1251")."</td>
              <td>".mb_convert_encoding($training, "utf-8", "cp1251")."</td>
              <td>".mb_convert_encoding($adress_index, "utf-8", "cp1251")."</td>
              <td>".mb_convert_encoding($home_adress, "utf-8", "cp1251")."</td>
              <td>".mb_convert_encoding($employer_phone, "utf-8", "cp1251")."</td>
              <td>".mb_convert_encoding($work_email, "utf-8", "cp1251")."</td>
              <td>".mb_convert_encoding($subjectname, "utf-8", "cp1251")."</td>
              <td>".mb_convert_encoding($coord_name, "utf-8", "cp1251")."</td>
              <td>".mb_convert_encoding($reg_date, "utf-8", "cp1251")."</td>
              <td>$test_res</td>
              <td>$creative_task_res</td>
              <td>$full_res</td>
          </tr>
      ";
  }
}
$html = "
<table>
    <tr>
        <th>email</th>
        <th>Фамилия</th>
        <th>Имя</th>
        <th>Отчество</th>
        <th>Мобильный телефон</th>
        <th>Рабочий телефон</th>
        <th>Номинация</th>
        <th>Паспортные данные</th>
        <th>Место работы и должность</th>
        <th>Стаж работы</th>
        <th>Образование и специальность</th>
        <th>Учебное заведение</th>
        <th>Повышение квалицикации</th>
        <th>Индекс места жительства</th>
        <th>Адрес места жительства</th>
        <th>Телефон и факс работодателя</th>
        <th>Рабочий адрес электронной почты</th>
        <th>Субъект РФ</th>
        <th>Координатор</th>
        <th>Дата регистрации</th>
        <th>Баллы за тест</th>
        <th>Баллы за творческое задание</th>
        <th>Общий балл</th>
    </tr>
    $tr
</table>
";

echo($html);
?>
