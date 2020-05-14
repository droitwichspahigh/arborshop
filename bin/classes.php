<?php
namespace ArborShop;
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
/* Have we been called before? */
if (!class_exists("ArborShop\Config")) {
    require 'Config.php';
    require 'Database.php';
    require 'GraphQLClient.php';
    require 'Purchase.php';
    require 'PurchaseDb.php';
    require 'Shop.php';
    require 'ShopItem.php';
    require 'Student.php';
    require Config::$site_docroot . "/contrib/php-graphql-client/vendor/autoload.php";
}

$time = $_SERVER['REQUEST_TIME'];

/**
 * for a 10 minute timeout, specified in seconds
 */
$timeout_duration = 600;

/**
 * Here we look for the user's LAST_ACTIVITY timestamp. If
 * it's set and indicates our $timeout_duration has passed,
 * blow away any previous $_SESSION data and start a new one.
 */
if (isset($_SESSION['LAST_ACTIVITY']) &&
    ($time - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
        session_unset();
        session_destroy();
        session_start();
    }
    
    /**
     * Finally, update LAST_ACTIVITY so that our timeout
     * is based on it and not the user's login time.
     */
    $_SESSION['LAST_ACTIVITY'] = $time;

if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off") {
    header('HTTP/1.1 301 Moved Permanently');
    header('location: ' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
}