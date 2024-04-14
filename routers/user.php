<?php
function route($method, $urlList, $requestData)
{
    $link = mysqli_connect('localhost', 'root', 'root', 'users');
    switch ($method){
        case 'GET':
            $token = substr(getallheaders()['Authorization'], 7);
            $userFromToken = $link->query("SELECT userID FROM tokens WHERE value='$token'")->fetch_assoc();
            if (!is_null($userFromToken))
            {
                $userID =  $userFromToken['userID'];
                $user = $link->query("SELECT * FROM users WHERE id='$userID'")->fetch_assoc();
                echo json_encode($user);
            }
            else
            {
                echo "401: Пользователь не авторизован";
            }
            break;
        case 'POST':
            $login = $requestData->body->login;
            $password = $requestData->body->password;
            $link = mysqli_connect('localhost', 'root', 'root', 'users');
            $user = $link->query("SELECT `id` FROM users WHERE `login`='$login'")->fetch_assoc();

            if (is_null($user))
            {
                $password = hash("sha1", $password);
                echo json_encode($requestData);
            }
            else
            {
                echo "exist";
            }
            break;
        default:
            break;
    }
}
