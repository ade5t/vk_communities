<?php
$client_id = '7029125';
$client_secret = '7mgutKgIhgu83PpoFHXH';
$redirect_uri = 'https://vk-app0.herokuapp.com/main.php';
//    'http://site2/main.php';
$code = '';
$token = '';

if (isset($_POST['login'])){
    $url = 'https://oauth.vk.com/authorize?client_id='.$client_id.'&display=page&redirect_uri='.$redirect_uri.'&scope=offline,groups&response_type=code&v=5.95';
    echo $url;
    exit();
}

if (isset($_POST['logout'])){
    session_start();
    unset($_SESSION['user_id']);
    unset($_SESSION['access_token']);
    unset($_SESSION['communities']);
    session_write_close();
    echo 'https://vk-app0.herokuapp.com/';
//    echo 'http://site2/';
    exit();
}

if (isset($_POST['communities'])){

    session_start();
    $tmp_access_token = $_SESSION['access_token'];
    session_write_close();

    $request_params = array(
        'user_id' =>  $_SESSION['user_id'],
        'extended' => '1',
        'fields' => 'id,name,members_count',
        'v' => '5.61',
        'access_token' => $tmp_access_token
    );

//    Получаем список сообществ
    $uri = 'https://api.vk.com/method/groups.get?'.http_build_query($request_params);
    $kur = curl_init();
    curl_setopt($kur, CURLOPT_URL, $uri);
    curl_setopt($kur, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($kur, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($kur,CURLOPT_RETURNTRANSFER,TRUE);
    $result = curl_exec($kur);
    curl_close($kur);
    $communities = json_decode($result, true);

//    Получаем для каждого сообщества дату последнего поста
    if (!$communities["response"]["error"]) {
        for ($i = 0; $i < $communities["response"]["count"]; $i++) {
            sleep(1);
            $request_params = array(
                'owner_id' => '-' . $communities["response"]["items"][$i]["id"],
                'domain' => $communities["response"]["items"][$i]["screen_name"],
                'count' => '2',
                'v' => '5.84',
                'access_token' => $tmp_access_token
            );

            $uri = 'https://api.vk.com/method/wall.get?' . http_build_query($request_params);
            $kur = curl_init();
            curl_setopt($kur, CURLOPT_URL, $uri);
            curl_setopt($kur, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($kur, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($kur, CURLOPT_RETURNTRANSFER, TRUE);
            $result = curl_exec($kur);
            curl_close($kur);
            $wall = json_decode($result, true);

            if (!$wall["response"]["error"]) {
                $communities["response"]["items"][$i]["date"] = $wall["response"]["items"][0]["is_pinned"] ? gmdate("Y-m-d", $wall["response"]["items"][1]["date"]) : gmdate("Y-m-d", $wall["response"]["items"][0]["date"]);
            }
            else  $communities["response"]["items"][$i]["date"] = 0;
        }
echo 'lol5';
exit();
        session_start();
        $_SESSION['communities'] = $communities;
        session_write_close();
    }
    echo json_encode($communities["response"]["items"]);
    exit();
}

if (isset($_GET['code'])){
    $code = $_GET['code'];
    $url_with_code = 'https://oauth.vk.com/access_token?client_id='.$client_id.'&client_secret='.$client_secret.'&redirect_uri='.$redirect_uri.'&code='.$code;
    $kur = curl_init();
    curl_setopt($kur, CURLOPT_URL, $url_with_code);
    curl_setopt($kur, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($kur, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($kur,CURLOPT_RETURNTRANSFER,TRUE);
    $result = curl_exec($kur);
    curl_close($kur);
    $token = json_decode($result);
    if ($token->access_token){
        session_start();
        $_SESSION['user_id'] = $token->user_id;
        $_SESSION['access_token'] = $token->access_token;
        session_write_close();
    }
    header("Location: https://vk-app0.herokuapp.com");
//    header("Location: http://site2");
    exit;
}

if (isset($_POST['date']) && isset($_POST['sub'])){
//    Проверяем на правильность ввода кол-во подписчиков
    if (!(preg_match('#^[1-9][0-9]*$#', $_POST['sub']) || ($_POST['sub'] == ''))){
        echo (json_encode('invalid_sub'));
        exit();
    }
//    Проверяем на правильность ввода дату последнего поста
    if (!(preg_match('#^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|1[0-9]|2[0-9]|3[0-1])*$#', $_POST['date']) || ((string)$_POST['date'] == '') || ($_POST['date'] == undefined))){
        echo (json_encode('invalid_date'));
        exit();
    }

    session_start();
    if ($_SESSION['communities'] != 0) {
        $arr = [];
        $j = 0;
        for ($i = 0; $i < $_SESSION['communities']["response"]["count"]; $i++) {
            if (((double)$_SESSION['communities']["response"]["items"][$i]["members_count"] >= (double)$_POST['sub'] || $_POST['sub'] == '') && (strtotime($_SESSION['communities']["response"]["items"][$i]["date"]) >= strtotime($_POST['date']) || (string)$_POST['date'] == '')){
                $arr[$j] = $_SESSION['communities']["response"]["items"][$i];
                $j++;
            }
        }
    }
    session_write_close();
    echo (json_encode($arr));
    exit();
}
