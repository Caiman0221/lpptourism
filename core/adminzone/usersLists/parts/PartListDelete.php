<?php

$_POST = json_decode(file_get_contents("php://input"), true);

$ID = $_POST['partsID']; //array of parts

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
for ($i = 0; $i < count($ID); $i++) {
    $idset = $ID[$i];
    $query = "SELECT email from t_new_parts where id ='$idset';";
    $email64 = base64_encode(mysqli_fetch_row(mysqliQuery($query))[0]);

    $query = "DELETE FROM t_new_parts WHERE id = '$idset';";
    mysqliQuery($query);

    $query = "DELETE FROM t_new_parts_works where id = '$idset';";
    mysqliQuery($query);

    $query = "DELETE FROM t_new_users WHERE email = '$email64';";
    mysqliQuery($query);


    $dir = $_SERVER['DOCUMENT_ROOT'] . "/files/parts/$idset";

    if (is_dir($dir)) {
      $scan = scandir($dir);
      foreach ($scan as $scan_name) {
        if($scan_name == '.' || $scan_name == '..'){
          continue;
        }else{
          $file_dir = "$dir/" . $scan_name;
          chmod($file_dir,0777);
          unlink($file_dir);
        }
      }
      rmdir($dir);
    }
}

if (count($ID) != 0) {
    echo("Пользователи были удалены");
} else if (count($ID) == 0) {
    echo("Вы не выбрали ни одного пользователя");
}
?>
