<?php
//Редактирование новостей

$id = $_POST['id'];
$url = $_SERVER['DOCUMENT_ROOT'] . "/news/".$_POST['url'];
$photo = $_FILES['photo'];
$name = $_POST['name'];
$description = $_POST['description'];
$text = $_POST['text'];

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
$query = "SELECT * from t_new_news where id = '$id'";
$result = mysqli_fetch_row(mysqliQuery($query));
$result[1] = mb_convert_encoding($result[1],'utf-8', 'cp1251');
$result[2] = mb_convert_encoding($result[2],'utf-8', 'cp1251');
$result[3] = mb_convert_encoding($result[3],'utf-8', 'cp1251');
$result[4] = mb_convert_encoding($result[4],'utf-8', 'cp1251');
$result[5] = mb_convert_encoding($result[5],'utf-8', 'cp1251');

//Проверяем url
if ($url == ($_SERVER['DOCUMENT_ROOT'] . '/news/')) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/core/other.php');
    $url = "$url". rus2translit($name);
}

if (file_exists($_FILES['photo']['tmp_name'])) {
    unlink($result[2]);
    $ext = substr($_FILES['photo']['name'], 1 + strrpos($_FILES['photo']['name'], ".")); //png, jpg ...
    $photoLink = $result[1]."/$id.$ext";
    if (is_uploaded_file($_FILES['photo']['tmp_name'])) {
        move_uploaded_file($_FILES['photo']['tmp_name'],$photoLink); //$photoURL exemple = /news/Chto_novogo_v_Ubuntu_20.04/1.png
        $photoLink_mysql = $url."/$id.$ext";
        $todb = "photo = '".mb_convert_encoding($photoLink_mysql, 'cp1251')."' ";
        $new_html_photo_link = str_ireplace($_SERVER['DOCUMENT_ROOT'],'http://lpptourism.ru',$photoLink_mysql);
    }
} else {
    //$result[2];
    $new_photo_link_mysql = str_ireplace($result[1],$url,$result[2]);
    $todb = "photo = '".mb_convert_encoding($new_photo_link_mysql, 'cp1251')."' ";
    $new_html_photo_link = str_ireplace($_SERVER['DOCUMENT_ROOT'],'http://lpptourism.ru',$new_photo_link_mysql);
}

if ($url != $result[1]) {
    $photoLink = "$url/$id.$ext";
    if ($todb != '') {$todb = $todb . ", ";}
    $todb = $todb . "url = '".mb_convert_encoding($url, 'cp1251')."' "; // меняет ссылку на новость и ссылку на фото к новости
    for ($i=0; $i <= count(scandir($result[1])); $i++) {
        $dir = scandir($result[1])[$i];
        $dir = $result[1]."/$dir";
        //chmod($dir, 0777);
    }
    rename($result[1],$url);
}

//обновляем название
if ($name != $result[3]) {
    if ($todb != '') {$todb = $todb . ", ";}
    $todb = $todb . "name = '".mb_convert_encoding($name, 'cp1251')."' ";
}
//обновляем описание
if ($description != $result[4]) {
    if ($todb != '') {$todb = $todb . ", ";}
    $todb = $todb . "description = '".mb_convert_encoding($description, 'cp1251')."' ";
}
//обновляем текст
if ($text != $result[5]) {
    if ($todb != '') {$todb = $todb . ", ";}
    $todb = $todb . "text = '".mb_convert_encoding($text, 'cp1251')."' ";
}

//mb_convert_encoding($row[0], 'cp1251')

$queryfull = "UPDATE t_new_news SET $todb WHERE id = '$id';";
mysqliQuery($queryfull);
//echo $queryfull;

///home/h812193481/lpptourism.ru/docs
///home/h812193481/lpptourism.ru/docs/news/Otredaktirovannaya_testovaya_novost/1.jpg

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
                <div><img src="'. $new_html_photo_link .'" alt=""></div>
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
$file = $url . "/index.html";
$fp = fopen($file, 'w'); // ("r" - считывать "w" - создавать "a" - добовлять к тексту),мы создаем файл
fwrite($fp, $html);
fclose($fp);

echo ('Новость была отредактирована');
?>
