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
		<label>Order Table:</label>
		<?php
			echo $showOrderTable;
			echo "</table>";
		?>
		<br><br>
		<a href="showOrderPage.php"><strong>Refresh</strong></a>
		<br>
		<a href="memberPage.php"><strong><-Back</strong></a>
	</form>
</body>
</html>