<?php
$_POST = json_decode(file_get_contents("php://input"), true);

$coordListNum = $_POST['coordListNum'];

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
$query = "SELECT * from t_new_coords";
$result = mysqliQuery($query);
  if ($result != null) {
  $coordRows = mysqli_num_rows($result);

  if ($coordListNum != 'all') {
      $coordRows = ceil($coordRows/$coordListNum);
      for ($i = 0; $i < $coordRows; $i++) {
          $html = $html . "<option value='".(0+$i*$coordListNum).",".($coordListNum+$i*$coordListNum)."'>".(1+$i*$coordListNum)."-".($coordListNum+$i*$coordListNum)."</option>";
      }
  }
} else {
  $html = $html . "<option value=''>0</option>";
}
echo($html);

?>
