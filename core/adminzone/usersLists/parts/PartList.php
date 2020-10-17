<?php
$_POST = json_decode(file_get_contents("php://input"), true);

$select = $_POST['select'];
$search = $_POST['search'];
$search_1251 = mb_convert_encoding($search, "cp1251");
$NumOfPages = $_POST['NumOfPages'];

if ($search != '') {
    $query_search = "where email like '%$search_1251%' OR name like '%$search_1251%' OR last_name like '%$search_1251%' OR mobile_phone like '%$search_1251%' OR work_phone like '%$search_1251%' OR pass like '%$search_1251%' OR name_education like '%$search_1251%' OR home_adress like '%$search_1251%' OR coord_name like '%$search_1251%'";
    if ($select == '5' || $select == '6') {
        $query_search = "AND (email like '%$search_1251%' OR name like '%$search_1251%' OR last_name like '%$search_1251%' OR mobile_phone like '%$search_1251%' OR work_phone like '%$search_1251%' OR pass like '%$search_1251%' OR name_education like '%$search_1251%' OR home_adress like '%$search_1251%' OR coord_name like '%$search_1251%')";
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

$query = "SELECT * from t_new_parts $query_select";
require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/core/other.php');
$result = mysqliQuery($query);
if ($result == false) {
  echo("Список участников пуст");
  return;
}
$rows = mysqli_num_rows($result);
for ($i = 0; $i < $rows; $i++) {
    $row = mysqli_fetch_row($result);
    $id = mb_convert_encoding($row[0],"utf-8", "cp1251");
    $email = mb_convert_encoding($row[1],"utf-8", "cp1251");
    $email64 = base64_encode($email);
    $query = "SELECT password from t_new_users where email='$email64';";
    $password = base64_decode(mysqli_fetch_row(mysqliQuery($query))[0]);
    $name = mb_convert_encoding($row[2],"utf-8", "cp1251");
    $last_name = mb_convert_encoding($row[3],"utf-8", "cp1251");
    $mobile_phone = mb_convert_encoding($row[4],"utf-8", "cp1251");
    $work_phone = mb_convert_encoding($row[5],"utf-8", "cp1251");
    $nomination = mb_convert_encoding($row[6],"utf-8", "cp1251");
    $pass = mb_convert_encoding($row[7],"utf-8", "cp1251");
    $work_place = mb_convert_encoding($row[8],"utf-8", "cp1251");
    $work_experience = mb_convert_encoding($row[9],"utf-8", "cp1251");
    $education = mb_convert_encoding($row[10],"utf-8", "cp1251");
    $name_education = mb_convert_encoding($row[11],"utf-8", "cp1251");
    $training = mb_convert_encoding($row[12],"utf-8", "cp1251");
    $adress_index = mb_convert_encoding($row[13],"utf-8", "cp1251");
    $home_adress = mb_convert_encoding($row[14],"utf-8", "cp1251");
    $employer_phone = mb_convert_encoding($row[15],"utf-8", "cp1251");
    $work_email = mb_convert_encoding($row[16],"utf-8", "cp1251");
    $subjectname = mb_convert_encoding($row[17],"utf-8", "cp1251");
    $coord_name = mb_convert_encoding($row[18],"utf-8", "cp1251");
    $reg_date = mb_convert_encoding($row[19],"utf-8", "cp1251");
    $status = mb_convert_encoding($row[20],"utf-8", "cp1251");
    $third_name = mb_convert_encoding($row[21],"utf-8", "cp1251");

    if ($status == 'activ') {
        $checkbox = 'checked';
        $div = '<div style="color: green;">activ</div>';
    } else {
        $checkbox = '';
        $div = '<div style="color: red;">disabled</div>';
    }

    $query_nom = "SELECT * from t_new_nominations;";
    $res_nom = mysqliQuery($query_nom);
    $rows_nom = mysqli_num_rows($res_nom);
    $nom_options = '';
    for ($n = 0; $n < $rows_nom; $n++) {
        $row_nom = mysqli_fetch_row($res_nom);
        $nom_id = $row_nom[0];
        $nom_nomination = $row_nom[1];
        $nom_nomination = mb_convert_encoding($nom_nomination,"utf-8","cp1251");
        $selected = '';
        if ($nom_id == $nomination) {
            $selected = 'selected';
        }
        $nom_options .= "<option value='$nom_id' $selected>$nom_nomination</option>";
    }

    $subjectsOptions = '';
    for ($n = 1; $n <= 85; $n++) {
        $subjectname_arr = RFsubjects($n);
        $selected = '';
        if ($subjectname_arr == $subjectname) {
            $selected = 'selected';
        }
        $subjectsOptions .= "<option value='$subjectname_arr' $selected>$subjectname_arr</option>";
    }

    $query_coords = "SELECT id, last_name, first_name from t_new_coords";
    $res_coords = mysqliQuery($query_coords);
    $rows_coords = mysqli_num_rows($res_coords);
    $coord_select = "<option value=''>У данного пользователя нет координатора</option>";
    for ($n = 0; $n < $rows_coords; $n++) {
        $row_coords = mysqli_fetch_row($res_coords);
        $coord_last_name = mb_convert_encoding($row_coords[1],'utf-8','cp1251');
        $coord_name_1 = mb_convert_encoding($row_coords[2],'utf-8','cp1251');
        $selected = '';
        if ($coord_name == $coord_last_name . " " . $coord_name_1) {
            $selected = 'selected';
        }
        $coord_select .= "<option value='" . $coord_last_name . " " . $coord_name_1 . "' $selected>" . $coord_last_name . " " . $coord_name_1 ."</option>";
    }

    //Результаты тестирования
    $query = "SELECT tests_json, res_of_exp from t_new_parts_works where email = '$email';";
    $part_works_results = (mysqli_fetch_row(mysqliQuery($query)));
    $test = mb_convert_encoding($part_works_results[0],"utf-8","cp1251");
    $test = json_decode($test);
    $creative_work_results = json_decode($part_works_results[1]);
    $creative_work_results = $creative_work_results->results;
    $K1 = $creative_work_results[0];
    $K2 = $creative_work_results[1];
    $K3 = $creative_work_results[2];
    $K4 = $creative_work_results[3];
    $K5 = $creative_work_results[4];
    $Kfull = $K1 + $K2 + $K3 + $K4 + $K5;
    if ($Kfull == '') {
        $creative_work_html_res = "<div class='errorDivTestListParts'>Работа еще не оценена</div>";
    } else {
        $creative_work_html_res = "
            <div class='creativeTableContainer'>
                <div class='creativeTableTR'>
                    <div class='creativeTableLeftTD'>Соответствие работы заявленной теме:</div>
                    <div class='creativeTableRightTD'>$K1</div>
                </div>
                <div class='creativeTableTR'>
                    <div class='creativeTableLeftTD'>Актуальность, новизна, целостность видео-контента:</div>
                    <div class='creativeTableRightTD'>$K2</div>
                </div>
                <div class='creativeTableTR'>
                    <div class='creativeTableLeftTD'>Оригинальность идеи, композиция:</div>
                    <div class='creativeTableRightTD'>$K3</div>
                </div>
                <div class='creativeTableTR'>
                    <div class='creativeTableLeftTD'>Информационная содержательность:</div>
                    <div class='creativeTableRightTD'>$K4</div>
                </div>
                <div class='creativeTableTR'>
                    <div class='creativeTableLeftTD'>Соответствие регламенту:</div>
                    <div class='creativeTableRightTD'>$K5</div>
                </div>
            </div>
        ";
    }
    $test_num_points = 0;
    $html_arr = [];
    $html_test = '';
    if ($test == NULL) {
        $html_test = '<div class="errorDivTestListParts">Тест не пройден</div>';
    } else {
        for ($n = 0; $n < count($test); $n++) {
            $question_obj = $test[$n];
            $question = $question_obj->question;
            $checkboxs_arr = $question_obj->checkboxs;
            $answers_arr = $question_obj->answers;
            $points = $question_obj->points;
            $question_num = $question_obj->question_num;
            $part_answer_arr = $question_obj->part_answer;
            if ($part_answer_arr == '') {
                $html_test = "<div class='errorDivTestListParts'>Тест пройден не до конца</div>";
                break;
            }
            if ($checkboxs_arr === $part_answer_arr) {
                $test_num_points += $points;
            }
            $answers_html = '';
            for ($m = 0; $m < count($answers_arr); $m++) {

                if ($checkboxs_arr[$m] == $part_answer_arr[$m] && $checkboxs_arr[$m] == 'true') {
                    $greencolor = 'style="background: green"';
                } else if ($checkboxs_arr[$m] == 'true' && ($part_answer_arr[$m] == 'false' || $part_answer_arr[$m] == 'empty' || $part_answer_arr[$n] == 'time') ) {
                    $greencolor = 'style="background: green"';
                } else if ($part_answer_arr[$m] == 'true' && $checkboxs_arr[$m] == 'false') {
                    $greencolor = 'style="background: red"';
                } else if ($part_answer_arr[$m] == 'empty' && $checkboxs_arr[$m] == 'false') {
                    $greencolor = 'style="background: yellow"';
                } else if ($part_answer_arr[$m] == 'time' && $checkboxs_arr[$m] == 'false') {
                    $greencolor = 'style="background: orange"';
                } else {
                    $greencolor = '';
                }

                $answers_html .= '<div>
                                        <span class="testAnswer">'.$answers_arr[$m].'</span>
                                        <span class="testCheckbox">
                                            <label for="" '.$greencolor.'><input type="checkbox" class="testCheckboxInput" name="answecheck" readonly></label>
                                        </span>
                                    </div>';
            }

            $html_arr[$question_num] = '<div class="testContainer">
                                            <div class="testContentQuestion">
                                                ' . $question . '
                                            </div>
                                            <div class="testContentAnswers">
                                                '.$answers_html.'
                                            </div>
                                        </div>';
        }
        for ($n = 0; $n < count($html_arr); $n++) {
            $html_test .= $html_arr[$n];
        }
    }

    $query = "SELECT nomination FROM t_new_nominations where id  = '$nomination';";
    $nomination = mysqli_fetch_row(mysqliQuery($query))[0];
    $nomination = mb_convert_encoding($nomination, 'utf-8', 'cp1251');

    $html = $html . "<section class='oneEditedUser'>
                        <div class='visiblePartOfEdit'>
                            <div class='visiblePartOfEditLeft'>
                                <input type='checkbox' name='partEditorCheck' id='PartEditorCheck".$id."'>
                            </div>
                            <div class='visiblePartOfEditRight' id='visiblePartPart".$id."' onclick='showHiddenButtonsPart(this)'>
                                <div>$name</div>
                                <div>$last_name</div>
                                <div>$mobile_phone</div>
                                <div>$email</div>
                                $div
                            </div>
                        </div>
                        <div id='hiddenPartButtonPart$id' class='hiddenPartOfButtons' style='display: none;'>
                            <div><button id='closeEditorPartButton$id' class='hiddenUsersButton' onclick='closeEditorPartButtonsClick(this)'>Закрыть</button></div>
                            <div><button id='infoEditorPartButton$id' class='hiddenUsersButton' onclick='infoEditorPartButtonsClick(this)'>Информация</button></div>
                            <div><button id='ResultsEditorPartButton$id' class='hiddenUsersButton' onclick='ResultsEditorPartButtonsClick(this)'>Результаты</button></div>
                            <div><button id='editEditorPartButton$id' class='hiddenUsersButton' onclick='EditEditorPartButtonsClick(this)'>Редактировать</button></div>
                        </div>
                        <div id='infoPartEditorPage$id' class='hiddenEditorAdminsFullInfo' style='display: none;'>
                            <div><a href='http://lpptourism.ru/files/parts/$id/docs.zip' type='application/zip' download>Скачайлы файлы, прикрепленные к пользователю</a></div>
                            <div>Email: $email</div>
                            <div>Пароль: $password</div>
                            <div>Фамилия: $last_name </div>
                            <div>Имя: $name</div>
                            <div>Отчество: $third_name </div>
                            <div>Мобильный телефон: $mobile_phone</div>
                            <div>Рабочий телефон: $work_phone</div>
                            <div>Номинация: $nomination</div>
                            <div>Паспортные данные: $pass</div>
                            <div>Место работы: $work_place</div>
                            <div>Стаж работы: $work_experience</div>
                            <div>Образование: $education</div>
                            <div>Наименование учебного заведения: $name_education</div>
                            <div>Повышение квалификации: $training</div>
                            <div>Индекс места жительства: $adress_index</div>
                            <div>Адрес места жительства: $home_adress</div>
                            <div>Телефон работодателя: $employer_phone</div>
                            <div>Рабочий адрес электронной почты: $work_email</div>
                            <div>Субьект РФ: $subjectname</div>
                            <div>Имя координатора: $coord_name </div>
                            <div>Дата регистрации: $reg_date </div>
                            <div>Статус аккаунта: $status </div>
                        </div>
                        <div id='editPartResults$id' style='display: none;'>
                            <h3 class='headH'>Результаты тестирований</h3>
                            <div class='partTestResultsTableHeader' onclick='dellPartTestResults(event)'>
                                <button name='partTestResultsTableHeader'>Удалить результат теста</button>
                                <div class='errDivDeletedTest' style='color: red;'></div>
                                <input value='$email' name='deletedTestPartEmail' style='display: none'>
                                <div>Колличество баллов за тест: $test_num_points</div>
                                <div>Баллы за творческую работу: $Kfull</div>
                                <div>Итоговый балл: ".($test_num_points + $Kfull)."</div>
                            </div>
                            $creative_work_html_res
                            $html_test
                        </div>
                        <div id='editPartEditorPage$id' class='hiddenEditorAdminInputs' style='display: none;' onclick='PartListEditOneClick(event)'>
                            <div class='newUserCard'>
                                <div style='display: none;'>
                                    <div class='newUserCardText'>Скрытый id:</div>
                                    <input class='newUserCardInput' name='editPartID' type='text' id='editPartID$id' placeholder='скрытый id' value='$id'>
                                </div>
                                <div>
                                    <div class='newUserCardText'>Электронная почта *:</div>
                                    <input class='newUserCardInput' name='editPartEmail' type='email' id='editPartEmail$id' placeholder='Email' value='$email'>
                                </div>
                                <div>
                                    <div class='newUserCardText'>Пароль (не изменится, если поле пустое):</div>
                                    <input class='newUserCardInput' name='editPartPassword' type='text' id='editPartPassword$id' placeholder='Пароль' value='$password'>
                                </div>
                                <div>
                                    <div class='newUserCardText'>Фамилия *</div>
                                    <input class='newUserCardInput' name='editPartLastName' type='text' id='editPartLast$id' placeholder='Фамилия' value='$last_name'>
                                </div>
                                <div>
                                    <div class='newUserCardText'>Имя *:</div>
                                    <input class='newUserCardInput' name='editPartName' type='text' id='editPartName$id' placeholder='Имя' value='$name'>
                                </div>
                                <div>
                                    <div class='newUserCardText'>Отчество *</div>
                                    <input class='newUserCardInput' name='editPartThirdName' type='text' id='editPartThirdName$id' placeholder='Отчество' value='$third_name'>
                                </div>
                                <div>
                                    <div class='newUserCardText'>Мобильный телефон *:</div>
                                    <input class='newUserCardInput' name='editPartPhone' type='tel' id='editPartPhone$id' placeholder='Телефон' value='$mobile_phone'>
                                </div>
                                <div>
                                    <div class='newUserCardText'>Рабочий телефон *:</div>
                                    <input class='newUserCardInput' name='editPartWorkPhone' type='tel' id='editPartWorkPhone$id' placeholder='Рабочий елефон' value='$work_phone'>
                                </div>
                                <div>
                                <div class='newUserCardText'>Номинация *:</div>
                                    <select class='editPartNomination' name='editPartNomination'>
                                        $nom_options
                                    </select>
                                </div>
                                <div>
                                    <div class='newUserCardText'>Паспортные данные (серия, номер, кем и когда выдан):</div>
                                    <input class='newUserCardInput' name='editPartPass' type='text' id='editPartPass$id' placeholder='Паспортные данные' value='$pass'>
                                </div>
                                <div>
                                    <div class='newUserCardText'>Место работы и должность (при наличии):</div>
                                    <input class='newUserCardInput' name='editPartWorkPlace' type='text' id='editPartWorkPlace$id' placeholder='Место работы' value='$work_place'>
                                </div>
                                <div>
                                    <div class='newUserCardText'>Стаж работы в сфере туризма *:</div>
                                    <input class='newUserCardInput' name='editPartWorkExperience' type='number' id='editPartWorkExperience$id' placeholder='Стаж работы' value='$work_experience'>
                                </div>
                                <div>
                                    <div class='newUserCardText'>Образование и специальность по диплому:</div>
                                    <input class='newUserCardInput' name='editPartEducation' type='text' id='editPartEducation$id' placeholder='Образование' value='$education'>
                                </div>
                                <div>
                                    <div class='newUserCardText'>Наименование учебного заведения:</div>
                                    <input class='newUserCardInput' name='editPartNameEducation' type='text' id='editPartNameEducation$id' placeholder='Название учебного заведения' value='$name_education'>
                                </div>
                                <div>
                                    <div class='newUserCardText'>Повышение квалификации:</div>
                                    <input class='newUserCardInput' name='editPartTraining' type='text' id='editPartNameTraining$id' placeholder='Повышение квалификации' value='$training'>
                                </div>
                                <div>
                                    <div class='newUserCardText'>Индекс места жительства:</div>
                                    <input class='newUserCardInput' name='editPartAdressIndex' type='text' id='editPartAdressIndex$id' placeholder='Индекс места жительства' value='$adress_index'>
                                </div>
                                <div>
                                    <div class='newUserCardText'>Адрес места жительства *:</div>
                                    <input class='newUserCardInput' name='editPartHomeAdress' type='text' id='editPartHomeAdress$id' placeholder='Адрес места жительства' value='$home_adress'>
                                </div>
                                <div>
                                    <div class='newUserCardText'>Телефон и факс работодателя (при наличии):</div>
                                    <input class='newUserCardInput' name='editPartEmployerPhone' type='text' id='editPartEmployerPhone$id' placeholder='Телефон и факс работодателя' value='$employer_phone'>
                                </div>
                                <div>
                                    <div class='newUserCardText'>Рабочий адрес электронной почты::</div>
                                    <input class='newUserCardInput' name='editPartWorkEmail' type='email' id='editPartWorkEmail$id' placeholder='Рабочий адрес электронной почты:' value='$work_email'>
                                </div>
                                <div>
                                    <div class='newUserCardText'>Субьект РФ:</div>
                                    <select class='editPartSubject' name='editPartSubject'>
                                        $subjectsOptions
                                    </select>
                                </div>
                                <div>
                                    <div class='newUserCardText'>Координатор</div>
                                    <select class='editPartCoord' name='editPartCoord'>
                                        $coord_select
                                    </select>
                                </div>
                                <div>
                                    <div class='newUserCardText'>Активен ли пользователь:
                                        <span><input type='checkbox' name='editPartCheckbox' class='checkbox' id='editPartCheckbox$id' $checkbox></span>
                                    </div>
                                </div>
                                <div class='PartListEditOneError'></div>
                                <button name='PartListEditOneClick'>Сохранить</button>
                            </div>
                        </div>
                    </section>";
}

echo($html);

?>
