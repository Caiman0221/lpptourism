<?php

//Загрузчик страницы для разных страниц и пользователей
function pageLoader($access_token,$page) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/core/other.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/core/links.php');

    $query = "SELECT access FROM t_new_users WHERE access_token = '".mb_convert_encoding($access_token, "cp1251")."';";
    $access = mysqliSelectQuery($query);
    $access = mb_convert_encoding($access,"utf-8", "cp1251");
    //$access = 'admin';
    //HTML для гостя

    $pageVal = (object) array();

    if ($access == null) {
        if ($page == 'main' || $page == 'private' || $page == 'news' || $page == 'adminzone') {
            require_once($_SERVER['DOCUMENT_ROOT'] . '/core/userContent/empty.php');
            $pageVal->menuNav = (menuNav($page));
            $pageVal->mainPartOfPage = (mainPartOfPage($page));
        } else {
            $pageVal->link = pageLinks('private');
        }
    }

    //HTML для админа
    if ($access == 'admin') {
        if ($page == 'main' ||  $page == 'news' || $page == 'adminzone' || $page == 'adminzone/index') {
            require_once($_SERVER['DOCUMENT_ROOT'] . '/core/userContent/admin.php');
            $pageVal->menuNav = (menuNav($page));
            $pageVal->mainPartOfPage = (mainPartOfPage($page));
        } else {
            $pageVal->link = pageLinks('adminzone/index');
        }
    }

    //HTML для модератора
    if ($access == 'moder') {}

    //HTML для пользователя
    if ($access == 'part') {
        if ($page == 'main' || $page == 'account' || $page == 'creative_work' || $page == 'news') {
            require_once($_SERVER['DOCUMENT_ROOT'] . '/core/userContent/part.php');
            $pageVal->menuNav = (menuNav($page));
            $pageVal->mainPartOfPage = (mainPartOfPage($page,$access_token));
        } else if ($page == 'adminzone'){

        } else if ($page == 'test') {
            require_once($_SERVER['DOCUMENT_ROOT'] . '/core/userContent/part.php');
            $pageVal->menuNav = (menuNav($page));
            require_once($_SERVER['DOCUMENT_ROOT'] . '/core/userContent/partFiles/startTest.php');
            $pageVal->mainPartOfPage = startPartTest($access_token);
        } else {
            $pageVal->link = pageLinks('account');
        }
    }

    //HTML для координатора
    if ($access == 'coord') {
        if ($page == 'main' || $page == 'account' || $page == 'news') {
            require_once($_SERVER['DOCUMENT_ROOT'] . '/core/userContent/coord.php');
            $pageVal->menuNav = (menuNav($page));
            $pageVal->mainPartOfPage = (mainPartOfPage($page));
        } else if ($page == 'adminzone'){

        } else {
            $pageVal->link = pageLinks('account');
        }

    }

    //HTML для эксперта
    if ($access == 'expert') {
        if ($page == 'main' || $page == 'account' || $page == 'news') {
            require_once($_SERVER['DOCUMENT_ROOT'] . '/core/userContent/expert.php');
            $pageVal->menuNav = (menuNav($page));
            $pageVal->mainPartOfPage = (mainPartOfPage($page,$access_token));
        } else if ($page == 'adminzone'){

        } else {
            $pageVal->link = pageLinks('account');
        }
    }

    //echo(var_dump($pageVal));
    return($pageVal);
}

?>
