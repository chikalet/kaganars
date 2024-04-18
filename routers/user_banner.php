<?php
$start = microtime(true);
function route($method, $urlList, $requestData)
{
    if ($method == 'GET') {
        $link = mysqli_connect('mysql', 'root', 's123123', 'users');
        switch ($urlList[1]) {
            case 'user_banner':
                $token = substr(getallheaders()['Authorization'], 7);
                $tokenSelestResult = $link->query("SELECT * FROM tokens WHERE `value`='$token'")->fetch_assoc();
                if ((!empty($tokenSelestResult['role'])) && ($tokenSelestResult['role'] == 'admin')){
                    $userTag = $tokenSelestResult['tag'];
                    $bannerSelectResult = $link->query("SELECT * FROM `Banner_tags` WHERE `Tag_ID`='$userTag'")->fetch_assoc();
                    $bannerID = $bannerSelectResult['Banner_ID'];
                    $bannerDetectedResult = $link->query("SELECT * FROM `Banners` WHERE `Banner_ID`='$bannerID'")->fetch_assoc();
                    echo "ID баннера:".$bannerDetectedResult['Banner_ID']."  Название баннера:".$bannerDetectedResult['name_banner']."   Фича баннера:".$bannerDetectedResult['Feature_ID']."   Флаг активности:".$bannerDetectedResult['active'];
                    break;
                } else if ((!empty($tokenSelestResult['role'])) && ($tokenSelestResult['role'] == 'user')){
                    $userTag = $tokenSelestResult['tag'];
                    $bannerSelectResult = $link->query("SELECT * FROM `Banner_tags` WHERE `Tag_ID`='$userTag'")->fetch_assoc();
                    $bannerID = $bannerSelectResult['Banner_ID'];
                    $bannerDetectedResult = $link->query("SELECT * FROM `Banners` WHERE `Banner_ID`='$bannerID'")->fetch_assoc();
                    $bannerActive = $bannerDetectedResult['active'];
                    if ($bannerActive == 1){
                        echo "ID баннера:".$bannerDetectedResult['Banner_ID']."  Название баннера:".$bannerDetectedResult['name_banner']."   Фича баннера:".$bannerDetectedResult['Feature_ID']."   Флаг активности:".$bannerDetectedResult['active'];
                        break;
                    } else{
                        echo "баннер по вашему тегу не найден";
                    }
            }   else{
                    var_dump(http_response_code(401));
                    echo "пользователь не авторизован";
                }
        }
    }
}
