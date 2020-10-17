<?php

header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8");
header("Content-Disposition: attachment; filename=Список координаторов.xlsx");
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
$sheet->setCellValue('D1', 'Мобильный телефон');
$sheet->setCellValue('E1', 'Рабочий телефон');
$sheet->setCellValue('F1', 'Субъект РФ');
$sheet->setCellValue('G1', 'Дата регистрации');

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/core/other.php');

$query = "SELECT * from t_new_coords;";
$coords = mysqliQuery($query);
if ($coords == false) {
  $tr = null;
} else {
  $coords_num = mysqli_num_rows($coords);

  for ($i = 0; $i < $coords_num; $i++) {
    $coord = mysqli_fetch_row($coords);
    $id = $coord[0];
    $email = $coord[1];
    $workphone = $coord[2];
    $mobilephone = $coord[3];
    $last_name = $coord[4];
    $first_name = $coord[5];
    $subjectnum = $coord[6];
    $subjectnum = mb_convert_encoding($subjectnum, 'utf-8','cp1251');
    $subjectname = RFsubjects($subjectnum);
    $reg_date = $coord[7];

    $email = mb_convert_encoding($email, "utf-8", "cp1251");
    $first_name = mb_convert_encoding($first_name, "utf-8", "cp1251");
    $last_name = mb_convert_encoding($last_name, "utf-8", "cp1251");
    $mobilephone = mb_convert_encoding($mobilephone, "utf-8", "cp1251");
    $workphone = mb_convert_encoding($workphone, "utf-8", "cp1251");
    $reg_date = mb_convert_encoding($reg_date, "utf-8", "cp1251");

    $num_col = (int) $i + 2 . "";

    $sheet->setCellValue("A$num_col", "$email");
    $sheet->setCellValue("B$num_col", "$first_name");
    $sheet->setCellValue("C$num_col", "$last_name");
    $sheet->setCellValue("D$num_col", "$mobilephone");
    $sheet->setCellValue("E$num_col", "$workphone");
    $sheet->setCellValue("F$num_col", "$subjectname");
    $sheet->setCellValue("G$num_col", "$reg_date");
  }
}

$writer = new Xlsx($spreadsheet);
ob_start();
$writer->save('php://output');
$xlsData = ob_get_contents();
ob_end_clean();
echo ($xlsData);

?>
