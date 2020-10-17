<?php

$_POST = json_decode(file_get_contents("php://input"), true);
$access_token = $_POST['access_token'];

//Достаем email из таблицы пользователей по токену
require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
$query = "SELECT email from t_new_users where access_token = '$access_token';";
$email = base64_decode(mysqli_fetch_row(mysqliQuery($query))[0]);

//Достаем имя и фамилию из таблицы координаторов
$query = "SELECT last_name, first_name from t_new_coords where email = '".mb_convert_encoding($email,"cp1251")."';";
$result = mysqli_fetch_row(mysqliQuery($query));

$coord_name = mb_convert_encoding($result[0],"utf-8", "cp1251") . " " . mb_convert_encoding($result[1],"utf-8", "cp1251");

//Достаем список пользователей, созданных координатором
$query = "SELECT id, last_name, name from t_new_parts where coord_name = '".mb_convert_encoding($coord_name,"cp1251")."';";
$res = mysqliQuery($query);
if ($res == null) {
  $html = "Вы еще не добавлили ни одного пользователя";
} else {
  $rows = mysqli_num_rows($res);
  for ($i = 0; $i < $rows; $i++) {
      $row = mysqli_fetch_row($res);
      $id = $row[0];
      $last_name = mb_convert_encoding($row[1],"utf-8","cp1251");
      $name = mb_convert_encoding($row[2],"utf-8","cp1251");
      $html = "<div class='coordOnePartList' onclick='coordPartsListClick(event)'>
                  <input type='text' name='partidFromPartsList' value='$id' style='display: none;'>
                  <div class='coordPartListName'>$last_name $name</div>
                  <div class='coordPartListButton'>
                      <button class='dellDocButton'>X</button>
                  </div>
              </div>" . $html;
  }
}
echo $html;

?>
