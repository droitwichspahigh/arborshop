<html>
<?php

require ("../bin/auth.php");

$first_connection = TRUE;

require ("../bin/db_connect.php");

if ($lets_start_right_from_the_beginning != TRUE)
    die ("You don't really want to do this.  Check config.php");
    
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
