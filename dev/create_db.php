<html>
<?php

require ("../bin/auth.php");

if ($lets_start_right_from_the_beginning != TRUE)
    die ("You don't really want to do this.  Check config.php");
    
$conn = mysqli_connect($dbhost, $dbuser, $dbpass);

if (!$conn) {
    die ("Database connection failure<br>");
}

echo "Connected successfully<br /><br />";

function dosql($sqlcmd, $critical = TRUE) {
    global $conn;
    
    if (mysqli_query($conn, $sqlcmd)) {
        echo "$sqlcmd performed successfully<br /><br />";
    } else {
        if ($critical == TRUE) {
            die ("Error:   $sqlcmd failed: " . mysqli_error($conn));
        } else {
            echo "Warning: $sqlcmd failed: " . mysqli_error($conn);
        }
    }
}

dosql("DROP DATABASE $dbname;", FALSE); /* Don't mind if this fails */
dosql("CREATE DATABASE $dbname;");
dosql("USE $dbname;");
$sql = <<< EOT
    CREATE TABLE purchases (
        purchase_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        upn VARCHAR(13) NOT NULL,
        datetime DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        price SMALLINT UNSIGNED NOT NULL,
        item_id INT UNSIGNED NOT NULL,
        collected DATETIME DEFAULT NULL,
        CONSTRAINT purchases_pk PRIMARY KEY (purchase_id)
    );
EOT;
dosql($sql);
dosql("CREATE TABLE spent (upn VARCHAR(13) NOT NULL, spent SMALLINT UNSIGNED NOT NULL DEFAULT 0);")

?>
</html>
