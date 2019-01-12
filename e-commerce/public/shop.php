<?php require 'shop2.php'; ?>

<!DOCTYPE html>
<html>
<head>
	<title>Shopping Zone</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<style type="text/css">
		.shopBtn{
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
		<!-- <button  class="shopBtn" type="submit" name="selectShopBtn">
			<div id="row1">
				name
			</div>
			<div id="row2">
				des
			</div>
		</button> -->

		<?php
			echo $showExistingShops;
		?>
	</form>
	<a href="shop.php">Refresh</a>
	<br>
	<a href="index.php">Back</a>
</body>
</html>