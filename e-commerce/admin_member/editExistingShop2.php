<?php
//header("content-type:image/jpeg");
require 'secureInput.php';

$selectedShopId = $selectedShopName = $selectedShopDes = $selectedCategoryName = $selectedCategoryDes = $selectedProductName = $selectedProductDes = $selectedProductPrice = $selectedProductAmount = $selectedProductImage = "";
$errFlag = 0;

$error_editShopName = $error_editCategoryName = $error_editProductName = "<span style='color: blue;'>Only letters, numbers and space allowed!</span>";
$error_editShopDes = $error_editCategoryDes = $error_editProductDes = "<span style='color: blue;'>Only letters, numbers, comma(,), full-stop(.) and space allowed!</span>";
$error_editProductPrice = $error_editProductAmount = "<span style='color: blue;'>Only numbers and decimal point (.) allowed!</span>";

// RETRIEVE EXISTING INFORMATIONS::
//$existingShopMenu = $existingCategoryMenu = "";
$existingShopNameId = $cat_existingShopNameId = $prod_existingShopNameId = '<option value="">-- select shop --</option>';
$edit_existingCategoryNameId = $prod_existingCategoryNameId = '<option value="">-- select category --</option>';
$edit_existingProductNameId = '<option value="">-- select product --</option>';

$query = "SELECT shop_table.*
		  FROM shop_table, registrationtable
		  WHERE shop_table.member_id=registrationtable.id
		  AND shop_table.approved='1'
		  AND registrationtable.eMail='$user';";
$result = $conn->query($query);

$i = 0;
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()) {
		$i++;
		$shopName = $row['shop_name'];
		$shopId = $row['shop_id'];
		$existingShopNameId .= '<option value="'.$shopId.'">'.$shopName.'</option>';
		$cat_existingShopNameId .= '<option value="'.$shopId.'">'.$shopName.'</option>';
		$prod_existingShopNameId .= '<option value="'.$shopId.'">'.$shopName.'</option>';
	}
}
//************************************************************//
////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////

if($_SERVER["REQUEST_METHOD"]=="POST") {

	//EDIT EXISTING PRODUCT::
	// After shop is selected::
	if (isset($_POST['prod_existingShopNameId'])) {
		# code...
		$prod_selectedShopId = $_POST['prod_existingShopNameId'];
		if ($prod_selectedShopId != "") {
			$_SESSION['edit_prod_selectedShopId'] = $prod_selectedShopId;
		}

		$sql = "SELECT product_category.*
			  	FROM product_category
			  	WHERE product_category.shop_id='$prod_selectedShopId';";
		//echo "sql= ".$sql;
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$prod_categoryId = $row['category_id'];
				$prod_categoryName = $row['category_name'];
				$prod_existingCategoryNameId .= '<option value="'.$prod_categoryId.'">'.$prod_categoryName.'</option>';
			}
		}

		// After category is selected::
		if (isset($_POST['prod_existingCategoryNameId'])) {
			$prod_selectedCategoryId = $_POST['prod_existingCategoryNameId'];
			if ($prod_selectedCategoryId != "") {
				$_SESSION['edit_prod_selectedCategoryId'] = $prod_selectedCategoryId;
			}

			$sql = "SELECT product_table.*
				  	FROM product_table
				  	WHERE product_table.product_category_id='$prod_selectedCategoryId';";
			//echo "sql= ".$sql;
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$productId = $row['product_id'];
					$productName = $row['product_name'];
					$edit_existingProductNameId .= '<option value="'.$productId.'">'.$productName.'</option>';
				}
			}

			// After product is selected ::
			if (isset($_POST['edit_existingProductNameId'])) {
				$selectedProductId = $_POST['edit_existingProductNameId'];
				if ($selectedProductId != "") {
					$_SESSION['edit_selectedProductId'] = $selectedProductId;
				}
				$sql = "SELECT product_table.*
				  		FROM product_table
				  		WHERE product_table.product_id='$selectedProductId';";
				$result = $conn->query($sql);
				if ($result->num_rows > 0) {
					while ($row = $result->fetch_assoc()) {
						$selectedProductName = $row['product_name'];
						$selectedProductPrice = $row['product_price'];
						$selectedProductAmount = $row['product_amount'];
						$selectedProductImage=base64_encode($row['product_image']);
						//$selectedProductImage = $row['product_image'];
						$selectedProductDes = $row['product_description'];
					}
				}
				if ($selectedProductName != "") {
					$_SESSION['selectedProductName'] = $selectedProductName;
				}
				//-------------------------------------------------------//

				// After update button is clicked::
				if (isset($_POST['updateProductInfoBtn']) and $_POST['editProductName']!="") {
					$editProductName = secureInput($_POST['editProductName']);
					$editProductPrice = secureInput($_POST['editProductPrice']);
					$editProductAmount = secureInput($_POST['editProductAmount']);
					$editProductDes = secureInput($_POST['editProductDes']);

					if(isset($_POST['editProductImage'])){
						$editProductImage = addslashes (file_get_contents($_FILES['editProductImage']['tmp_name']));
					}
					else{
						$editProductImage = 'Image Not Available!';
					}

					$selectedProductId = $_SESSION['edit_selectedProductId'];
					$selectedProductName = $_SESSION['selectedProductName'];

					// Sanitize user input::
					if(!preg_match("/^[a-zA-Z0-9 ]*$/", $editProductName)) { //Regular expression comparison
						//If input first name is invalid::
						$error_editProductName = "<span style='color: red;'>Only letters, numbers and space allowed!</span>";
						$errFlag = 1;
					}
					if(!preg_match("/^[0-9.]*$/", $editProductPrice)) { //Regular expression comparison
						//If input first name is invalid::
						$error_editProductPrice = "<span style='color: red;'>Only numbers and decimal point (.) allowed!</span>";
						$errFlag = 1;
					}
					if(!preg_match("/^[0-9]*$/", $editProductAmount)) { //Regular expression comparison
						//If input first name is invalid::
						$error_editProductAmount = "<span style='color: red;'>Only numbers allowed!</span>";
						$errFlag = 1;
					}
					if (!preg_match("/^[a-zA-Z0-9,. ]*$/", $editProductDes)) {
						$error_editProductDes = "<span style='color: red;'>Only letters, numbers, comma(,), full-stop(.) and space allowed!</span>";
						$errFlag = 1;
					}

					// Update database::
					if ($errFlag == 0) {
						$sql = "UPDATE product_table
								SET product_table.product_name=CASE
								WHEN '$editProductName'='' THEN '$selectedProductName'
								ELSE '$editProductName'
								END,
								product_table.product_price=CASE
								WHEN '$editProductPrice'='' THEN '0.00'
								ELSE '$editProductPrice'
								END,
								product_table.product_amount=CASE
								WHEN '$editProductAmount'='' THEN '0'
								ELSE '$editProductAmount'
								END,
								product_table.product_description=CASE
								WHEN '$editProductDes'='' THEN Null
								ELSE '$editProductDes'
								END,
								WHERE product_table.product_id='$selectedProductId';";
						//echo "sql= ".$sql;
						if ($conn->query($sql) === TRUE) {
							echo "Successfully updated product info!";
						}
						else{
							echo "Error updating product info: " . $conn->error;
						}
					}
					else{
						echo "Invalid input!";
					}
				}

				// Delete Product::
				if (isset($_POST['deleteProductBtn'])) {
					$selectedProductId = $_SESSION['edit_selectedProductId'];

					$sql="DELETE FROM product_table
						  WHERE product_table.product_id='$selectedProductId';";

					if ($conn->query($sql) === TRUE) {
					    echo "product deleted successfully!";
					} 
					else {
					    echo "Error deleting product: " . $conn->error;
					}
				}
			}
		}
	}
	//************************************************************//
////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////
	
	// EDIT EXISTING CATEGORY::
	if (isset($_POST['cat_existingShopNameId'])) {
		$cat_selectedShopId = $_POST['cat_existingShopNameId'];
		if ($cat_selectedShopId != "") {
			$_SESSION['edit_cat_selectedShopId'] = $cat_selectedShopId;
		}

		$sql = "SELECT product_category.*
			  	FROM product_category
			  	WHERE product_category.shop_id='$cat_selectedShopId';";
		//echo "sql= ".$sql;
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$categoryId = $row['category_id'];
				$categoryName = $row['category_name'];
				$edit_existingCategoryNameId .= '<option value="'.$categoryId.'">'.$categoryName.'</option>';
			}
		}

		if (isset($_POST['edit_existingCategoryNameId'])) {
			$cat_selectedCategoryId = $_POST['edit_existingCategoryNameId'];
			if ($cat_selectedCategoryId != "") {
				$_SESSION['cat_selectedCategoryId'] = $cat_selectedCategoryId;
			}
			$sql = "SELECT product_category.*
			  		FROM product_category
			  		WHERE product_category.category_id='$cat_selectedCategoryId';";
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$selectedCategoryName = $row['category_name'];
					$selectedCategoryDes = $row['category_description'];
				}
			}
			if ($selectedCategoryName != "") {
				$_SESSION['selectedCategoryName'] = $selectedCategoryName;
			}
			//-------------------------------------------------------//

			// After update button is clicked::
			if (isset($_POST['updateCategoryInfoBtn']) and $_POST['editCategoryName']!="") {
				$editCategoryName = secureInput($_POST['editCategoryName']);
				$editCategoryDes = secureInput($_POST['editCategoryDes']);
				$cat_selectedCategoryId = $_SESSION['cat_selectedCategoryId'];
				$selectedCategoryName = $_SESSION['selectedCategoryName'];

				if(!preg_match("/^[a-zA-Z0-9 ]*$/", $editCategoryName)) { //Regular expression comparison
					//If input first name is invalid::
					$error_editShopName = "<span style='color: red;'>Only letters, numbers and space allowed!</span>";
					$errFlag = 1;
				}
				if (!preg_match("/^[a-zA-Z0-9,. ]*$/", $editCategoryDes)) {
					$error_editShopDes = "<span style='color: red;'>Only letters, numbers, comma(,), full-stop(.) and space allowed!</span>";
					$errFlag = 1;
				}

				// Update database::
				if ($errFlag == 0) {
					$sql = "UPDATE product_category
							SET product_category.category_name=CASE
							WHEN '$editCategoryName'='' THEN '$selectedCategoryName'
							ELSE '$editCategoryName'
							END,
							product_category.category_description=CASE
							WHEN '$editCategoryDes'='' THEN Null
							ELSE '$editCategoryDes'
							END
							WHERE product_category.category_id='$cat_selectedCategoryId';";
					//echo "sql= ".$sql;
					if ($conn->query($sql) === TRUE) {
						echo "Successfully updated shop info!";
					}
					else{
						echo "Error updating info: " . $conn->error;
					}
					
				}
				else{
					echo "Invalid input!";
				}
			}

			// Delete Category::
			if (isset($_POST['deleteCategoryBtn'])) {
				$cat_selectedCategoryId = $_SESSION['cat_selectedCategoryId'];

				$sql="DELETE FROM product_category
					  WHERE product_category.category_id='$cat_selectedCategoryId';
					  DELETE FROM product_table
					  WHERE product_table.product_category_id='$cat_selectedCategoryId';";

				if ($conn->query($sql) === TRUE) {
				    echo "Category deleted successfully!";
				} 
				else {
				    echo "Error deleting category: " . $conn->error;
				}
			}
		}
	}
	//************************************************************//
////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////

	// EDIT EXISTING SHOP::
	if (isset($_POST['existingShopNameId'])) {
		$selectedShopId = $_POST['existingShopNameId'];

		if ($selectedShopId != "") {
			$_SESSION['edit_selectedShopId'] = $selectedShopId;
		}

		$sql = "SELECT shop_table.*
		  		FROM shop_table
		  		WHERE shop_table.shop_id='$selectedShopId'
		  		AND shop_table.approved='1';";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$selectedShopName = $row['shop_name'];
				$selectedShopDes = $row['shop_description'];
			}
		}
		$_SESSION['selectedShopName'] = $selectedShopName;
		//-------------------------------------------------------//

		if (isset($_POST['updateShopInfoBtn']) and $_POST['editShopName']!="") {
			$updatedShopName = secureInput($_POST['editShopName']);
			$updatedShopDes = secureInput($_POST['editShopDes']);
			$selectedShopId = $_SESSION['edit_selectedShopId'];
			$selectedShopName = $_SESSION['selectedShopName'];

			if(!preg_match("/^[a-zA-Z0-9 ]*$/", $updatedShopName)) { //Regular expression comparison
				//If input first name is invalid::
				$error_editShopName = "<span style='color: red;'>Only letters, numbers and space allowed!</span>";
				$errFlag = 1;
			}
			if (!preg_match("/^[a-zA-Z0-9,. ]*$/", $updatedShopDes)) {
				$error_editShopDes = "<span style='color: red;'>Only letters, numbers, comma(,), full-stop(.) and space allowed!</span>";
				$errFlag = 1;
			}

			// Update database::
			if ($errFlag == 0) {
				$sql = "UPDATE shop_table
						SET shop_table.shop_name=CASE
						WHEN '$updatedShopName'='' THEN '$selectedShopName'
						ELSE '$updatedShopName'
						END,
						shop_table.shop_description=CASE
						WHEN '$updatedShopDes'='' THEN Null
						ELSE '$updatedShopDes'
						END
						WHERE shop_table.shop_id='$selectedShopId'
						AND shop_table.approved='1';";
				//echo "sql= ".$sql;
				if ($conn->query($sql) === TRUE) {
					echo "Successfully updated shop info!";
				}
				else{
					echo "Error updating info: " . $conn->error;
				}
				
			}
			else{
				echo "Invalid input!";
			}
		}

		// Delete Category::
		if (isset($_POST['deleteShopBtn'])) {
			$selectedShopId = $_SESSION['edit_selectedShopId'];

			$sql="DELETE FROM product_table
				  WHERE product_table.product_category_id IN (
				  	   SELECT product_category.category_id
					   FROM product_category
					   WHERE product_category.shop_id='$selectedShopId');
				  DELETE FROM product_category
				  WHERE product_category.shop_id='$selectedShopId';
				  DELETE FROM shop_table
				  WHERE shop_table.shop_id='$selectedShopId'
				  AND shop_table.approved='1';";

			if ($conn->query($sql) === TRUE) {
			    echo "Shop deleted successfully!";
			} 
			else {
			    echo "Error deleting shop: " . $conn->error;
			}
		}
	}
	//************************************************************//
////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////

}
?>