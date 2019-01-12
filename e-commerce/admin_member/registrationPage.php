<?php //require 'registrationPage2.php';?>
<!DOCTYPE html>
<html>
<head>
	<title>Registration</title>
	<link rel="stylesheet" type="text/css" href="registrationPage.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body id="bodyId">
	<div id="headerId"><h2>Registration Here</h2></div>
	<!--The following part needs to be loaded before the form section to use the variables declared and assigned in the file-->
	<?php 
		//Declaring necessary variables with certain messages to show the user about the different characteristics that the input password should have::[default color of the messages is blue]
	    $pass_size = "*Password should be atleast 8 characters!";
	    $pass_char_lower = "*Password should contain atleast one lowercase character!";
	    $pass_char_upper = "*Password should contain atleast one uppercase character!";
	    $pass_char_num = "*Password should contain atleast one number!";
	    $pass_char_spe = "*No special character is permitted except (_)!";
	    require 'registrationPage2.php';
	?>
	<!--'POST' method prevents data from exposing on the url bar-->
	<!--Don't use $_SERVER['PHP_SELF'] as it is vulnerable to Cross-Site Scripting(XSS)-->
	<!--If you have to use $_SERVER['PHP_SELF'], then use it as htmlspecialchars($_SERVER['PHP_SELF'])-->
	<form id="form" method="POST" action="" ><!--action="" refers to the self submission of this form-->
		<div id="tableId">
			<div id="td1">Full Name: 
				<div id="td2">
					<input id="inputId" type="text" name="fullName" value="<?php echo $fName; ?>" required autofocus><!--First name input area-->
				</div>
				<div id="error">* <?php echo $fNameErr; ?></div><!--Error messages related to first name-->
			</div><br>
			
			<div id="td1">E-mail:
				<div id="td2">
					<input id="inputId" type="E-mail" name="email" value="<?php echo $eMail; ?>" required><!--Email input area-->
				</div>
				<div id="error">* <?php echo $eMailErr; ?></div><!--Error messages related to email-->
			</div><br>

			<div id="td1">Password:
				<div id="td2">
					<input id="inputId" type="password" name="password" required><!--Password input area-->
				</div>
			</div>

			<div id="td1">
				<div id="td2">
					<span id="pass_message"><?php echo $pass_size; ?></span><br>
	                <span id="pass_message"><?php echo $pass_char_lower; ?></span><br>
	                <span id="pass_message"><?php echo $pass_char_upper; ?></span><br>
	                <span id="pass_message"><?php echo $pass_char_num; ?></span><br>
	                <span id="pass_message"><?php echo $pass_char_spe; ?></span><br>
				</div>
			</div><br>

			<div id="td1">Confirm Password:
				<div id="td2">
					<input id="inputId" type="password" name="conPassword" required><!--Confirmation password input area-->
				</div>
				<div id="error">* <?php echo $conPasswordErr; ?></div><!--Error messages related to confirmation password-->
			</div><br>

			<div id="td1">Captcha: <img src="captcha.php"><!--Captcha image area-->
			</div><br>
			<div id="td1">Enter captcha:
				<div id="td2"><input id="inputId" type="text" name="vercode" required/><!--Captcha code input area-->
				</div>
				<div id="td2"> <span id="error">* <?php echo $captchaErr; ?></span></div><!--Error messages related to captcha code-->
			</div><br>
			<div id="td1">	
				<input id="submitId" type="submit" value="Submit"/><!--Submit button-->					
				<br>
				<div id="td2">
					<h6 style="color: red; font-style: italic;">*Required field</h6>
				</div>
			</div>
		</div>
		<footer id="footerId">Already have an account? Login <a href="loginPage.php"><strong>here</strong></a>
			&nbsp;
			<a href="../public/index.php">Back</a>
		</footer><!--Link to the login page-->
		<br><br><br>
	</form>
</body>
</html>