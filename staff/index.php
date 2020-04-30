<?php
require '../bin/auth.php';
require "../bin/database.php";
require "../bin/Shop.php";
?>
<html lang="en">

<?php require '../bin/head.php';?>

<body>
	<div class="container">
    	<h1>Welcome to the staff area of <?= $site_name ?>!</h1>
    	
		<?php
		if (in_array($auth_user, $shopmanagers)) {
		    $sm_btn = '<a class="btn btn-warning" href="shopmanager/">Edit stock details</a>';
		    $sm_msg = "You are also a shop manager, so you can edit the shop details.";
		}
		
		if (in_array($auth_user, $shopkeepers)) { ?>
			<p>As you are a shopkeeper, you may sell products to students.  <?= $sm_msg; ?></p>
			
			<p><a class="btn btn-success" href="sell.php">Process sales</a> <?= $sm_btn; ?></p>
		<?php } ?>
    	
        <!-- Give the shop items -->
        <?php 
        	$shop = new bin\Shop($conn);
        	$shop->outputHtmlListItems();
        ?>
    	
    	<p></p>
    	
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