<?php
session_start();
$_SESSION['error'] = [];

// NOTE: dev toggle
define('DEV', true);
$mfs = true;
if(DEV == true) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    define('MFS', ($mfs == true ? 1 : 0));
}

date_default_timezone_set("America/New_York");
// determine if this is a local copy
$localCopies = ['local.frankenstein.com'];

if(in_array($_SERVER['HTTP_HOST'], $localCopies))
{
    define('IS_LOCAL', true);
    define('HTTP', 'http');
}
else
{
    define('IS_LOCAL', false);
    define('HTTP', 'https');
}
define('USERID', (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0));

define('SERVER_PATH', $_SERVER['HTTP_HOST'] . rtrim($_SERVER["REQUEST_URI"], '/'));
define('SITE_HOST', '127.0.0.1');
define('SITE_USERNAME', 'root');
define('SITE_PASSWORD', '');
define('SITE_DATABASE', 'frankenstein');

define('SYSTEM_ADMIN', (array_key_exists('is_super_admin', $_SESSION) && $_SESSION['is_super_admin'] ? true : false));

if(!isset($incudedPath))
{
   $incudedPath = '';
}

define('PATH', $incudedPath);
ob_start();

include_once PATH.'includes/class/class.database.php';
include_once PATH.'includes/class/class.dbobject.php';
include_once PATH.'inc.functions.php';

// get URL of the site
define('SITE_URL', HTTP . '://' . SERVER_PATH);
define('BASE_URL', HTTP . '://' . $_SERVER['HTTP_HOST']);

$cron = false;

//Let's make sure no one accesses the site without permission (unless otherwise stated)
if ((!isset($_SESSION['user_id']) || (int)$_SESSION['user_id'] == 0)  && $cron !== true && $_SERVER['SCRIPT_NAME'] != '/index.php' && $_SERVER['SCRIPT_NAME'] != '/images/index.php')
{
    $allowedWithoutLogin = array('login.php'
    );
    $allowAccess = false;
    
    // foreach ($allowedFolders as $f)
    // {
    //     $folderToAllow = explode('/', $_SERVER['SCRIPT_NAME']);

    //     if ($folderToAllow[1] == $f)
    //         $allowAccess = true;
    // }

    foreach ($allowedWithoutLogin as $f)
    {
    //    $folderToAllow = explode();
        if ('/' . $f == $_SERVER['SCRIPT_NAME'])
        {
            $allowAccess = true;
        }
    }
    
    if (!$allowAccess)
    {
        die('Access Denied');
    }
       
}

if (!isset($db) || !is_object($db)) {
    $db = new Database(SITE_HOST, SITE_USERNAME, SITE_PASSWORD, SITE_DATABASE);
    $db->connect();
}
$number = 0;
foreach (glob(PATH."includes/class/class.*.php") as $filename)
{
    include_once $filename;
}


?>