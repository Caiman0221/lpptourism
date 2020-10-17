<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
$query = "SELECT * from t_new_nominations;";
$result = mysqliQuery($query);
$options = "<option value=''>Выбреите номинацию</option>";
if ($result != null) {
  $rows = mysqli_num_rows($result);
  for ($i = 0; $i < $rows; $i++ ) {
      $row = mysqli_fetch_row($result);
      $id_nom = $row[0];
      $nomination = $row[1];
      $options .= "<option value='$id_nom'>".mb_convert_encoding($nomination, 'utf-8', "cp1251")."</option>";
  }
} else {
  $options = "<option value=''>Пока нет ни одной номинациия</option>";
}
echo($options);

?>
