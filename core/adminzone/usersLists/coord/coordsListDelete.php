<?php

$_POST = json_decode(file_get_contents("php://input"), true);

$ID = $_POST['coordsID']; //array of coords

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
for ($i = 0; $i < count($ID); $i++) {
    $idset = $ID[$i];
    $query = "SELECT email from t_new_coords where id ='$idset';";
    $email64 = base64_encode(mysqli_fetch_row(mysqliQuery($query))[0]);

    $query = "DELETE FROM t_new_coords WHERE id = '$idset';";
    mysqliQuery($query);

    $query = "DELETE FROM t_new_users WHERE email = '$email64';";
    mysqliQuery($query);
}

if (count($ID) != 0) {
    echo("Пользователи были удалены");
} else if (count($ID) == 0) {
    echo("Вы не выбрали ни одного пользователя");
}
?>
