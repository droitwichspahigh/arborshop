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
}

/* So, let's check this user should actually be here! */

$site_section = basename(dirname($_SERVER['REQUEST_URI']));

/* 
 * So if you're on index.php, dirname strips off 
 * the current directory as there is no filename.
 * 
 * I find this behaviour abhorrent personally.
 */
if ($site_section == 'shop')
    $site_section = basename($_SERVER['REQUEST_URI']);

switch($site_section) {
case 'students':
    if (!preg_match("/^$student_user_regex/", $auth_user)) {
        if (!in_array($auth_user, $admin_users)) {
            header("location: $site_url/denied.php");
        }
    }
    break;
case 'staff':
    if (!preg_match("/$staff_user_regex/", $auth_user)) {
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
default:
    /* This means, I haven't actually put this location into
     * auth.php yet, so we're going to be safe and kick you out */
    header("location: $site_url/denied.php");
    break;    
}

/* These are not the droids you have been looking for */