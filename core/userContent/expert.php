<?php

function menuNav($page) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/core/links.php');
    $mainLink = pageLinks('main');
    $accountLink = pageLinks('account');
    if ($page == 'main' || $page == 'account') {
    $pageVal = '<menu class="menuContainer">
            <div class="menuButtons mainPageMenuButton"><a href="'.$mainLink.'">Главная</a></div>
            <div class="menuButtons mainPageMenuPartLK"><a href="'.$accountLink.'">Личный кабинет</a></div>
            <div class="menuButtons emptyPartMenuButton"></div>
            <div class="menuButtons entranceMenuButton">
                <a onclick="exitClick()">Выход</a>
            </div>
        </menu>';
    } else if ($page == 'adminzone') {
        $pageVal = '<div class="adminEntrance">
        <div class="adminEntranceText">Панель администрирования</div>
        <div class="adminEntranceBorder">
            <div><input type="email" placeholder="email" id="adminemail"></div>
            <div><input type="password" placeholder="Пароль" id="adminpassword"></div>
            <div id="brokenPass"></div>
            <div><button onclick="accountOnClick()">Войти</button></div>
        </div>
    </div>';
    };
    return ($pageVal);
}

function mainPartOfPage($page,$access_token) {
    if ($page == 'main') {
        require_once($_SERVER['DOCUMENT_ROOT'] . '/core/userContent/NewsListContent.php');
        $newsHtml = NewsListContent();
        require_once($_SERVER['DOCUMENT_ROOT'] . '/core/other.php');
        $docslist = docsList('main');

        $pageVal = '<main class="mainContainer">
        <div class="inlineMainClass mainDocumentsContainerPhone" id="mainDocumentsContainerIdPhone">
            <h2>Документы конкурса</h2>
            <div id="LoaderDocsMainPage" class="marginTop linksMargin MainPageDocs">
                '.$docslist.'
            </div>
        </div>
        </div>
        <div class="inlineMainClass mainInfoContainer" id="mainInfoContainerId">
        '.$newsHtml.'
        </div>
        <div class="inlineMainClass mainDocumentsContainerPC" id="mainDocumentsContainerIdPC">
            <h2>Документы конкурса</h2>
            <div id="LoaderDocsMainPage" class="marginTop linksMargin MainPageDocs">
                '.$docslist.'
            </div>
        </div>
        </div>
    </main>';
    } else if ($page == 'account') {

        require_once($_SERVER['DOCUMENT_ROOT'] . '//core/mysqli.php');
        //email эксперта
        $query = "SELECT email from t_new_users where access_token = '$access_token';";
        $email = base64_decode(mysqli_fetch_row(mysqliQuery($query))[0]);
        $email = mb_convert_encoding($email, "utf-8", "cp1251");

        //список проставленных вариантов
        $query = "SELECT results FROM t_new_experts where email = '".mb_convert_encoding($email, "cp1251")."';";
        $result = mysqliQuery($query);
        if ($result != false || $result != null) {
          $ex_res_obj = mysqli_fetch_row($result)[0];
          $ex_res_obj = mb_convert_encoding($ex_res_obj,'utf-8','cp1251');
          $ex_res_obj = json_decode($ex_res_obj);
        } else {
          $ex_res_obj = null;
        }

        //загружаем список номинаций
        $query = "SELECT * from t_new_nominations;";
        $nomination_res = mysqliQuery($query);
        $nomination_rows = mysqli_num_rows($nomination_res);
        for ($i = 0; $i < $nomination_rows; $i++) {
            $nomination_row = mysqli_fetch_row($nomination_res);
            $nomination_id = mb_convert_encoding($nomination_row[0],'utf-8','cp1251');
            $nomination_name = mb_convert_encoding($nomination_row[1],'utf-8','cp1251');
            $options .= "<option value='$nomination_id'>$nomination_name</option>";  //записали все о номинации

            //Заготовка для таблицы пользователей
            $table .= "<div id='tableForNomination$nomination_id' style='display: none;'>";

            $query = "SELECT * from t_new_parts where nomination = '$nomination_id';";
            $parts_res = mysqliQuery($query);
            $parts_rows = mysqli_num_rows($parts_res);
            for ($n = 0; $n < $parts_rows; $n++) {
                $parts_row = mysqli_fetch_row($parts_res);
                $part_id = mb_convert_encoding($parts_row[0],"utf-8", "cp1251");
                $part_email = mb_convert_encoding($parts_row[1],"utf-8", "cp1251");
                $part_name = mb_convert_encoding($parts_row[2],"utf-8", "cp1251");
                $part_last_name = mb_convert_encoding($parts_row[3],"utf-8", "cp1251");
                $part_subjectname = mb_convert_encoding($parts_row[17],"utf-8", "cp1251");

                if ($ex_res_obj != null) {
                  if (array_key_exists($part_id,$ex_res_obj) == false) {
                      $res_arr = $ex_res_obj->$part_id;
                      $K1 = (int) $res_arr[0];
                      $K2 = (int) $res_arr[1];
                      $K3 = (int) $res_arr[2];
                      $K4 = (int) $res_arr[3];
                      $K5 = (int) $res_arr[4];
                      $partRes = $K1 + $K2 + $K3 + $K4 + $K5;
                      if ($K5 == 1) {
                          $K5 = 'checked';
                      } else {
                          $K5 = '';
                      }
                  } else {
                      $K1 = '';
                      $K2 = '';
                      $K3 = '';
                      $K4 = '';
                      $K5 = '';
                  }
                } else {
                  $K1 = '';
                  $K2 = '';
                  $K3 = '';
                  $K4 = '';
                  $K5 = '';
                }
                $query = "SELECT res_of_exp from t_new_parts_works where email = '$part_email';";
                $creative_task_link = json_decode(mysqli_fetch_row(mysqliQuery($query))[0]);
                $creative_task_link = $creative_task_link->creative_task_link;
                $creative_task_link = mb_convert_encoding($creative_task_link, "utf-8","cp1251");
                if ($creative_task_link == '') {
                    $creative_task_link = '<div style="color: red;">Работы нет</div>';
                    $readonly = 'readonly';
                    $button_disable = "disabled";
                } else {
                    $creative_task_link = "<a target='_blank' rel='noopener noreferrer' href='$creative_task_link'>Ссылка</a>";
                    $readonly = '';
                    $button_disable = "";
                }

                $query = "SELECT tests_json from t_new_parts_works where email = '".mb_convert_encoding($part_email,"cp1251")."';";
                $part_test_json = mysqli_fetch_row(mysqliQuery($query))[0];
                $part_test_json = mb_convert_encoding($part_test_json,"utf-8","cp1251");
                $part_test = json_decode($part_test_json);
                $test_result_for_part = 0;
                for ($m = 0; $m < count($part_test); $m++) {
                    $question_obj = $part_test[$m];
                    $true_res_arr = $question_obj->checkboxs;
                    $part_res_arr = $question_obj->part_answer;
                    $question_points = $question_obj->points;
                    if ($true_res_arr === $part_res_arr) {
                        $test_result_for_part += $question_points;
                    }
                }
                $table .= "<div class='ExpertTable' onclick='expertTableClick(event)'>
                                <div class='inlineExpertTable' style='display: none;'>
                                    <input name='partID' value='$part_id'>
                                </div>
                                <div class='inlineExpertTable partName'>
                                    $part_last_name $part_name
                                </div>
                                <div class='inlineExpertTable partLocation'>
                                    $part_subjectname
                                </div>
                                <div class='inlineExpertTable partTest'>
                                    $test_result_for_part
                                </div>
                                <div class='inlineExpertTable partK1'>
                                    <input name='partK1' type='number' value='$K1' class='expertKInputs' min='0' max='5' $readonly>
                                    <button name='partK1Button' class='submitExpertButton' $button_disable>V</button>
                                </div>
                                <div class='inlineExpertTable partK2'>
                                    <input name='partK2' type='number' value='$K2' class='expertKInputs' min='0' max='5' $readonly>
                                    <button name='partK2Button' class='submitExpertButton' $button_disable>V</button>
                                </div>
                                <div class='inlineExpertTable partK3'>
                                    <input name='partK3' type='number' value='$K3' class='expertKInputs' min='0' max='5' $readonly>
                                    <button name='partK3Button' class='submitExpertButton' $button_disable>V</button>
                                </div>
                                <div class='inlineExpertTable partK4'>
                                    <input name='partK4' type='number' value='$K4' class='expertKInputs' min='0' max='5' $readonly>
                                    <button name='partK4Button' class='submitExpertButton' $button_disable>V</button>
                                </div>
                                <div class='inlineExpertTable partK5'>
                                    <input name='partcheck' type='checkbox' class='expertK5Checkbox' $K5 $readonly>
                                </div>
                                <div class='inlineExpertTable partRes'>
                                    <input name='partRes' type='number' value='$partRes' class='expertKInputs' readonly>
                                </div>
                                <div class='inlineExpertTable partWork'>
                                    $creative_task_link
                                </div>
                            </div>";
            }
            $table .= "</div>";
        }

        $pageVal = '<main class="mainExpertContainer">
        <!-- Анкета эксперта -->
        <div id="expertPage">
            <div class="expertContainer">
                <div class="expertContainerNaming">
                    <h2>Эксперт</h2>
                    <div>Оценка номинации:</div>
                    <div>
                        <select name="" id="nominationExpertSelect" onchange="expertSelectNomination(this)">
                            <option value="">Выберите номинацию</option>
                            ' . $options . '
                        </select>
                    </div>
                </div>
                <div class="tableForExpert">
                    <div class="tableForExpertName">Электронный протокол оценки</div>
                    <div class="tableForExpertContainer">
                        <div class="ExpertTable">
                            <div class="tableHeader">
                                <div class="inlineExpertTable partName">
                                    <b>ФИО участника</b>
                                </div>
                                <div class="inlineExpertTable partLocation">
                                    <b>Субъект РФ</b>
                                </div>
                                <div class="inlineExpertTable partTest">
                                    <b>Баллы за тест</b>
                                </div>
                                <div class="inlineExpertTable partK1">
                                    <b>Соответствие работы заявленной теме</b>
                                </div>
                                <div class="inlineExpertTable partK2">
                                    <b>Актуальность, новизна, целостность видео-контента</b>
                                </div>
                                <div class="inlineExpertTable partK3">
                                    <b>Оригинальность идеи, композиция</b>
                                </div>
                                <div class="inlineExpertTable partK4">
                                    <b>Информационная содержательность</b>
                                </div>
                                <div class="inlineExpertTable partK5">
                                    <b>Соответствие регламенту</b>
                                </div>
                                <div class="inlineExpertTable partRes">
                                    <b>Итог</b>
                                </div>
                                <div class="inlineExpertTable partWork">
                                    <b>Работа</b>
                                </div>
                            </div>
                        </div>
                        <div id="expertTablePartsContainer">
                            '.$table.'
                        </div>
                    </div>
                </div>
            </div>
        </div></main>';
    }

    return ($pageVal);
}
?>
