<?php

function menuNav($page) {
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
        $pageVal = '<menu class="menuContainer">
        <div class="menuButtons mainPageMenuButton"><a href="http://lpptourism.ru">Главная</a></div>
        <div class="menuButtons emptyMenuButton"></div>
        <div class="menuButtons entranceMenuButton">
            <a href="http://lpptourism.ru/private/">Войти в личный кабинет</a>
        </div>
        </menu>';
    }
    return $pageVal;
}

function mainPartOfPage($page) {
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
    } else if ($page == 'private') {
        require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
        $query = "SELECT html from t_new_pages_editor where name = 'PrivateTextPage';";
        $res = mysqliQuery($query);
        if ($res == null) {
            $discription = '';
        } else {
            $discription = mysqli_fetch_row($res)[0];
            $discription = mb_convert_encoding($discription, "utf-8", "cp1251");
        }

        $query = "SELECT html from t_new_pages_editor where name = 'checkboxForCoordReg';";
        $res = mysqliQuery($query);
        if ($res == null) {
            $check = '';
        } else {
            $check = mysqli_fetch_row(mysqliQuery($query))[0];
            $check = mb_convert_encoding($check,"utf-8","cp1251");
        }
        if ($check == 'true') {
            $coordRegTable = '<div class="coordRegContainer" id="coordRegContainer">
            <div class="coordRegContainerData">
                <h3>ФОРМА РЕГИСТРАЦИИ КООРДИНАТОРА</h3>
                <div>Субъект РФ</div>
                <select name="coordRegSubject" class="coordRegSelector" id="coordRegSelector">
                    <option value="">--субъекты рф--</option>
                    <option value="1">Республика Адыгея (Адыгея)</option>
                    <option value="2">Республика Алтай</option>
                    <option value="3">Республика Башкортостан</option>
                    <option value="4">Республика Бурятия</option>
                    <option value="5">Республика Дагестан</option>
                    <option value="6">Республика Ингушетия</option>
                    <option value="7">Кабардино-Балкарская Республика</option>
                    <option value="8">Республика Калмыкия</option>
                    <option value="9">Карачаево-Черкесская Республика</option>
                    <option value="10">Республика Карелия</option>
                    <option value="11">Республика Коми</option>
                    <option value="12">Республика Крым</option>
                    <option value="13">Республика Марий Эл</option>
                    <option value="14">Республика Мордовия</option>
                    <option value="15">Республика Саха (Якутия)</option>
                    <option value="16">Республика Северная Осетия – Алания</option>
                    <option value="17">Республика Татарстан (Татарстан)</option>
                    <option value="18">Республика Тыва</option>
                    <option value="19">Удмуртская Республика</option>
                    <option value="20">Республика Хакасия</option>
                    <option value="21">Чеченская Республика</option>
                    <option value="22">Чувашская Республика – Чувашия</option>
                    <option value="23">Алтайский край</option>
                    <option value="24">Забайкальский край</option>
                    <option value="25">Камчатский край</option>
                    <option value="26">Краснодарский край</option>
                    <option value="27">Красноярский край</option>
                    <option value="28">Пермский край</option>
                    <option value="29">Приморский край</option>
                    <option value="30">Ставропольский край</option>
                    <option value="31">Хабаровский край</option>
                    <option value="31">Амурская область</option>
                    <option value="33">Архангельская область</option>
                    <option value="34">Астраханская область</option>
                    <option value="35">Белгородская область</option>
                    <option value="36">Брянская область</option>
                    <option value="37">Владимирская область</option>
                    <option value="38">Волгоградская область</option>
                    <option value="39">Вологодская область</option>
                    <option value="40">Воронежская область</option>
                    <option value="41">Ивановская область</option>
                    <option value="42">Иркутская область</option>
                    <option value="43">Калининградская область</option>
                    <option value="44">Калужская область</option>
                    <option value="45">Кемеровская область</option>
                    <option value="46">Кировская область</option>
                    <option value="47">Костромская область</option>
                    <option value="48">Курганская область</option>
                    <option value="49">Курская область</option>
                    <option value="50">Ленинградская область</option>
                    <option value="51">Липецкая область</option>
                    <option value="52">Магаданская область</option>
                    <option value="53">Московская область</option>
                    <option value="54">Мурманская область</option>
                    <option value="55">Нижегородская область</option>
                    <option value="56">Новгородская область</option>
                    <option value="57">Новосибирская область</option>
                    <option value="58">Омская область</option>
                    <option value="59">Оренбургская область</option>
                    <option value="60">Орловская область</option>
                    <option value="61">Пензенская область</option>
                    <option value="62">Псковская область</option>
                    <option value="63">Ростовская область</option>
                    <option value="64">Рязанская область</option>
                    <option value="65">Самарская область</option>
                    <option value="66">Саратовская область</option>
                    <option value="67">Сахалинская область</option>
                    <option value="68">Свердловская область</option>
                    <option value="69">Смоленская область</option>
                    <option value="70">Тамбовская область</option>
                    <option value="71">Тверская область</option>
                    <option value="72">Томская область</option>
                    <option value="73">Тульская область</option>
                    <option value="74">Тюменская область</option>
                    <option value="75">Ульяновская область</option>
                    <option value="76">Челябинская область</option>
                    <option value="77">Ярославская область</option>
                    <option value="78">Город Москва</option>
                    <option value="79">Город Санкт-Петербург</option>
                    <option value="80">Город Севастополь</option>
                    <option value="81">Еврейская автономная область</option>
                    <option value="82">Ненецкий автономный округ</option>
                    <option value="83">Ханты-Мансийский автономный округ – Югра</option>
                    <option value="84">Чукотский автономный округ</option>
                    <option value="85">Ямало-Ненецкий автономный округ</option>
                </select>
            </div>
            <div>
                <div>Фамилия:</div>
                <input type="text" placeholder="Фамилия" id="coordLastName" maxlength="100">
            </div>
            <div>
                <div>Имя:</div>
                <input type="text" placeholder="Имя" id="coordFirstName" maxlength="100">
            </div>
            <div>
                <div>Рабочий телефон:</div>
                <input type="tel" placeholder="Рабочий номер телефона" id="coordWorkPhone" maxlength="40">
            </div>
            <div>
                <div>Мобильный телефон:</div>
                <input type="tel" placeholder="Мобильный" id="coordMobilePhone" maxlength="40">
            </div>
            <div>
                <div>Адрес электронной почты (он же логин):</div>
                <input type="email" placeholder="Email" id="coordEmail" maxlength="255">
            </div>
            <div>
                <div>Согласие на обработку персональных данных:</div>
                <input type="checkbox" class="checkbox" id="coordCheckbox">
            </div>
            <div id="coordRegErr"></div>
            <div class="coordRegButtonDiv">
                <button onclick="coordRegClick()">Подтвердить</button>
            </div>
        </div>';
        } else {
            $coordRegTable = '';
        }

        $pageVal = '<main class="mainContainer">
        <div class="inlineMainClass mainDocumentsContainerPhone" id="mainDocumentsContainerIdPhone">

        </div>
        <div class="inlineMainClass mainInfoContainer" id="mainInfoContainerId">
            <div class="private">
                '.$discription.'
            </div>
            <div class="privateEntrance">
                <div class="privateEntranceText">Уже зарегистрированы?</div>
                <div class="privateEntranceBorder">
                    <div><input type="email" placeholder="email" id="email"></div>
                    <div><input type="password" placeholder="Пароль" id="password"></div>
                    <div id="brokenPass"></div>
                    <div><button onclick="accountOnClick()">Войти</button></div>
                    <div class="forgotPassword"><a onclick="forgotPasswordClick()">Забыли пароль?</a></div>
                </div>
            </div>
        </div>
        <div class="inlineMainClass mainDocumentsContainerPC" id="mainDocumentsContainerIdPC">
            ' . $coordRegTable . '
        </div>
    </main>';
    }
    return $pageVal;
}
?>
