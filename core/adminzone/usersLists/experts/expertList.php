<?php

$_POST = json_decode(file_get_contents("php://input"), true);

$select = $_POST['select'];
$search = $_POST['search'];
$search_1251 = mb_convert_encoding($search,"cp1251");
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

$query = "SELECT * from t_new_experts $query_select";
require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/core/other.php');
$result = mysqliQuery($query);
if ($result == false) {
  echo("Список экспертов пуст");
  return;
}
$rows = mysqli_num_rows($result);
for ($i = 0; $i < $rows; $i++) {
    $row = mysqli_fetch_row($result);
    $id = $row[0];
    $email = mb_convert_encoding($row[1], "utf-8", "cp1251");
    $email64 = base64_encode($email);
    $query = "SELECT password from t_new_users where email = '$email64';";
    $password = base64_decode(mysqli_fetch_row(mysqliQuery($query))[0]);
    $wordphone = mb_convert_encoding($row[2], "utf-8", "cp1251");
    $mobilephone = mb_convert_encoding($row[3], "utf-8", "cp1251");
    $last_name = mb_convert_encoding($row[4], "utf-8", "cp1251");
    $first_name = mb_convert_encoding($row[5], "utf-8", "cp1251");
    $reg_date = mb_convert_encoding($row[6], "utf-8", "cp1251");
    $status = mb_convert_encoding($row[7], "utf-8", "cp1251");
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
                                <input type='checkbox' name='ExpertEditorCheck' id='ExpertEditorCheck".$id."'>
                            </div>
                            <div class='visiblePartOfEditRight' id='visibleExpertPart".$id."' onclick='showHiddenButtonsExpert(this)'>
                                <div>".$first_name."</div>
                                <div>".$last_name."</div>
                                <div>".$mobilephone."</div>
                                $div
                            </div>
                        </div>
                        <div id='hiddenExpertButtonPart$id' class='hiddenPartOfButtons' style='display: none;'>
                            <div><button id='closeEditorExpertButton$id' class='hiddenUsersButton' onclick='closeEditorExpertButtonsClick(this)'>Закрыть</button></div>
                            <div><button id='infoEditorExpertButton$id' class='hiddenUsersButton' onclick='infoEditorExpertButtonsClick(this)'>Информация</button></div>
                            <div><button id='editEditorExpertButton$id' class='hiddenUsersButton' onclick='EditEditorExpertButtonsClick(this)'>Редактировать</button></div>
                        </div>
                        <div id='infoExpertEditorPage$id' class='hiddenEditorAdminsFullInfo' style='display: none;'>
                            <div>Email: $email</div>
                            <div>Пароль: $password</div>
                            <div>Имя: $first_name</div>
                            <div>Фамилия: $last_name</div>
                            <div>Телефон: $mobilephone</div>
                            <div>Рабочий телефон: $wordphone</div>
                            <div>Дата регистрации: $reg_date</div>
                            <div>Пользователь: $status</div>
                        </div>
                        <div id='editExpertEditorPage$id' class='hiddenEditorAdminInputs' style='display: none;' onclick='ExpertListEditOneClick(event)'>
                            <div class='newUserCard'>
                                <div style='display: none;'>
                                    <div class='newUserCardText'>Скрытый id:</div>
                                    <input class='newUserCardInput' name='editExpertID' type='text' id='editExpertID$id' placeholder='скрытый id' value='$id'>
                                </div>
                                <div>
                                    <div class='newUserCardText'>Email:</div>
                                    <input class='newUserCardInput' name='editExpertEmail' type='email' id='editExpertEmail$id' placeholder='Email' value='$email'>
                                </div>
                                <div>
                                    <div class='newUserCardText'>Пароль (не изменится, если поле пустое):</div>
                                    <input class='newUserCardInput' name='editExpertPassword' type='text' id='editExpertPassword$id' placeholder='Пароль' value='$password'>
                                </div>
                                <div>
                                    <div class='newUserCardText'>Имя:</div>
                                    <input class='newUserCardInput' name='editExpertName' type='text' id='editExpertName$id' placeholder='Имя' value='$first_name'>
                                </div>
                                <div>
                                    <div class='newUserCardText'>Фамилия:</div>
                                    <input class='newUserCardInput' name='editExpertLastName' type='text' id='editExpertLast$id' placeholder='Фамилия' value='$last_name'>
                                </div>
                                <div>
                                    <div class='newUserCardText'>Контактный телефон:</div>
                                    <input class='newUserCardInput' name='editExpertPhone' type='tel' id='editExpertPhone$id' placeholder='Телефон' value='$mobilephone'>
                                </div>
                                <div>
                                    <div class='newUserCardText'>Активен ли пользователь:
                                        <span><input type='checkbox' name='editExpertCheckbox' class='checkbox' id='editExpertCheckbox$id' $checkbox></span>
                                    </div>
                                </div>
                                <div class='ExpertListEditOneError'></div>
                                <button name='ExpertListEditOneClick'>Сохранить</button>
                            </div>
                        </div>
                    </section>";
}

echo($html);

?>
