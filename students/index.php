<?php
require '../bin/auth.php';
require 'bin/arborStudent.php';

?>
<html lang="en">
<head>
	<?php require ('../bin/head.php');?>
	<link href="../css/students.css" rel="stylesheet">
</head>

<body>
	<div class="container">
		<div class="row justify-content-md-center">
			<div class="col-lg-9">
				<div class="card border-primary">
					<div class="card-body">
						<img class="mb-4" src="../img/logo_v2.jpg" alt="" height="72">
						<h1 class="h3 font-weight-normal">Welcome to <?= $site_name; ?>, <?= $arborStudent->getPerson()->getPreferredFirstName(); ?>.</h1>
						<div id="user-details">
							You have <?= $points; ?> points to spend.  Please have a look at the products available for you below, and click on them to purchase.
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
</body>

</html>