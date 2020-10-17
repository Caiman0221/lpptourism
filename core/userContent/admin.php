<?php

function menuNav($page) {
    if ($page == 'main' || $page == 'news') {
        require_once($_SERVER['DOCUMENT_ROOT'] . '/core/links.php');
        $mainLink = pageLinks('main');
        $accountLink = pageLinks('adminzone/index');
        $pageVal = '<menu class="menuContainer">
                <div class="menuButtons mainPageMenuButton"><a href="'.$mainLink.'">Главная</a></div>
                <div class="menuButtons mainPageMenuPartLK"><a href="'.$accountLink.'">Личный кабинет</a></div>
                <div class="menuButtons emptyPartMenuButton"></div>
                <div class="menuButtons entranceMenuButton">
                    <a onclick="exitClick()">Выход</a>
                </div>
            </menu>';
    } else if ($page == 'adminzone') {
        $pageVal = '<menu id="adminMenuNav">
        <div class="adminMenuNavCont1"><button onclick="adminMainPartClick()">Главная страница</button></div>
        <div class="adminMenuNavCont2"><button onclick="adminNewsClick()">Новости</button></div>
        <div class="adminMenuNavCont3"><button onclick="adminTestClick()">Тесты</button></div>
        <div class="adminMenuNavCont4"><button onclick="adminUsersClick()">Пользователи</button></div>
        <div class="adminMenuNavCont5"><button onclick="adminPageEditorClick()">Редактор страниц</button></div>
    </menu>';
    };
    return ($pageVal);
}

function mainPartOfPage($page) {
    if ($page == 'main') {
        require_once($_SERVER['DOCUMENT_ROOT'] . '/core/userContent/NewsListContent.php'); ///core/userContent/NewsListContent.php
        $newsHtml = NewsListContent();
        require_once($_SERVER['DOCUMENT_ROOT'] . '/core/other.php');
        $docslist = docsList('main');

        $pageVal = '<main class="mainContainer">
            <div class="inlineMainClass mainDocumentsContainerPhone" id="mainDocumentsContainerIdPhone" onload="LoaderDocsMainPage()">
                <h2>Документы конкурса</h2>
                    <div id="LoaderDocsMainPage" class="marginTop linksMargin MainPageDocs">
                        '.$docslist.'
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
        </main>';
    } else if ($page == 'adminzone') {
        require_once($_SERVER['DOCUMENT_ROOT'] . '/core/userContent/adminFiles/htmlParts.php');
        $pageVal = ''.adminMainPart() . adminNewsContainer().'';
    }

    return ($pageVal);
}


?>
