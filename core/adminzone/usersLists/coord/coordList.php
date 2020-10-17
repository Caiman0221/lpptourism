<?php

$_POST = json_decode(file_get_contents("php://input"), true);

$select = $_POST['select'];
$search = $_POST['search'];
$search_1251 = mb_convert_encoding($search, "cp1251");
$NumOfPages = $_POST['NumOfPages'];

if ($search != '') {
    $query_search = "where email like '%$search_1251%' OR first_name like '%$search_1251%' OR last_name like '%$search_1251%' OR mobilephone like '%$search_1251%' OR workphone like '%$search_1251%'";
    if ($select == '5' || $select == '6') {
        $query_search = "AND (email like '%$search_1251%' OR first_name like '%$search_1251%' OR last_name like '%$search_1251%' OR mobilephone like '%$search_1251%' OR workphone like '%$search_1251%')";
    }
}

if ($select == '1') {
    $query_select = "$query_search order by last_name";
} else if ($select == '2') {
    $query_select = "$query_search order by last_name desc";
} else if ($select == '3') {
    $query_select = "$query_search order by id";
} else if ($select == '4') {
    $query_select = "$query_search order by id desc";
} else if ($select == '5') {
    $query_select = "where status = 'disabled' $query_search";
} else if ($select == '6') {
    $query_select = "where status = 'activ' $query_search";
}

if ($NumOfPages != '') {
    $query_select = "$query_select limit $NumOfPages;";
} else {
    $query_select = "$query_select;";
}

$query = "SELECT * from t_new_coords $query_select";
require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/core/other.php');
$result = mysqliQuery($query);
if ($result == false) {
  echo("Список координаторов пуст");
  return;
}
$rows = mysqli_num_rows($result);
for ($i = 0; $i < $rows; $i++) {
    $row = mysqli_fetch_row($result);
    $id = $row[0];
    $email = $row[1];
    $email64 = base64_encode($email);
    $query = "SELECT password from t_new_users where email = '$email64';";
    $password = base64_decode(mysqli_fetch_row(mysqliQuery($query))[0]);
    $workphone = $row[2];
    $mobilephone = $row[3];
    $last_name = $row[4];
    $first_name = $row[5];
    $subjectname = $row[6];
    $reg_date = $row[7];
    $status = $row[8];

    $id = mb_convert_encoding($id,'utf-8', "cp1251");
    $email = mb_convert_encoding($email,'utf-8', "cp1251");
    $password = mb_convert_encoding($password,'utf-8', "cp1251");
    $workphone = mb_convert_encoding($workphone,'utf-8', "cp1251");
    $mobilephone = mb_convert_encoding($mobilephone,'utf-8', "cp1251");
    $last_name = mb_convert_encoding($last_name,'utf-8', "cp1251");
    $first_name = mb_convert_encoding($first_name,'utf-8', "cp1251");
    $subjectname = mb_convert_encoding($subjectname,'utf-8', "cp1251");
    $reg_date = mb_convert_encoding($reg_date,'utf-8', "cp1251");
    $status = mb_convert_encoding($status,'utf-8', "cp1251");

    if ($status == 'activ') {
        $checkbox = 'checked';
        $div = '<div style="color: green;">activ</div>';
    } else {
        $checkbox = '';
        $div = '<div style="color: red;">disabled</div>';
    }
    $html = $html . "<section class='oneEditedUser'>
                        <div class='visiblePartOfEdit'>
                            <div class='visiblePartOfEditLeft'>
                                <input type='checkbox' name='CoordEditorCheck' id='CoordEditorCheck".$id."'>
                            </div>
                            <div class='visiblePartOfEditRight' id='visibleCoordPart".$id."' onclick='showHiddenButtonsCoord(this)'>
                                <div>".$first_name."</div>
                                <div>".$last_name."</div>
                                <div>".$mobilephone."</div>
                                <div>".RFsubjects($subjectname)."</div>
                                $div
                            </div>
                        </div>
                        <div id='hiddenCoordButtonPart$id' class='hiddenPartOfButtons' style='display: none;'>
                            <div><button id='closeEditorCoordButton$id' class='hiddenUsersButton' onclick='closeEditorCoordButtonsClick(this)'>Закрыть</button></div>
                            <div><button id='infoEditorCoordButton$id' class='hiddenUsersButton' onclick='infoEditorCoordButtonsClick(this)'>Информация</button></div>
                            <div><button id='editEditorCoordButton$id' class='hiddenUsersButton' onclick='EditEditorCoordButtonsClick(this)'>Редактировать</button></div>
                        </div>
                        <div id='infoCoordEditorPage$id' class='hiddenEditorAdminsFullInfo' style='display: none;'>
                            <div onclick='loadCoordFileNormDocs(event)'>
                                <a>Скачать протоколы работы координатора</a>
                                <div style='color: red;'></div>
                                <input type='text' value='$email' style='display: none;'>
                            </div>
                            <div>Email: $email</div>
                            <div>Пароль: $password</div>
                            <div>Имя: $first_name</div>
                            <div>Фамилия: $last_name</div>
                            <div>Телефон: $mobilephone</div>
                            <div>Рабочий телефон: $workphone</div>
                            <div>Название субъекта: ". RFsubjects($subjectname) ."</div>
                            <div>Дата регистрации: $reg_date</div>
                            <div>Пользователь: $status</div>
                        </div>
                        <div id='editCoordEditorPage$id' class='hiddenEditorAdminInputs' style='display: none;' onclick='СoordListEditOneClick(event)'>
                            <div class='newUserCard'>
                                <div style='display: none;'>
                                    <div class='newUserCardText'>Скрытый id:</div>
                                    <input class='newUserCardInput' name='editCoordID' type='text' id='editCoordID$id' placeholder='скрытый id' value='$id'>
                                </div>
                                <div>
                                    <div class='newUserCardText'>Email:</div>
                                    <input class='newUserCardInput' name='editCoordEmail' type='email' id='editCoordEmail$id' placeholder='Email' value='$email'>
                                </div>
                                <div>
                                    <div class='newUserCardText'>Пароль (не изменится, если поле пустое):</div>
                                    <input class='newUserCardInput' name='editCoordPassword' type='text' id='editCoordPassword$id' placeholder='Пароль' value='$password'>
                                </div>
                                <div>
                                    <div class='newUserCardText'>Имя:</div>
                                    <input class='newUserCardInput' name='editCoordName' type='text' id='editCoordName$id' placeholder='Имя' value='$first_name'>
                                </div>
                                <div>
                                    <div class='newUserCardText'>Фамилия:</div>
                                    <input class='newUserCardInput' name='editCoordLastName' type='text' id='editCoordLast$id' placeholder='Фамилия' value='$last_name'>
                                </div>
                                <div>
                                    <div class='newUserCardText'>Контактный телефон:</div>
                                    <input class='newUserCardInput' name='editCoordPhone' type='tel' id='editCoordPhone$id' placeholder='Телефон' value='$mobilephone'>
                                </div>
                                <div>
                                    <div class='newUserCardText'>Активен ли пользователь:
                                        <span><input type='checkbox' name='editCoordCheckbox' class='checkbox' id='editCoordCheckbox$id' $checkbox></span>
                                    </div>
                                </div>
                                <div class='CoordListEditOneError'></div>
                                <button name='CoordListEditOneClick'>Сохранить</button>
                            </div>
                        </div>
                    </section>";
}

echo($html);

?>
