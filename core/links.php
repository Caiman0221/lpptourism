<?php

function pageLinks($pageLink) {
    $link = 'http://lpptourism.ru/';
    if ($pageLink == 'main') {
        $link = $link;
    } else if ($pageLink == 'private') {
        $link = $link . 'private';
    } else if ($pageLink == 'account') {
        $link = $link . 'account';
    } else if ($pageLink == 'creative-work') {
        $link = $link . 'account/creative-work';
    } else if ($pageLink == 'adminzone') {
        $link = $link . 'adminzone';
    } else if ($pageLink == 'adminzone/index') {
        $link = $link . 'adminzone/index';
    }
    return($link);
}

?>
