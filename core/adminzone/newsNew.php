<?php

//Собираем данные из админки для добавления новой новости
$url = $_POST['url'];
$photo = $_FILES['photo'];
$name = $_POST['name'];
$description = $_POST['description'];
$text = $_POST['text'];

//Если url пустая, то вписываем название новости транслитом
require_once($_SERVER['DOCUMENT_ROOT'] . '/core/other.php');

$url = rus2translit($name);

//Ссылка на новый каталог для новости
$catalogdir = $_SERVER['DOCUMENT_ROOT'] . "/news/" . $url;

//Проверяем нет ли такой новости уже
require($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
$query = "SELECT * from t_new_news where url = '$catalogdir';";
$result = mysqliQuery($query);
if ($result != false) {
  if (mysqli_num_rows($result) > 0) {
      echo("Новость с таким названием уже есть");
      return;
  }
}
//Если таблицы новостей еще нет, то создаем
$query = "CREATE TABLE IF NOT EXISTS t_new_news (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    url TEXT,
    photo TEXT,
    name TEXT,
    description MEDIUMTEXT,
    text MEDIUMTEXT,
    publish_time DATETIME NOT NULL DEFAULT NOW()
);";
mysqliSelectQuery($query);

//создаем каталог
if (!is_dir($catalogdir)) {
  mkdir($catalogdir, 0777);
}
//chmod($catalogdir, 0777);

//Если фото было загружено, то формируем для него ссылку и новое название
if (file_exists($_FILES['photo']['tmp_name'])) {
    //Формируем ссылку
    $ext = substr($_FILES['photo']['name'], 1 + strrpos($_FILES['photo']['name'], ".")); //png, jpg ...
    $query = "SELECT max(id) from t_new_news;"; //имя фотографии
    $res = mysqliQuery($query);
    if ($res == false) {
      $maxid = 0;
    } else {
      $maxid = mysqli_fetch_row($res)[0] + 1;
    }

    $photoURL = "$catalogdir/$maxid.$ext"; ///news/nazvanie_novosti
    //Сохраняем на сервере
    if (is_uploaded_file($_FILES['photo']['tmp_name'])) {
        move_uploaded_file($_FILES['photo']['tmp_name'],"$photoURL"); //$photoURL exemple = /news/Chto_novogo_v_Ubuntu_20.04/1.png
    }
} else {
    $photoURL = null;
}

//Записываем новую новость в таблицу
if ($name != '' && $description != '' && $text != '') {
    $query = "INSERT INTO t_new_news VALUES (
      null,
      '".mb_convert_encoding($catalogdir, "cp1251")."',
      '".mb_convert_encoding($photoURL, "cp1251")."',
      '".mb_convert_encoding($name, "cp1251")."',
      '".mb_convert_encoding($description, "cp1251")."',
      '".mb_convert_encoding($text, "cp1251")."',
      now()
    );";
    mysqliSelectQuery($query);
}
///home/h812193481/lpptourism.ru/docs/news/Otredaktirovannaya_testovaya_novost/1.jpg

$query = "SELECT publish_time FROM t_new_news WHERE url = '$catalogdir';";
$publishTime = mysqli_fetch_row(mysqliQuery($query))[0];

$photoLink = str_ireplace($_SERVER['DOCUMENT_ROOT'],'http://lpptourism.ru',$photoURL);

$html = '<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="windows-1251">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Всероссийский конкурс профессионального мастерства работников сферы туризма</title>
    <link rel="stylesheet" href="http://lpptourism.ru/style/style.css" type="text/css">
    <link rel="stylesheet" href="http://lpptourism.ru/account/account.css" type="text/css">
    <link rel="stylesheet" href="http://lpptourism.ru/news/news.css" type="text/css">
    <script src="http://lpptourism.ru/JS/login.js"></script>
</head>

<body>
    <div class="wrapper">

        <!-- main information about lpptourism and other-->
        <div id="headerDiv">
            <header class="informationContainer" id="headerInfoEdited">
                <div class="inlineContainer logoContainer">
                    <img src="http://lpptourism.ru/files/logos/lpit_logo%20copy.jpg" alt="">
                </div>
                <div class="inlineContainer aboutInfo">
                    <h2>Всероссийский конкурс профессионального мастерства работников сферы туризма</h2>
                    <h1>ЛУЧШИЙ ПО ПРОФЕССИИ В ИНДУСТРИИ ТУРИЗМА</h1>
                </div>
                <div class="inlineContainer contacts">
                    <div class="contactsContainer">
                        <div>
                            Оргкомитет:<br>
                            <a href="tel:8 800 700 37 26">8 800 700 37 26</a><br>
                            <a href="mailto:lpptourism@gmail.com">lpptourism@gmail.com</a><br>
                        </div>
                    </div>
                </div>
                <div class="inlineContainer tourismLogo">
                    <img src="http://lpptourism.ru/files/logos/partner.svg" alt="">
                </div>
            </header>
        </div>

        <!-- button to go in main page and in other pages-->
        <div id="menuNav">

        </div>

        <!-- main part, dynamic updated to other pages-->
        <div id="mainPartOfPage">
            <section>
                <div>
                    <h2>'.$name.'</h2>
                    '.$publishTime.'
                </div>
                <div><img src="'.$photoLink.'" alt=""></div>
                <div>'.$text.'</div>
            </section>
        </div>
    </div>
    <div id="footerDiv">
        <footer>
            <!-- button to go in main page and in other pages-->
            <div id="menuNav2">

            </div>

            <!-- black information contant about project-->
            <div class="blackInformationContainer">
                <div class="informationContainer" id="blackHeaderInfoEdited">
                    <div class="inlineContainer logoContainer">
                        <img src="http://lpptourism.ru/files/logos/lpit_logo_light.png" alt="">
                    </div>
                    <div class="inlineContainer contacts">
                        <div class="contactsContainer">
                            <div>
                                Оргкомитет:<br>
                                <a href="tel:8 800 700 37 26">8 800 700 37 26</a><br>
                                <a href="mailto:lpptourism@gmail.com">lpptourism@gmail.com</a><br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>

<script>
    TokenCheck("http://lpptourism.ru/core/token.php", "news");
</script>
<script src="http://lpptourism.ru/JS/headerInfoLoader.js"></script>
</html>';

//создаем html
$file = $catalogdir . "/index.html";
$fp = fopen($file, 'w'); // ("r" - считывать "w" - создавать "a" - добовлять к тексту),мы создаем файл
fwrite($fp, $html);
fclose($fp);

for ($i=0; $i <= count(scandir($catalogdir)); $i++) {
    $dir = scandir($catalogdir)[$i];
    $dir = "$catalogdir/$dir";
    //chmod($dir, 0777);
}

echo ('Новость была успешно опубликова')
?>
