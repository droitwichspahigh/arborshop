<html>
<?php

die ("Not audited yet");

ini_set('display_errors', 1);
error_reporting(E_ALL);

echo("hello and welcome to the happy place that is TESTING<BR><BR>");
echo"It begins ...<BR><BR>";

echo "here ";

$con = mysqli_connect("localhost", $user, $password);
if(!$con)
{
	die("Could not connect: " . mysqli_error());
}
echo "and here";
$sql = "SELECT VERSION";
$result = mysqli_query($con, $sql);

if(mysqli_num_rows($result) > 0)
{
	while($row = mysqli_fetch_assoc($result))
	{
		echo $row . "<BR>";
	}
}

//$query = "SELECT VERSION";
//$result = mysqli_query($query);
print_r($result);
/* if($result)
{
	echo "success<BR>";
}
else
{
	echo "failed<BR>";
} */

mysqli_close($con);

echo "<BR>the end<BR>"
?>
