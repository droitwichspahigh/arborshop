<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
/* Have we been called before? */
if (!class_exists("ArborShop\Config")) {
    require 'Config.php';
    require 'ArborConnection.php';
    require 'Database.php';
    require 'Shop.php';
    require 'ShopItem.php';
    require 'Student.php';
}