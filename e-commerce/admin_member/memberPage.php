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
		require 'memberPage2.php';
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
	<title>Members' Zone</title>
	<link rel="stylesheet" type="text/css" href="memberPage.css">	
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- <link rel="stylesheet" type="text/css" href="../css/navbar.css"> -->
</head>
<body>
	<form method="post" action="" enctype="multipart/form-data">
		<div id="showOrderTable">
			<div id="row">
				<div id="col1">
					<!--<button id="button" onclick="newShop()">Add New Shop</button>-->
					<input id="button" type="submit" name="showOrderPage" value="Show Orders ->">
				</div>
			</div>
		</div>

		<div id="selectOptionTable">
			<div id="row">
				<div id="col1">
					<!--<button id="button" onclick="newShop()">Add New Shop</button>-->
					<input id="button" type="submit" name="editPage" value="Edit Existing Shop ->">
				</div>
			</div>
		</div>

		<div id="newShopTable"><h3>Add New Shop</h3>
			<div id="row">
				<div id="col1">
					<input id="text" type="text" name="newShopName" placeholder="Shop Name">
					<br>
					<?php echo $error_newShopName; ?>
				</div>
			</div>
			<br>
			<div id="row">
				<div id="col1">
					<textarea id="text" name="newShopDes" placeholder="Short Description"></textarea>
					<br>
					<?php echo $error_newShopDes; ?>
				</div>
			</div>
			<br>
			<div id="row">
				<div>
					<input id="button" type="submit" name="newShopBtn" value="Add New Shop">
				</div>
			</div>
			<h4>********************************************</h4>
		</div>

		<div id="newCategoryTable"><h3>Add New Product Category</h3>
			<div id="row">
				<select id="select" name="existingShopMenu">
					<?php
						echo $existingShopMenu;
						echo "</select></div>";
					?>
				</select>
			</div>
			<div id="row">
				<div id="col1">
					<input id="text" type="text" name="newCategoryName" placeholder="Category Name">
					<br>
					<?php echo $error_newCategoryName; ?>
				</div>
			</div>
			<div id="row">
				<div id="col1">
					<textarea id="text" name="newCategoryDes" placeholder="Short Description"></textarea>
					<br>
					<?php echo $error_newCategoryDes; ?>
				</div>
			</div>
			<br>
			<div id="row">
				<div>
					<input id="button" type="submit" name="newCategoryBtn" value="Add New Category">
				</div>
			</div>
			<h4>********************************************</h4>
		</div>

		<div id="newCategoryTable"><h3>Add New Product</h3>
			<div id="row">
				<select id="select" name="existingShopMenu_2" onchange='if(this.value != 0) { this.form.submit(); }'>
					<?php
						echo $existingShopMenu_2;
						echo "</select></div>";
					?>
				</select>
			</div>
			<div id="row">
				<select id="select" name="existingCategoryMenu">
					<?php
						echo $existingCategoryMenu;
						echo "</select></div>";
					?>
				</select>
			</div>
			<div id="row">
				<div id="col1">
					<input id="text" type="text" name="newProductName" placeholder="Product Name">
					<br>
					<?php echo $error_newProductName; ?>
				</div>
			</div>
			<div id="row">
				<div id="col1">
					<input id="text" type="text" name="newProductPrice" placeholder="Product Price">
					<br>
					<?php echo $error_newProductPrice; ?>
				</div>
			</div>
			<div id="row">
				<div id="col1">
					<input id="text" type="text" name="newProductAmount" placeholder="Product Amount">
					<br>
					<?php echo $error_newProductAmount; ?>
				</div>
			</div>
			<div id="row">
				<div id="col1">
					<textarea id="text" name="newProductDes" placeholder="Short Description"></textarea>
					<br>
					<?php echo $error_newProductDes; ?>
				</div>
			</div>
			<br>
			<div id="row">
				<div id="col1">
					<input type="file" name="newProductImage">
				</div>
			</div>
			<br>
			<div id="row">
				<div>
					<input id="button" type="submit" name="newProductBtn" value="Add New Category">
				</div>
			</div>
			<h4>********************************************</h4>
		</div>

		<div>
			<a href="memberPage.php"><strong>Refresh</strong></a>
		</div>
		<br>
		<div>
			<a href="authenticatedUser.php"><strong><-Back</strong></a>
		</div>
		<br>
		<footer id="footerId"><a href="logOut.php"><strong>Logout</strong></a></footer> <!--Log-out link-->
		<br><br><br>
	</form>
	<script type="text/javascript">
		function newShop() {
		  var x = document.getElementById("newShopTable");
		  if (x.style.display === "none") {
		    x.style.display = "block";
		  } else {
		    x.style.display = "none";
		  }
		}
	</script>
</body>
</html>