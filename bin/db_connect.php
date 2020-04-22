<?php

require ("config.php");

$conn = mysqli_connect($dbhost, $dbuser, $dbpass);

if(!$conn)
{
    die ("Database connection failure<br>");
}