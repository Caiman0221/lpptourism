<?php

$_POST = json_decode(file_get_contents("php://input"), true);
require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $query = "DELETE from t_new_nominations where id = '$id';";
    mysqliQuery($query);
    echo ('Номинация была успешно удалена');
} else if (isset($_POST['idtest'])) {
    $idtest = $_POST['idtest'];
    $query = "UPDATE t_new_nominations set test = NULL where id = '$idtest';";
    mysqliQuery($query);
    echo ('Тест был откреплен от номинации');
}

?>
