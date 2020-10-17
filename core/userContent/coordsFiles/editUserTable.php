<?php

$_POST = json_decode(file_get_contents("php://input"), true);
$id = $_POST['partID'];

$obj = (object) array();
//Забираем данные из таблицы пользователей
require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
$query = "SELECT * from t_new_parts where id = '$id';";
$res = mysqli_fetch_row(mysqliQuery($query));

//собираем селектор из бд номинаций
$query = "SELECT * from t_new_nominations;";
$res_nom = mysqliQuery($query);
$rows_nom = mysqli_num_rows($res_nom);
for ($i = 1; $i <= $rows_nom; $i++) {
    $row_nom = mysqli_fetch_row($res_nom);
    $id_nom = $row_nom[0];
    $name_nom = $row_nom[1];
    $name_nom = mb_convert_encoding($name_nom,'utf-8','cp1251');

    if ($res[6] == $id_nom) {
        $selected = 'selected';
    } else {
        $selected = '';
    }
    $html = $html . "<option value='$id_nom' $selected>$name_nom</option>";
}

//http://lpptourism.ru/home/h812193481/lpptourism.ru/docs/files/parts/1/%D0%90%D0%BD%D0%BA%D0%B5%D1%82%D0%B0%20%D0%BF%D0%BE%D0%BB%D1%8C%D0%B7%D0%BE%D0%B2%D0%B0%D1%82%D0%B5%D0%BB%D1%8F.docx

$part_dir = $_SERVER['DOCUMENT_ROOT'] . '/files/parts/' . $id;
$html_files = '';
for ($i = 2; $i < count(scandir($part_dir)); $i++) {
  $file_name = scandir($part_dir)[$i];
  if ($file_name != 'docs.zip') {
    $file_link = $part_dir . '/' . $file_name;
    $file_link = str_ireplace($_SERVER['DOCUMENT_ROOT'] . "/",'http://lpptourism.ru/',$file_link);
    $html_files = $html_files . "<div>
      <a href='$file_link' download>$file_name</a>
    </div>";
}
};

$obj->id = mb_convert_encoding($res[0],'utf-8','cp1251');
$obj->email = mb_convert_encoding($res[1],'utf-8','cp1251');
$obj->name = mb_convert_encoding($res[2],'utf-8','cp1251');
$obj->last_name = mb_convert_encoding($res[3],'utf-8','cp1251');
$obj->mobile_phone = mb_convert_encoding($res[4],'utf-8','cp1251');
$obj->work_phone = mb_convert_encoding($res[5],'utf-8','cp1251');
$obj->nomination = $html;
$obj->pass = mb_convert_encoding($res[7],'utf-8','cp1251');
$obj->work_place = mb_convert_encoding($res[8],'utf-8','cp1251');
$obj->work_experience = mb_convert_encoding($res[9],'utf-8','cp1251');
$obj->education = mb_convert_encoding($res[10],'utf-8','cp1251');
$obj->name_education = mb_convert_encoding($res[11],'utf-8','cp1251');
$obj->training = mb_convert_encoding($res[12],'utf-8','cp1251');
$obj->adress_index = mb_convert_encoding($res[13],'utf-8','cp1251');
$obj->home_adress = mb_convert_encoding($res[14],'utf-8','cp1251');
$obj->employer_phone = mb_convert_encoding($res[15],'utf-8','cp1251');
$obj->work_email = mb_convert_encoding($res[16],'utf-8','cp1251');
$obj->third_name = mb_convert_encoding($res[21],'utf-8','cp1251');
$obj->part_files = $html_files;

echo(json_encode($obj,JSON_UNESCAPED_UNICODE));

?>
