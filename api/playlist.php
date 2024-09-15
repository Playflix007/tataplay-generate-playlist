<?php
header("Cache-Control: max-age=84000, public");
header('Content-Type: audio/x-mpegurl');
header('Content-Disposition: attachment; filename="playlist.m3u"');
function getAllChannelInfo(): array {
    $json = @file_get_contents('https://raw.githubusercontent.com/ttoor5/tataplay_urls/main/origin.json');
    if ($json === false) {
        header("HTTP/1.1 500 Internal Server Error");
        exit;
    }
    $channels = json_decode($json, true);
    if ($channels === null) {
        header("HTTP/1.1 500 Internal Server Error");
        exit;
    }
    return $channels;
}
$channels = getAllChannelInfo();
$serverAddress = $_SERVER['HTTP_HOST'] ?? 'default.server.address';
$serverPort = $_SERVER['SERVER_PORT'] ?? '80';
$serverScheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
$dirPath = dirname($requestUri);
$portPart = ($serverPort != '80' && $serverPort != '443') ? ":$serverPort" : '';
$m3u8PlaylistFile = "#EXTM3U x-tvg-url=\"https://www.tsepg.cf/epg.xml.gz\"\n";
foreach ($channels as $channel) {
    $id = $channel['id'];
    $dashUrl = $channel['streamData']['MPD='] ?? null;
    if ($dashUrl === null) {
        continue;
    }
    $extension = pathinfo(parse_url($dashUrl, PHP_URL_PATH), PATHINFO_EXTENSION);
    $playlistUrl = "https://$serverAddress/{$id}.$extension|X-Forwarded-For=59.178.72.184";
    $m3u8PlaylistFile .= "#EXTINF:-1 tvg-id=\"{$id}\" tvg-logo=\"https://mediaready.videoready.tv/tatasky-epg/image/fetch/f_auto,fl_lossy,q_auto,h_250,w_250/{$channel['channel_logo']}\" group-title=\"{$channel['channel_genre'][0]}\",{$channel['channel_name']}\n";
    $m3u8PlaylistFile .= "#KODIPROP:inputstream.adaptive.license_type=clearkey\n";
    $m3u8PlaylistFile .= "#KODIPROP:inputstream.adaptive.license_key=https://tsdevil.fun/tp-api/catchup10.key?id={$id}\n";
    $m3u8PlaylistFile .= "#EXTVLCOPT:http-user-agent=third-party\n";
    $m3u8PlaylistFile .= "$playlistUrl\n\n";
}
$additionalEntries = <<<EOT
#EXTINF:-1 tvg-logo="https://mediaready.videoready.tv/tatasky-epg/image/fetch/f_auto,fl_lossy,q_auto,h_250,w_250/https://ltsk-cdn.s3.eu-west-1.amazonaws.com/jumpstart/Temp_Live/cdn/HLS/Channel/imageContent-11073-j95e7nyo-v1/imageContent-11073-j95e7nyo-m1.png" group-title="zee",Zee TV HD
#EXTVLCOPT:http-user-agent=Mozilla/5.0
https://la.drmlive.au/tp/zee.php?id=tvhd
#EXTINF:-1 tvg-logo="https://mediaready.videoready.tv/tatasky-epg/image/fetch/f_auto,fl_lossy,q_auto,h_250,w_250/https://ltsk-cdn.s3.eu-west-1.amazonaws.com/jumpstart/Temp_Live/cdn/HLS/Channel/imageContent-117-j5fl7440-v1/imageContent-117-j5fl7440-m1.png" group-title="zee",&tv HD
#EXTVLCOPT:http-user-agent=Mozilla/5.0
https://la.drmlive.au/tp/zee.php?id=andtvhd
#EXTINF:-1 tvg-logo="https://mediaready.videoready.tv/tatasky-epg/image/fetch/f_auto,fl_lossy,q_auto,h_250,w_250/https://ltsk-cdn.s3.eu-west-1.amazonaws.com/jumpstart/Temp_Live/cdn/HLS/Channel/XPOHD_Thumbnail-v1/XPOHD_Thumbnail.png" group-title="zee",&Xplor HD
#EXTVLCOPT:http-user-agent=Mozilla/5.0
https://la.drmlive.au/tp/zee.php?id=andxplorehd
#EXTINF:-1 tvg-logo="https://mediaready.videoready.tv/tatasky-epg/image/fetch/f_auto,fl_lossy,q_auto,h_250,w_250/https://ltsk-cdn.s3.eu-west-1.amazonaws.com/jumpstart/Temp_Live/cdn/HLS/Channel/imageContent-11173-j9hth720-v1/imageContent-11173-j9hth720-m1.png" group-title="zee",&pictures HD
#EXTVLCOPT:http-user-agent=Mozilla/5.0
https://la.drmlive.au/tp/zee.php?id=andpictureshd
#EXTINF:-1 tvg-logo="https://mediaready.videoready.tv/tatasky-epg/image/fetch/f_auto,fl_lossy,q_auto,h_250,w_250/https://ltsk-cdn.s3.eu-west-1.amazonaws.com/jumpstart/Temp_Live/cdn/HLS/Channel/imageContent-11915-j9l5clzs-v1/imageContent-11915-j9l5clzs-m1.png" group-title="zee",Zee Cinema HD
#EXTVLCOPT:http-user-agent=Mozilla/5.0
https://la.drmlive.au/tp/zee.php?id=cinemahd
#EXTINF:-1 tvg-logo="https://upload.wikimedia.org/wikipedia/commons/1/12/%26flix_logo.png" group-title="Movies",&flix HD
#EXTVLCOPT:http-user-agent=Mozilla/5.0
https://la.drmlive.au/tp/zee.php?id=andflixhd
#EXTINF:-1 tvg-logo="https://upload.wikimedia.org/wikipedia/en/0/0b/Zee_Zest_logo.jpeg" group-title="zee",Zee Zest HD
#EXTVLCOPT:http-user-agent=Mozilla/5.0
https://la.drmlive.au/tp/zee.php?id=zesthd
#EXTINF:-1 tvg-logo="https://mediaready.videoready.tv/tatasky-epg/image/fetch/f_auto,fl_lossy,q_auto,h_250,w_250/https://ltsk-cdn.s3.eu-west-1.amazonaws.com/jumpstart/Temp_Live/cdn/HLS/Channel/imageContent-11266-j9j2spmg-v1/imageContent-11266-j9j2spmg-m1.png" group-title="zee",Big Magic
#EXTVLCOPT:http-user-agent=Mozilla/5.0
https://la.drmlive.au/tp/zee.php?id=bigmagic
#EXTINF:-1 tvg-logo="https://upload.wikimedia.org/wikipedia/commons/thumb/7/77/%26priv%C3%A9_HD.svg/2880px-%26priv%C3%A9_HD.svg.png" group-title="zee",&prive HD
#EXTVLCOPT:http-user-agent=Mozilla/5.0
https://la.drmlive.au/tp/zee.php?id=privehd
#EXTINF:-1 tvg-logo="https://upload.wikimedia.org/wikipedia/en/a/a4/Zee_Action_2023_logo.png" group-title="zee",Zee Action
#EXTVLCOPT:http-user-agent=Mozilla/5.0
https://la.drmlive.au/tp/zee.php?id=action
#EXTINF:-1 tvg-logo="https://mediaready.videoready.tv/tatasky-epg/image/fetch/f_auto,fl_lossy,q_auto,h_250,w_250/https://ltsk-cdn.s3.eu-west-1.amazonaws.com/jumpstart/Temp_Live/cdn/HLS/Channel/imageContent-31233-jli1wlvc-v1/imageContent-31233-jli1wlvc-m1.png" group-title="zee",Zee Bollywood
#EXTVLCOPT:http-user-agent=Mozilla/5.0
https://la.drmlive.au/tp/zee.php?id=bollywood
#EXTINF:-1 tvg-logo="https://mediaready.videoready.tv/tatasky-epg/image/fetch/f_auto,fl_lossy,q_auto,h_250,w_250/https://ltsk-cdn.s3.eu-west-1.amazonaws.com/jumpstart/Temp_Live/cdn/HLS/Channel/imageContent-11090-j95hdh6o-v1/imageContent-11090-j95hdh6o-m1.png" group-title="zee",Zee Anmol Cinema
#EXTVLCOPT:http-user-agent=Mozilla/5.0
https://la.drmlive.au/tp/zee.php?id=anmolcinema
#EXTINF:-1 tvg-logo="https://upload.wikimedia.org/wikipedia/en/1/14/Zee_Caf%C3%A9_2011_logo.png" group-title="zee",Zee Cafe HD
#EXTVLCOPT:http-user-agent=Mozilla/5.0
https://la.drmlive.au/tp/zee.php?id=cafehd
#EXTINF:-1 tvg-logo="https://mediaready.videoready.tv/tatasky-epg/image/fetch/f_auto,fl_lossy,q_auto,h_250,w_250/https://ltsk-cdn.s3.eu-west-1.amazonaws.com/jumpstart/Temp_Live/cdn/HLS/Channel/imageContent-11969-j9luigc0-v2/imageContent-11969-j9luigc0-m2.png" group-title="zee",Zee Anmol
#EXTVLCOPT:http-user-agent=Mozilla/5.0
https://la.drmlive.au/tp/zee.php?id=anmol
#EXTINF:-1 tvg-logo="https://mediaready.videoready.tv/tatasky-epg/image/fetch/f_auto,fl_lossy,q_auto,h_250,w_250/https://ltsk-cdn.s3.eu-west-1.amazonaws.com/jumpstart/Temp_Live/cdn/HLS/Channel/imageContent-49009-k5g6nid4-v1/imageContent-49009-k5g6nid4-m1.png" group-title="zee",Zee Punjabi
#EXTVLCOPT:http-user-agent=Mozilla/5.0
https://la.drmlive.au/tp/zee.php?id=punjabi

#EXTINF:-1 tvg-logo="http://jiotv.catchup.cdn.jio.com/dare_images/images/Sony_HD.png" group-title="Sony Liv",SONY HD
https://la.drmlive.au/tp/sliv.php?id=sony
#EXTINF:-1 tvg-logo="http://jiotv.catchup.cdn.jio.com/dare_images/images/Sony_SAB_HD.png" group-title="Sony Liv",SONY SAB HD
https://la.drmlive.au/tp/sliv.php?id=sab
#EXTINF:-1 tvg-logo="https://i.postimg.cc/ZqnmcXdx/Sony-KAL.png" group-title="Sony Liv",SONY KAL
https://spt-sonykal-1-us.lg.wurl.tv/playlist.m3u8
#EXTINF:-1 tvg-logo="http://jiotv.catchup.cdn.jio.com/dare_images/images/Sony_Pal.png" group-title="Sony Liv",SONY PAL
https://la.drmlive.au/tp/sliv.php?id=pal
#EXTINF:-1 tvg-logo="http://jiotv.catchup.cdn.jio.com/dare_images/images/Sony_Wah.png" group-title="Sony Liv",SONY WAH
https://la.drmlive.au/tp/sliv.php?id=wah
#EXTINF:-1 tvg-logo="http://jiotv.catchup.cdn.jio.com/dare_images/images/SET_MAX.png" group-title="Sony Liv",SONY MAX
https://la.drmlive.au/tp/sliv.php?id=max
#EXTINF:-1 tvg-logo="http://jiotv.catchup.cdn.jio.com/dare_images/images/Sony_Max_HD.png" group-title="Sony Liv",SONY MAX HD
https://la.drmlive.au/tp/sliv.php?id=maxhd
#EXTINF:-1 tvg-logo="http://jiotv.catchup.cdn.jio.com/dare_images/images/Sony_MAX2.png" group-title="Sony Liv",SONY MAX2
https://la.drmlive.au/tp/sliv.php?id=max2
#EXTINF:-1 tvg-logo="http://jiotv.catchup.cdn.jio.com/dare_images/images/Ten_HD.png" group-title="Sony Liv",SONY TEN 1 HD
https://la.drmlive.au/tp/sliv.php?id=ten1hd
#EXTINF:-1 tvg-logo="http://jiotv.catchup.cdn.jio.com/dare_images/images/Ten_1.png" group-title="Sony Liv",SONY TEN 1
https://la.drmlive.au/tp/sliv.php?id=ten1
#EXTINF:-1 tvg-logo="http://jiotv.catchup.cdn.jio.com/dare_images/images/Ten2_HD.png" group-title="Sony Liv",SONY TEN 2 HD
https://la.drmlive.au/tp/sliv.php?id=ten2hd
#EXTINF:-1 tvg-logo="https://jiotv.catchup.cdn.jio.com/dare_images/images/Ten_2.png" group-title="Sony Liv",SONY TEN 2
https://la.drmlive.au/tp/sliv.php?id=ten2
#EXTINF:-1 tvg-logo="http://jiotv.catchup.cdn.jio.com/dare_images/images/Ten3_HD.png" group-title="Sony Liv",SONY TEN 3 HD
https://la.drmlive.au/tp/sliv.php?id=ten3hd
#EXTINF:-1 tvg-logo="http://jiotv.catchup.cdn.jio.com/dare_images/images/Ten_3.png" group-title="Sony Liv",SONY TEN 3
https://la.drmlive.au/tp/sliv.php?id=ten3
#EXTINF:-1 tvg-logo="https://www.sonypicturesnetworks.com/images/logos/SONY_SportsTen4_HD_Logo_CLR.png" group-title="Sony Liv",SONY TEN 4 HD
https://la.drmlive.au/tp/sliv.php?id=ten4hd
#EXTINF:-1 tvg-logo="https://www.sonypicturesnetworks.com/images/logos/SONY_SportsTen4_SD_Logo_CLR.png" group-title="Sony Liv",SONY TEN 4 
https://la.drmlive.au/tp/sliv.php?id=ten4
#EXTINF:-1 tvg-logo="https://www.sonypicturesnetworks.com/images/logos/SONY_SportsTen5_HD_Logo_CLR.png" group-title="Sony Liv",SONY TEN 5 HD
https://la.drmlive.au/tp/sliv.php?id=ten5hd
#EXTINF:-1 tvg-logo="https://www.sonypicturesnetworks.com/images/logos/SONY_SportsTen5_SD_Logo_CLR.png" group-title="Sony Liv",SONY TEN 5 
https://la.drmlive.au/tp/sliv.php?id=ten5
#EXTINF:-1 tvg-logo="http://jiotv.catchup.cdn.jio.com/dare_images/images/Sony_BBC_Earth_HD.png" group-title="Sony Liv",SONY BBC EARTH
https://la.drmlive.au/tp/sliv.php?id=bbc
#EXTINF:-1 tvg-logo="http://jiotv.catchup.cdn.jio.com/dare_images/images/Sony_Yay_Hindi.png" group-title="Sony Liv",SONY YAY
https://la.drmlive.au/tp/sliv.php?id=yay
#EXTINF:-1 tvg-logo="http://jiotv.catchup.cdn.jio.com/dare_images/images/Sony_Pix_HD.png" group-title="Sony Liv",SONY PIX HD
https://la.drmlive.au/tp/sliv.php?id=pix
#EXTINF:-1 tvg-logo="http://jiotv.catchup.cdn.jio.com/dare_images/images/Sony_Marathi_SD.png" group-title="Sony Liv",SONY MARATHI
https://la.drmlive.au/tp/sliv.php?id=marathi
#EXTINF:-1 tvg-logo="http://jiotv.catchup.cdn.jio.com/dare_images/images/Sony_aath.png" group-title="Sony Liv",SONY AATH 
https://la.drmlive.au/tp/sliv.php?id=aath
EOT;
echo $m3u8PlaylistFile . $additionalEntries;
?>
