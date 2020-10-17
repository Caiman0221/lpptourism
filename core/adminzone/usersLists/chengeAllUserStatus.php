<?php

$_POST = json_decode(file_get_contents("php://input"), true);

$ID = $_POST['adminsID']; //array
$user = $_POST['user']; //admin
$status = $_POST['status']; //activ or disabled

if (count($ID) == 0) {
    echo ('Вы не выбрали ни одного пользователя');
    return;
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/core/mysqli.php');
if ($user == 'admin') {
    $table = "t_new_admins";
} else if ($user == 'coord') {
    $table = "t_new_coords";
} else if ($user == 'expert') {
    $table = "t_new_experts";
} else if ($user == 'part') {
    $table = 't_new_parts';
}

if ($status == 'activ') {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/core/other.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/core/emailMessage/email.php');

    $mails_post_false = '';

    for ($i = 0; $i < count($ID); $i++) {
        $id = $ID[$i];
        $query = "SELECT email from $table where id = '$id';";
        $email = mysqli_fetch_row(mysqliQuery($query))[0];
        //echo ($email);
        $email64 = base64_encode($email);
        $query = "SELECT password, emailpass from t_new_users where email = '$email64';";
        $result = mysqli_fetch_row(mysqliQuery($query));
        $password = base64_decode($result[0]);
        $emailpass = $result[1];
        //Если у пользователя нет пароля или ему не отправлялся пароль на почту, необходимо отправить его заного
        if ($password == null || $emailpass == null) {
          //Если в БД не было пароля, генерируем новый
          if ($password == null) {
            $password = randomString(15);
          }
          $password64 = base64_encode($password);
            if ($user == 'admin') {
                if (mailNewAdmin($email,$password) != true) {
                    $mails_post_false .="$email<br>";
                } else {
                    $query = "UPDATE t_new_users SET password = '$password64', emailpass = 'post' where email = '$email64';";
                    mysqliQuery($query);
                    $query = "UPDATE $table SET status = '$status' WHERE email = '".mb_convert_encoding($email, "cp1251")."';";
                    mysqliQuery($query);
                }
            } else if ($user == 'coord') {
                if (mailNewCoord($email,$password) != true) {
                    $mails_post_false .= "$email<br>";
                } else {
                    $query = "UPDATE t_new_users SET password = '$password64', emailpass = 'post' where email = '$email64';";
                    mysqliQuery($query);
                    $query = "UPDATE $table SET status = '$status' WHERE email = '".mb_convert_encoding($email, "cp1251")."';";
                    mysqliQuery($query);
                }
            } else if ($user == 'expert') {
                if (mailNewExpert($email,$password) != true) {
                    $mails_post_false .= "$email<br>";
                } else {
                    $query = "UPDATE t_new_users SET password = '$password64', emailpass = 'post' where email = '$email64';";
                    mysqliQuery($query);
                    $query = "UPDATE $table SET status = '$status' WHERE email = '".mb_convert_encoding($email, "cp1251")."';";
                    mysqliQuery($query);
                }
            } else if ($user == 'part') {
                if (mailNewPart($email,$password) != true) {
                    $mails_post_false .= "$email<br>";
                } else {
                    $query = "UPDATE t_new_users SET password = '$password64', emailpass = 'post' where email = '$email64';";
                    mysqliQuery($query);
                    $query = "UPDATE $table SET status = '$status' WHERE email = '".mb_convert_encoding($email, "cp1251")."';";
                    mysqliQuery($query);
                }
            }
        } else {
            $query = "UPDATE $table SET status = '$status' WHERE email = '".mb_convert_encoding($email, "cp1251")."';";
            mysqliQuery($query);
        }
    }
    if ($mails_post_false == '') {
        echo ("Всем активированным пользователям были разосланы пароли<br>");
    } else {
        echo ("Произошла ошибка при отправке паролей у этих пользователей: $mails_post_false");
    }
} else if ($status == 'disabled') {
    for ($i = 0; $i < count($ID); $i++) {
        $id = $ID[$i];
        $query = "UPDATE $table SET status = '$status' WHERE id = '$id';";
        mysqliQuery($query);
    }
}


if ($status == 'activ' && count($ID) != 0) {
    echo("Пользователи были активированы");
} else if ($status == 'disabled' && count($ID) != 0) {
    echo("Пользователи были деактивированы");
} else {
    echo("Вы не выбрали ни одного пользователя");
}
?>
