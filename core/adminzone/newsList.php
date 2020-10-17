<?php

//функция получения списка новостей для Главной страницы
(NewsListContent());

function NewsListContent() {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php'); ///core/mysqli.php
    $html = '';
    $query = "SELECT * from t_new_news ORDER BY id DESC";
    $result = mysqliQuery($query);
    if ($result == NULL) {
      echo("
      <div class='errNewsDivAll'>
        Ни одна новость пока не создана
      </div>
      <style>
        .errNewsDivAll {
          width: 100%;
          text-align: center;
          color: #666666;
          font-size: 18px;
        }
      </style>
      ");
      return;
    }
    $rows = mysqli_num_rows($result);
    for ($i = $rows; $i >= 1; $i = $i - 1) {
        $row = mysqli_fetch_row($result);
        $id = mb_convert_encoding($row[0], "utf-8", 'cp1251');
        $url = mb_convert_encoding($row[1], "utf-8", 'cp1251');
        $url = str_ireplace($_SERVER['DOCUMENT_ROOT'] . '/news/','',$url);
        $photo = mb_convert_encoding($row[2], "utf-8", 'cp1251');
        $photo = str_ireplace($_SERVER['DOCUMENT_ROOT'],'http://lpptourism.ru',$photo);
        $name = mb_convert_encoding($row[3], "utf-8", 'cp1251');
        $description = mb_convert_encoding($row[4], "utf-8", 'cp1251');
        $text = mb_convert_encoding($row[5], "utf-8", 'cp1251');
        $publish_time = mb_convert_encoding($row[6], "utf-8", 'cp1251');
        $html = $html . '<section class="oneNewsFromList" onclick="visibleNewsClick(event)">
                            <div id="visibleNewsPart'.$id.'" class="visibleNewsPart" style="display: block;">
                                <div class="visibleNewsName">
                                    <h2>'.$name.'</h2>
                                    '.$publish_time.'
                                </div>
                                <div class="visibleNewsDescription">'.$description.'</div>
                                <div class="visibleNewsImg"><img src="'.$photo.'" alt=""></div>
                            </div>
                            <div class="hiddenNewsPart" style="display: none;" id="hiddenNewsPart'.$id.'" onclick="oneNewsEditClick(event)">
                                <button class="deleteNewsButton hiddenNewsButtons" name="deleteNewsButton">Удалить новость</button>
                                <button class="closeNewsButton hiddenNewsButtons" name="closeNewsButton">Закрыть новость</button>
                                <button class="saveNewsButton hiddenNewsButtons" name="saveNewsButton">Сохранить изменения</button>
                                <div class="saveNewsDivErr"></div>
                                <div style="display: none">
                                    <div>Скрытый id</div>
                                    <input name="editInputID" type="number" id="editIDInput'.$id.'" value="'.$id.'">
                                </div>
                                <div class="hiddenUrlDiv" style="display: none">
                                    <div>Ссылка:</div>
                                    <input name="editInputURl" type="text" id="editUrlInput'.$id.'" value="">
                                </div>
                                <div class="hiddenNameDiv">
                                    <div>Название:</div>
                                    <input name="editInputName" type="text" id="editNameInput'.$id.'" value="'.$name.'">
                                </div>
                                <div class="hiddenPhotoDiv">
                                    <div>Фото:</div>
                                    <input name="editInputPhoto" type="file" id="editPhotoInput'.$id.'">
                                </div>
                                <div class="hiddenDescriptionDiv">
                                    <div>Описание:</div>
                                    <!-- <input type="text" id="editDescriptionInput'.$id.'" value="'.$description.'"> -->
                                    <div class="editNewsSummerDescription" id="editDescriptionInput'.$id.'">'.$description.'</div>
                                </div>
                                <div class="hiddenTextDiv">
                                    <div>Содержание:</div>
                                    <!-- <input type="text" id="editTextInput'.$id.'" value="'.$text.'"> -->
                                    <div class="editNewsSummerText" id="editTextInput'.$id.'">'.$text.'</div>
                                </div>
                            </div>
                        </section>
                    ';
    }
    echo $html;
}

?>
