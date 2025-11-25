<?php
$Live = $_GET['ID'];
$Referer = "https://url24.link/AtomSporTV";
$Header = [
    'Accept: */*',
    'Accept-Encoding: gzip',
    'Accept-Language: tr-TR,tr;q=0.8',
    'Connection: keep-alive',
    'User-Agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36'
];

function getData($url, $headers, $referer) {
    $ch = curl_init($url);
    $headers[] = 'Referer: ' . $referer;

    curl_setopt_array($ch, [
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
	CURLOPT_HEADER => true,
        CURLOPT_HTTPHEADER => $headers
    ]);

    $res = curl_exec($ch);
    curl_close($ch);
    return $res;
}

$site = getData('https://url24.link/AtomSporTV', $Header, $Referer);
preg_match('/location:\s*(.+)/i', $site, $icerik);
$Data = trim($icerik[1]);

$site1 = getData($Data, $Header, $Referer);
preg_match('/location:\s*(.+)/i', $site1, $icerik);
$Url = trim($icerik[1]);

$site2 = getData($Url . 'matches?id=' . $Live, $Header, $Referer);
preg_match('#fetch\("(.*?)"#', $site2, $icerik);
$DataURL = trim($icerik[1]);

$atomSPOR = $Header;
$atomSPOR[] = "Origin: $Data";
$atomSPOR[] = "Referer: $Data";
$site3 = getData($DataURL . $Live, $atomSPOR, $Data);
preg_match('#"deismackanal":"(.*?)"#', $site3, $icerik);
$Link = str_replace('\\', '', $icerik[1]);
header ("Location: $Link");
?>