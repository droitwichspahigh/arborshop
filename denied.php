<?php
require('bin/config.php');
?>
<html lang="en">

<?php include('bin/head.php');?>

<head>
	<link href="css/signin.css" rel="stylesheet">
</head>

<body class="text-center">

	<div class="card border-danger">
		<div class="card-body">
			<form action="index.php" method="post">
				<a href="./"><img class="mb-4" src="img/times-circle.svg" alt="" width="72" height="72"></a>
				<p class="text-footer text-muted">Access Denied: You are not authorised to use this resource. Please contact <a href="mailto:<?= $support_email ?>?subject=<?= $brand_name; ?>: Access Request"><?= $powered_by ?></a> if this is an error.</p>
				<p class="text-footer text-muted">You're trying to access this site as <?= $_SERVER['PHP_AUTH_USER'];?></p>
			</form>
		</div>
	</div>
	
</body>

</html>