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

if (isset($_GET['purchase'])) {
    require "bin/do_purchase.php";
}

?>
<html lang="en">
<head>
	<?php require ('../bin/head.php');?>
</head>

<body>
	<?php if (isset($_GET['masquerade_name'])) { ?>
	<!-- Probably an Administrator, but this link will deny if someone
	     tries just setting ?masquerade= anyway -->
	     <nav aria-label="breadcrumb">
	     	<ol class="breadcrumb">
	     		<li class="breadcrumb-item"><a href="../">Back to staff area</a></li>
	     	</ol>
	     </nav>
	<?php } else echo "<br />"; ?>
<?php if (isset($_GET['successful_purchase'])) {
echo <<<EOF
<div class="modal fade" id="successfulPurchase" tabindex="-1" role="dialog" aria-labelledby="successfulPurchaseLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="successfulPurchaseLabel">Congratulations on your purchase!</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Please find the Shop during its opening times to claim your purchase.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">OK</button>
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function(){
    $('#successfulPurchase').modal('show');
});
</script>
EOF;
} ?>
	<div class="container">
    	<div class="text-center"><img class="mb-4 img-responsive" src="../img/logo_v2.jpg" alt="" height="72" /></div>
    	<h3 class="h3 font-weight-normal mb-4">Welcome to <?= Config::$site_name; ?>, <?= $student->getFirstName(); ?>.</h3>
    	<div id="user-details" class="mb-3">
    		You have <span class="font-weight-bold"><?= $student->getPoints(); ?></span> points to spend.  Please have a look at the products available for you below, and click on them to purchase.
    	</div>
    	<div>
    		<a href="review_purchases.php<?= $masqueraded_username != "" ? "?masquerade_user=$masqueraded_username" : ""; ?>" class="btn btn-primary">Review previous purchases</a>
    	</div>
    	<?php 
        	$shop->studentShop($student->getYearGroup(), $student->getPoints());
        ?>
    	
	</div>
	
</body>

</html>