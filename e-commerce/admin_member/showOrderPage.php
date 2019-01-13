<?php require 'showOrderPage2.php'; ?>
<!DOCTYPE html>
<html>
<head>
	<title>Show Orders</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style type="text/css">
		td{
			text-align: center;
		}
	</style>
</head>
<body>
	<form method="post" action="">
		<?php
			echo $table_label;
			echo $errMsg;

			echo $showOrderTable;
			echo "</table>";

			echo "<br><span style='float: right;'>Total Bill = ".$totalBill."</span>";
		?>
		<br><br>
		<label>Show previous orders for </label>
		<input type="number" name="prevOrderCount">
		<label> days</label>
		<input type="submit" name="prevOrderCountBtn" value="Show">
		<br><br>
		<a href="showOrderPage.php"><strong>Refresh</strong></a>
		<br>
		<a href="memberPage.php"><strong><-Back</strong></a>
	</form>
</body>
</html>
