<?php
	require 'config.php';
	include 'session.php';
	session_set_cookie_params(time()+$lifetime, $path, $domain, $secure, $httponly);
	session_start();
	//session_regenerate_id(true);
	//$_SESSION['tutorial'] = "";
	$user = "";
	//$areaList = $sub_areaList = $selectedArea = $existingAreas = $selectedSub_Area = $newTutorial = "";
	if(isset($_SESSION['user'])){
		$user = $_SESSION['user'];
		echo "<div style='text-align: right; color: indigo;'><h4>" . $user . "</h4></div>";
		require 'adminPanel2.php';
	}
	else{
		//header("location: ../login(08.09.2017)_session/view/index.php");
		$redirect = '<script>';
		$redirect .= 'window.location.href = "loginPage.php";';
		$redirect .= '</script>';
		echo $redirect;
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Admin Panel</title>
	<link rel="stylesheet" type="text/css" href="">	
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
	<form action=""method="post" >

		<div>
			<?php
				echo $showShopRequestTable;
			?>
			<br>
			<input type="submit" id="button" name="showShopRequestsBtn" value="Show New Shop Requests (<?php echo $totalNewShopReq; ?>)">
		</div><br>

		<div>
			<?php
				echo $showMemberRequestTable;
			?>
			<br>
			<input type="submit" id="button" name="showMemberRequestsBtn" value="Show New Member Requests (<?php echo $totalNewMemberReq; ?>)">
		</div><br>
	
		<div>
			<a href="adminPanel.php"><strong>Refresh</strong></a>
		</div>
		<br>
		<div>
			<a href="authenticatedUser.php"><strong><-Back</strong></a>
		</div>
		<br>
		<footer id="footerId"><a href="logOut.php"><strong>Logout</strong></a></footer> <!--Log-out link-->
	</form>
</body>
</html>