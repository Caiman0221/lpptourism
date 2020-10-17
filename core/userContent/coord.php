<?php

function coordNormDocsLoader() {
    $url = 'http://lpptourism.ru/files/docsForUsers/coords'; ///files/docsForUsers/coords
    $res = '';
    $i = 2;
    $dir = str_ireplace('http://lpptourism.ru/',$_SERVER['DOCUMENT_ROOT'] . "/",$url);
    while ($i < count(scandir($dir))) {
      $file = scandir($dir)[$i];
      $file_link = $url . "/$file";
      $res = $res . "<a href='$file_link' download=''>$file</a><br>";
      $i++;
    }
    return $res;
}

function coordNominationsSelect() {
    $query = "SELECT * from t_new_nominations";
    $html = "<option value=''>Выберите номинацию</option>";
    $result = mysqliQuery($query);
    $rows = mysqli_num_rows($result);
    for ($i = 1; $i <= $rows; $i++) {
        $row = mysqli_fetch_row($result);
        $id = $row[0];
        $nominationName = $row[1];
        $nominationName = mb_convert_encoding($nominationName,'utf-8', "cp1251");
        $html = $html . '<option value="'.$id.'">'.$nominationName.'</option>';
    }
    return $html;
}

function menuNav($page) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/core/links.php');

    $mainLink = pageLinks('main');
    $accountLink = pageLinks('account');
    if ($page == 'main' || $page == 'news') {
    $pageVal = '<menu class="menuContainer">
            <div class="menuButtons mainPageMenuButton"><a href="'.$mainLink.'">Главная</a></div>
            <div class="menuButtons mainPageMenuPartLK"><a href="'.$accountLink.'">Личный кабинет</a></div>
            <div class="menuButtons emptyPartMenuButton"></div>
            <div class="menuButtons entranceMenuButton">
                <a onclick="exitClick()">Выход</a>
            </div>
        </menu>';
    } else if ($page == 'account') {
        $pageVal = '<menu class="coordMenuContainer" id="coordMenuContainer">
            <div class="coordCabinetContainer">
                <button class="coordCabinet" onclick="coordCabinet()">Личный кабинет</button>
            </div>
            <div class="coordRegulationsContainer">
                <button class="coordRegulations" onclick="coordRegulations()">Скачать нормативные документы</button>
            </div>
            <div class="coordDownloadWorkLogContainer">
                <button class="coordDownloadWorkLog" onclick="coordDownloadWorkLog()">Загрузить протоколы работы конкурсной комиссии</button>
            </div>
            <div class="coordCreateProfileContainer">
                <button class="coordCreateProfile" onclick="coordCreateProfile()">Создать профиль участника федерального этапа</button>
            </div>
            <div class="coordExitContainer">
                <button class="coordExit" onclick="exitClick()">Выйти</button>
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

function mainPartOfPage($page) {
    if ($page == 'main') {
        require_once($_SERVER['DOCUMENT_ROOT'] . '/core/userContent/NewsListContent.php'); // /core/userContent/NewsListContent.php
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
        $pageVal = '
        <div id="coordPage" class="coordPage">
            <div id="coordPageCabinet" class="coordPageCabinet" style="display: block;">
                <div id="visiblePartOfCoordMainPage">
                    <div class="coordPageCabinetContent">
                        <h2>Загруженные файлы</h2>
                        <div id="normFilesContainer" class="normFilesContainer">

                        </div>
                        <div id="dellNormDocsErrDiv"></div>
                    </div>
                    <div class="coordPageCabinetContent">
                        <h2>Созданные профили</h2>
                        <div class="tableForPartsList">
                            <!-- Таблица уже созданных профилей -->
                            <div id="coordAllCreatedPartsList">

                            </div>
                            <div id="errDivForPartsList"></div>
                        </div>
                    </div>
                </div>
                <div id="hiddenPartOfCoordMainPage" style="display: none;">
                    <h2>Редактировать профиль участника федерального этапа</h2>
                    <button class="yellowButton" onclick="coordCloseEditPartClick()">Закрыть</button>
                    <div class="coordNewPartTable" id="coordEditPartTable" onclick="submitEditPartProfile(event)">
                        <div style="display: none;">
                            <input type="number" placeholder="id" name="ctuID" >
                        </div>
                        <div>
                            <h3>Электронная почта *</h3>
                            <input type="text" placeholder="Электронная почта" name="ctuEmail" maxlength="255">
                        </div>
                        <div>
                            <h3>Фамилия *</h3>
                            <input type="text" placeholder="Фамилия" name="ctuLastName" maxlength="100">
                        </div>
                        <div>
                            <h3>Имя *</h3>
                            <input type="text" placeholder="Имя" name="ctuName" maxlength="100">
                        </div>
                        <div>
                            <h3>Отчество *</h3>
                            <input type="text" placeholder="Отчество" name="ctuThirdName" maxlength="100">
                        </div>
                        <div>
                            <h3>Мобильный телефон *</h3>
                            <input type="tel" placeholder="Мобильный телефон" name="ctuMobilePhone" maxlength="25">
                        </div>
                        <div>
                            <h3>Рабочий телефон *</h3>
                            <input type="tel" placeholder="Рабочий телефон" name="ctuWorkPhone" maxlength="25">
                        </div>
                        <div>
                            <h3>Номинация / подноминация *</h3>
                            <select name="ctuNomination" class="ctuNomination" id="editListOfNomination">

                            </select>
                        </div>
                        <div>
                            <h3>Паспортные данные (серия, номер, кем и когда выдан)</h3>
                            <input type="text" placeholder="Паспортные данные" name="ctuPass" maxlength="255">
                        </div>
                        <div>
                            <h3>Место работы и должность (при наличии)</h3>
                            <input type="text" placeholder="Место работы и должность" name="ctuWorkPlace" maxlength="255">
                        </div>
                        <div>
                            <h3>Стаж работы в сфере туризма *</h3>
                            <input type="number" placeholder="Стаж работы" name="ctuWorkExperience">
                        </div>
                        <div>
                            <h3>Образование и специальность по диплому</h3>
                            <input type="text" placeholder="Образование" name="ctuEducation" maxlength="255">
                        </div>
                        <div>
                            <h3>Наименование учебного заведения (при наличии нескольких указывать все)</h3>
                            <input type="text" placeholder="Наименование учебного заведения" name="ctuNameEducation" maxlength="255">
                        </div>
                        <div>
                            <h3>Повышение квалификации (при наличии) (дата, уч. заведение и тема)</h3>
                            <input type="text" placeholder="Повышение квалификации" name="ctuTraining" maxlength="255">
                        </div>
                        <div>
                            <h3>Индекс места жительства</h3>
                            <input type="text" placeholder="Индекс места жительства" name="ctuIndex" maxlength="15">
                        </div>
                        <div>
                            <h3>Адрес места жительства *</h3>
                            <input type="text" placeholder="Адрес места жительства" name="ctuHomeAdress" maxlength="255">
                        </div>
                        <div>
                            <h3>Телефон и факс работодателя (при наличии)</h3>
                            <input type="text" placeholder="Телефон и факс работодателя" name="ctuEmployerPhone" maxlength="50">
                        </div>
                        <div>
                            <h3>Рабочий адрес электронной почты</h3>
                            <input type="text" placeholder="Рабочий адрес электронной почты" name="ctuWorkEmail" maxlength="255">
                        </div>
                        <div>
                            <h3>Загруженные документы участника</h3>
                            <div id="containerLoadedFilesForPart">

                            </div>
                        </div>
                        <div style="padding-top: 15px;">
                          <h3>Загрузить дополнительные документы:</h3>
                        </div>
                        <div>
                            <h3>Анкета участника</h3>
                            <div class="ButtonsForLoading" onchange="getTextFromEditInput(event)">
                                <label for="ctuEditMemberProfile" class="editPartLabel">Выберите файл</label>
                                <input type="file" name="DocEditInput" id="ctuEditMemberProfile" style="display: none;">
                            </div>
                        </div>
                        <div>
                            <h3>Другие документы</h3>
                            <div class="ButtonsForLoading" onchange="getTextFromEditInput(event)">
                                <label for="ctuEditOtherDocs1" class="editPartLabel">Выберите файл</label>
                                <input type="file" name="DocEditInput" id="ctuEditOtherDocs1" style="display: none;">
                            </div>
                            ' . /*
                            <div class="ButtonsForLoading" onchange="getTextFromEditInput(event)">
                                <label for="ctuEditOtherDocs2" class="editPartLabel">Выберите файл</label>
                                <input type="file" name="DocEditInput" id="ctuEditOtherDocs2" style="display: none;">
                            </div>
                            <div class="ButtonsForLoading" onchange="getTextFromEditInput(event)">
                                <label for="ctuEditOtherDocs3" class="editPartLabel">Выберите файл</label>
                                <input type="file" name="DocEditInput" id="ctuEditOtherDocs3" style="display: none;">
                            </div>
                            <div class="ButtonsForLoading" onchange="getTextFromEditInput(event)">
                                <label for="ctuEditOtherDocs4" class="editPartLabel">Выберите файл</label>
                                <input type="file" name="DocEditInput" id="ctuEditOtherDocs4" style="display: none;">
                            </div>
                            <div class="ButtonsForLoading" onchange="getTextFromEditInput(event)">
                                <label for="ctuEditOtherDocs5" class="editPartLabel">Выберите файл</label>
                                <input type="file" name="DocEditInput" id="ctuEditOtherDocs5" style="display: none;">
                            </div>
                            */
                            '
                        </div>
                        <div id="coordEditPartErr"></div>
                        <button name="submitEditPartProfile" class="yellowButton">Сохранить</button>
                    </div>
                </div>
            </div>
            <div id="coordPageRegulations" class="coordPageRegulations" style="display: none;">
                <h2>Скачать нормативные документы</h2>
                <div>
                    '. coordNormDocsLoader() .'
                </div>
            </div>
            <div id="coordPageDownloadWorkLog" class="coordPageDownloadWorkLog" style="display: none;">
                <h2>Загрузить протоколы работы</h2>
                <div class="ButtonsForLoading" onchange="getTextFromInput(event)">
                    <label for="normDocInput">Выберите файл</label>
                    <input type="file" name="DocInput" id="normDocInput" style="display: none;">
                </div>
                Максимальный размер файла 10Мб<br>
                <div id="nomrDocsErrDiv" class="errDivNormFiles"></div>
                <button onclick="normDocsLoader()">Загрузить</button>
            </div>
            <div id="coordPageCreateProfile" class="coordPageCreateProfile" style="display: none;">
                <h2>Создать профиль участника федерального этапа</h2>
                <!-- таблица для создания нового пользователя-->
                <div class="coordNewPartTable">
                    <div>
                        <h3>Электронная почта *</h3>
                        <input type="text" placeholder="Электронная почта" id="ctuEmail" maxlength="255">
                    </div>
                    <div>
                        <h3>Фамилия *</h3>
                        <input type="text" placeholder="Фамилия" id="ctuLastName" maxlength="100">
                    </div>
                    <div>
                        <h3>Имя *</h3>
                        <input type="text" placeholder="Имя" id="ctuName" maxlength="100">
                    </div>
                    <div>
                        <h3>Отчество *</h3>
                        <input type="text" placeholder="Отчество" id="ctuThirdName" maxlength="100">
                    </div>
                    <div>
                        <h3>Мобильный телефон *</h3>
                        <input type="tel" placeholder="Мобильный телефон" id="ctuMobilePhone" maxlength="25">
                    </div>
                    <div>
                        <h3>Рабочий телефон *</h3>
                        <input type="tel" placeholder="Рабочий телефон" id="ctuWorkPhone" maxlength="25">
                    </div>
                    <div>
                        <h3>Номинация / подноминация *</h3>
                        <select name="" id="ctuNomination" class="ctuNomination">
                            '.coordNominationsSelect().'
                        </select>
                    </div>
                    <div>
                        <h3>Паспортные данные (серия, номер, кем и когда выдан)</h3>
                        <input type="text" placeholder="Паспортные данные" id="ctuPass" maxlength="255">
                    </div>
                    <div>
                        <h3>Место работы и должность (при наличии)</h3>
                        <input type="text" placeholder="Место работы и должность" id="ctuWorkPlace" maxlength="255">
                    </div>
                    <div>
                        <h3>Стаж работы в сфере туризма *</h3>
                        <input type="number" placeholder="Стаж работы" id="ctuWorkExperience">
                    </div>
                    <div>
                        <h3>Образование и специальность по диплому</h3>
                        <input type="text" placeholder="Образование" id="ctuEducation" maxlength="255">
                    </div>
                    <div>
                        <h3>Наименование учебного заведения (при наличии нескольких указывать все)</h3>
                        <input type="text" placeholder="Наименование учебного заведения" id="ctuNameEducation" maxlength="255">
                    </div>
                    <div>
                        <h3>Повышение квалификации (при наличии) (дата, уч. заведение и тема)</h3>
                        <input type="text" placeholder="Повышение квалификации" id="ctuTraining" maxlength="255">
                    </div>
                    <div>
                        <h3>Индекс места жительства</h3>
                        <input type="text" placeholder="Индекс места жительства" id="ctuIndex" maxlength="15">
                    </div>
                    <div>
                        <h3>Адрес места жительства *</h3>
                        <input type="text" placeholder="Адрес места жительства" id="ctuHomeAdress" maxlength="255">
                    </div>
                    <div>
                        <h3>Телефон и факс работодателя (при наличии)</h3>
                        <input type="text" placeholder="Телефон и факс работодателя" id="ctuEmployerPhone" maxlength="50">
                    </div>
                    <div>
                        <h3>Рабочий адрес электронной почты</h3>
                        <input type="text" placeholder="Рабочий адрес электронной почты" id="ctuWorkEmail" maxlength="255">
                    </div>
                    <div>
                        <h3>Анкета участника *</h3>
                        <div class="ButtonsForLoading" onchange="getTextFromInput(event)" id="ctuMemberProfileContainer">
                            <label for="ctuMemberProfile" id="ctuMemberProfileLabel">Выберите файл</label>
                            <input type="file" name="DocInput" id="ctuMemberProfile" style="display: none;">
                        </div>
                    </div>
                    <div>
                        <h3>Другие документы</h3>
                        <div class="ButtonsForLoading" onchange="getTextFromInput(event)" id="ctuOtherDocs1Container">
                            <label for="ctuOtherDocs1" id="ctuOtherDocs1Label">Выберите файл</label>
                            <input type="file" name="DocInput" id="ctuOtherDocs1" style="display: none;">
                        </div>
                        <div class="ButtonsForLoading" onchange="getTextFromInput(event)" id="ctuOtherDocs2Container">
                            <label for="ctuOtherDocs2" id="ctuOtherDocs2Label">Выберите файл</label>
                            <input type="file" name="DocInput" id="ctuOtherDocs2" style="display: none;">
                        </div>
                        <div class="ButtonsForLoading" onchange="getTextFromInput(event)" id="ctuOtherDocs3Container">
                            <label for="ctuOtherDocs3" id="ctuOtherDocs3Label">Выберите файл</label>
                            <input type="file" name="DocInput" id="ctuOtherDocs3" style="display: none;">
                        </div>
                        <div class="ButtonsForLoading" onchange="getTextFromInput(event)" id="ctuOtherDocs4Container">
                            <label for="ctuOtherDocs4" id="ctuOtherDocs4Label">Выберите файл</label>
                            <input type="file" name="DocInput" id="ctuOtherDocs4" style="display: none;">
                        </div>
                        <div class="ButtonsForLoading" onchange="getTextFromInput(event)" id="ctuOtherDocs5Container">
                            <label for="ctuOtherDocs5" id="ctuOtherDocs5Label">Выберите файл</label>
                            <input type="file" name="DocInput" id="ctuOtherDocs5" style="display: none;">
                        </div>
                    </div>
                    <div id="coordNewPartErr"></div>
                    <button onclick="submitUserProfile()" id="submitUserProfile">Отправить</button>
                </div>
            </div>
        </div>';
    }

    return ($pageVal);
}
?>
