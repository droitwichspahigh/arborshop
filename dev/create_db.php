<html>
<?php

require "../bin/auth.php";

if($installer_mode != TRUE) {
    die("Installer mode not true");
}

$conn = mysqli_connect($dbhost, $dbuser, $dbpass);

if(!$conn)
{
	echo "Connected failure<br>";
}
echo "Connected successfully<br><br>";
$sql = "DROP DATABASE " . $dbname;

/*if(mysqli_query($conn, $sql))
{
	echo $dbname . " database deleted successfully";
}
else
{
	echo "Error deleting " . $dbname . " database : " . mysqli_error($conn);
} */

echo "<br><br>";

echo $sql;
if(mysqli_query($conn, $sql))
{
	echo $dbname . " database created successfully";
}
else
{
	echo "Error creating " . $dbname . " database : " . mysqli_error($conn);
}

mysqli_close($conn);


?>
</html>
