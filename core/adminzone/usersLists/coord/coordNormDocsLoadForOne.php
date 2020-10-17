<?php

$_POST = json_decode(file_get_contents("php://input"), true);
$email = $_POST['coord_email'];

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
$query = "SELECT last_name, first_name, subjectname from t_new_coords where email = '" . mb_convert_encoding($email, "cp1251") . "';";
$res = mysqli_fetch_row(mysqliQuery($query));

$obj = (object) array();

if ($res[0] == '') {
    $obj->error = 'Ошибка';
    echo(json_encode($obj));
    return;
}
$last_name = $res[0];
$first_name = $res[1];
$subjectnum = $res[2];

$last_name =  mb_convert_encoding($last_name,'utf-8', "cp1251");
$first_name = mb_convert_encoding($first_name,'utf-8', "cp1251");
$subjectnum = mb_convert_encoding($subjectnum,'utf-8', "cp1251");

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/other.php');
$subjectname = RFsubjects($subjectnum);

$subjectname_tr = rus2translit($subjectname);
$last_name_tr = rus2translit($last_name);
$first_name_tr = rus2translit($first_name);
$file_name_search = $last_name_tr . "_" . $first_name_tr;
$dir = $_SERVER['DOCUMENT_ROOT'] . "/files/normFiles/$subjectname_tr/";

//chmod($zip_dir);
if (file_exists($zip_dir)) {
  unlink($zip_dir);
}

$zip = new ZipArchive();
$zip->open($_SERVER['DOCUMENT_ROOT'] . '/files/normFiles/CoordNormfiles.zip',ZipArchive::CREATE);
$zip_dir = $_SERVER['DOCUMENT_ROOT'] . '/files/normFiles/CoordNormfiles.zip';
//chmod($zip_dir, 0777);

$zip_no_empty = 0;

if (is_dir($dir) == false) {
  $obj->error = 'Координатор не загружал документов';
  echo(json_encode($obj));
  return;
}

for($i = 0; $i < count(scandir($dir)); $i++) {
    $file_name = scandir($dir)[$i];
    if (strpos($file_name,$file_name_search) !== false) {
        $zip_no_empty++;
        $file_dir = $dir . $file_name;
        //chmod($file_dir, 0777);
        $zip->addFile($file_dir, $file_name);
    }
}

if ($zip_no_empty > 0) {
    $obj->coord = $first_name . "_" . $last_name;
    echo(json_encode($obj));
} else {
    $obj->error = 'Координатор не загружал документов';
    echo(json_encode($obj));
}

$zip->close();
?>
