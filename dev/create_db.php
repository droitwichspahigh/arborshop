<html>
<?php

require "../bin/classes.php";
require "../bin/auth.php";

use ArborShop\Database;
use ArborShop\Config;

$db = new Database(TRUE);
$dbname = Config::$db['name'];

$db->dosql("USE $dbname;", FALSE);

if (Config::allowed_maintenance('lets_start_right_from_the_beginning')) {
    $db->dosql("DROP DATABASE $dbname;", FALSE); /* Don't mind if this fails */
    $db->dosql("CREATE DATABASE $dbname;");
    $db->dosql("USE $dbname;");
    include "reset_products.php";
    include "newyear.php";
} else if (Config::allowed_maintenance('new_year')) {
    include "newyear.php";
}

?>
</html>
