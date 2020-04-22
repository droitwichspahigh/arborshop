<html>
<?php

require "../bin/auth.php";
require "../bin/db_connect.php";

echo "Connected successfully<br /><br />";

if ($lets_start_right_from_the_beginning != TRUE)
    die ("You don't want to do this.  Check config.php");

$sql = "DROP DATABASE $dbname";

if(mysqli_query($conn, $sql))
{
	echo "$dbname database deleted successfully";
}
else
{
	echo "Error deleting $dbname database: " . mysqli_error($conn);
}

echo "<br /><br />";

echo $sql;
if(mysqli_query($conn, $sql))
{
	echo "$dbname database created successfully";
}
else
{
	echo "Error creating $dbname database: " . mysqli_error($conn);
}

mysqli_close($conn);


?>
</html>
