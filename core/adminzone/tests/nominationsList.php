<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
$query = "SELECT * from t_new_nominations";
$result = mysqliQuery($query);
if ($result == false) {
  echo("Список номинаций пуст");
  return;
}
$rows = mysqli_num_rows($result);
for ($i = 1; $i <= $rows; $i++) {
    $row = mysqli_fetch_row($result);
    $id = $row[0];
    $nominationName = $row[1];
    $testName = $row[2];

    $query = "SELECT name from t_new_tests where id = '$testName';";
    $res = mysqliQuery($query);
    if ($res == false) {
      $testName = null;
    } else {
      $testName = mysqli_fetch_row($res)[0];
    }

    $html = $html . "<div class='nominationsContainer'>
                        <div class='nominationName'>
                            <div>Номинация: ".mb_convert_encoding($nominationName,"utf-8", "cp1251")."</div>
                            <div>Тест: ".mb_convert_encoding($testName,"utf-8", "cp1251")."</div>
                        </div>
                        <div class='nominationDellButton'>
                            <button class='yellowButton' id='dellTestFromNomination$id' onclick='dellTestFromNominationClick(this)'>Открепить тест</button>
                            <button class='redButton' id='dellNomination$id' onclick='dellNominationClick(this)'>Удалить</button>
                        </div>
                    </div>";
}
if ($html != '') {
    echo ($html);
} else {
    echo ('Список номинаций пуст');
}
?>
