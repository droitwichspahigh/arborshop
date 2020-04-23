<?php

if (!$dbhost) {
    die ("dbhost not defined.  Check config.php<br /><br />");
}

if ($first_connection) {
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass);
} else {
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
}

if (!$conn) {
    die ("Database connection failure<br />");
}

function debug($msg) {
    global $debug;
    
    if ($debug) {
        echo $msg;
    }
}

function dosql($sqlcmd, $critical = TRUE) {
    global $conn;
    
    if (mysqli_query($conn, $sqlcmd)) {
        debug("$sqlcmd performed successfully<br /><br />");
    } else {
        if ($critical == TRUE) {
            die ("Error:   $sqlcmd failed: " . mysqli_error($conn));
        } else {
            debug("Warning: $sqlcmd failed: " . mysqli_error($conn));
        }
    }
}
