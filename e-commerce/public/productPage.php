<?php require 'productPage2.php'; ?>
<!DOCTYPE html>
<html>
<head>
	<title>
		<?php echo $titleCategory; ?>
	</title>
	<style type="text/css">
		.productDiv{
			display: inline-block;
			width: 30%;
			margin-left: 2%;
			margin-top: 1%;
			margin-bottom: 1%;

			border-style: ridge;
			border-width: 5px;
		}
		.productBtn{
			width: 100%;
		}
		.productCheckBox{
			float: right;
			zoom: 2;
		}
		.productSelect{
			width: 50%;
		}
		#row1{
			background-color: lightgrey;
			height: 10vh;
			text-align: center;
			font-weight: bolder;
			font-size: x-large;		
		}
		#row2{
			background-color: #e6ffe6;
			height: 5vh;
			padding: 10px;
			font-size: large;
			text-align: left;
		}
		#row3{
			background-color: #e6e6ff;
			height: 5vh;
			padding: 10px;
			font-size: large;
			text-align: left;
		}
		#row4{
			background-color: white;
			height: 15vh;
			padding: 10px;
			font-size: large;
			text-align: left;
		}
		#row5{
			background-color: #e6e6ff;
			height: 5vh;
			padding: 10px;
			font-size: large;
			text-align: left;
		}
		#orderBtnId{
			width: 30%;
			margin-left: 2%;
			padding: 10px;
			font-size: x-large;
			background-color: #b3e6b3;
		}
		#cartBtnId{
			float: right;
			width: 30%;
			margin-right: 2%;
			padding: 10px;
			font-size: x-large;
			background-color: #ffff66;
		}
		#resetBtnId{
			float: right;
			width: 30%;
			margin-right: 3%;
			padding: 10px;
			font-size: x-large;
			background-color: #ffc6b3;
		}
	</style>
</head>
<body>
	<form method="post" action="">
		<?php
			echo $cartMsg;
			echo $showExistingProducts;
		?>
		<br><br>
		<input id="orderBtnId" type="submit" name="orderNowBtn" value="Order Now!">
		<input id="cartBtnId" type="submit" name="addToCartBtn" value="Add to Cart">
		<input id="resetBtnId" type="submit" name="resetOrder" value="Reset Order!">
	</form>
	<br>
	<a href="productPage.php">Refresh</a>
	<br>
	<a href="productCategory.php">Back</a>

</body>
</html>