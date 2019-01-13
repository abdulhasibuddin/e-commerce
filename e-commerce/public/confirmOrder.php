<?php require 'confirmOrder2.php'; ?>
<!DOCTYPE html>
<html>
<head>
	<title>Confirm Order</title>
	<link rel="stylesheet" type="text/css" href="confirmOrder.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
	<form method="post" action="">
		
		<?php
			echo $quantityExceededMsg;
			echo "<br>";
			echo $showOrderTable;
		?>
		<br><br>
		<div id="tableId">
			<?php echo $errorMsg; ?>
			<br>
			<div id="td1">Full Name: 
				<div id="td2">
					<input id="inputId" type="text" name="fullName" value="<?php echo $fName; ?>" autofocus><!--First name input area-->
				</div>
				<div id="error">* <?php echo $fNameErr; ?></div><!--Error messages related to first name-->
			</div><br>
			
			<div id="td1">E-mail:
				<div id="td2">
					<input id="inputId" type="E-mail" name="email" value="<?php echo $eMail; ?>"><!--Email input area-->
				</div>
				<div id="error">* <?php echo $eMailErr; ?></div><!--Error messages related to email-->
			</div><br>

			<div id="td1">Address: 
				<div id="td2">
					<textarea rows="10" cols="36" name="address">
						<?php echo $address; ?>
					</textarea><!--First name input area-->
				</div>
				<div id="error">* <?php echo $addressErr; ?></div><!--Error messages related to first name-->
			</div><br>

			<div id="td1">Contact: 
				<div id="td2">
					<input id="inputId" type="text" name="contact" value="<?php echo $contact; ?>" ><!--First name input area-->
				</div>
				<div id="error">* <?php echo $contactErr; ?></div><!--Error messages related to first name-->
			</div><br>

			<div id="td1">Captcha: <img src="../admin_member/captcha.php"><!--Captcha image area-->
			</div><br>
			<div id="td1">Enter captcha:
				<div id="td2"><input id="inputId" type="text" name="vercode"/><!--Captcha code input area-->
				</div>
				<div id="td2"> <span id="error">* <?php echo $captchaErr; ?></span></div><!--Error messages related to captcha code-->
			</div>
		</div>
		<br><br>
		<input id="confirmBtnId" type="submit" name="confirmOrderBtn" value="Confirm Order!">
		<input id="resetBtnId" type="submit" name="resetOrder" value="Reset Order!">
	</form>
	<br>
	<a href="confirmOrder.php">Refresh</a>
	<br>
	<a href="productPage.php">Back</a>
</body>
</html>
