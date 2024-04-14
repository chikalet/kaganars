<?php
$start = microtime(true);
function route($method, $urlList, $requestData)
{
    if ($method == "POST") {
        $link = mysqli_connect('localhost', 'root', 'root', 'users');
        switch ($urlList[2]) {
            case "login":
                $login = $requestData->body->login;
                $password = hash("sha1", $requestData->body->password);


                $user = $link->query("SELECT `id` FROM users WHERE `login`='$login'")->fetch_assoc();
                if (!is_null($user)) {
                    $token = bin2hex(random_bytes(16));
                    $userID = $user['id'];
                    $tokenSelectResult = $link->query("SELECT * FROM  `tokens` WHERE `userID`='$userID'")->fetch_assoc();
                    if (empty($tokenSelectResult)) {
                        $tokenInsertResult = $link->query("INSERT INTO `tokens`(`value`, `userID`) VALUES('$token', '$userID')");
                        if (!$tokenInsertResult) {
                            //4000
                            echo "too bad";
                        } else {
                            echo json_encode(['token' => $token]);
                        }

                    } else {
                        echo "у пользователя уже имеется токен";
                        break;
                    }
                    } else {
                        echo "400: input data incorrect";
                    }
                    echo json_encode($userID);
            case "registration":
                $token = bin2hex(random_bytes(16));
                $name = $requestData->body->name;
                $login = $requestData->body->login;
                $password = hash("sha1", $requestData->body->password);
                $loginSelectResult = $link->query("SELECT * FROM  `users` WHERE `login`='$login'")->fetch_assoc();
                if (!empty($tokenSelectResult['id'])) {
                    echo "466: Пользователь с таким логином уже существует";
                    break;
                } else {
                    $loginInsertResult = $link->query("INSERT INTO `users`(`name`, `login`, `password`) VALUES('$name', '$login', '$password')");
                    $user = $link->query("SELECT `id` FROM users WHERE `login`='$login'")->fetch_assoc();
                    $userID = $user['id'];
                    $tokenSelectResult = $link->query("SELECT * FROM  `tokens` WHERE `userID`='$userID'")->fetch_assoc();
                    $tokenInsertResult = $link->query("INSERT INTO `tokens`(`value`, `userID`) VALUES('$token', '$userID')");
                    echo "description: Пользователь успешно добавлен.";
                }
            case 'CreateTag':
                $link = mysqli_connect('localhost', 'root', 'root', 'users');
                $nameTag = $requestData->body->nameTag;
                $TagSelestResult = $link->query("SELECT * FROM Tags WHERE `name_group`='$nameTag'")->fetch_assoc();
                if (!empty($TagSelestResult['Tag_ID'])) {
                    echo "467: Тег уже существует";
                    break;
                } else {
                    $TagInsertResult = $link->query("INSERT INTO `Tags`(`name_group`) VALUES('$nameTag')");
                    echo "description: Тег успешно создан.";
                }
            case 'CreateFitch':
                $link = mysqli_connect('localhost', 'root', 'root', 'users');
                $nameFitch = $requestData->body->nameFitch;
                $FitchSelestResult = $link->query("SELECT * FROM Features WHERE `name_features`='$nameFitch'")->fetch_assoc();
                if (!empty($FirchSelestResult['Feature_ID'])) {
                    echo "468: Функция уже существует";
                    break;
                } else {
                    $FitchInsertResult = $link->query("INSERT INTO `Features`(`name_features`) VALUES('$nameFitch')");
                    echo "description: Фича успешно создана.";
                }
            case 'AddTagUser':
                $link = mysqli_connect('localhost', 'root', 'root', 'users');
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
                }else {
                    $AddInsertResult = $link->query("UPDATE `tokens` SET `tag`='$tagID' WHERE `userID`='$userID'");
                    echo "description: Тег успешно присвоен.";
                }
                }
            }
    }