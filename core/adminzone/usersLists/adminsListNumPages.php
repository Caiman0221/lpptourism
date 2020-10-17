<?php
$_POST = json_decode(file_get_contents("php://input"), true);

$adminListNum = $_POST['adminListNum'];

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
$query = "SELECT * from t_new_admins";
$adminRows = mysqli_num_rows(mysqliQuery($query));

if ($adminListNum != 'all') {
    $adminRows = ceil($adminRows/$adminListNum);
    for ($i = 0; $i < $adminRows; $i++) {
        $html = $html . "<option value='".(0+$i*$adminListNum).",".($adminListNum+$i*$adminListNum)."'>".(1+$i*$adminListNum)."-".($adminListNum+$i*$adminListNum)."</option>";
    }
}
echo($html);

?>
