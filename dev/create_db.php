<html>
<?php

require "../bin/auth.php";

$first_connection = TRUE;

require "../bin/database.php";

if ($lets_start_right_from_the_beginning == TRUE) {
    dosql("DROP DATABASE $dbname;", FALSE); /* Don't mind if this fails */
    dosql("CREATE DATABASE $dbname;");
    dosql("USE $dbname;");
    include "reset_products.php";
    $new_year = TRUE;
}

if ($new_year == TRUE)
    include "newyear.php";

?>
</html>
