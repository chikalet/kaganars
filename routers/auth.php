<?php
$start = microtime(true);
function route($method, $urlList, $requestData)
{
    if ($method == "POST") {
        $link = mysqli_connect('mysql', 'root', 's123123', 'users');
        switch ($urlList[2]) {
            case "registration":
                $token = bin2hex(random_bytes(16));
                $name = $requestData->body->name;
                $login = $requestData->body->login;
                $password = hash("sha1", $requestData->body->password);
                $loginSelectResult = $link->query("SELECT * FROM  `users` WHERE `login`='$login'")->fetch_assoc();
                if (!empty($loginSelectResult['id'])) {
                    echo "466: Пользователь с таким логином уже существует";
                    break;
                } else {
                    $loginInsertResult = $link->query("INSERT INTO `users`(`name`, `login`, `password`) VALUES('$name', '$login', '$password')");
                    $user = $link->query("SELECT `id` FROM users WHERE `login`='$login'")->fetch_assoc();
                    $userID = $user['id'];
                    $tokenSelectResult = $link->query("SELECT * FROM  `tokens` WHERE `userID`='$userID'")->fetch_assoc();
                    $tokenInsertResult = $link->query("INSERT INTO `tokens`(`value`, `userID`) VALUES('$token', '$userID')");
                    echo "description: Пользователь успешно добавлен.";
                    break;
                }
            case 'CreateTag':
                $link = mysqli_connect('mysql', 'root', 's123123', 'users');
                $token = substr(getallheaders()['Authorization'], 7);
                $token = substr(getallheaders()['Authorization'], 7);
                $nameTag = $requestData->body->nameTag;
                $tokenSelestResult = $link->query("SELECT * FROM tokens WHERE `value`='$token'")->fetch_assoc();
                $TagSelestResult = $link->query("SELECT * FROM Tags WHERE `name_group`='$nameTag'")->fetch_assoc();
                if ((empty($tokenSelestResult['role'])) or ($tokenSelestResult['role'] != 'admin')) {
                    var_dump(http_response_code(403));
                    echo "пользователь не имеет доступа";
                    break;
                } else if (!empty($TagSelestResult['Tag_ID'])) {
                    var_dump(http_response_code(403));
                    echo "Тег уже существует";
                    break;
                } else {
                    $TagInsertResult = $link->query("INSERT INTO `Tags`(`name_group`) VALUES('$nameTag')");
                    echo "description: Тег успешно создан.";
                    break;
                }
            case 'CreateFitch':
                $link = mysqli_connect('mysql', 'root', 's123123', 'users');
                $token = substr(getallheaders()['Authorization'], 7);
                $token = substr(getallheaders()['Authorization'], 7);
                $nameFitch = $requestData->body->nameFitch;
                $tokenSelestResult = $link->query("SELECT * FROM tokens WHERE `value`='$token'")->fetch_assoc();
                $FitchSelestResult = $link->query("SELECT * FROM Features WHERE `name_features`='$nameFitch'")->fetch_assoc();
                if ((empty($tokenSelestResult['role'])) or ($tokenSelestResult['role'] != 'admin')) {
                    var_dump(http_response_code(403));
                    echo "пользователь не имеет доступа";
                    break;
                } else if (!empty($FirchSelestResult['Feature_ID'])) {
                    var_dump(http_response_code(400));
                    echo "Функция уже существует";
                    break;
                } else {
                    $FitchInsertResult = $link->query("INSERT INTO `Features`(`name_features`) VALUES('$nameFitch')");
                    echo "description: Фича успешно создана.";
                    break;
                }
            case 'AddTagUser':
                $link = mysqli_connect('mysql', 'root', 's123123', 'users');
                $token = substr(getallheaders()['Authorization'], 7);
                $token = substr(getallheaders()['Authorization'], 7);
                $tagID = $requestData->body->tagID;
                $loginUser = $requestData->body->loginUser;
                $tokenSelestResult = $link->query("SELECT * FROM tokens WHERE `value`='$token'")->fetch_assoc();
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
                    echo "description: Тег успешно присвоен.";
                    break;
                }
        }
    } else if ($method == 'GET') {
        $link = mysqli_connect('mysql', 'root', 's123123', 'users');
        switch ($urlList[2]) {
            case 'features':
                $features = $link->query("SELECT * FROM `Features`");
                if ($features) {
                    while ($row = $features->fetch_assoc()) {
                        echo "Feature_ID:   " . $features_id = $row['Feature_ID'];
                        echo "    name_features:   " . $features_name = $row['name_features'] . PHP_EOL;
                    }
                }
                break;
            case 'tags':
                $tags = $link->query("SELECT * FROM `Tags`");
                if ($tags) {
                    while ($row = $tags->fetch_assoc()) {
                        echo "Tag_ID:   " . $tags_id = $row['Tag_ID'];
                        echo "    name_group:   " . $tags_name = $row['name_group'] . PHP_EOL;
                    }
                }
                break;
            case 'banners':
                $banners = $link->query("SELECT * FROM `Banners`");
                if ($banners) {
                    while ($row = $banners->fetch_assoc()) {
                        echo "Banner ID:   " . $tags_id = $row['Banner_ID'];
                        echo "    Feature_ID:   " . $tags_name = $row['Feature_ID'];
                        echo "    active:   " . $tags_id = $row['active'];
                        echo "    name_banner:   " . $tags_name = $row['name_banner'] . PHP_EOL;
                    }
                }
        }
    } else if ($method == 'GET') {
        $link = mysqli_connect('localhost', 'root', 'root', 'users');
        switch ($urlList[2]) {
            case 'features':
                $features = $link->query("SELECT * FROM `Features`");
                if ($features) {
                    while ($row = $features->fetch_assoc()) {
                        echo "Feature_ID:   " . $features_id = $row['Feature_ID'];
                        echo "    name_features:   " . $features_name = $row['name_features'] . PHP_EOL;
                    }
                }
                break;
            case 'tags':
                $tags = $link->query("SELECT * FROM `Tags`");
                if ($tags) {
                    while ($row = $tags->fetch_assoc()) {
                        echo "Tag_ID:   " . $tags_id = $row['Tag_ID'];
                        echo "    name_group:   " . $tags_name = $row['name_group'] . PHP_EOL;
                    }
                }
                break;
            case 'banners':
                $banners = $link->query("SELECT * FROM `Banners`");
                if ($banners) {
                    while ($row = $banners->fetch_assoc()) {
                        echo "Banner ID:   " . $tags_id = $row['Banner_ID'];
                        echo "    Feature_ID:   " . $tags_name = $row['Feature_ID'];
                        echo "    active:   " . $tags_id = $row['active'];
                        echo "    name_banner:   " . $tags_name = $row['name_banner'] . PHP_EOL;
                    }
                }
                break;
        }
    }
}


//case "login":
//                $login = $requestData->body->login;
//                $password = hash("sha1", $requestData->body->password);
//                $user = $link->query("SELECT `id` FROM users WHERE `login`='$login'")->fetch_assoc();
//                if (!is_null($user)) {
//                    $token = bin2hex(random_bytes(16));
//                    $userID = $user['id'];
//                    $tokenSelectResult = $link->query("SELECT * FROM  `tokens` WHERE `userID`='$userID'")->fetch_assoc();
//                    if (empty($tokenSelectResult)) {
//                        $tokenInsertResult = $link->query("INSERT INTO `tokens`(`value`, `userID`) VALUES('$token', '$userID')");
//                        if (!$tokenInsertResult) {
//                            //4000
//                            echo "too bad";
//                        } else {
//                            echo json_encode(['token' => $token]);
//                        }
//                    } else {
//                        echo "у пользователя уже имеется токен";
//                        break;
//                    }
//                } else {
//                    echo "400: input data incorrect";
//                }
//                    echo json_encode($userID);