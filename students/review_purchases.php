<?php
namespace ArborShop;

require '../bin/auth.php';
require '../bin/classes.php';
require 'bin/masquerade.php';

/**
 * From masquerade.php:
 * 
 * @var string $masqueraded_username
 * 
 * The apparent username of the student
 */

$db = new Database();
$shop = new Shop($db);
$student = new Student($masqueraded_username);
$purchaseHistory = new PurchaseHistory($student);

if ($masqueraded_username != "") {
    $m = "&masquerade_name=$masqueraded_username";
} else {
    $m = "";
}
if (isset($_GET['cancel'])) {
    $purchaseHistory->deletePurchase($_GET['cancel']);
    header("Location: review_purchases.php?dummy=none$m");
}

?>
<html lang="en">
<?php require "../bin/head.php"; ?>
<body>
	<br />
	<div class="container">
    	<div class="text-center"><img class="mb-4 img-responsive" src="../img/logo_v2.jpg" alt="" height="72" /></div>
    	<h3 class="h3 font-weight-normal mb-4">Purchase history for <?= $student->getFirstName(); ?> <?= $student->getLastName(); ?>.</h3>
    	<div id="user-details" class="mb-3">
    		In your purchase history, you can click purchases that you haven't collected to cancel them.
    	</div>
    	<div>
    		<a href="index.php<?= $masqueraded_username != "" ? "?masquerade_name=$masqueraded_username" : ""; ?>" class="btn btn-primary">Back to the shop</a>
    	</div>    	
    	<?php
    	/* Let's first get the ones we can cancel */
    	/** @var Purchase $p */
    	foreach ($purchaseHistory->getPurchases() as $p) {
    	    if ($p->getCollected() == "") {
    	        $itemName = $shop->getItemById($p->getItemId())->getName();    
    	        $link = '<a href="?cancel=' . $p->getPurchaseId() . $m . '" class="stretched-link"></a>';
    	        $datetime = $p->getDatetime();
    	        $price = $p->getPrice();
    	        echo <<< EOF
<div class="row">
    <div class="col-sm-2 text-center">$datetime$link</div>
    <div class="col-sm-1 text-center"><strong>$price</strong>$link</div>
    <div class="col-sm-7">$itemName$link</div>
</div>
EOF;
    	    }
    	}
    	/* Now the ones we can't cancel */
    	foreach ($purchaseHistory->getPurchases() as $p) {
    	    if ($p->getCollected() != "") {
    	        $itemName = $shop->getItemById($p->getItemId())->getName();
    	        $datetime = $p->getDatetime();
    	        $price = $p->getPrice();
    	        $collected = $p->getCollected();
    	        echo <<< EOF
<div class="row">
    <div class="col-sm-2 text-center">$datetime</div>
    <div class="col-sm-1 text-center"><strong>$price</strong></div>
    <div class="col-sm-3">$itemName</div>
    <div class="col-sm-6">Collected on: $collected</div>
</div>
EOF;
    	    }
    	}
    	?>
	</div>
</body>
</html>