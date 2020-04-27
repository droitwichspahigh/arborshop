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
 * Enable this to drop all the student records from the previous year.
 * 
 * A September job!
 */
$new_year = TRUE;

/**
 * This means the ENTIRE database is going to be dropped and remade
 */
$lets_start_right_from_the_beginning = FALSE;

/** Set up some PHP error reporting too */

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);