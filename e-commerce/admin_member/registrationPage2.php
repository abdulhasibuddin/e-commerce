<?php
	//After submitting the form of 'registrationPage.php', submitted 'name' properies are processed in this file::
	session_start(); //Start session
	require 'secureInput.php'; //This file checks for cross-site scripting(XSS) vulnerability
//---------------------------------------------------------------------------
	

	$fName = $eMail = $password = $conPasswrd = "";
	$fNameErr = "<span style='color: white;'>Only letters and space allowed!</span>";
	$eMailErr = "<span style='color: white;'>example: mail@email.com</span>";
	$conPasswordErr = "<span style='color: white;'>Password and Confirm Password must match.</span>";
	$captchaErr = "<span style='color: white;'>Enter captcha given in the above image.</span>";
	$errFlag = 0; //If any error occures, this flag would be valued 1
//---------------------------------------------------------------------------


	if($_SERVER["REQUEST_METHOD"]=="POST"){ //Checking if the form was submitted as POST

		$fName = secureInput($_POST["fullName"]); //XSS vulnerability checking of input first name
		if(!preg_match("/^[a-zA-Z ]*$/", $fName)) { //Regular expression comparison
			//If input first name is invalid::
			$fNameErr = "<span style='color: red;'>Only letters and space allowed!</span>"; 
			$errFlag = 1;
		}

	//-----------------------------------------------------------------------
		$eMail = secureInput($_POST["email"]); //XSS vulnerability checking of input email
		if(!filter_var($eMail, FILTER_VALIDATE_EMAIL)) { //Validating email using PHP's own function
			//If input email format is not valid::
			$eMailErr = "<span style='color: red;'>Invalid email format!</span>"; 
			$errFlag = 1;
		}	

		//-------------------------------------------------------------------
		require 'checkExistingAccount.php'; //This file checks if the account [corresponding to the email] extsts or not
		foreach($result as $value){ //Traverse columns of the selected row
			if(count(array_filter($value)) > 0){ //If account exists...[count(array_filter($value)) gives the column numbers of the selected row. The row is empty if there is no column i.e. the account does not exist]
				$eMailErr = "<span style='color: red;'>Account already exists!</span>"; //notify the user...
				$errFlag = 1; //set error flag high
			}
		}
        //-------------------------------------------------------------------
		/*require 'checkUniqueUserName.php'; //This file checks if the username input by the user already exists or not
		foreach($result as $value){ //Traverse columns of the selected row
			if(count(array_filter($value)) > 0){ //If username already exists...
				$stdIdErr = "Student id already taken!"; //notify user...
				$errFlag = 1; //set error flag high
			}
		}*/
		

	//-----------------------------------------------------------------------
		$password = secureInput($_POST["password"]); //XSS vulnerability checking of input password
		require 'passwordValidation.php'; //This file validates the input password format
		

	//-----------------------------------------------------------------------
		$conPasswrd = secureInput($_POST["conPassword"]); //XSS vulnerability checking of input confirm password
		if(!preg_match("/^[a-zA-Z0-9_]*$/", $conPasswrd)) { //Regular expression comparison
			//If input confirmation password doesn't match with input password::
			$conPasswordErr = "<span style='color: red;'>Password doesn't match! Invalid input!</span>";
			$errFlag = 1;
		}
		//-------------------------------------------------------------------

		//Password hashing/encryption::
		if($password == $conPasswrd){ //If password & confirmation password are same...
			$password = password_hash($password, PASSWORD_DEFAULT); //hash the input password[default bcrypt]
		}
		else{ 
			$conPasswordErr = "<span style='color: red;'>Password doesn't match!</span>";
			$errFlag = 1; 
		} //If password & confirmation password don't match, set error flag high


	//-----------------------------------------------------------------------
		if (isset($_SESSION["vercode"])) {
			if($_POST["vercode"] != $_SESSION["vercode"] OR $_SESSION["vercode"] == ""){
				//If the captcha input is not correct or if the session 'vercode' does not contain any captcha::
				$captchaErr =  "<span style='color: red;'>Incorrect captcha code!</span>";
				$errFlag = 1;
			}
		}
		
	}
//---------------------------------------------------------------------------


	if (($fName == "" || $eMail == "" || $password == "" || $conPasswrd == "") || $errFlag == 1) { } //Check for if any required field is empty or there is any error...
	else{ //If everything is ok...
		
		$conPasswrd = ""; //Setting the unencrypted value of '$conPasswrd' to empty for security reason
		require 'registrationDatabase.php'; //If everything is ok, take the input values to registration database
	}
?>
