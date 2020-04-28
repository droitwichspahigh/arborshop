<?php

require('config.php');

/* User authenticated? */

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header("location: $site_url/");
}

$auth_user = $_SERVER['PHP_AUTH_USER'];

/* Maintenance mode?  Sorry, devs only */

if ($maintenance == TRUE) {
    if (!in_array($auth_user, $admin_users)) {
        header("location: $site_url/denied.php");
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
    if (!preg_match("/^$student_user_regex/", $auth_user)) {
        if (!in_array($auth_user, $shopkeepers)) {
            header("location: $site_url/denied.php");
        }
    }
    break;
case 'staff':
    /* Let's explicitly keep kids out of staff, as staff regex may match kids! */
    if (preg_match("/$student_user_regex/", $auth_user) || 
             !preg_match("/$staff_user_regex/", $auth_user)) {
        header("location: $site_url/denied.php");
    }
    break;
case 'dev':
    /* Well, no rule, just depends what we said in config.php */
    if (!in_array($auth_user, $admin_users)) {
        header("location: $site_url/denied.php");
    } else if ($installer_mode != TRUE) {
        die ("You need to turn on installer mode in config.php before you go any further...");
    }
    break;
case 'shopkeeper':
    /* Shopkeepers are allowed in here */
    if (!in_array($auth_user, $shopkeepers)) {
        header("location: $site_url/denied.php");
    }
    break;
case 'shopmanager':
case 'stockeditor':
    /* Guess who is allowed in here? */
    if (!in_array($auth_user, $shopmanagers)) {
        header("location: $site_url/denied.php");
    }
    break;
default:
    /* This means, I haven't actually put this location into
     * auth.php yet, so we're going to be safe and kick you out */
    header("location: $site_url/denied.php");
    break;    
}

/* These are not the droids you have been looking for */