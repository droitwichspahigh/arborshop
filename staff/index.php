<?php
use ArborShop\Config;

require "../bin/auth.php";
require "../bin/classes.php";

/** From auth.php:
 * 
 * @var string $auth_user
 */
?>
<html lang="en">

<?php require "../bin/head.php"; ?>

<body>
	<?php include "../bin/breadcrumbs.php"; ?>
	<div class="container">
    	<h1>Welcome to the staff area of <?= Config::$site_name ?>!</h1>
    	
		<?php
		if (Config::is_shopmanager($auth_user)) {
		    $sm_btn = '<a class="btn btn-warning" href="shopmanager/">Edit stock details</a>';
		    $sm_btn .= ' <a class="btn btn-primary" href="../students/">See the shop as a student</a>';
		    $sm_msg = "You are also a shop manager, so you can edit the shop details.";
		}
		
		if (Config::is_shopkeeper($auth_user)) { ?>
			<p>As you are a shopkeeper, you may sell products to students.  <?= $sm_msg; ?></p>
			
			<p><a class="btn btn-success" href="sell.php">Process sales</a> <?= $sm_btn; ?></p>
		<?php } ?>
    	
        <!-- Give the shop items -->
        <?php 
        	$shop = new ArborShop\Shop(new ArborShop\Database());
        	$shop->staffShop();
        ?>
    	
    	<p></p>
    	
    </div>
    	
    	
    	
    	
    	
    	
    	
<?php
if (!Config::is_shopkeeper($auth_user)) {
?>
	<p>There's not really anything for you to do here.</p>
	
	<p>
		If you are someone who runs the Shop, then please get in touch with
		<a href="mailto:<?= Config::$support_email ?>?subject=<?= Config::$site_name; ?>: Access Request (shopkeeper)"><?= Config::$powered_by ?></a>
	</p>
<?php 
}
?>
</body>

</html>