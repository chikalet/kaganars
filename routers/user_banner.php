<?php
$start = microtime(true);
function route($method, $urlList, $requestData)
{
    if ($method == 'GET') {
        $link = mysqli_connect('localhost', 'root', 'root', 'users');
        switch ($urlList[1]) {
            case 'user_banner':
                $token = substr(getallheaders()['Authorization'], 7);
                $userFromToken = $link->query("SELECT userID FROM tokens WHERE value='$token'")->fetch_assoc();
                if (!is_null($userFromToken)) {
                    $userID = $userFromToken['userID'];
                    $user = $link->query("SELECT * FROM users WHERE id='$userID'")->fetch_assoc();
                    echo json_encode($user);
                } else {
                    echo "401: Пользователь не авторизован";
                }
                break;
        }
    }
}
