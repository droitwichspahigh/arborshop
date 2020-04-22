<html>
<?php

die ("This doesn't work yet");

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if(!$conn)
{
	echo "Connected failure<br>";
}
echo "Connected successfully<br><br>";

$sql = "DROP TABLE students";

if(mysqli_query($conn, $sql))
{
	echo "students table deleted successfully<br><br>";
}
else
{
	echo "Error deleting students table : " . mysqli_error($conn) . "<br><br>";
}

$sql = "CREATE TABLE students(admin INT, first_name VARCHAR(30) NOT NULL, last_name VARCHAR(30) NOT NULL, 
		dob date, gender VARCHAR(1), upn VARCHAR(20), year_group VARCHAR(10), tutor_group VARCHAR(25), network_username VARCHAR(50), 
		house_number VARCHAR(50), house_name VARCHAR(50), apartment VARCHAR(50), street VARCHAR(200), town VARCHAR(100), 
		postcode VARCHAR(10), parental_salutation VARCHAR(500), en_set VARCHAR(10), ma_set VARCHAR(10),
		sc_set VARCHAR(10), opt_a VARCHAR(10), opt_b VARCHAR(10), opt_c VARCHAR(10), opt_d VARCHAR(10), 
		primary key (admin))";

if(mysqli_query($conn, $sql))
{
	echo "students table created successfully<br><br>";
}
else
{
	echo "Error creating students table : " . mysqli_error($conn) . "<br><br>";
}

$student_data = array();
$student_data = file("student_import.csv");
if($student_data)
{
	echo "Loaded awards data file<br><br>";
}
else
{
	echo "Failed to load awards data file<br><br>";
}

//print_r($student_data);

foreach($student_data as $sdl)
{
	//echo $sdl . "<br>";
	$sd = array();
	$sd = explode(",", $sdl);
	$sd = str_replace('"',"",$sd);
	//print_r($sd);
	$sql = "INSERT INTO students(admin, first_name, last_name, dob, gender, upn, year_group, tutor_group, network_username, house_number, house_name, apartment, street, town, postcode, parental_salutation, en_set, ma_set, sc_set, opt_a, opt_b, opt_c, opt_d) VALUES 
			($sd[0],\"$sd[1]\",\"$sd[2]\",\"" . date("Y-m-d", strtotime($sd[3])) . "\",\"$sd[4]\",\"$sd[5]\",\"$sd[6]\",\"$sd[7]\",\"$sd[8]\",\"$sd[9]\",\"$sd[10]\",\"$sd[11]\",\"$sd[12]\",\"$sd[13]\",\"$sd[14]\",\"$sd[15]\",\"$sd[16]\",\"$sd[17]\",\"$sd[18]\",\"$sd[19]\",\"$sd[20]\",\"$sd[21]\",\"$sd[22]\")";
	echo $sql . "<br>";
	if(mysqli_query($conn, $sql))
	{
		echo "successful<br>";
	}
	else
	{
		echo "Error : " . mysqli_error($conn) . "<br>";
	}
	
}

$sql = "INSERT INTO students(admin, first_name, last_name, dob, gender, upn, year_group, tutor_group, network_username, en_set, ma_set, sc_set) VALUES 
			(099999,\"Joe\",\"Bloggs\",\"2000-01-01\",\"M\",\"N885331610003\",\"Year 11\",\"11MDG\",\"15BloggsJ\",\"10l/En3\",\"10l/Ma4\",\"10l/Sc3a\")";
	echo $sql . "<br>";
	if(mysqli_query($conn, $sql))
	{
		echo "successful<br>";
	}
	else
	{
		echo "Error : " . mysqli_error($conn) . "<br>";
	}

mysqli_close($con);

?>
</html>
