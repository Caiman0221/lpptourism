<?php
$_POST = json_decode(file_get_contents("php://input"), true);

$partListNum = $_POST['partListNum'];

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
$query = "SELECT * from t_new_parts";
$res = mysqliQuery($query);
if ($res == null) {
  $partRows = 1;
} else {
  $partRows = mysqli_num_rows($res);
}
if ($partListNum != 'all') {
    $partRows = ceil($partRows/$partListNum);
    for ($i = 0; $i < $partRows; $i++) {
        $html = $html . "<option value='".(0+$i*$partListNum).",".($partListNum+$i*$partListNum)."'>".(1+$i*$partListNum)."-".($partListNum+$i*$partListNum)."</option>";
    }
}
echo($html);

?>
