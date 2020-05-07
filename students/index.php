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

$db = new Database();
$shop = new Shop($db);
$student = new Student($masqueraded_username);

if (isset($_GET['purchase'])) {
    require "bin/do_purchase.php";
}

if (isset($_GET['successful_purchase'])) {
    /* TODO Congratulate the kid here */
}

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
	<?php } else echo "<br />"; ?>
	<div class="container">
    	<div class="text-center"><img class="mb-4 img-responsive" src="../img/logo_v2.jpg" alt="" height="72" /></div>
    	<h3 class="h3 font-weight-normal mb-4">Welcome to <?= Config::$site_name; ?>, <?= $student->getFirstName(); ?>.</h3>
    	<div id="user-details" class="mb-3">
    		You have <span class="font-weight-bold"><?= $student->getPoints(); ?></span> points to spend.  Please have a look at the products available for you below, and click on them to purchase.
    	</div>
    	<div>
    		<a href="previous-purchases.php<?= $masqueraded_username != "" ? "?masquerade_user=$masqueraded_username" : ""; ?>" class="btn btn-primary">Review previous purchases</a>
    	</div>
    	<?php 
        	$shop->studentShop($student->getYearGroup(), $student->getPoints());
        ?>
    	
	</div>
	
</body>

</html>