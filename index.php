<?php
include('bin/config.php');

if(isset($_SERVER['PHP_AUTH_USER'])) {
    if(preg_match("/$student_user_regex/", $_SERVER['PHP_AUTH_USER'])) {
        header('location: students');
    } elseif(preg_match("/$staff_user_regex/", $_SERVER['PHP_AUTH_USER'])) {
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
				<h3 class="mb-3"><?= $site_name; ?></h1>
				<button type="submit" name="authenticate" class="btn btn-lg btn-primary btn-block mb-3">Sign in</button>
				<p class="small text-muted">Please enter your school username and password when prompted.</p>
				<hr />
				<p class="text-footer text-muted">Powered by <a href="mailto:<?= $support_email; ?>?subject=<?= $brand_name; ?>"><?= $powered_by ?></a><br><small><?php echo $version; ?></small></p> 
			</form>
		</div>
	</div>
	
</body>

</html>
