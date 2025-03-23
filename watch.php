<?php
session_start();  
include('config.php');
$langrow = $defaultLang;
include('lang.php');

$currentUrl = "http";
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    $currentUrl .= "s";
}
$currentUrl .= "://";
$currentUrl .= $_SERVER['HTTP_HOST'];
$currentUrl .= $_SERVER['REQUEST_URI'];
?>
<html>
<head>
<?php

$lang = $langrow;
$videoId = $_GET['v'];

    $InvApiUrl = $InvVIServer . '/api/v1/videos/' . $videoId . '?hl=' . $lang;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $InvApiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    
    $value = json_decode($response, true);
    $apiError = $value['error'] ?? null;
    if ($value == null) {
        $apiError = "API returned null";
    }
                
                
                    $title = $value['title'];
                    $description = $value['description'];
                    $views = number_format($value['viewCount']);
                    $likes = number_format($value['likeCount']);
                    $author = $value['author'];
                    $authorId = $value['authorId'];
                    $autsubs = $value['subCountText'];
                    $shared = $value['publishedText'];

                    

                    foreach ($value['captions'] as $caption) {
                        $captionshtml .= '<track kind="captions" label="' . htmlspecialchars($caption['label']) . '" srclang="' . htmlspecialchars($caption['languageCode']) . '" src="videodata/captions.php/?c_ext=' . htmlspecialchars($caption['url']) . '" default>';
                    }


                    if (isset($value['formatStreams']) && is_array($value['formatStreams'])) {
                        foreach ($value['formatStreams'] as $formatStream) {
                            if (isset($formatStream['url'])) {
                                $nonHlsUrls[] = $formatStream['url'];
                                $nonHlsItag[] = $formatStream['itag'];
                                $nonHlsQuality[] = $formatStream['qualityLabel'];
                                $nonHlsType[] = $formatStream['container'];
                                $nonHlsSize[] = $formatStream['size'];
                            }
                        }
                    }
                    if (isset($value['adaptiveFormats']) && is_array($value['adaptiveFormats'])) {
                        foreach ($value['adaptiveFormats'] as $formatStream) {
                            if (isset($formatStream['url'])) {
                                $HlsUrls[] = $formatStream['url'];
                                $HlsItag[] = $formatStream['itag'];
                                $HlsQuality[] = $formatStream['qualityLabel'];
                                $HlsType[] = $formatStream['container'];
                                $HlsSize[] = $formatStream['size'];
                            }
                        }
                    }


        $dislikeapiurl = 'https://returnyoutubedislikeapi.com/votes?videoId=' . $videoId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $dislikeapiurl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        $dislikeData = json_decode($response, true);
        $dataToCache = json_encode($dislikeData);
        $dislikes = " · " . number_format($dislikeData['dislikes'] ?? 0) . ' ' . $translations[$langrow]['estimated_dislikes'];
?> 

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="apple-mobile-web-app-title" content="Liberatube">
        <link rel="apple-touch-icon" href="favicon.ico">
        <meta property="og:title" content="<?php echo $title; ?>">
        <meta property="og:type" content="website">
        <meta property="og:url" content="?v=<?php echo $_GET['v']; ?>">
        <meta name="theme-color" content="#303EE1">
        <meta name="author" content="<?php echo $title; ?>">
        <meta name="keywords" content="badyt.cf, liberatube, EpicFaucet, two.epicfaucet.gq, yewtu.be, online videos, alternative youtube frontend, Liberatube">
        <meta property="og:locale" content="en_GB">
        <meta property="og:description" content="<?php echo $description; ?>">
        <meta property="description" content="<?php echo $description; ?>">

<span><meta property="og:site_name" content="Liberatube">
<link itemprop="name" content="Liberatube"></span></head>

<link rel="stylesheet" href="../styles/-w3.css">
<link rel="stylesheet" href="../styles/-bootstrap.min.css">

<?php
echo '<link rel="stylesheet" href="../styles/player'.$defaultTheme.'.css">';
?>

<div class="w3-sidebar w3-bar-block w3-collapse w3-card sidebar" style="width:55px;" id="mySidebar">
  <button class="w3-bar-item w3-button w3-large w3-hide-large" onclick="w3_close()">&times;</button>
  <a href="../" class="w3-bar-item sidebarbtn awhitesidebar"><span class="material-symbols-outlined">home</span><span class="tooltiptext"><?php echo $translations[$langrow]['home']; ?></span></a>
  <hr class="hr">
  <?php
            if ($_GET['listen'] == "true") {
                echo '<a href="?v='.$_GET['v'].'&listen=false" class="w3-bar-item sidebarbtn awhitesidebar"><span class="material-symbols-outlined">live_tv</span><span class="tooltiptext">'.$translations[$langrow]['video'].'</span></a>
                      <a href="#" class="w3-bar-item sidebarbtn awhitesidebar sidebarbtn-selected"><span class="material-symbols-outlined">headphones</span><span class="tooltiptext">'.$translations[$langrow]['audio'].'</span></a>';
            }
            else {
                echo '<a href="#" class="w3-bar-item sidebarbtn awhitesidebar sidebarbtn-selected"><span class="material-symbols-outlined">live_tv</span><span class="tooltiptext">'.$translations[$langrow]['video'].'</span></a>
                      <a href="?v='.$_GET['v'].'&listen=true" class="w3-bar-item sidebarbtn awhitesidebar"><span class="material-symbols-outlined">headphones</span><span class="tooltiptext">'.$translations[$langrow]['audio'].'</span></a>';
            }
        ?>
  </div>
</div>

<div class="w3-main" style="margin-left:55px">
<div class="w3-tssseal">
  <button class="w3-button w3-darkgrey w3-xlarge w3-hide-large" onclick="w3_open()">&#9776;</button>
  <div class="w3-container">
  </div>
</div>


<div class="tenborder">

            <?php
            if ($apiError) {
                echo "<h3>API Error: ".$apiError."</h3>";
            }
            if ($userproxysetting == "on" and $allowProxy = "true") {
                $dlsetting = "&dl=true";
            } else {
                $dlsetting = "&dl=false";
            }
            if ($_GET['listen'] == "true") {

                echo '<link rel="stylesheet" href="../styles/audioplayer.css">
                    <center><img style="max-height: 60vh; max-width: 100%;" src="https://i.ytimg.com/vi/'.$_GET['v'].'/maxresdefault.jpg"></center>
                    <audio preload="auto" style="width: 100%; max-height: 90vh; background-color: rgb(0,0,0);" autoplay controls>
                    <source src="/videodata/hls.php?id='.$_GET['v'].$dlsetting.'" type="audio/mp4">
                    Your Browser Sucks! Can not play the audio.
                    </audio>';
            }
            else {


                    $baseUrl = '../videodata/hls.php?id={{videoId}}&itag={{itag}}';

                    foreach ($HlsItag as $index => $itag) {
                        $quality = $HlsQuality[$index];
                        if ($HlsQuality[$index] === null AND $HlsType[$index] !== null AND $HlsType[$index] !== 'webm') {
                            $audioUrl = str_replace(['{{videoId}}', '{{itag}}'], [$_GET['v'], $itag], $baseUrl);
                        } else {
                            $videoUrls[] = [
                                'url' => str_replace(['{{videoId}}', '{{itag}}'], [$_GET['v'], $itag], $baseUrl),
                                'quality' => $quality,
                                'type' => $HlsType[$index]
                            ];
                            
                        }
                    }
                    $videoUrls = array_reverse($videoUrls);
                    echo '<video id="video" class="video" controls preload="auto" data-setup="{}" style="width: 100%; max-height: 90vh; background-color: rgb(0,0,0);" poster="https://i.ytimg.com/vi/'.$_GET['v'].'/maxresdefault.jpg" autoplay>';
                    foreach ($videoUrls as $video) {
                        echo '<source src="'.$video['url'].$dlsetting.'" type="video/mp4" label="HLS '.$video['quality'].' '.$video['type'].'">';
                    }
                    echo '<source src="../videodata/non-hls.php?id='.$_GET['v'].$dlsetting.'" type="video/mp4" label="360p">'
                    .$captionshtml.'Your Browser Sucks! Can not play the video.</video>

                    <audio id="audio" preload="auto">
                    <source src="'.$audioUrl.$dlsetting.'" type="audio/mp4">
                    </audio>';

            } ?>

    <div class="relatedVideos" style="float: right;">
    <h3>Related videos</h3>
    <?php
    for ($i = 0; $i < 9; $i++) {
        $suggestedvideoId = $value['recommendedVideos'][$i]['videoId'] ?? "";
        $suggestedtitle = $value['recommendedVideos'][$i]['title'] ?? "";
        $suggesteddescription = $value['recommendedVideos'][$i]['descriptionHtml'] ?? "";
        $suggestedchannel = $value['recommendedVideos'][$i]['author'] ?? "";
        $suggestedsharedat = $value['recommendedVideos'][$i]['publishedText'] ?? "";
        $suggestedauthorId = $value['recommendedVideos'][$i]['authorId'] ?? "";

        $lengthseconds = $value['recommendedVideos'][$i]['lengthSeconds'] ?? "";
        $vidhours = floor($lengthseconds / 3600) ?? "";
        $vidmins = floor($lengthseconds / 60 % 60) ?? "";
        $vidsecs = floor($lengthseconds % 60) ?? "";
        if ($vidhours == "0") {
            $timestamp = $vidmins . ':' . $vidsecs ?? "";
        } else {
            $timestamp = $vidhours . ':' . $vidmins . ':' . $vidsecs ?? "";
        }
    ?>

        <a class="awhite" href=".?v=<?php echo $suggestedvideoId; ?>">
            <div class="video-tile w3-animate-left">
                <div class="videoDiv">
                    <img src="http://i.ytimg.com/vi/<?php echo $suggestedvideoId; ?>/mqdefault.jpg" width="256px">
                    <div class="timestamp"><?php echo $timestamp; ?></div>
                </div>
                <div class="videoInfo">
                    <div class="videoTitle"><b><?php echo $suggestedtitle; ?></b><br><?php echo $suggestedchannel; ?> <div style="float: right;"><?php echo $suggestedsharedat; ?></div></div>
                </div>
            </div>
        </a>
    <?php 
    }
    ?> 
</div>
   
       
<h3><?php echo $title; ?></h3>
<h4><?php echo $shared; ?> · <?php echo $views; ?> <?php echo $translations[$langrow]['views']; ?> · <?php echo $likes; ?> <?php echo $translations[$langrow]['likes']; ?><?php echo $dislikes; ?></h4>

<?php if ($_GET['listen'] != "true") { ?>
    <select class="button" id="qualitySelector"></select>
<?php } ?>

<a class="button" onclick="Alert.render('ok')"><?php echo $translations[$langrow]['download']; ?></a>
<?php echo $author; ?> · <?php echo $autsubs; ?>


<div id="popUpBox"  style="display: none;">
<div id="box">
<?php
if ($allowProxy == "true" or $allowProxy == "downloads") {
    $isProxyDisabled = "";
} else {
    $isProxyDisabled = "disabled";
    $isProxyDisabledMessage = "Proxying is disabled by this instance.";
}
?>
<h3><?php echo $translations[$langrow]['download_this_video']; ?></h3>
<table>
<?php
echo $isProxyDisabledMessage;
?>
<tr><td><h4><?php echo $translations[$langrow]['non-hls_options']; ?></h4></td></tr>
<?php
if (isset($nonHlsItag) && is_array($nonHlsItag) && !empty($nonHlsItag)) {
    for ($i = 0; $i < count($nonHlsItag); $i++) {
        $itag = $nonHlsItag[$i];
        $url = $nonHlsUrls[$i];
        $quality = $nonHlsQuality[$i];
        $type = $nonHlsType[$i];
        $size = $nonHlsSize[$i];

        echo '<tr><td>'.$quality.'('.$itag.') - '.$type.'</td><td><a class="button-in-table" href="'.$url.'">'.$translations[$langrow]['direct'].'</a><a download="'.$_GET['v'].'.'.$type.'" class="button-in-table" href="../videodata/non-hls.php?id='.$_GET['v'].'&dl=dl&itag='.$itag.'">'.$translations[$langrow]['proxy'].'</a></td></tr>';
    }
}
?>
<tr><td><h4><?php echo $translations[$langrow]['hls_options']; ?></h4></td></tr>
<?php
if (isset($HlsItag) && is_array($HlsItag) && !empty($HlsItag)) {
    for ($i = 0; $i < count($HlsItag); $i++) {
        $itag = $HlsItag[$i];
        $url = $HlsUrls[$i];
        $quality = $HlsQuality[$i];
        $type = $HlsType[$i];
        $size = $HlsSize[$i];

        echo '<tr><td>'.$quality.'('.$itag.') - '.$type.'</td><td><a class="button-in-table" href="'.$url.'">'.$translations[$langrow]['direct'].'</a><a download="'.$_GET['v'].'.'.$type.'" class="button-in-table" href="../videodata/hls.php?id='.$_GET['v'].'&dl=dl&itag='.$itag.'">'.$translations[$langrow]['proxy'].'</a></td></tr>';
    }
}
?>
</table>
<div id="closeModal"><a class="button" onclick="Alert.ok()">Close</a></div>
</div>
</div>
<h6></h6>
     <?php
function makeUrltoLink($string) {
    $reg_pattern = "/(((http|https|ftp|ftps)\:\/\/)|(www\.))[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,12}(\:[0-9]+)?(\/\S*)?/";
    return preg_replace($reg_pattern, '<a href="$0" target="_blank" rel="noopener noreferrer">$0</a>', $string);
}

function makeTimestamptoLink($string) {
    $reg_pattern = "/(?<!<\/a>)\b\d{1,2}:\d{1,2}:\d{1,2}\b/";
    return preg_replace($reg_pattern, '<a onclick="seekToTime(\'$0\')">$0</a>', $string);
}

function makeTimestamptoLinkSmaller($string) {
    $reg_pattern = "/<a [^>]*>.*?<\/a>(*SKIP)(*F)|\b\d{1,2}:\d{1,2}\b/";
    return preg_replace($reg_pattern, '<a onclick="seekToTime(\'$0\')">$0</a>', $string);
}

$str = $description;
$cdesc = nl2br($convertedStr = makeUrltoLink($str));
$cdesc = makeTimestamptoLink($cdesc);
$cdesc = makeTimestamptoLinkSmaller($cdesc);

$cdesc = str_replace('href="https://youtu.be/','href="/watch/?v=',$cdesc);
$cdesc = str_replace('href="https://www.youtube.com/watch?v=','href="/watch/?v=',$cdesc);
?>


 
 <details><summary><a class="button"><?php echo $translations[$langrow]['show-hide-desc']; ?></a></summary> <a style="margin-right: 3px;" class="button" href="//youtu.be/<?php echo $_GET['v']?>"><?php echo $translations[$langrow]['watch_on_yt']; ?></a><a style="margin-right: 3px;" class="button" href="//redirect.invidious.io/<?php echo $_GET['v']?>"><?php echo $translations[$langrow]['watch_on_inv']; ?></a><a href="https://liberatube-instances.epicsite.xyz/?v=<?php echo $_GET['v']?>" class="button"><?php echo $translations[$langrow]['switch_instance']; ?></a><hr style="margin-top: 8px; margin-bottom: 5px;" class="hr"><?php echo $cdesc; ?> </details><br>

        <title><?php echo $title; ?> · Liberatube</title>
        <script src="../scripts/-jquery-3.6.4.min.js"></script>
        <script src="../scripts/playermain.js"></script>
        <script src="../scripts/sidebar.js"></script>
<div class="relatedVideosMob">
    <h3>Related videos</h3>
    <?php
    for ($i = 0; $i < 9; $i++) {
        $suggestedvideoId = $value['recommendedVideos'][$i]['videoId'] ?? "";
        $suggestedtitle = $value['recommendedVideos'][$i]['title'] ?? "";
        $suggesteddescription = $value['recommendedVideos'][$i]['descriptionHtml'] ?? "";
        $suggestedchannel = $value['recommendedVideos'][$i]['author'] ?? "";
        $suggestedsharedat = $value['recommendedVideos'][$i]['publishedText'] ?? "";
        $suggestedauthorId = $value['recommendedVideos'][$i]['authorId'] ?? "";

        $lengthseconds = $value['recommendedVideos'][$i]['lengthSeconds'] ?? "";
        $vidhours = floor($lengthseconds / 3600) ?? "";
        $vidmins = floor($lengthseconds / 60 % 60) ?? "";
        $vidsecs = floor($lengthseconds % 60) ?? "";
        if ($vidhours == "0") {
            $timestamp = $vidmins . ':' . $vidsecs ?? "";
        } else {
            $timestamp = $vidhours . ':' . $vidmins . ':' . $vidsecs ?? "";
        }
    ?>

        <a class="awhite" href=".?v=<?php echo $suggestedvideoId; ?>">
            <div class="video-tile w3-animate-left">
                <div class="videoDiv">
                    <img src="http://i.ytimg.com/vi/<?php echo $suggestedvideoId; ?>/mqdefault.jpg" width="256px">
                    <div class="timestamp"><?php echo $timestamp; ?></div>
                </div>
                <div class="videoInfo">
                    <div class="videoTitle"><b><?php echo $suggestedtitle; ?></b><br><?php echo $suggestedchannel; ?> <div style="float: right;"><?php echo $suggestedsharedat; ?></div></div>
                </div>
            </div>
        </a>

    <?php 
    }
    ?> 
</div>
 <?php

    if ($_GET['comments']) {
        $loadcomments = $_GET['comments'];
    }
    if ($loadcomments) {} else {
        $loadcomments = $defaultLoadCommentsSetting;
    }
    if ($loadcomments != "nothing") {
 
    if(!empty($response)) { ?>
        <?php }?>
        <?php                        
                $InvApiUrl = $InvCServer.'/api/v1/comments/'.$_GET['v'].'?hl='.$langrow;

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_URL, $InvApiUrl);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_VERBOSE, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $response = curl_exec($ch);
                curl_close($ch);
                $data = json_decode($response);
                $value = json_decode(json_encode($data), true);
                $ccount = $value['commentCount'] ?? "";
                
                echo '<br><br><br><br><h3>'.number_format($ccount).' '.$translations[$langrow]['comments'].'</h3><br>';

                if ($ccount > 20) {
                $ccountl = "20";
                }
                else {
                    $ccountl = $ccount - "1";
                }

                for ($i = 0; $i < $ccountl; $i++) {
                    $aname = $value['comments'][$i]['author'] ?? "";
                    $aturl = $value['comments'][$i]['authorThumbnails']['0']['url'] ?? "";
                    $acon = $value['comments'][$i]['content'] ?? "";
                    $ptex = $value['comments'][$i]['publishedText'] ?? "";
                    $alik = $value['comments'][$i]['likeCount'] ?? "";
                    $auid = $value['comments'][$i]['authorId'] ?? "";

                    if ($loadcomments != "noreplies") {
                    $commentreplycontinuation = $value['comments'][$i]['replies']['continuation'] ?? "";
                    $commentreplyamount = $value['comments'][$i]['replies']['replyCount'] ?? "";

                    $nextpagestr = $value['continuation'] ?? "";

                        $InvApiUrl = $InvCServer.'/api/v1/comments/'.$_GET['v'].'?hl='.$langrow.'&continuation='.$commentreplycontinuation;

                        $ch = curl_init();
        
                        curl_setopt($ch, CURLOPT_HEADER, 0);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_URL, $InvApiUrl);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                        curl_setopt($ch, CURLOPT_VERBOSE, 0);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        $response = curl_exec($ch);
                        curl_close($ch);
                        $data = json_decode($response);
                        $value_reply = json_decode(json_encode($data), true);
                        $ccount_reply = substr_count($response,"authorIsChannelOwner");

                        if ($ccount_reply > 20) {
                            $ccountl_reply = "20";
                            }
                            else {
                                $ccountl_reply = $ccount_reply - "1";
                            } }
                    ?>
                    <div style="width: 100%; max-width: 775px;">
                    <h4><img style="margin-bottom: -25px; max-width: 48px;" src=<?php echo $aturl; ?>> <a href="/channel/?id=<?php echo $auid.'">'.$aname."</a>"; ?> · <?php echo $ptex; ?> · <?php echo number_format($alik)." ".$translations[$langrow]['likes']; ?>
                    </h4>
                    <br><h5 style="margin-left: 53px; margin-top: -25px;"><?php echo makeTimestamptoLinkSmaller(makeTimestamptoLink(makeUrltoLink($acon)));
                    
                        if ($commentreplyamount != "") {?>

                        <details><summary><?php echo $translations[$langrow]['show']; ?> <?php echo $commentreplyamount; ?> <?php echo $translations[$langrow]['replies']; ?></summary><?php

                            for ($ii = 0; $ii < $ccountl_reply; $ii++) {
                                $aname_reply = $value_reply['comments'][$ii]['author'] ?? "";
                                $aturl_reply = $value_reply['comments'][$ii]['authorThumbnails']['0']['url'] ?? "";
                                $acon_reply = $value_reply['comments'][$ii]['content'] ?? "";
                                $ptex_reply = $value_reply['comments'][$ii]['publishedText'] ?? "";
                                $alik_reply = $value_reply['comments'][$ii]['likeCount'] ?? "";
                                $auid_reply = $value_reply['comments'][$ii]['authorId'] ?? "";
                             ?>

                            <div style="width: 100%; max-width: 775px;">
                            <h4><img style="margin-bottom: -25px; max-width: 48px;" src=<?php echo $aturl_reply; ?>> <a href="/channel/?id=<?php echo $auid.'">'.$aname_reply."</a>"; ?> · <?php echo $ptex_reply; ?> · <?php if($alik_reply > -1){echo number_format($alik_reply)." ".$translations[$langrow]['likes'];} ?>
                            </h4>
                            <br><h5 style="margin-left: 53px; margin-top: -25px;"><?php echo makeTimestamptoLinkSmaller(makeTimestamptoLink(makeUrltoLink($acon_reply))); ?></h5><br>

                       <?php } }?></details></h5>
                    <br>
                </div>  
           <?php 
                    }
                } else {
                    echo '<a class="button" href="?v='.$_GET['v'].'&comments=noreplies">'.$translations[$langrow]['load_comments'].'</a>';
                }
            ?> 
            </div>
            </div>
        
</div> 
</div>
</body>
</html>