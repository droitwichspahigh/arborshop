<?php
require '../bin/auth.php';
require "../bin/database.php";
require "../bin/Shop.php";
?>
<html lang="en">

<?php require '../bin/head.php';?>

<body>
	<div class="container-sm">
    	<h1>Welcome to the staff area of <?= $site_name ?>!</h1>
    	
    	<!-- First, the items -->
    	
<?php 
	$shop = new bin\Shop($conn);
	$shop->outputHtmlListItems();
?>
    	
    	<p></p>
    	
    	<!-- Then, the shopkeeper links -->
    	
    	<!-- Then, the shopmanager links -->
    	
    </div>
    	
    	
    	
    	
    	
    	
    	
<?php
if (!in_array($auth_user, $shopkeepers)) {
?>
	<p>There's not really anything for you to do here.</p>
	
	<p>
		If you are someone who runs the Shop, then please get in touch with
		<a href="mailto:<?= $support_email ?>?subject=<?= $site_name; ?>: Access Request (shopkeeper)"><?= $powered_by ?></a>
	</p>
<?php 
}
?>
</body>

</html>