<?php
require "../../bin/auth.php";
?>
<html lang="en">

<?php require "../../bin/head.php"; ?>

<body>
	<?php include "../../bin/breadcrumbs.php"; ?>
	<div class="card text-center border-secondary mx-auto my-5" style="width: 22rem;">
    	<div class="card-body">
	  		<img class="mb-4 w-100" src="../../img/logo_v2.jpg" alt="Droitwich Spa High School" />
	  		
        	<h4 class="card-title"><?= $site_name ?> Stock Management area</h4>
        	
        	<p class="card-text">First upload images to the folder.
        		The default images are provided <a href="<?= $site_url; ?>/img/default_stock_images.zip">here</a> in case you delete any by mistake.</p>
        	
        	<p class="card-text">Please then enter the details of each product.</p>
        	
        	<p><a href="stock_images.php" class="btn btn-warning">Image manager</a>
        						 <a href="stockeditor/CkEditor.php" class="btn btn-warning">Stock details editor</a></p>
        	
        	<p class="card-text text-muted">It is often helpful to have both of these open, perhaps in
        		separate windows (Shift+click does this), so that you can keep track of the names of the images</p>

        	<div class="alert alert-danger" role="alert">
  				<strong>Take care!</strong> Do not use double quotes or other special characters in names, descriptions or other fields.
  					Rather than deleting items, it's nearly always better to disable them and put a note into the description box, as it may mess up previous purchases. 
			</div>
    	</div>
    </div>
</body>

</html>