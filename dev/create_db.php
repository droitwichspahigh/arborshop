<html>
<?php

require "../bin/classes.php";
require "../bin/auth.php";

use ArborShop\Database;
use ArborShop\Config;

$db = new Database(TRUE);
$dbname = Config::$db['name'];

$db->dosql("USE $dbname;", FALSE);

echo "Got here";

if (isset($lets_start_right_from_the_beginning) && $lets_start_right_from_the_beginning == TRUE) {
    $db->dosql("DROP DATABASE $dbname;", FALSE); /* Don't mind if this fails */
    $db->dosql("CREATE DATABASE $dbname;");
    $db->dosql("USE $dbname;");
    include "reset_products.php";
    $new_year = TRUE;
}

if (isset($new_year) && $new_year == TRUE)
    include "newyear.php";

?>
</html>
