<?php

require('config.php');

/* User authenticated? */

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header("location: $site_url/");
}

/* Maintenance mode?  Sorry, devs only */

if ($maintenance == TRUE) {
    if (!in_array($_SERVER['PHP_AUTH_USER'], $admin_users)) {
        die ("Maintenance mode");
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
    /* Student usernames begin with two or more numbers and then alphanums */
    if (!preg_match('/^[0-9]{2}[-_\@.a-zA-Z]+$/', $_SERVER['PHP_AUTH_USER'])) {
        if (!in_array($_SERVER['PHP_AUTH_USER'], $admin_users)) {
            header("location: $site_url/denied.php");
        }
    }
    break;
case 'staff':
    /* Staff usernames do not contain numbers */
    if (!preg_match('/^[-_\@.a-zA-Z]+$/', $_SERVER['PHP_AUTH_USER'])) {
        header("location: $site_url/denied.php");
    }
    break;
case 'dev':
    /* Well, no rule, just depends what we said in config.php */
    if (!in_array($_SERVER['PHP_AUTH_USER'], $admin_users)) {
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