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
    	'User-Agent: Dalvik/2.1.0 (Linux; U; Android 9; ASUS_I005DA Build/PI)',
	'Connection: Keep-Alive',
	'Accept-Encoding: gzip'
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

$Atom = getData("https://www.fullhdfilmizlesene.tv/player/api.php?id=$Data&type=t&name=atom&get=video&format=json", $headers);
$Turbo = getData("https://www.fullhdfilmizlesene.tv/player/api.php?id=$Data&type=t&name=advid&get=video&pno=tr&format=json", $headers);
$Atom = str_replace('\\', '',$Atom);
$Turbo = str_replace('\\', '',$Turbo);

preg_match('#"html":"(.*?)"#', $Atom, $icerik);
$atomUrl = $icerik[1];
preg_match('#/watch/(.*?)"#', $Turbo, $icerik);
$TurboUrl = $icerik[1];

$site1 = getData($atomUrl, $headers);
preg_match('#"file":\s*av\([\'"]([^\'"]+)[\'"]\)#', $site1, $icerik);
$Link = $icerik[1];
$Link = decodeLink($Link);

$site1 = getData("https://turbo.imgz.me/play/$TurboUrl?autoplay=true", $headers);
preg_match('#file: "(.*?)"#', $site1, $icerik);
$M3U8 = $icerik[1];
header ("Location: $M3U8$Link");
?>
