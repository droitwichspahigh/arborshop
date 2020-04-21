<?php
include('bin/config.php');

if(isset($_SERVER['PHP_AUTH_USER'])) {
    header('location: redirector.php');
}

if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['authenticate'])) {
    if (!isset($_SERVER['PHP_AUTH_USER'])) {
        header('WWW-Authenticate: Basic realm="CSE2K"');
        header('HTTP/1.0 401 Unauthorized');
        exit;
    } else {
        $secure_user = strtolower($_SERVER["PHP_AUTH_USER"]);
        header('location: redirector.php');
    }
}
?>
<html lang="en">

<?php include('bin/head.php');?>

<head>
	<link href="css/signin.css" rel="stylesheet">
</head>

<body class="text-center">

	<div class="card border-primary">
		<div class="card-body">
			<form action="index.php" method="post">
				<img class="mb-4" src="img/badge_primary.svg" alt="" width="72" height="72">
				<h1 class="h3 mb-3 font-weight-normal"><?= $brand_name; ?></h1>
				<h1 class="h3 mb-3 font-weight-normal"><small><?= $site_name; ?></small></h1>
				<button type="submit" name="authenticate" class="btn btn-lg btn-primary btn-block mb-3">Sign in</button>
				<small class="text-muted">Please enter your network username and password when prompted.</small>
				<hr>
				<p class="text-footer text-muted">Powered by <a href="mailto:reescm@droitwichspahigh.worcs.sch.uk?subject=<?= $brand_name; ?>">DSHS</a><br><small><?php echo $version; ?></small></p> 
			</form>
		</div>
	</div>
	
</body>

</html>
