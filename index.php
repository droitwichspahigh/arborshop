<?php
include('bin/classes.php');
use ArborShop\Config;

if(isset($_SERVER['PHP_AUTH_USER'])) {
    if(Config::is_student($_SERVER['PHP_AUTH_USER'])) {
        header('location: students');
    } elseif(Config::is_staff($_SERVER['PHP_AUTH_USER'])) {
        header('location: staff');
    } else {
        header('location: denied.php');
    }
} else if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['authenticate'])) {
    header('WWW-Authenticate: Basic realm="CSE2K"');
    header('HTTP/1.0 401 Unauthorized');
}
?>
<html lang="en">

<head>
	<?php include('bin/head.php');?>
</head>

<body>
	<div class="card text-center border-primary mx-auto my-5" style="width: 18rem">
		<div class="card-body">
			<form action="index.php" method="post">
				<img class="mb-4 w-100" src="img/logo_v2.jpg" alt="Droitwich Spa High School">
				<h3 class="mb-3"><?= Config::$site_name; ?></h1>
				<button type="submit" name="authenticate" class="btn btn-lg btn-primary btn-block mb-3">Sign in</button>
				<p class="small text-muted">Please enter your school username and password when prompted.</p>
				<hr />
				<p class="text-footer text-muted">Powered by <a href="mailto:<?= Config::$support_email; ?>?subject=<?= Config::$brand_name; ?>"><?= Config::$powered_by ?></a><br><small><?php echo Config::$version; ?></small></p> 
			</form>
		</div>
	</div>
	
</body>

</html>
