<?php
// Invidious
$InvCServer = "https://oci-invidious.epicsite.xyz";  // The Invidious instance that will be used to fetch comments
$InvVIServer = "https://lekker.gay";                 // The Invidious instance that will be used to fetch video information
$InvTServer = "https://oci-invidious.epicsite.xyz";  // The Invidious instance that will be used to fetch trending videos for the home page
$InvSServer = "https://oci-invidious.epicsite.xyz";  // The Invidious instance that will be used to search for content

// Admin and Defaults
$defaultRegion = "GB";                             // If the user is logged out or doesn't have a region set this will be the default, (put any 2 digit country code)
$defaultTheme = "ultra-dark";                      // If the user is logged out or doesn't have a theme set this will be the default, (blue / ultra-dark)
$defaultLang = "en";                               // If the user is logged out or doesn't have a language set this will be the default
$defaultLoadCommentsSetting = "nothing";           // If the user is logged out or doesn't have a comments loading preference set this will be the default (nothing / noreplies / showall)
$adminuser = "GoldDominik893";                     // The user on liberatube you want to have access to the admin dashboard
$testinstance = true;                              // Whether this is a test instance. A disclaimer will be shown, (true / false)
$allowProxy = "false";                             // Choose if the users can proxy video data through the server, (true / false / downloads)
$useReturnYTDislike = true;                        // Choose whether the server contacts the return youtube dislike api for an estimate of the dislikes, (true / false)
