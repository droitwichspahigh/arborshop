<?php
namespace ArborShop;

require '../bin/auth.php';
require '../bin/classes.php';
require 'bin/masquerade.php';

/**
 * From masquerade.php:
 * 
 * @var string $masqueraded_username
 * 
 * The apparent username of the student
 */

$student = new Student($masqueraded_username);

?>
<html lang="en">
<head>
	<?php require ('../bin/head.php');?>
</head>

<body>
	<?php if (isset($_GET['masquerade_name'])) { ?>
	<!-- Probably an Administrator, but this link will deny if someone
	     tries just setting ?masquerade= anyway -->
	     <nav aria-label="breadcrumb">
	     	<ol class="breadcrumb">
	     		<li class="breadcrumb-item"><a href="../">Back to staff area</a></li>
	     	</ol>
	     </nav>
	<?php } ?>
	<div class="container">
    	<div class="text-center"><img class="mb-4 img-responsive" src="../img/logo_v2.jpg" alt="" height="72" /></div>
    	<h1 class="h3 font-weight-normal">Welcome to <?= Config::$site_name; ?>, <?= $student->getFirstName(); ?>.</h1>
    	<div id="user-details">
    		You have <?= $student->getPoints(); ?> points to spend.  Please have a look at the products available for you below, and click on them to purchase.
    	</div>
    	<?php 
        	$shop = new Shop(new Database());
        	$shop->studentShop($student->getYearGroup(), $student->getPoints());
        ?>
    	
	</div>
	
</body>

</html>