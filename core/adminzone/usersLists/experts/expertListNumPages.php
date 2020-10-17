<?php
$_POST = json_decode(file_get_contents("php://input"), true);

$expertListNum = $_POST['expertListNum'];

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
$query = "SELECT * from t_new_experts";
$res = mysqliQuery($query);
if ($res == null) {
  $expertRows = 1;
} else {
  $expertRows = mysqli_num_rows($res);
}

if ($expertListNum != 'all') {
    $expertRows = ceil($expertRows/$expertListNum);
    for ($i = 0; $i < $expertRows; $i++) {
        $html = $html . "<option value='".(0+$i*$expertListNum).",".($expertListNum+$i*$expertListNum)."'>".(1+$i*$expertListNum)."-".($expertListNum+$i*$expertListNum)."</option>";
    }
}
echo($html);

?>
