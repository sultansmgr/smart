<?php
$Live = $_GET['ID'];
$Headers = [
    'Accept: application/json',
    'Connection: Keep-Alive',
    'Content-Type: application/x-www-form-urlencoded',
    'Host: api.bnnapp.com',
    'User-Agent: OKAndroid/25.11.28 b25112800 (Android 9; tr_TR; Asus ASUS_I005DA Build/Asus-user 9.0.0 20171130.276299 release-keys; hdpi 240dpi 720x1280)',
];

function postData($url, $headers, $postData) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData,
        CURLOPT_HTTPHEADER => $headers
    ]);

    $res = curl_exec($ch);
    curl_close($ch);
    return $res;
}

$Login = "application_key=CBAFJIICABABABABA&deviceId=INSTALL_ID=bfacced7-340e-4cd0-bb05-3ccd1b6b1c69;DEVICE_ID=903910667827172;ANDROID_ID=adcda19bf1b12eb8;&gaid=0501236e-7ba8-46de-bb58-e3bd542aa4c8&mtid=de3c9872-2ee5-4181-b15d-2a323287c47b&token=800041591478_3f35c54f1911a99d013bc24ab5c86337a3964d5b4c682b1bd15287b04d689ba6&verification_supported=true&verification_supported_v=6";

$site = postData(
    'https://api.bnnapp.com/__proxy_host/api.ok.ru/api/auth/loginByToken',
    $Headers,
    $Login
);
preg_match('#"session_key":"(.*?)"#', $site, $icerik);
$Token = $icerik[1];

$methods = '[{"search.globalGrouped":{"params":{"count":3,"fieldset":"android.7","filters":"[{\"type\":\"user\"}]","query":"'.$Live.'","queryMeta":"{\"sourceLocation\":\"DISCOVERY_SEARCH\"}","screen":"GLOBAL_SEARCH_USERS_NO_RESULTS","types":"USER,GROUP,COMMUNITY,VIDEO,APP"}}},{"users.getRelationInfo":{"params":{"fields":"*"},"supplyParams":{"friend_ids":"search.globalGrouped.user_ids"}}}]';

$Ticket = "application_key=CBAFJIICABABABABA&session_key=$Token&methods=" . urlencode($methods);
$site1 = postData(
    'https://api.bnnapp.com/__proxy_host/api.ok.ru/api/batch/executeV2',
    $Headers,
    $Ticket
);
preg_match(
    '#"text":"'.$Live.'(.*?)","scope":"PORTAL"[\\s\\S]*?"entities":\\{"videos":\\[\\{"id":"(\\d+)"#',
    $site1,
    $icerik
);
$Link = "https://movie.okru.workers.dev/?ID=" . $icerik[2];
echo $Link;
?>
