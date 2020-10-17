<?php

function startPartTest($access_token) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
    $query = "SELECT email from t_new_users where access_token = '$access_token';";
    $email = base64_decode(mysqli_fetch_row(mysqliQuery($query))[0]);

    //Узнаем номинацию;
    $query = "SELECT nomination from t_new_parts where email = '".mb_convert_encoding($email,"cp1251")."';";
    $nomination_id = mysqli_fetch_row(mysqliQuery($query))[0];
    if ($nomination_id == NULL) {
        return('<div class="testContainer">К Вашему аккаунту не прикреплена ни одна номинация, пожалуйства свяжитесь с администратором</div>');
    }

    $query = "SELECT test from t_new_nominations where id = '$nomination_id';";
    $test_id = mysqli_fetch_row(mysqliQuery($query))[0];
    if ($test_id == NULL) {
        return ('<div class="testContainer">Тест к вашей номинации еще не готов</div>');
    }

    $query = "SELECT tests_json from t_new_parts_works where email = '".mb_convert_encoding($email,"cp1251")."';";
    $tests_json = mysqliQuery($query);
    $tests_json = mysqli_fetch_row($tests_json)[0];
    $tests_json = mb_convert_encoding($tests_json, "utf-8", "cp1251");

    require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
    $query = "SELECT html from t_new_pages_editor where name = 'StartTestPage';";
    $discription = mysqli_fetch_row(mysqliQuery($query))[0];
    $discription = mb_convert_encoding($discription,"utf-8","cp1251");

    if ($tests_json == NULL) {
        $html = '<div id="startTestContainer" class="testContainer">
                    <div class="startTestContent">
                        '.$discription.'
                    </div>
                    <div class="testButtonsContainer">
                        <button class="testButtons" onclick="startTestButtonClick()">Перейти к тестированию</button>
                    </div>
                </div>';
                return ($html);
    }

    /*
    if ($tests_json == NULL) {
        return('<div class="testContainer">Тест к вашей номинации еще не готов</div>');
    }
    */

    $test = json_decode($tests_json);

    for ($i = 0; $i < count($test); $i++) {
        if (property_exists($test[$i],'part_answer') == false) {
            $obj = $test[$i];
            $question = $obj->question;
            $question_num = $obj->question_num;
            $question_time = $obj->time;
            $answers_arr = $obj->answers;
            $answers_num = $obj->answers_pos;

            require_once ($_SERVER['DOCUMENT_ROOT'] . '/core/other.php');
            if (property_exists($test[$i],'start_question_time') == false) {
                $start_question_time = unixRealTime();
                $obj->start_question_time = $start_question_time;
            }
            $test[$i] = $obj;

            for ($n = 0; $n < count($answers_num); $n++) {
                $answer_number_in_list = $n + 1;
                $answers_html .= '<div>
                                    <span class="testAnswer">'. $answer_number_in_list .') '.$answers_arr[$answers_num[$n]].'</span>
                                    <span class="testCheckbox">
                                        <input name="answerid" value="'.$answers_num[$n].'">
                                        <label for=""><input type="checkbox" class="testCheckboxInput" name="answecheck"></label>
                                    </span>
                                </div>';
            }
            break;
        }
    }
    if ($answers_html != '') {
        $html = '<div id="testContainer" class="testContainer" onclick="testButtonsClick(event)">
                    <div class="testContentQuestion">
                        ' . $question . '
                        <input id="questionID" name="questionID" value="'.$question_num.'" style="display: none;">
                        <input id="questionTime" value="'.$question_time.'" style="display: none;">
                    </div>
                    <div class="testContentAnswers" onclick="inputCheckClick(event)">
                        '.$answers_html.'
                    </div>
                    <div class="testButtonsContainer">
                        <button class="testButtons" id="testButtons" name="testButtons">Пропустить</button>
                        <button class="testButtonConfirm" id="testButtonConfirm" name="testButtonConfirm">Ответить</button>
                        <div id="timerContainer">Осталось:<span id="timerID"></span></div>
                    </div>
                </div>';

        $test_json = json_encode($test,JSON_UNESCAPED_UNICODE);
        $query = "UPDATE t_new_parts_works SET tests_json = '".mb_convert_encoding($test_json,"cp1251")."' where email = '".mb_convert_encoding($email,"cp1251")."';";
        mysqliQuery($query);
        return ($html);
    } else {
        $html_arr = [];
        for ($i = 0; $i < count($test); $i++) {
            $obj = $test[$i];
            $question = $obj->question;
            $question_num = $obj->question_num;
            $checkboxs_arr = $obj->checkboxs;
            $answers_arr = $obj->answers;
            $part_answer_arr = $obj->part_answer;
            $answers_html = '';
            for ($n = 0; $n < count($answers_arr); $n++) {
                if ($checkboxs_arr[$n] == $part_answer_arr[$n] && $checkboxs_arr[$n] == 'true') {
                    $greencolor = 'style="background: green"';
                } else if (($part_answer_arr[$n] == 'false' || $part_answer_arr[$n] == 'empty' || $part_answer_arr[$n] == 'time') && $checkboxs_arr[$n] == 'true') {
                    $greencolor = 'style="background: green"';
                } else if ($part_answer_arr[$n] == 'true' && $checkboxs_arr[$n] == 'false') {
                    $greencolor = 'style="background: red"';
                } else if ($part_answer_arr[$n] == 'empty' && $checkboxs_arr[$n] == 'false') {
                    $greencolor = 'style="background: yellow"';
                } else if ($part_answer_arr[$n] == 'time' && $checkboxs_arr[$n] == 'false') {
                    $greencolor = 'style="background: orange"';
                } else {
                    $greencolor = '';
                }
                $answers_html .= '<div>
                                    <span class="testAnswer">'.$answers_arr[$n].'</span>
                                    <span class="testCheckbox">
                                        <label for="" '.$greencolor.'><input type="checkbox" class="testCheckboxInput" name="answecheck" readonly></label>
                                    </span>
                                </div>';
            }
            $html_arr[$question_num] = '<div id="testContainer" class="testContainer">
                                            <div class="testContentQuestion">
                                                ' . $question . '
                                            </div>
                                            <div class="testContentAnswers">
                                                '.$answers_html.'
                                            </div>
                                        </div>';
        }
        for ($i = 0; $i < count($html_arr); $i++) {
            $html .= $html_arr[$i];
        }
        return ($html);
    }
}

?>
