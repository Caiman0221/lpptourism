<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
$query = "SELECT * from t_new_nominations";
$html = "<option value=''>Выберите номинацию</option>";
$result = mysqliQuery($query);
if ($result != null) {
$rows = mysqli_num_rows($result);
  for ($i = 1; $i <= $rows; $i++) {
      $row = mysqli_fetch_row($result);
      $id = $row[0];
      $nominationName = $row[1];
      $html = $html . "<option value='$id'>".mb_convert_encoding($nominationName,"utf-8", "cp1251")."</option>";
  }
} else {
  $html = "<option value=''>Пока нет ни одной номинации</option>";
}
echo ($html)

?>
