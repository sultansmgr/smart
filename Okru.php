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

$methods = '[{"video.getVideos":{"params":{"fields":"video.direct_link_access,video.in_watch_later,video.url_ultrahd,video.payment_info,video.online_chat,video.is_published,video.url_chat_archive,video.url_provider,video.duration,video.TV_STREAM,video.url_webm_dash_live,video.big_thumbnail_url,video.ord_info,video.owner_album_id,video.live_stream,video.from_time,video.annotations_info,video.live_playback_duration,video.failover_host,video.url_live_playback_hls,video.MOVIE_SUBSCRIBED,video.content_presentations,video.episodes,video.small_thumbnail_url,video.publish_at,video.need_my_tracker,video.video_advertisement,video.hd_thumbnail_url,video.url_mp4,video.tags,video.permalink,video.url_dash,video.url_ondemand_hls,video.album_id_for_autoplay,video.collage,video.url_low,video.url_medium,video.url_fullhd,video.url_tiny,video.video_pixel,video.IN_WATCH_LATER,video.url_orientations,video.ok_donates_support,video.url_ondemand_dash,video.url_quadhd,video.content_type,video.title,video.owner_id,video.created_ms,video.LIVE_STREAM_ACCESS,video.id,video.rotation_log,video.recommendation_source,video.comments_blocked,video.url_hls,video.downloadable,video.provider,video.online_viewers,video.DIMENSIONS,video.thumbnail_url,video.url_high,video.high_thumbnail_url,video.base_thumbnail_url,video.discussion_summary,video.url_live_hls,video.trend_position,video.url_mobile,video.privacy,video.total_views,video.status,video.fake,video.options,video.url_webm_dash,video.description,video.group_id,like_summary.like_count,like_summary.like_possible,like_summary.self,like_summary.unlike_possible,like_summary.reactions_count,like_summary.like_id,video_advertisement.slot,video_advertisement.content_id,video_advertisement.rb_genre,video_advertisement.site_zone,video_advertisement.duration,video_advertisement.ad_points,video_advertisement.rb_ad_allowed,video_advertisement.puids,video_advertisement.user_id","vids":"'.$Live.'"}}},{"group.getInfo":{"params":{"fields":"members_count,name,notifications_subscription,main_photo,daily_photo_post_allowed,uid,group_photo.pic_base,role","move_to_top":false},"supplyParams":{"uids":"video.getVideos.group_ids"},"onError":"SKIP"}},{"users.getInfo":{"params":{"emptyPictures":"false","fields":"uid,premium,pic190x190,vip,show_lock,name,birthday,gender"},"supplyParams":{"uids":"video.getVideos.owner_ids"},"onError":"SKIP"}}]';

$Ticket = "application_key=CBAFJIICABABABABA&session_key=$Token&methods=" . urlencode($methods);
$site1 = postData(
    'https://api.bnnapp.com/__proxy_host/api.ok.ru/api/batch/executeV2',
    $Headers,
    $Ticket
);
preg_match('#"url_mp4":"(.*?)"#', $site1, $icerik);
$Link = "https:" . $icerik[1];
header ("Location: $Link");
?>