<?php
namespace ArborShop;

require "../../bin/auth.php";
require "../../bin/classes.php";

/** From auth.php:
 *
 * @var string $auth_user
 */

$db = new Database();
$purchaseDb = new PurchaseDb($db);
$shop = new Shop($db);
$studentList = [];

if (isset($_GET['collect'])) {
    $purchaseDb->collect($_GET['collect']);
    header("location: index.php");
} else if (isset($_GET['uncollect'])) {
    $purchaseDb->uncollect($_GET['uncollect']);;
    header("location: index.php");
}


?>
<html lang="en">

<?php require "../../bin/head.php"; ?>

<body>
	<?php include "../../bin/breadcrumbs.php"; ?>
	<div class="container">
    	<h1>Welcome to the shopkeeper area of <?= Config::$site_name ?>!</h1>
    	
    	<div>Here, you can see purchases, most recent first.  If you click a purchase, it is
    		marked 'collected' and they can no longer obtain a refund on it.</div>
    	
    	<hr />
    	
    	<?php 
    	/** @var \ArborShop\Purchase $p */
    	foreach ($purchaseDb->getPurchases() as $p) {
    	    /* First, we'll show the uncollected purchases */
	        if ($p->getCollected() == "") {
	            if (!array_key_exists($p->getArborId(), $studentList)) {
	                $studentList[$p->getArborId()] = new Student($p->getArborId());
	            }
	            $name = $studentList[$p->getArborId()]->getFirstName() . ' ' . $studentList[$p->getArborId()]->getLastName();
	            $itemName = $shop->getItemById($p->getItemId())->getName();
	            $link = '<a href="?collect=' . $p->getPurchaseId() . '" class="stretched-link"></a>';
	            $datetime = $p->getDatetime();
	            $price = $p->getPrice();
	            echo <<< EOF
<div class="row">
    <div class="col-sm-3 text-left">$name$link</div>
    <div class="col-sm-3">$itemName$link</div>
    <div class="col-sm-2 text-center">$datetime$link</div>
    <div class="col-sm-1 text-center"><strong>$price</strong>$link</div>
</div>
<hr />
EOF;
	        }
    	}
    	
    	echo "<h5>These purchases have been marked as collected today, so don't give them again!  Click any if that was a mistake:</h5>";
    	echo "<hr />";
    	
    	foreach ($purchaseDb->getPurchases() as $p) {
    	    /* Now we'll show the collected purchases */
    	    if ($p->getCollected() != "") {
    	        if (strcmp(substr($p->getCollected(), 0, 10), date("Y-m-d")) != 0) {
    	            continue;
    	        }
    	        if (!array_key_exists($p->getArborId(), $studentList)) {
    	            $studentList[$p->getArborId()] = new Student($p->getArborId());
    	        }
    	        $name = $studentList[$p->getArborId()]->getFirstName() . ' ' . $studentList[$p->getArborId()]->getLastName();
    	        $itemName = $shop->getItemById($p->getItemId())->getName();
    	        $link = '<a href="?uncollect=' . $p->getPurchaseId() . '" class="stretched-link"></a>';
    	        $datetime = $p->getDatetime();
    	        $price = $p->getPrice();
    	        echo <<< EOF
<div class="row bg-secondary">
    <div class="col-sm-3 text-left">$name$link</div>
    <div class="col-sm-3">$itemName$link</div>
    <div class="col-sm-2 text-center">$datetime$link</div>
    <div class="col-sm-1 text-center"><strong>$price</strong>$link</div>
</div>
<hr />
EOF;
    	    }
    	}
    	
    	?>
	</div>
</body>
</html>
