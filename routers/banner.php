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
                $bannerValidate = $link->query("SELECT * FROM `Banners` WHERE `name_banner`='$nameBanner'")->fetch_assoc();
                $names = explode(",", $idTags);
                $i = 0;
                if ((empty($tokenSelestResult['role'])) or ($tokenSelestResult['role'] != 'admin')) {
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
                } else if (!empty($bannerValidate['Banner_ID'])) {
                    var_dump(http_response_code(400));
                    echo "некорректные данные: баннер с таким именем уже существует";
                    break;
                } else {
                    $count = count($names);
                    $bannerInsertResult = $link->query("INSERT INTO `Banners` (`Feature_ID`, `name_banner`) VALUES ('$idFitch','$nameBanner')");
                    $bannerSelectResult = $link->query("SELECT * FROM `Banners` WHERE `Feature_ID`='$idFitch' AND `name_banner`='$nameBanner'")->fetch_assoc();
                    echo "баннер добавлен!";
                    $bannerID = $bannerSelectResult['Banner_ID'];
                    while ($i < $count) {
                        $idTag = $names[$i];
                        $tagSelestResult = $link->query("SELECT * FROM Tags WHERE `Tag_ID`='$idTag'")->fetch_assoc();
                        if (empty($tagSelestResult['Tag_ID'])) {
                            var_dump(http_response_code(400));
                            echo "некорректные данные: неверный id тэга: " . $names[$i];
                            $i++;
                        } else {
                            $tagInsertResult = $link->query("INSERT INTO `Banner_Tags` (`Banner_name`, `Tag_ID`, `Banner_ID`) VALUES ('$nameBanner','$idTag', '$bannerID')");
                            $i++;
                        }
                    }
                }
        }
    } else if ($method == 'GET') {
        $link = mysqli_connect('localhost', 'root', 'root', 'users');
        switch ($urlList[1]) {
            case 'banner':
                $token = substr(getallheaders()['Authorization'], 7);
                $idFitch = substr(getallheaders()['fitch_id'], 0);
                $idTag = substr(getallheaders()['tag_id'], 0);
                $tokenSelestResult = $link->query("SELECT * FROM tokens WHERE `value`='$token'")->fetch_assoc();
                $tagSelestResult = $link->query("SELECT * FROM Banner_tags WHERE `Tag_ID`='$idTag'")->fetch_assoc();
                $fitchSelestResult = $link->query("SELECT * FROM Banners WHERE `Feature_ID`='$idFitch'")->fetch_assoc();
                if (empty($tokenSelestResult['role']) or ($tokenSelestResult['role'] != 'admin')) {
                    var_dump(http_response_code(403));
                    echo "пользователь не имеет доступа";
                    break;
                } else if (!empty($fitchSelestResult['Banner_ID']) && ($tagSelestResult['id'])) {
                    $tagBannerResult = $tagSelestResult['Banner_ID'];
                    $fitchBannerResult = $link->query("SELECT * FROM `Banners` WHERE `Feature_ID`='$idFitch' AND `Banner_ID`='$tagBannerResult'")->fetch_assoc();
                    if (!empty($fitchBannerResult['Banner_ID'])) {
                        echo "Баннер по вашим фильтрам: " . $fitchBannerResult['name_banner'] . "     Флаг активности: " . $fitchBannerResult['active'];
                        break;
                    } else {
                        var_dump(http_response_code(400));
                        echo "Баннер по вашим фильтрам не найден";
                    }
                } else if (!empty($fitchSelestResult['Banner_ID'])) {
                    echo "Баннер по указанной фиче: " . $fitchSelestResult['name_banner'] . "       Флаг активности: " . $fitchSelestResult['active'];
                    break;
                } else if (!empty($tagSelestResult['id'])) {
                    $bannerID = $tagSelestResult['Banner_ID'];
                    $fitchBannerResult = $link->query("SELECT * FROM `Banners` WHERE `Banner_ID`='$bannerID'")->fetch_assoc();
                    echo "Баннер по вашему тегу: " . $tagSelestResult['Banner_name'] . "       Флаг активности: " . $fitchBannerResult['active'];;
                    break;
                } else {
                    var_dump(http_response_code(400));
                    echo "Неверные данные";
                }
        }
    } else if ($method == 'PATCH') {
        $link = mysqli_connect('localhost', 'root', 'root', 'users');
        switch ($urlList[1]) {
            case 'banner':
                $token = substr(getallheaders()['Authorization'], 7);
                $idBanner = $urlList[2];
                $nameBanner = $requestData->body->name_banner;
                $idFitch = $requestData->body->idFitch;
                $idTags = $requestData->body->idTags;
                $tokenSelestResult = $link->query("SELECT * FROM tokens WHERE `value`='$token'")->fetch_assoc();
                $fitchSelestResult = $link->query("SELECT * FROM Features WHERE `Feature_ID`='$idFitch'")->fetch_assoc();
                $bannerValidate = $link->query("SELECT * FROM `Banners` WHERE `Banner_ID`='$idBanner'")->fetch_assoc();
                $names = explode(",", $idTags);
                $i = 0;
                if ((empty($tokenSelestResult['role'])) or ($tokenSelestResult['role'] != 'admin')) {
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
                } else if (empty($bannerValidate['Banner_ID'])) {
                    var_dump(http_response_code(400));
                    echo "некорректные данные: баннер с таким ID не существует";
                    break;
                } else {
                    $count = count($names);
                    $bannerUpdateResult = $link->query("UPDATE `Banners` SET `Feature_ID`='$idFitch', `name_banner`='$nameBanner' WHERE `Banner_ID`='$idBanner'");
                    $bannerSelectResult = $link->query("SELECT * FROM `Banners` WHERE `Feature_ID`='$idFitch' AND `name_banner`='$nameBanner'")->fetch_assoc();
                    echo "баннер обновлен!";
                    $bannerID = $bannerSelectResult['Banner_ID'];
                    while ($i < $count) {
                        $idTag = $names[$i];
                        $tagSelestResult = $link->query("SELECT * FROM Tags WHERE `Tag_ID`='$idTag'")->fetch_assoc();
                        if (empty($tagSelestResult['Tag_ID'])) {
                            var_dump(http_response_code(400));
                            echo "некорректные данные: неверный id тэга: " . $names[$i];
                            $i++;
                        } else {
                            $tagUpdateResult = $link->query("UPDATE `Banner_Tags` SET `Banner_name`='$nameBanner', `Tag_ID`='$idTag', `Banner_ID`='$bannerID' WHERE `Banner_ID`='$idBanner'");
                            $i++;
                        }
                    }
                }
        }
    } else if ($method == 'DELETE') {
        $link = mysqli_connect('localhost', 'root', 'root', 'users');
        switch ($urlList[1]) {
            case 'banner':
                $token = substr(getallheaders()['Authorization'], 7);
                $idBanner = $urlList[2];
                $tokenSelestResult = $link->query("SELECT * FROM tokens WHERE `value`='$token'")->fetch_assoc();
                $bannerValidate = $link->query("SELECT * FROM `Banners` WHERE `Banner_ID`='$idBanner'")->fetch_assoc();
                if ((empty($tokenSelestResult['role'])) or ($tokenSelestResult['role'] != 'admin')) {
                    var_dump(http_response_code(403));
                    echo "пользователь не имеет доступа";
                    break;
                } else if (empty($tokenSelestResult['userID'])) {
                    var_dump(http_response_code(401));
                    echo "пользователь не авторизован";
                    break;
                } else if (empty($bannerValidate['Banner_ID'])) {
                    var_dump(http_response_code(400));
                    echo "некорректные данные: баннер с таким ID не существует";
                    break;
                } else {
                    $tagDeleteResult = $link->query("DELETE FROM `Banner_Tags` WHERE `Banner_ID`='$idBanner'");
                    $bannerDeleteResult = $link->query("DELETE FROM `Banners` WHERE `Banner_ID`='$idBanner'");
                    var_dump(http_response_code(204));
                    echo "баннер успешно удален";
                }
        }
    }
}