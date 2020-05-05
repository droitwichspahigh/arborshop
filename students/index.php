<?php
require '../bin/auth.php';
require 'bin/arborStudent.php';

?>
<html lang="en">
<head>
	<?php require ('../bin/head.php');?>
</head>

<body>
	<?php include "../bin/breadcrumbs.php"; ?>
	<div class="container">
    	<div class="text-center"><img class="mb-4 img-responsive" src="../img/logo_v2.jpg" alt="" height="72" /></div>
    	<h1 class="h3 font-weight-normal">Welcome to <?= $site_name; ?>, <?= $arborStudent->getPerson()->getPreferredFirstName(); ?>.</h1>
    	<div id="user-details">
    		You have <?= $points; ?> points to spend.  Please have a look at the products available for you below, and click on them to purchase.
    	</div>
	</div>
	
</body>

</html>