<?php
require '../bin/auth.php';
require '../bin/arbor_connection.php';
require 'bin/masquerade.php';

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
						<h1 class="h3 font-weight-normal">Welcome to <?= $site_name; ?>, <?= $student; ?></h1>
						<div id="user-details">
							<!-- Here we're going to fill in the user's name, net points and balance once fetched from Arbor -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
</body>

</html>