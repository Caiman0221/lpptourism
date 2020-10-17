<?php

function NewsListContent() {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
    $html = '';
    $query = "SELECT * from t_new_news ORDER BY id DESC";
    $result = mysqliQuery($query);
    if ($result == false) {
      $html = 'Новостей еще нет';
      return $html;
    }
    $rows = mysqli_num_rows($result);
    for ($i = 0; $i < $rows; $i++) {
        $row = mysqli_fetch_row($result);

        $linkonnews = mb_convert_encoding($row[1],'utf-8','cp1251');
        $imglink = mb_convert_encoding($row[2],'utf-8','cp1251');

        $imglink = str_ireplace($_SERVER['DOCUMENT_ROOT'] . "/",'http://lpptourism.ru/',$imglink);
        if ($imglink != null) {
          $imglink = '<div style="text-align: center"><img src="'.$imglink.'" alt=""></div>';
        } else {
          $imglink = '';
        }
        $linkonnews = str_ireplace($_SERVER['DOCUMENT_ROOT'] . "/",'http://lpptourism.ru/',$linkonnews);
        //'.$linkonnews.'
        //'.$imglink.'
        $html = $html . '<section class="partWithNews mainPageSectionWithNews">
                    <div>
                      <a href="'.$linkonnews.'"><h2>'.mb_convert_encoding($row[3],"utf-8","cp1251").'</h2></a>
                      '.$row[6].'
                    </div>
                    '.$imglink.'
                    <div>'.mb_convert_encoding($row[4],"utf-8","cp1251").'</div>
                </section>';
    }
    return $html;
  }

?>
