<?php require 'productCategory2.php'; ?>
<!DOCTYPE html>
<html>
<head>
	<title>
		<?php echo $titleShop; ?>
	</title>
	<style type="text/css">
		.categoryBtn{
			display: inline-block;
			width: 30%;
			margin-left: 2%;
			margin-top: 1%;
			margin-bottom: 1%;

			border-style: ridge;
			border-width: 5px;
		}
		#row1{
			background-color: lightgrey;
			height: 10vh;
			text-align: center;
			font-weight: bolder;
			font-size: x-large;		
		}
		#row2{
			background-color: white;
			height: 15vh;
			padding: 10px;
			font-size: large;
			text-align: left;
		}
	</style>
</head>
<body>
	<form method="post" action="">
		<?php
			echo $showExistingCategories;
		?>
	</form>
	<br>
	<a href="productCategory.php">Refresh</a>
	<br>
	<a href="shop.php">Back</a>
</body>
</html>