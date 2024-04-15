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
    } else if ($method == "POST") {
        $link = mysqli_connect('localhost', 'root', 'root', 'users');
        switch ($urlList[1]) {
            case 'banner':
                $tagID = $requestData->body->tagID;
                $loginUser = $requestData->body->loginUser;
                $loginSelestResult = $link->query("SELECT * FROM users WHERE `login`='$loginUser'")->fetch_assoc();
                $userID = $loginSelestResult['id'];
                $TagSelestResult = $link->query("SELECT * FROM Tags WHERE `Tag_ID`='$tagID'")->fetch_assoc();
                if (empty($TagSelestResult['Tag_ID'])) {
                    var_dump(http_response_code(404));
                    echo "404: тег не найден";
                    break;
                } else if (empty($loginSelestResult['id'])) {
                    var_dump(http_response_code(404));
                    echo "404: пользователь не найден";
                } else {
                    $AddInsertResult = $link->query("UPDATE `tokens` SET `tag`='$tagID' WHERE `userID`='$userID'");
                }
                echo "description: Тег успешно присвоен.";
        }
    }
}
