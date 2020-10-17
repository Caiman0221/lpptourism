<?php
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8");
header("Content-Disposition: attachment; filename=Список участников.xlsx");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);

require 'PHPExcel/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'email');
$sheet->setCellValue('B1', 'Имя');
$sheet->setCellValue('C1', 'Фамилия');
$sheet->setCellValue('D1', 'Отчество');
$sheet->setCellValue('E1', 'Мобильный телефон');
$sheet->setCellValue('F1', 'Рабочий телефон');
$sheet->setCellValue('G1', 'Номинация');
$sheet->setCellValue('H1', 'Паспортные данные');
$sheet->setCellValue('I1', 'Место работы и должность');
$sheet->setCellValue('J1', 'Стаж работы');
$sheet->setCellValue('K1', 'Образование и специальность');
$sheet->setCellValue('L1', 'Учебное заведение');
$sheet->setCellValue('M1', 'Повышение квалицикации');
$sheet->setCellValue('N1', 'Индекс места жительства');
$sheet->setCellValue('O1', 'Адрес места жительства');
$sheet->setCellValue('P1', 'Телефон и факс работодателя');
$sheet->setCellValue('Q1', 'Рабочий адрес электронной почты');
$sheet->setCellValue('R1', 'Субъект РФ');
$sheet->setCellValue('S1', 'Координатор');
$sheet->setCellValue('T1', 'Дата регистрации');
$sheet->setCellValue('U1', 'Баллы за тест');
$sheet->setCellValue('V1', 'Баллы за творческое задание');
$sheet->setCellValue('W1', 'Общий балл');


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

    $email =  mb_convert_encoding($email, "utf-8", "cp1251");
    $last_name = mb_convert_encoding($last_name, "utf-8", "cp1251");
    $name = mb_convert_encoding($name, "utf-8", "cp1251");
    $third_name = mb_convert_encoding($third_name, "utf-8", "cp1251");
    $mobile_phone = mb_convert_encoding($mobile_phone, "utf-8", "cp1251");
    $work_phone = mb_convert_encoding($work_phone, "utf-8", "cp1251");
    $nomination = mb_convert_encoding($nomination, "utf-8", "cp1251");
    $pass = mb_convert_encoding($pass, "utf-8", "cp1251");
    $work_place = mb_convert_encoding($work_place, "utf-8", "cp1251");
    $work_experience = mb_convert_encoding($work_experience, "utf-8", "cp1251");
    $education = mb_convert_encoding($education, "utf-8", "cp1251");
    $name_education = mb_convert_encoding($name_education, "utf-8", "cp1251");
    $training = mb_convert_encoding($training, "utf-8", "cp1251");
    $adress_index = mb_convert_encoding($adress_index, "utf-8", "cp1251");
    $home_adress = mb_convert_encoding($home_adress, "utf-8", "cp1251");
    $employer_phone = mb_convert_encoding($employer_phone, "utf-8", "cp1251");
    $work_email = mb_convert_encoding($work_email, "utf-8", "cp1251");
    $subjectname = mb_convert_encoding($subjectname, "utf-8", "cp1251");
    $coord_name = mb_convert_encoding($coord_name, "utf-8", "cp1251");
    $reg_date = mb_convert_encoding($reg_date, "utf-8", "cp1251");

    $num_col = (int) $i + 2;

    $sheet->setCellValue("A$num_col", "$email");
    $sheet->setCellValue("B$num_col", "$name");
    $sheet->setCellValue("C$num_col", "$last_name");
    $sheet->setCellValue("D$num_col", "$third_name");
    $sheet->setCellValue("E$num_col", "$mobile_phone");
    $sheet->setCellValue("F$num_col", "$work_phone");
    $sheet->setCellValue("G$num_col", "$nomination");
    $sheet->setCellValue("H$num_col", "$pass");
    $sheet->setCellValue("I$num_col", "$work_place");
    $sheet->setCellValue("J$num_col", "$work_experience");
    $sheet->setCellValue("K$num_col", "$education");
    $sheet->setCellValue("L$num_col", "$name_education");
    $sheet->setCellValue("M$num_col", "$training");
    $sheet->setCellValue("N$num_col", "$adress_index");
    $sheet->setCellValue("O$num_col", "$home_adress");
    $sheet->setCellValue("P$num_col", "$employer_phone");
    $sheet->setCellValue("Q$num_col", "$work_email");
    $sheet->setCellValue("R$num_col", "$subjectname");
    $sheet->setCellValue("S$num_col", "$coord_name");
    $sheet->setCellValue("T$num_col", "$reg_date");
    $sheet->setCellValue("U$num_col", "$test_res");
    $sheet->setCellValue("V$num_col", "$creative_task_res");
    $sheet->setCellValue("W$num_col", "$full_res");
  }
}

$writer = new Xlsx($spreadsheet);
ob_start();
$writer->save('php://output');
$xlsData = ob_get_contents();
ob_end_clean();
echo ($xlsData);

?>
