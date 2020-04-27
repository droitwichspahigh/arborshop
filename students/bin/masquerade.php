<?php
/**
 * Sets student to the student's login, if an administrator tries to appear as a student
 * 
 * The administrator is expected to know the correct form of a student's username
 */

/* Am I a student? */
if (preg_match("/^$student_user_regex/", $_SERVER['PHP_AUTH_USER'])) {
    $student = $_SERVER['PHP_AUTH_USER'];
} else {
    if (isset($_GET['masquerade_name'])) {
        $student = $_GET['masquerade_name'];
    } else {
        $student = "";
    }
}

/* We may have been allowed onto this page as an administrator, so
 * we'll just collect a student login to pretend to be.
 */
if (strcmp($student, "") == 0) {    ?>
    <html lang="en">
    	<head>
    		<title>Masquerade time!</title>
    	</head>
    	<body>
    		<form action="index.php" method="get">
    			<p>Whom to masquerade as? 
    				<input type="text" name="masquerade_name" />
    				<input type="submit" value="Masquerade!" />
    			</p>
    		</form>
    	</body>
    </html>
<?php
    die("<!-- Collecting username for masquerade purposes -->");
}