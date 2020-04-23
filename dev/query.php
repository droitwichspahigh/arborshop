<html>
<?php

require ("../bin/auth.php");
require ("../bin/database.php");

die ("This doesn't do anything useful yet<br /><br />");

echo "List of years<br><br>";

$sql = "SELECT year FROM awards GROUP BY year";

$result = mysqli_query($conn, $sql);

if($result)
{
	echo "SQL successful<br><br>";
}
else
{
	echo "Error : " . mysqli_error($conn) . "<br><br>";
}

//echo mysqli_num_rows($result) . "<br>";

if(mysqli_num_rows($result) > 0)
{
	while($row = mysqli_fetch_assoc($result))
	{
		echo $row["year"] . "<br>";
	}
}
else
{
	echo "No results<br>";
}
echo "<br><br>";



echo "Subjects in a year<br><br>";

$year = 8;
$sql = "SELECT subject,year FROM awards WHERE year=$year GROUP BY subject";

$result = mysqli_query($conn, $sql);

if($result)
{
	echo "SQL successful<br><br>";
}
else
{
	echo "Error : " . mysqli_error($conn) . "<br><br>";
}

//echo mysqli_num_rows($result) . "<br>";

if(mysqli_num_rows($result) > 0)
{
	while($row = mysqli_fetch_assoc($result))
	{
		echo $row["subject"] . "<br>";
	}
}
else
{
	echo "No results<br>";
}



echo "Students belonging to an award<br><br>";

$sql = "SELECT * FROM awards,students WHERE students.admin=awards.admin";


$result = mysqli_query($conn, $sql);

if($result)
{
	echo "SQL successful<br><br>";
}
else
{
	echo "Error : " . mysqli_error($conn) . "<br><br>";
}

//echo mysqli_num_rows($result) . "<br>";

if(mysqli_num_rows($result) > 0)
{
	while($row = mysqli_fetch_assoc($result))
	{
		//echo $row["year"] . "<br>";
		print_r($row);
		echo "<br>";
	}
}
else
{
	echo "No results<br>";
}
echo "<br><br>";


echo "Add students to an award<br><br>";
$award_id = 10;
$student_admin_no = 20479;
$nominated_by = "wylder";

$sql = "UPDATE awards SET admin=$student_admin_no,nominated_by=\"$nominated_by\" WHERE id=$award_id";


$result = mysqli_query($conn, $sql);

if($result)
{
	echo "SQL successful<br><br>";
}
else
{
	echo "Error : " . mysqli_error($conn) . "<br><br>";
}


echo "Students name from admin number<br><br>";

$admin_no = 19374;
$sql = "SELECT * FROM students WHERE admin=$admin_no";


$result = mysqli_query($conn, $sql);

if($result)
{
	echo "SQL successful<br><br>";
}
else
{
	echo "Error : " . mysqli_error($conn) . "<br><br>";
}

//echo mysqli_num_rows($result) . "<br>";

if(mysqli_num_rows($result) > 0)
{
	while($row = mysqli_fetch_assoc($result))
	{
		//echo $row["year"] . "<br>";
		print_r($row);
		echo "<br>";
	}
}
else
{
	echo "No results<br>";
}
echo "<br><br>";



echo "Students number of awards<br><br>";

$sql = "SELECT students.first, students.surname, awards.admin, awards.id, count(awards.admin) as no_of_awards FROM students left join awards on (students.admin = awards.admin) GROUP BY students.admin";


$result = mysqli_query($conn, $sql);

if($result)
{
	echo "SQL successful<br><br>";
}
else
{
	echo "Error : " . mysqli_error($conn) . "<br><br>";
}

//echo mysqli_num_rows($result) . "<br>";

if(mysqli_num_rows($result) > 0)
{
	while($row = mysqli_fetch_assoc($result))
	{
		//echo $row["year"] . "<br>";
		print_r($row);
		echo "<br>";
	}
}
else
{
	echo "No results<br>";
}
echo "<br><br>";

mysqli_close($con);

?>

</html>
