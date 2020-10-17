<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
$data = $_POST['data'];

$query = "SELECT * FROM t_new_tests;";
$result = mysqliQuery($query);
if ($result == false) {
  echo('Еще не добавлено ни одного теста');
  return;
}

$rows = mysqli_num_rows($result);
for ($i = 1; $i <= $rows; $i++) {
    $row = mysqli_fetch_row($result);
    $id = $row[0];
    $query_test_nomination = "SELECT id from t_new_nominations where test = '$id';";
    $id_test_nom = mysqli_fetch_row(mysqliQuery($query_test_nomination))[0];
    $name = $row[1];
    $questions_array = json_decode(mb_convert_encoding($row[2],"utf-8", "cp1251"));

    $html = $html . "<div class='hiddenListTest' onclick='hiddenListTestClick(this); closeHiddenTest(event)'>
                        <div>
                            ".mb_convert_encoding($name,"utf-8", "cp1251")."
                        </div>
                        <div class='HiddenTest' style='display: none;'>
                            <div class='addTestInDB'>
                                <button name='closeEditTest' class='yellowButton'>Закрыть</button>
                            </div>
                            <div class='OneTestContainer' onclick='addNewQuestionClick(event); addTestInDBClick(event)'>
                                <div class='OneTestName'>
                                    <h2>Название теста:</h2>
                                </div>
                                <div class='OneTestNameInput'>
                                    <input type='number' name='OneTestIDnum' value='$id' style='display: none;'>
                                    <input type='text' name='OneTestName' placeholder='Название теста' value='".mb_convert_encoding($name,"utf-8", "cp1251")."' onchange='inputCheck(event)'>
                                </div>
                                <div>Выберите номинацию (если не выбирать, тест не прикрепится к номинации)</div>
                                <div>
                                    <select name='Nomination' id='newTestNominationSelect' class='selectorForUsers'>
                                        <option value='0'>Оставить тест без номинации</option>";
    $query_nominations = "SELECT * FROM t_new_nominations";
    $result_nominations = (mysqliQuery($query_nominations));
    $rows_nominations = mysqli_num_rows($result_nominations);
    $sel = '';
    for ($z = 1; $z <= $rows_nominations; $z++) {
        $row_nom = mysqli_fetch_row($result_nominations);
        $id_nom = $row_nom[0];
        $name_nom = mb_convert_encoding($row_nom[1],"utf-8", "cp1251");
        $test_nom = mb_convert_encoding($row_nom[2],"utf-8", "cp1251");
        if ($id == $test_nom) {
            $sel = 'selected';
        } else {
            $sel = '';
        }
        $html = $html . "<option value='$id_nom' $sel>$name_nom</option>";
    }

                    $html = $html . "</select>
                                    <script>
                                        NominationSelect('newTestNominationSelect')
                                    </script>
                                </div>
                                <div class='QuestionsContainer'>";

    for ($n = 0; $n < count($questions_array); $n++) {
        $question_obj = $questions_array[$n];
        $question = ($question_obj->question);
        $time = $question_obj->time;
        $points = $question_obj->points;
        $question_number = $n + 1;
        $html = $html . "<div class='OneQuestionContainer' onclick='DellQuestionClick(event)'>
                            <div class='QuestionContainer'>
                                <div class='QuestionContent'>
                                    <div><input type='number' name='QuestionNumber' class='QuestionNumber' style='display: none;' value='$question_number'></div>
                                    <div>$question_number) Вопрос:</div>
                                    <div><input type='text' name='Question' class='Questions' placeholder='Вопрос' value='$question' onchange='inputCheck(event)'></div>
                                </div>
                                <div class='AnswersContainer' onclick='AddNewAnsewerClick(event)'>
                                    <div class='OneAnswerContainer'>";

        for ($m = 0; $m < count($question_obj->answers); $m++) {
            if ($question_obj->checkboxs[$m] == 'true') {
                $check = 'checked';
            } else {
                $check = '';
            }
            $answer = $question_obj->answers[$m];
            $html = $html . "<div class='AnswerContent' onclick='DellAnswerClick(event)'>
                                <span><input type='checkbox' class='AnsewerCheckboxes' name='TrueAnswer' $check></span>
                                <span><input type='text' name='AnswerInput' class='AnswerInputClass' placeholder='Ответ' value='$answer' onchange='inputCheck(event)'></span>
                                <span><button name='DeleteAnswerButton' class='dellDocButton'>X</button></span>
                            </div>";
        }

        $html = $html . "
        </div>
        <div class='addNewAnswerButtonContainer'>
            <button name='addNewAnswerClick' class='greenButton'>Добавить ответ</button>
        </div>
    </div>
    <div class='OtherInfoOfAnswerContainer'>
        <span>
            <div>Время на ответ</div>
            <div><input type='number' name='TimeForAnswer' value='$time'></div>
        </span>
        <span>
            <div>Баллы за ответ</div>
            <div><input type='number' name='PointsForAnswer' value='$points'></div>
        </span>
    </div>
</div>
<div class='DellQuestionButtonContainer'>
    <button name='DellQuestionClick' class='dellDocButton'>X</button>
</div>
</div>";

    }

    $html = $html . "
    </div>
    <div class='addNewQuestionButtonContainer'>
        <button name='addNewQuestionClick' class='greenButton'>Добавить вопрос</button>
        <!-- добавить вопрос -->
    </div>
    <div class='errorDivContainer'></div>
    <div class='addTestInDB'>
        <button name='addTestInDBClick' class='yellowButton'>Сохранить</button>
    </div>
    <div class='addTestInDB'>
        <button name='closeEditTest' class='yellowButton'>Закрыть</button>
    </div>
</div>
</div>
</div>";

}

echo ($html);

?>
