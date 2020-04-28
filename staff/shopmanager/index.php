<?php
require ('../../bin/auth.php');
?>
<html lang="en">

<?php require ('../../bin/head.php');?>

<body>
	<h1>Welcome to the Stock Management area of <?= $site_name ?>!</h1>
	
	<p>Please upload images to the folder <a href="stock_images.php">here</a>.</p>
	
	<p>Please then enter the details of each product <a href="stockeditor/CkEditor.php">here</a>.</p>
	
	<p>It is often helpful to have both of these open, perhaps in separate windows (Shift+click does this), so that you can keep track of the names of the images</p>
</body>

</html>