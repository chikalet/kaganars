<?php
$start = microtime(true);
function route($method, $urlList, $requestData)
{
    if ($method == "POST") {
        $link = mysqli_connect('localhost', 'root', 'root', 'users');
        switch ($urlList[1]) {
            case 'banner':
                $token = substr(getallheaders()['Authorization'], 7);
                $nameBanner = $requestData->body->nameBanner;
                $idFitch = $requestData->body->idFitch;
                $idTags = $requestData->body->idTags;
                $tokenSelestResult = $link->query("SELECT * FROM tokens WHERE `value`='$token'")->fetch_assoc();
                $fitchSelestResult = $link->query("SELECT * FROM Features WHERE `Feature_ID`='$idFitch'")->fetch_assoc();
                $names = explode(",", $idTags);
                $i = 0;
                if ($tokenSelestResult['role'] != 'admin') {
                    var_dump(http_response_code(403));
                    echo "пользователь не имеет доступа";
                    break;
                } else if (empty($tokenSelestResult['userID'])) {
                    var_dump(http_response_code(401));
                    echo "пользователь не авторизован";
                    break;
                } else if (empty($fitchSelestResult['Feature_ID'])) {
                    var_dump(http_response_code(400));
                    echo "некорректные данные: неверный id фичи";
                    break;
                } else {
                    $count = count($names);
                    while ($i < $count) {
                        $idTag = $names[$i];
                        $tagSelestResult = $link->query("SELECT * FROM Tags WHERE `Tag_ID`='$idTag'")->fetch_assoc();
                        if (empty($tagSelestResult['Tag_ID'])) {
                            var_dump(http_response_code(400));
                            echo "некорректные данные: неверный id тэга: " . $names[$i];
                            $i++;
                            break;
                        } else if ($i = $count){
                            $bannerInsertResult = $link->query("INSERT INTO `Banners` (`Feature_ID`, `name_banner`) VALUES ('$idFitch','$nameBanner')");
                            echo "баннер создан и добавлен!";
                            break;
                        } else {
                            $tagInsertResult = $link->query("INSERT INTO `Banner_Tags` (`Banner_name`, `Tag_ID`) VALUES ('$nameBanner','$idTag')");
                            $i++;
                        }
                    }
                }

//              if empty($tagsSelestResult['Tag_ID']){
//                var_dump(http_response_code(400));
//                echo "некорректные данные: неверны";
            }
        }
    }

//                $tagID = $requestData->body->tagID;
//                $loginUser = $requestData->body->loginUser;
//                $loginSelestResult = $link->query("SELECT * FROM users WHERE `login`='$loginUser'")->fetch_assoc();
//                $userID = $loginSelestResult['id'];
//                $TagSelestResult = $link->query("SELECT * FROM Tags WHERE `Tag_ID`='$tagID'")->fetch_assoc();
//                if (empty($TagSelestResult['Tag_ID'])) {
//                    var_dump(http_response_code(404));
//                    echo "404: тег не найден";
//                    break;
//                } else if (empty($loginSelestResult['id'])) {
//                    var_dump(http_response_code(404));
//                    echo "404: пользователь не найден";
//                } else {
//                    $AddInsertResult = $link->query("UPDATE `tokens` SET `tag`='$tagID' WHERE `userID`='$userID'");
//                }
//                echo "description: Тег успешно присвоен.";

