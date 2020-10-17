<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
$query = "SELECT id, last_name, first_name from t_new_coords;";
$result = mysqliQuery($query);
if($result != null) {
  $rows = mysqli_num_rows($result);
  $obj = "<option value=''>Выберите имя координатора</option>";
  for ($i = 1; $i <= $rows; $i++) {
      $row = mysqli_fetch_row($result);
      //$row[0]$row[1];
      $obj = $obj . "<option value='". $row[0] ."'>". mb_convert_encoding($row[1],'utf-8', "cp1251") . " " . mb_convert_encoding($row[2],'utf-8', "cp1251") ."</option>\n";
  }
} else {
  $obj = "<option value=''>Пока нет ни одного координатора</option>";
}
echo $obj;

?>
