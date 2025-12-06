<?php
$Live = $_GET['ID'];
$Header = [
    'Accept: */*',
    'Accept-Encoding: gzip, deflate',
    'Accept-Language: tr-TR,tr;q=0.7',
    'Cache-Control: max-age=0',
    'Connection: keep-alive',
    'Content-Type: application/x-www-form-urlencoded',
    'Host: cehennempass.pw',
    'Origin: https://cehennempass.pw',
    'Referer: https://cehennempass.pw/',
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36'
];

function postData($url, $headers, $postData) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData,
        CURLOPT_HTTPHEADER => $headers
    ]);

    $res = curl_exec($ch);
    curl_close($ch);
    return $res;
}

$Post = "password=hdfilmcehennemi.com";
$site = postData("https://cehennempass.pw/download/$Live", $Header, $Post);
preg_match("#fetch\('(.*?)\'#", $site, $icerik);
$Url = trim($icerik[1]);

$Data = http_build_query([
    'video_id' => $Live,
    'selected_quality' => 'high'
]);

$site1 = postData($Url, $Header, $Data);
preg_match('#download_link":"(.*?)"#', $site1, $icerik);
$Link = str_replace('\\', '', $icerik[1]);
header ("Location: $Link");
?>