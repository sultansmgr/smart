<?php
function decodeLink($Link) {
    $Link = strrev($Link);
    $step1 = base64_decode($Link);
    if ($step1 === false) return null;

    $key = 'K9L';
    $output = '';

    for ($i = 0; $i < strlen($step1); $i++) {
        $r = $key[$i % 3];
        $n = ord($step1[$i]) - (ord($r) % 5 + 1);
        $output .= chr($n);
    }

    $decoded = base64_decode($output);
    return $decoded;
}

$Live = $_GET['ID'];
$headers = [
    'Accept: */*',
    'Accept-Encoding: gzip',
    'Accept-Language: tr-TR,tr;q=0.9',
    'Connection: keep-alive',
    'User-Agent: okhttp/4.12.0'
];

function getData($url, $headers) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_HTTPHEADER => array_merge(
            $headers,
        )
    ]);
    $res = curl_exec($ch);
    curl_close($ch);
    return $res;
}
$site = getData("https://www.fullhdfilmizlesene.tv/film/$Live/", $headers);
preg_match('#vidid = \'(.*?)\'#', $site, $icerik);
$Data = $icerik[1];

$site1 = getData("https://www.fullhdfilmizlesene.tv/player/api.php?id=$Data&type=t&name=atom&get=video&format=json", $headers);
$site1 = str_replace('\\', '',$site1);
preg_match('#"html":"(.*?)"#', $site1, $icerik);
$Url = $icerik[1];

$site2 = getData($Url, $headers);
preg_match('#"file":\s*av\([\'"]([^\'"]+)[\'"]\)#', $site2, $icerik);
$Link = $icerik[1];
$Link = decodeLink($Link);
header ("Location: $Link");
?>