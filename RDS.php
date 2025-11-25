<?php
$Live = $_GET['ID'];
$Referer = $Live;

$Headers = [
    'Accept: */*',
    'Accept-Encoding: gzip',
    'Accept-Language: tr-TR,tr;q=0.8',
    'Connection: keep-alive',
    'User-Agent: Dalvik/2.1.0 (Linux; U; Android 7.1.2; SM-G935F Build/N2G48H)'
];

function getData($url, $headers, $referer) {
    $ch = curl_init($url);
    $headers[] = 'Referer: ' . $referer;

    curl_setopt_array($ch, [
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_HTTPHEADER => $headers
    ]);

    $res = curl_exec($ch);
    curl_close($ch);
    return $res;
}

function postData($url, $headers, $referer, $postData) {
    $ch = curl_init($url);
    $headers[] = 'Referer: ' . $referer;

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

$site = getData($Live, $Headers, $Referer);
preg_match('#postID = \'(.*?)\'#', $site, $icerik);
$PostID = $icerik[1];
preg_match('#url: \'(.*?)\'#', $site, $icerik);
$Data = $icerik[1];
preg_match('#iframeUrl = \`(.*?)\`#', $site, $icerik);
$IframeURL = $icerik[1];

$Post = "action=get_video_source&tab=tab1&post_id=$PostID";
$site1 = postData($Data, $Headers, $Referer, $Post);
preg_match('#"data":"(.*?)"#', $site1, $icerik);
$Link = str_replace('\\', '', $icerik[1]);
$IframeURL = str_replace('${encodeURIComponent(response.data)}',"$Link",$IframeURL);

$site2 = getData($IframeURL, $Headers, $Referer);
preg_match('#const TOKEN_API = "(.*?)"#', $site2, $icerik);
$constToken = str_replace('\\', '', $icerik[1]);
$constToken .= "?source=$Link";

$site3 = getData($constToken, $Headers, $Referer);
preg_match('#"token":"(.*?)"#', $site3, $icerik);
$Token = $icerik[1];
$Link .= "?token=$Token";
header ("Location: $Link");
?>