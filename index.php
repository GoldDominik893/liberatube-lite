<?php
session_start();  
include('config.php');
$langrow = $defaultLang;
include('lang.php');

$keyword = $_GET['q']; 
?>
<!DOCTYPE HTML>
<html>
    <head>
<title>Liberatube Lite · <?php echo $translations[$langrow]['search_results']; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="Liberatube">
<link rel="apple-touch-icon" href="favicon.ico">
<link rel="stylesheet" href="styles/-w3.css">
<link rel="stylesheet" href="styles/-bootstrap.min.css">
<script src="scripts/sidebar.js"></script>

<?php
echo '<link rel="stylesheet" href="styles/home'.$defaultTheme.'.css">';
?>

<div class="w3-sidebar w3-bar-block w3-collapse w3-card sidebar" style="width:55px;" id="mySidebar">
  <button class="w3-bar-item w3-button w3-large w3-hide-large" onclick="w3_close()">&times;</button>
  <hr class="hr">
  <a href="#" class="w3-bar-item sidebarbtn awhitesidebar sidebarbtn-selected"><span class="material-symbols-outlined">search</span><span class="tooltiptext"><?php echo $translations[$langrow]['search']; ?></span></a>
</div>

<div class="w3-main" style="margin-left:55px">
<div class="w3-tssseal">
  <button class="w3-button w3-darkgrey w3-xlarge w3-hide-large" onclick="w3_open()">&#9776;</button>
  <div class="w3-container">
  <div class="topbar">
    <div class="topbarelements topbarelements-center">
    <h3 class="title-top topbarelements">Liberatube Lite</h3>
    <form class="input-row topbarelements" id="keywordForm" method="get" action=".">
                    <input class="input-field" type="search" id="keyword" name="q" placeholder="<?php echo $translations[$langrow]['search_yt']; ?>" value="<?php echo $keyword; ?>">
            </form>
    </div>
        </div>
</div>

<div class="tenborder">
        
        <?php 
    $searchqk = $_GET['q'];
    $_GET['q'] = str_replace(' ','+',$_GET['q']);
    ?>
        
    
        <?php if(!empty($response)) { ?>
                <div class="response <?php echo $response["type"]; ?>"> <?php echo $response["message"]; ?> </div>
        <?php }?>
        <?php                        
              if (!empty($_GET['q']))
              {
                $pagenumber = $_GET['page'] ?? 1;
                $googleApiUrl = $InvSServer.'/api/v1/search?q=' . $_GET['q'] . '&hl='.$langrow.'&page='.$pagenumber;

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_VERBOSE, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $response = curl_exec($ch);

                curl_close($ch);
                $data = json_decode($response);
                $value = json_decode(json_encode($data), true);
            ?>

            <br>
            <div class="videos-data-container w3-animate-left" id="SearchResultsDiv">
                <?php
                if ($pagenumber == 1) {
                    echo '<a href="?q='.$_GET['q'].'&page='.($pagenumber + 1).'">'.$translations[$langrow]['next_page'].'</a>';
                } else {
                    echo '<a href="?q='.$_GET['q'].'&page='.($pagenumber - 1).'">'.$translations[$langrow]['previous_page'].'</a> · ';
                    echo '<a href="?q='.$_GET['q'].'&page='.($pagenumber + 1).'">'.$translations[$langrow]['next_page'].'</a>';
                }
                ?>
                
<div style="text-align: center;">
            <?php
                for ($i = 0; $i < 20; $i++) {
                    $channel = $value[$i]['author'] ?? "";
                    $channelId = $value[$i]['authorId'] ?? "";
                    $type = "video";
                    $videoId = $value[$i]['videoId'] ?? "";

                    if ($value[$i]['type'] == 'channel') {
                        $type = "channel";
                        $profpic = $value[$i]['authorThumbnails'][0]['url']; 
                    } elseif ($value[$i]['type'] == 'playlist'){
                        $type = "playlist";
                        $plImage = $value[$i]['playlistThumbnail']; 
                        $videoId = $value[$i]['playlistId'];
                        $plVideoCount = $value[$i]['videoCount'];
                    }

                    if ($type == "channel") {
                        $burl = "#";
                        $videoId = $channelId;
                    }
                    elseif ($type == "video") {
                        $burl = "watch.php/?v=";
                    }
                    elseif ($type == "playlist") {
                        $burl = "#";
                    }
                    $title = $value[$i]['title'] ?? "";
                    $sharedat = $value[$i]['publishedText'] ?? "";

                    $lengthseconds = $value[$i]['lengthSeconds'] ?? "0";
                    $vidhours = floor($lengthseconds / 3600) ?? "";
                    $vidmins = floor($lengthseconds / 60 % 60) ?? "";
                    $vidsecs = floor($lengthseconds % 60) ?? "";
                    if ($vidhours == "0") {
                        $timestamp = $vidmins.':'.$vidsecs ?? "";
                    } else {
                        $timestamp = $vidhours.':'.$vidmins.':'.$vidsecs ?? "";
                    }
                    ?> <a class="awhite" href="<?php echo $burl.$videoId; ?>">
                       <div class="video-tile w3-animate-left">
                        <div class="videoDiv">

                            
                        <?php 
                        if ($type == "video") { ?>
                            <img src="http://i.ytimg.com/vi/<?php echo $videoId; ?>/mqdefault.jpg" width="256px">

                            <div class="timestamp"><?php echo $timestamp; ?></div>
                            </div>
                            <div class="videoInfo">
                            <div class="videoTitle"><b><?php echo $title; ?></b><br><?php echo $channel; ?> <div style="float: right;"><?php echo $sharedat; ?></div></div>
                            </div>
                            </div>
                            </a>


                        <?php }
                        elseif ($type == "channel") { ?>
                            <p>Channels unsupported in this version.</p>

                            <div class="timestamp"></div>
                            </div>
                            <div class="videoInfo">
                            <div class="videoTitle"><b><?php echo $title; ?></b><br><?php echo $channel; ?> <div style="float: right;"><?php echo $sharedat; ?></div></div>
                            </div>
                            </div>
                            </a>


                        <?php } elseif ($type == "playlist") { ?>
                            <p>Playlists unsupported in this version.</p>

                            <div class="timestamp"></div>
                            </div>
                            <div class="videoInfo">
                            <div class="videoTitle"><b><?php echo $title; ?></b><br><?php echo $channel; ?> <div style="float: right;"><?php echo $sharedat; ?></div></div>
                            </div>
                            </div>
                            </a>

                        <?php }
                            ?>

           <?php 
                    }
            }
            ?> 
            </div>
        </div>
        <div class="videos-data-container footer w3-animate-left">
            Liberatube Lite Alpha · This version of Liberatube should not be used outside testing.
            <br>Licensed under AGPLv3 on GitHub · <a href="https://github.com/GoldDominik893/liberatube-lite">GitHub</a> · <a href="https://epicsite.xyz#donate">Donate to the Liberatube project</a>
            </div>
        </div>
    </body>
</html>