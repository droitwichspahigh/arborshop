<html>
<?php

require ("../bin/auth.php");

$conn = mysqli_connect($dbhost, $dbuser, $dbpass);

if (!$conn) {
    die ("Database connection failure<br>");
}

if ($lets_start_right_from_the_beginning != TRUE)
    die ("You don't really want to do this.  Check config.php");
    
echo "Connected successfully<br /><br />";

function dosql($sqlcmd) {
    if (mysqli_query($conn, $sqlcmd)) {
        echo "$sqlcmd performed successfully<br /><br />";
    } else {
        die ("Error in $sqlcmd : " . mysqli_error($conn));
    }
}

dosql("DROP DATABASE $dbname;");
dosql("CREATE DATABASE $dbname;");
dosql("USE $dbname;");
dosql("CREATE TABLE purchases ");

?>
</html>
