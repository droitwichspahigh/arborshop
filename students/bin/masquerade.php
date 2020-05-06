<?php

/**
 * Sets student to the student's login, if an administrator tries to appear as a student
 * 
 * The administrator is expected to know the correct form of a student's username
 */

require "../bin/classes.php";

/* Am I a student? */
if (ArborShop\Config::is_student($_SERVER['PHP_AUTH_USER'])) {
    $masqueraded_username = $_SERVER['PHP_AUTH_USER'];
} else {
    if (isset($_GET['masquerade_name'])) {
        $masqueraded_username = $_GET['masquerade_name'];
    } else {
        $masqueraded_username = "";
    }
}

/* We may have been allowed onto this page as a ShopManager, so
 * we'll just collect a student login to pretend to be.
 */
if (strcmp($masqueraded_username, "") == 0) {    ?>
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