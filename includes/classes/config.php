<?php // --- CONFIGURATION FILE --- //

// determine if this is a local copy
$localCopies = ['local.frankenstein.com'];
if(in_array($_SERVER['HTTP_HOST'], $localCopies)) {
    define("IS_LOCAL", true);
} else {
    define("IS_LOCAL", false);
}

// get URL of the site
define("SITE_URL", HTTP . "://" . SERVER_PATH);


?>