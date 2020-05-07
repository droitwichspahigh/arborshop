<?php

require('classes.php');

use \ArborShop\Config;

/* User authenticated? */

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header("location: " . Config::$site_url . '/');
}

$auth_user = preg_replace('/@' . Config::$site_emaildomain . '/', "", $_SERVER['PHP_AUTH_USER']);

/* Maintenance mode?  Sorry, devs only */

if (Config::allowed_maintenance()) {
    if (!Config::is_admin($auth_user)) {
        header("location: " . Config::$site_url . "/denied.php");
    }
} else {
    /* Don't let the site go live with adminer around! */
    if (file_exists("../contrib/adminer") || file_exists("contrib/mate-free-4.2"))
        die ("You MUST get rid of adminer and mate-free contrib bits first!");
}

/* So, let's check this user should actually be here! */

$site_section = basename(dirname($_SERVER['PHP_SELF']));

switch($site_section) {
case 'students':
    /* Only let in students and shopkeepers */
    if (!Config::is_student($auth_user) && !Config::is_shopmanager($auth_user)) {
        header("location: " . Config::$site_url . "/denied.php");
    }
    break;
case 'staff':
    /* Let's explicitly keep kids out of staff, as staff regex may match kids! */
    if (Config::is_student($auth_user) || !Config::is_staff($auth_user)) {
        header("location: " . Config::$site_url . "/denied.php");
    }
    break;
case 'dev':
    /* Well, no rule, just depends what we said in config.php */
    if (!Config::is_admin($auth_user)) {
        header("location: " . Config::$site_url . "/denied.php");
    } else if (Config::allowed_maintenance('installer_mode')) {
        die ("You need to turn on installer mode in maintenance.php before you go any further...");
    }
    break;
case 'shopkeeper':
    /* Shopkeepers are allowed in here */
    if (!Config::is_shopkeeper($auth_user)) {
        header("location: " . Config::$site_url . "/denied.php");
    }
    break;
case 'shopmanager':
case 'stockeditor':
    /* Guess who is allowed in here? */
    if (!Config::is_shopmanager($auth_user)) {
        header("location: " . Config::$site_url . "/denied.php");
    }
    break;
default:
    /* This means, I haven't actually put this location into
     * auth.php yet, so we're going to be safe and kick you out */
    header("location: " . Config::$site_url . "/denied.php");
    break;    
}

/* These are not the droids you have been looking for */