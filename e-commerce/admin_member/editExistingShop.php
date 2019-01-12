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
		require 'editExistingShop2.php';
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
	<title>Edit Existing Shop</title>
	<link rel="stylesheet" type="text/css" href="memberPage.css">	
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
	<form method="post" action=""  enctype="multipart/form-data">
		<div id="editProductTable"><h3>Edit Product Info:</h3>
			<div id="row">
				<select id="select" name="prod_existingShopNameId" onchange='if(this.value != 0) { this.form.submit(); }'>
					<?php
						echo $prod_existingShopNameId;
						echo "</select></div>";
					?>
				</select>
			</div>
			<div id="row">
				<select id="select" name="prod_existingCategoryNameId" onchange='if(this.value != 0) { this.form.submit(); }'>
					<?php
						echo $prod_existingCategoryNameId;
						echo "</select></div>";
					?>
				</select>
			</div>
			<div id="row">
				<select id="select" name="edit_existingProductNameId" onchange='if(this.value != 0) { this.form.submit(); }'>
					<?php
						echo $edit_existingProductNameId;
						echo "</select></div>";
					?>
				</select>
			</div><br>
			<div id="row">
				<div id="col1">
					<label>Product Name:</label>
				</div>
				<div id="col2">
					<input id="text" type="text" name="editProductName" placeholder="Edit Shop Name" value="<?php echo $selectedProductName; ?>">
					<br>
					<?php echo $error_editProductName; ?>
				</div>
			</div><br>
			<div id="row">
				<div id="col1">
					<label>Product Price:</label>
				</div>
				<div id="col2">
					<input id="text" type="text" name="editProductPrice" placeholder="Edit Price" value="<?php echo $selectedProductPrice; ?>">
					<br>
					<?php echo $error_editProductPrice; ?>
				</div>
			</div><br>
			<div id="row">
				<div id="col1">
					<label>Product Amount:</label>
				</div>
				<div id="col2">
					<input id="text" type="text" name="editProductAmount" placeholder="Edit Amount" value="<?php echo $selectedProductAmount; ?>">
					<br>
					<?php echo $error_editProductAmount; ?>
				</div>
			</div><br>
			<div id="row">
				<div id="col1">
					<label>Product Description:</label>
				</div>
				<div id="col2">
					<textarea id="text" name="editProductDes" placeholder="Edit Description">
						<?php echo $selectedProductDes; ?>
					</textarea>
					<br>
					<?php echo $error_editProductDes; ?>
				</div>
			</div><br>
			<div id="row">
				<img alt="105x105" class="img-responsive" src="data:image/png;charset=utf8;base64,<?php echo $selectedProductImage ?>"/>
			</div>
			<br>
			<div id="row">
				<div>
					<input id="button" type="submit" name="updateProductInfoBtn" value="Update Product Info">
				</div>
				<div>
					<input id="button" type="submit" name="deleteProductBtn" value="Delete Product">
				</div>
			</div>
			<h4>********************************************</h4>
		</div>
		<!--/////////////////////////////////////////////////////////////////////////////-->


		<div id="editCategoryTable"><h3>Edit Category Info:</h3>
			<div id="row">
				<select id="select" name="cat_existingShopNameId" onchange='if(this.value != 0) { this.form.submit(); }'>
					<?php
						echo $cat_existingShopNameId;
						echo "</select></div>";
					?>
				</select>
			</div>
			<div id="row">
				<select id="select" name="edit_existingCategoryNameId" onchange='if(this.value != 0) { this.form.submit(); }'>
					<?php
						echo $edit_existingCategoryNameId;
						echo "</select></div>";
					?>
				</select>
			</div>
			<div id="row">
				<div id="col1">
					<input id="text" type="text" name="editCategoryName" placeholder="Edit Shop Name" value="<?php echo $selectedCategoryName; ?>">
					<br>
					<?php echo $error_editCategoryName; ?>
				</div>
			</div>
			<div id="row">
				<div id="col1">
					<textarea id="text" name="editCategoryDes" placeholder="Edit Description">
						<?php echo $selectedCategoryDes; ?>
					</textarea>
					<br>
					<?php echo $error_editCategoryDes; ?>
				</div>
			</div>
			<br>
			<div id="row">
				<div>
					<input id="button" type="submit" name="updateCategoryInfoBtn" value="Update Category Info">
				</div>
				<div>
					<input id="button" type="submit" name="deleteCategoryBtn" value="Delete Category">
				</div>
			</div>
			<h4>********************************************</h4>
		</div>
		<!--/////////////////////////////////////////////////////////////////////////////-->


		<div id="editShopTable"><h3>Edit Shop Info:</h3>
			<div id="row">
				<select id="select" name="existingShopNameId" onchange='if(this.value != 0) { this.form.submit(); }'>
					<?php
						echo $existingShopNameId;
						echo "</select></div>";
					?>
				</select>
			</div>
			<div id="row">
				<div id="col1">
					<input id="text" type="text" name="editShopName" placeholder="Edit Shop Name" value="<?php echo $selectedShopName; ?>">
					<br>
					<?php echo $error_editShopName; ?>
				</div>
			</div>
			<div id="row">
				<div id="col1">
					<textarea id="text" name="editShopDes" placeholder="Edit Description">
						<?php echo $selectedShopDes; ?>
					</textarea>
					<br>
					<?php echo $error_editShopDes; ?>
				</div>
			</div><br>
			<br>
			<div id="row">
				<div>
					<input id="button" type="submit" name="updateShopInfoBtn" value="Update Shop Info">
				</div>
				<div>
					<input id="button" type="submit" name="deleteShopBtn" value="Delete Shop">
				</div>
			</div>
			<h4>********************************************</h4>
		</div>
		<br><br>
		<!--/////////////////////////////////////////////////////////////////////////////-->

		<br><br>
		<div>
			<a href="editExistingShop.php"><strong>Refresh</strong></a>
		</div>
		<br>
		<div>
			<a href="memberPage.php"><strong><-Back</strong></a>
		</div>
		<br>
		<footer id="footerId"><a href="logOut.php"><strong>Logout</strong></a></footer> <!--Log-out link-->
		<br><br><br>
	</form>
</body>
</html>