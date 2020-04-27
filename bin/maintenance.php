<?php
/* Here you can change parts to temporarily allow certain actions */ 

/**
 * Lock everyone out of the website.
 * 
 * Not a good idea to disable this while maintenance mode active,
 * otherwise people will be allowed to login and you might forget...
 */
$maintenance = TRUE;

/**
 * Set to TRUE to echo warnings and notices from SQL.
 */
$debug = TRUE;

/**
 * Everything inside the /dev hierarchy depends on this being TRUE
 */
$installer_mode = TRUE;

/** 
 * If this is set, it enables /dev/create_db, which entails dropping
 * the old database.
 */
$lets_start_right_from_the_beginning = TRUE;

/** Set up some PHP error reporting too */

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);