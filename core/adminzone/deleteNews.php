<?php
//Страница удаления новостей!
$_POST = json_decode(file_get_contents("php://input"), true);

$id = $_POST['id'];
$buttonFunc = $_POST['buttonFunc'];

if ($buttonFunc == 'delete') {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php'); //подключаем модуль mysql
    $query = "SELECT url FROM t_new_news WHERE id = '$id';"; //делаем запрос
    $url = mysqli_fetch_row(mysqliQuery($query))[0];

    //Удаляем все файлы из папки
    if (file_exists($url))
        foreach (glob("$url/*") as $file)
        unlink($file);

    //удаляем директорию и запись о ней в БД
    if (rmdir($url) == true) {
        $query = "DELETE from t_new_news where id ='$id';";
        mysqliQuery($query);
    }

}

echo ('Новость была успешно удалена');



?>
