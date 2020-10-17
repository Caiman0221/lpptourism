<?php

$_POST = json_decode(file_get_contents("php://input"), true);
$id = $_POST['partID'];

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');

//забираем email из таблицы пользователей
$query = "SELECT email from t_new_parts where id = '$id'";
$email = mysqli_fetch_row(mysqliQuery($query))[0];

//удаляем папку пользователя (подчищаем мусор)
$dir = $_SERVER['DOCUMENT_ROOT'] . "/files/parts/".$id;
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

//удаляем пользователя из t_parts_works
$query = "DELETE FROM t_new_parts_works where email = '$email';";
mysqliQuery($query);

//удаляем пользователя из t_parts
$query = "DELETE FROM t_new_parts where email = '$email';";
mysqliQuery($query);

//удаляем пользователя из t_users
$email64 = base64_encode($email);
$query = "DELETE FROM t_new_users where email = '$email64';";
mysqliQuery($query);

echo ('Пользователь был удален');

?>
