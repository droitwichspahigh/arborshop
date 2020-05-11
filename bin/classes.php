<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
/* Have we been called before? */
if (!class_exists("ArborShop\Config")) {
    require 'Config.php';
    require 'ArborConnection.php';
    require 'Database.php';
    require 'Purchase.php';
    require 'PurchaseDb.php';
    require 'Shop.php';
    require 'ShopItem.php';
    require 'Student.php';
}

if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off") {
    header('HTTP/1.1 301 Moved Permanently');
    header('location: ' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
}