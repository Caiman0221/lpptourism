<?php

$_POST = json_decode(file_get_contents("php://input"), true);
$newNomination = $_POST['newNomination'];
require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');

$query = "CREATE TABLE IF NOT EXISTS t_new_nominations (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nomination TEXT,
    test TEXT
);";
if(mysqliQuery($query) == null) {
    return;
}

$query = "SELECT * from t_new_nominations where nomination = '".mb_convert_encoding($newNomination, "cp1251")."';";
if (mysqli_num_rows(mysqliQuery($query)) != null) {
    echo('такая номинация уже есть, проверьте список');
    return;
}

$query = "INSERT INTO t_new_nominations VALUES (NULL,'".mb_convert_encoding($newNomination, "cp1251")."',NULL);";
if(mysqliQuery($query) == null) {
    return;
}
echo('Номинация была добавлена');


?>
