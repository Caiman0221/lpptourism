<?php

$_POST = json_decode(file_get_contents("php://input"), true);

$email = $_POST['email'];
$email64 = base64_encode($_POST['email']);
$password64 = base64_encode($_POST['password']);
$page = $_POST['page'];

$object = (object) array();
//$email64 = base64_encode('admin@test.ru');
//$password64 = base64_encode('adminpass');

if ($email64 != null) {
    require('mysqli.php');
    if ($email64 != null) {
        $status_query = "SELECT access from t_new_users where email = '$email64';";
        $res = mysqli_fetch_row(mysqliQuery($status_query))[0];

        if ($res == 'admin') {
            $status_query = "SELECT * from t_new_admins where email = '$email';";
        } else if ($res == 'coord') {
            $status_query = "SELECT * from t_new_coords where email = '$email';";
        } else if ($res == 'part') {
            $status_query = "SELECT * from t_new_parts where email = '$email';";
        } else if ($res == 'expert') {
            $status_query = "SELECT * from t_new_experts where email = '$email';";
        }
        $status_res = mysqli_fetch_row(mysqliQuery($status_query))[0];
        if ($status_res == 'disabled') {
            $object->answer = "Ваш аккаунт был временно заблокирован";
            echo(json_encode($object));
            return;
        }
    }
    $query = "SELECT * FROM t_new_users WHERE email = '$email64';";
    if (mysqliSelectQuery($query) == true) {
        if ($password64 != null) {
            $query = "SELECT * FROM t_new_users WHERE email = '$email64' AND password = '$password64';";
            if (mysqliSelectQuery($query) == true) {
                //Проверка статуса пользователя (activ/disable)

                $query = "SELECT access from t_new_users where email = '$email64';";
                $res = mysqli_fetch_row(mysqliQuery($query))[0];
                if ($res == 'admin') {
                    $query = "SELECT status from t_new_admins where email = '$email';";
                } else if ($res == 'coord') {
                    $query = "SELECT status from t_new_coords where email = '$email';";
                } else if ($res == 'part') {
                    $query = "SELECT status from t_new_parts where email = '$email';";
                } else if ($res == 'expert') {
                    $query = "SELECT status from t_new_experts where email = '$email';";
                }
                $res = mysqli_fetch_row(mysqliQuery($query))[0];
                if ($res == 'disabled') {
                    $object->answer = "Ваш пользователь был временно заблокирован";
                    echo(json_encode($object));
                    return;
                }

                require($_SERVER['DOCUMENT_ROOT'] . '/core/other.php');
                $new_access_token = randomString(45);
                $new_refresh_token = randomString(60);
                $datetime = realTime();

                $query = "UPDATE t_new_users SET
                    access_token = '$new_access_token',
                    refresh_token = '$new_refresh_token',
                    access_token_date = '$datetime',
                    refresh_token_date = '$datetime' WHERE
                    email = '$email64';";
                mysqliQuery($query);
                $unixdatetime = strtotime($datetime);

                $object->access_token = $new_access_token;
                $object->refresh_token = $new_refresh_token;
                $object->token_time = $unixdatetime;
                if ($page == 'private' || $page == 'adminzone') {
                    $query = "SELECT access from t_new_users where access_token = '$new_access_token';";
                    $access = mysqli_fetch_row(mysqliQuery($query))[0];
                    if ($access == 'admin') {
                        $object->link = 'http://lpptourism.ru/adminzone/index';
                    } else {
                        $object->link = 'http://lpptourism.ru/account';
                    }
                }
            } else {
                $object->answer = 'Вы ввели неправильный email или пароль';
            }
        } else {
            $object->answer = 'Вы не ввели пароль';
        }
    } else {
        $object->answer = 'Вы ввели неправильный email или пароль';
    }
} else {
    $object->answer = 'Вы не ввели email';
}

printf(json_encode($object));

?>
