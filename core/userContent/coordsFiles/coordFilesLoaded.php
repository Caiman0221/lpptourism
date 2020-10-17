<?php
$_POST = json_decode(file_get_contents("php://input"), true);

$access_token = $_POST['access_token'];


require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
$query = "SELECT email from t_new_users where access_token = '$access_token';";
$email64 = mysqli_fetch_row(mysqliQuery($query))[0];

if ($email64 == '') {
    echo("Произошла ошибка при загрузке данных 1");
    return;
}

$email = base64_decode($email64);

$query = "SELECT last_name, first_name, subjectname from t_new_coords where email = '$email';";
$result = mysqli_fetch_row(mysqliQuery($query));

if ($result == '') {
    echo("Произошла ошибка при загрузке данных 2");
    return;
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/other.php');
$last_name = rus2translit(mb_convert_encoding($result[0],'utf-8', "cp1251"));
$first_name = rus2translit(mb_convert_encoding($result[1],'utf-8', "cp1251"));
$subjectnum = mb_convert_encoding($result[2],'utf-8', "cp1251");

$search_name = $last_name . "_" . $first_name;

$subjectname = RFsubjects($subjectnum);
$subjectnameTR = rus2translit($subjectname);

$dir = $_SERVER['DOCUMENT_ROOT'] . "/files/normFiles";
for ($i=2; $i < count(scandir($dir)); $i++) {
    if (scandir($dir)[$i] == $subjectnameTR) {
        $dir_sub = "$dir/$subjectnameTR";
        for ($n = 2; $n < count(scandir($dir_sub)); $n++) {
            if (strpos(scandir($dir_sub)[$n], "$search_name") !== false) {
                $href = "$dir_sub/".scandir($dir_sub)[$n];
                $href = str_ireplace($_SERVER['DOCUMENT_ROOT'] . "/",'http://lpptourism.ru/',$href);
                $link_name = scandir($dir_sub)[$n];
                $html = $html . "<div class='normFileContent' onclick='dellNormFileCoordClick(event)'>
                                    <div class='normFileContentLink'>
                                        <a href='$href' download>$link_name</a>
                                    </div>
                                    <div class='normFileContentButton'>
                                        <button name='dellDocButton' class='dellDocButton'>X</button>
                                    </div>
                                </div>";
            }
        }
    }
}
if ($html == '') {
    echo("У вас нет созданных документов");
} else {
    echo $html;
}

?>
