<?php

//(adminNewsContainer());

function adminMainPart() {
    $html = '
            <section id="adminMainPart" class="sectionContainers" style="display: block;">
                <article>
                    <h3>Добро пожаловать в панель администратора</h3>
                </article>
                Информация об обновлениях и ошибках (временно)
                <li>26.03 : </li>
            </section>
            <script>
                let adminMainPart = document.getElementById("adminMainPart");
                let adminNewsContainer = document.getElementById("adminNewsContainer");
                let adminTestsContainer = document.getElementById("adminTestsContainer");
                let adminUsersContainer = document.getElementById("adminUsersContainer");
                let adminPageEditor = document.getElementById("adminPageEditor");

                function adminMainPartClick() {
                    adminMainPart.style.display = "block";
                    adminNewsContainer.style.display = "none";
                    adminTestsContainer.style.display = "none";
                    adminUsersContainer.style.display = "none";
                    adminPageEditor.style.display = "none";
                }

                function adminNewsClick() {
                    adminMainPart.style.display = "none";
                    adminNewsContainer.style.display = "block";
                    adminTestsContainer.style.display = "none";
                    adminUsersContainer.style.display = "none";
                    adminPageEditor.style.display = "none";
                }

                function adminTestClick() {
                    adminMainPart.style.display = "none";
                    adminNewsContainer.style.display = "none";
                    adminTestsContainer.style.display = "block";
                    adminUsersContainer.style.display = "none";
                    adminPageEditor.style.display = "none";
                }

                function adminUsersClick() {
                    adminMainPart.style.display = "none";
                    adminNewsContainer.style.display = "none";
                    adminTestsContainer.style.display = "none";
                    adminUsersContainer.style.display = "block";
                    adminPageEditor.style.display = "none";
                }

                function adminPageEditorClick() {
                    adminMainPart.style.display = "none";
                    adminNewsContainer.style.display = "none";
                    adminTestsContainer.style.display = "none";
                    adminUsersContainer.style.display = "none";
                    adminPageEditor.style.display = "block";
                }

                //меню для новостей
                let newsAddArticle = document.getElementById("newsAddArticle");
                let newsListArticle = document.getElementById("newsListArticle");

                function newsAddClick() {
                    newsAddArticle.style.display = "block";
                    newsListArticle.style.display = "none";
                }

                function newsListClick() {
                    newsAddArticle.style.display = "none";
                    newsListArticle.style.display = "block";
                }
            </script>';
    return $html;
}

function adminNewsContainer() {
    $news = NewsListContent();
    $html = '<section id="adminNewsContainer" class="sectionContainers" style="display: none;">
                <menu class="hiddenMenuNav">
                    <div class="hiddenMenuNav1"><button onclick="newsAddClick()">Добавление новостей</button></div>
                    <div class="hiddenMenuNav2"><button onclick="newsListClick()">Список новостей fuf</button></div>
                </menu>
                <!-- news add -->
                <div id="newsAddArticle" style="display: none;">
                    <article>
                        <h2>Добавить новость</h2>
                    </article>
                    <div id="newsAddUrl" class="newsContent">
                        <div class="newsAddInputArticle">
                            Ссылка (если пустая, делается автоматически):
                        </div>
                        <input type="text" id="newsAddInputUrl">
                    </div>
                    <div id="newsAddPhoto" class="newsContent">
                        <div class="newsAddInputArticle">
                            Фото:
                        </div>
                        <input type="file" id="newsAddInputPhoto">
                    </div>
                    <div id="newsAddName" class="newsContent">
                        <div class="newsAddInputArticle">
                            Название:
                        </div>
                        <input type="text" id="newsAddInputName" value="название новости">
                    </div>
                    <div id="newsAddDiscription" class="newsContent">
                        <div class="newsAddInputArticle">
                            Краткое описание:
                        </div>
                        <input type="text" id="newsAddInputDescription" value="description">
                    </div>
                    <div id="newsAddText" class="newsContent">
                        <div class="newsAddInputArticle">
                            Содержание:
                        </div>
                        <input type="text" id="newsAddInputText" value="text">
                    </div>
                    <button id="newsPublish" onclick="newsPublishClick()">Опубликовать</button>
                </div>

                <!-- news list -->
                <div id="newsListArticle" style="display: block;">
                    <article>
                        <h2>Список новостей</h2>
                    </article>
                    <!-- news table -->
                    <div class="noMobileDesign">
                        <div class="newsHiddenInfo newsHiddenInfoDiv1">
                            <div>Название: </div>
                            <div>Дата публицакии: </div>
                        </div>
                        <div class="newsHiddenInfo newsHiddenInfoDiv2">Описание: </div>
                        <div class="newsHiddenInfo newsHiddenInfoDiv3">Фото: </div>
                    </div>

                    <!-- one news -->
                    <div id="newsListContainer">
                        '. $news .'
                    </div>
                </div>
                <script>
                    let newsAddArticle = document.getElementById("newsAddArticle");
                    let newsListArticle = document.getElementById("newsListArticle");

                    function newsAddClick() {
                        newsAddArticle.style.display = "block";
                        newsListArticle.style.display = "none";
                    }

                    function newsListClick() {
                        newsAddArticle.style.display = "none";
                        newsListArticle.style.display = "block";
                    }
                </script>
            </section>';
    return $html;
}

function NewsListContent() {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php'); ///core/mysqli.php
    $query = "SELECT * from t_new_news ORDER BY id DESC";
    $result = mysqliQuery($query);
    $rows = mysqli_num_rows($result);
    for ($i = $rows; $i >= 1; $i = $i - 1) {
        $row = mysqli_fetch_row($result);
        $id = mb_convert_encoding($row[0],'utf-8', 'cp1251');
        $url = mb_convert_encoding($row[1],'utf-8', 'cp1251');
        $url = str_ireplace($_SERVER['DOCUMENT_ROOT'] . '/news/','',$url);
        $photo = mb_convert_encoding($row[2],'utf-8', 'cp1251');
        $photo = str_ireplace($_SERVER['DOCUMENT_ROOT'] . '/','http://lpptourism.ru/',$photo);
        $name = mb_convert_encoding($row[3],'utf-8', 'cp1251');
        $description = mb_convert_encoding($row[4],'utf-8', 'cp1251');
        $text = mb_convert_encoding($row[5],'utf-8', 'cp1251');
        $publish_time = mb_convert_encoding($row[6],'utf-8', 'cp1251');
        $html = '<div class="oneNewsFromList">
                            <div id="visibleNewsPart'.$id.'" class="visibleNewsPart" style="display: block;">
                                <div class="visibleNewsName">
                                    <h2>'.$name.'</h2>
                                    '.$publish_time.'
                                </div>
                                <div class="visibleNewsDescription">'.$description.'</div>
                                <div class="visibleNewsImg"><img src="'.$photo.'" alt=""></div>
                            </div>
                            <div class="hiddenNewsPart" style="display: none;" id="hiddenNewsPart'.$id.'">
                                <button class="deleteNewsButton" onclick="deleteNewsButton'.$id.'()">Удалить новость</button>
                                <button class="closeNewsButton" onclick="closeNewsButton'.$id.'()">Закрыть новость</button>
                                <button class="saveNewsButton" onclick="saveNewsButton'.$id.'()">Сохранить изменения</button>
                                <div class="hiddenUrlDiv">
                                    <div>Ссылка:</div>
                                    <input type="text" id="editUrlInput'.$id.'" value="'.$url.'">
                                </div>
                                <div class="hiddenPhotoDiv">
                                    <div>Фото:</div>
                                    <input type="file" id="editPhotoInput'.$id.'">
                                </div>
                                <div class="hiddenNameDiv">
                                    <div>Название:</div>
                                    <input type="text" id="editNameInput'.$id.'" value="'.$name.'">
                                </div>
                                <div class="hiddenDescriptionDiv">
                                    <div>Описание:</div>
                                    <input type="text" id="editDescriptionInput'.$id.'" value="'.$description.'">
                                </div>
                                <div class="hiddenTextDiv">
                                    <div>Содержание:</div>
                                    <input type="text" id="editTextInput'.$id.'" value="'.$text.'">
                                </div>
                            </div>
                            <script>
                                let visibleNewsPart'.$id.' = document.getElementById("visibleNewsPart'.$id.'")
                                let hiddenNewsPart'.$id.' = document.getElementById("hiddenNewsPart'.$id.'");

                                visibleNewsPart'.$id.'.onclick = function() {
                                    visibleNewsPart'.$id.'.style.display = "none";
                                    hiddenNewsPart'.$id.'.style.display = "block";
                                }

                                function deleteNewsButton'.$id.'() {
                                    let buttonData = {
                                        id: "'.$id.'",
                                        buttonFunc: "delete"
                                    }
                                    DeleteButtonsFetch(buttonData)
                                }

                                function closeNewsButton'.$id.'() {
                                    visibleNewsPart'.$id.'.style.display = "block";
                                    hiddenNewsPart'.$id.'.style.display = "none";
                                }

                                function saveNewsButton'.$id.'() {
                                    let saveData = new FormData()
                                    saveData.append("id", "'.$id.'");
                                    saveData.append("url", document.getElementById("editUrlInput'.$id.'").value);
                                    saveData.append("photo", document.getElementById("editPhotoInput'.$id.'").files[0]);
                                    saveData.append("name", document.getElementById("editNameInput'.$id.'").value);
                                    saveData.append("description", document.getElementById("editDescriptionInput'.$id.'").value);
                                    saveData.append("text", document.getElementById("editTextInput'.$id.'").value);

                                    fetchNews("http://lpptourism.ru/core/adminzone/editNews.php", saveData);
                                }
                            </script>
                        </div>';
    }

    return $html;
}
?>
