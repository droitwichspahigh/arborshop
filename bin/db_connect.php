<?php

require ("config.php");

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, "purchases");

if (!$conn) {
    die ("Database connection failure<br>");
}