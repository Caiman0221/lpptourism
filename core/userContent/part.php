<?php

function menuNav($page) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/core/links.php');
    if ($page == 'adminzone') {
        $pageVal = '<div class="adminEntrance">
            <div class="adminEntranceText">Панель администрирования</div>
            <div class="adminEntranceBorder">
                <div><input type="email" placeholder="email" id="adminemail"></div>
                <div><input type="password" placeholder="Пароль" id="adminpassword"></div>
                <div id="brokenPass"></div>
                <div><button onclick="accountOnClick()">Войти</button></div>
            </div>
        </div>';
    } else {
    $mainLink = pageLinks('main');
    $accountLink = pageLinks('account');
    $pageVal = '<menu class="menuContainer">
            <div class="menuButtons mainPageMenuButton"><a href="'.$mainLink.'">Главная</a></div>
            <div class="menuButtons mainPageMenuPartLK"><a href="'.$accountLink.'">Личный кабинет</a></div>
            <div class="menuButtons emptyPartMenuButton"></div>
            <div class="menuButtons entranceMenuButton">
                <a onclick="exitClick()">Выход</a>
            </div>
        </menu>';
    }
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
        require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
        $query = "SELECT email from t_new_users where access_token = '$access_token';";
        $email = base64_decode(mysqli_fetch_row(mysqliQuery($query))[0]);

        $query = "SELECT res_of_exp from t_new_parts_works where email = '$email';";
        $creative_task_link_json = mysqli_fetch_row(mysqliQuery($query))[0];
        $creative_task_link_json = mb_convert_encoding($creative_task_link_json,"utf-8","cp1251");
        $creative_task_link_obj = json_decode($creative_task_link_json);
        $creative_task_link = $creative_task_link_obj->creative_task_link;
        if ($creative_task_link == null) {
          $creative_task_link = "<div style='color: #666666;'>
            Вы еще не добавили работу
          </div>";
        } else {
          $creative_task_link = "<a href='$creative_task_link' target='_blank' rel='noopener noreferrer'>Сслыка на вашу работу</a>";
        }

        $pageVal = '<main class="mainContainer">
        <div id="participantPage">
            <div class="participantTable mainContainerContent">
                <div class="participantProfile">Анкета участника</div>
                <div id="participantTableData">

                </div>
            </div>

            <div class="participantTests mainContainerContent">
                <div class="participantsTestsContent">
                    <button class="passTheTestButton" onclick="passTheTestButton()">Страница тестирования</button>
                </div>
                <div class="participantsTestsContent">
                    <button class="creativeWorkButton" onclick="creativeWorkButtonClick()">Творческая
                        работа</button><br>
                </div>
                <div class="participantsTestsContent">
                    <h2>Загруженные файлы</h2>
                    <div id="partDataLoadedContainer">'.$creative_task_link.'</div>
                </div>
            </div>
        </div>
    </main>';
    } else if ($page == 'creative_work') {
        require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
        $query = "SELECT html from t_new_pages_editor where name = 'CreativWorkPage';";
        $discription = mysqli_fetch_row(mysqliQuery($query))[0];
        $discription = mb_convert_encoding($discription,"utf-8","cp1251");

        $pageVal = "<main class='mainContainer'>
        <!--container for uploading creative work-->
        <div class='creativeWorkLoadContainer'>
            <div>
                <h1>Вставьте ссылку на Вашу творческую работу</h1>
            </div>
            <div><input type='text' placeholder='Творческая работа' id='linkOnCreativWork'></div>
            <div id='errorLink'></div>
            <div><button onclick='uploadCreativWord()'>Загрузить работу</button></div>
        </div>
        <!--description of creative work-->
        <div class='creativeWorkInfo'>
            $discription
        </div>
    </main>";
    }

    return ($pageVal);
}
?>
