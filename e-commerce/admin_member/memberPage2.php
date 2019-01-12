<?php
require 'secureInput.php'; //This file checks for cross-site scripting(XSS) vulnerability

$error_newShopName = $error_newCategoryName = $error_newProductName = "<span style='color: blue;'>Only letters, numbers and space allowed!</span>";
$error_newShopDes = $error_newCategoryDes = $error_newProductDes = "<span style='color: blue;'>Only letters, numbers, comma(,), full-stop(.) and space allowed!</span>";
$error_newProductPrice = $error_newProductAmount = "<span style='color: blue;'>Only numbers allowed!</span>";
$errFlag = 0; //If any error occures, this flag would be valued 1

// EXISTING PRODUCT CATEGORIES::
$i = 0;
$existingShopMenu = $existingCategoryMenu = "";
$existingShopMenu_2 = '<option value="">-- select shop --</option>';
$query = "SELECT shop_table.*
		  FROM shop_table, registrationtable
		  WHERE shop_table.member_id=registrationtable.id
		  AND shop_table.approved='1'
		  AND registrationtable.eMail='$user';";
$result = $conn->query($query);
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()) {
		$i++;
		$res = $row['shop_name'];
		$existingShopMenu .= '<option value="'.$res.'">'.$res.'</option>';
		$existingShopMenu_2 .= '<option value="'.$res.'">'.$res.'</option>';
	}
}
//************************************************************//

if($_SERVER["REQUEST_METHOD"]=="POST"){
	//  GO TO ORDER PAGE::
	if (isset($_POST['showOrderPage'])) {
		$redirect = '<script>';
		$redirect .= 'window.location.href = "showOrderPage.php";';
		$redirect .= '</script>';
		echo $redirect;
	}

	//  GO TO EDIT PAGE::
	if (isset($_POST['editPage'])) {
		$redirect = '<script>';
		$redirect .= 'window.location.href = "editExistingShop.php";';
		$redirect .= '</script>';
		echo $redirect;
	}

	// ADD NEW SHOP::
	if (isset($_POST['newShopBtn']) and $_POST['newShopName']!="") {
		$newShopName = secureInput($_POST["newShopName"]); //XSS vulnerability checking
		$newShopDes = secureInput($_POST['newShopDes']);

		if(!preg_match("/^[a-zA-Z0-9 ]*$/", $newShopName)) { //Regular expression comparison
			//If input first name is invalid::
			$error_newShopName = "<span style='color: red;'>Only letters, numbers and space allowed!</span>";
			$errFlag = 1;
		}
		if (!preg_match("/^[a-zA-Z0-9,. ]*$/", $newShopDes)) {
			$error_newShopDes = "<span style='color: red;'>Only letters, numbers, comma(,), full-stop(.) and space allowed!</span>";
			$errFlag = 1;
		}

		if($errFlag == 0){
			$sql = "INSERT INTO shop_table(shop_table.shop_name, shop_table.member_id, shop_table.shop_description)
					SELECT '$newShopName', registrationtable.id, 
						CASE
					    WHEN '$newShopDes'='' THEN Null
					    WHEN '$newShopDes'!='' THEN '$newShopDes'
					    END
					FROM registrationtable
					WHERE registrationtable.eMail='$user';";
			if ($conn->query($sql) === TRUE) {
				//echo "Error inserting info: " . $conn->error;
				echo "Successfully created new shop!";
			}
			else{
				echo "Error inserting info: " . $conn->error;
			}
		}
		else{
			echo "Invalid input!";
		}
	}
	//************************************************************//

	// ADD NEW PRODUCT CATEGORY::
	if (isset($_POST['newCategoryBtn']) and $_POST['newCategoryName']!="") {
		$selectedShopName = $_POST['existingShopMenu'];
		$newCategoryName = secureInput($_POST["newCategoryName"]); //XSS vulnerability checking
		$newCategoryDes = secureInput($_POST['newCategoryDes']);

		if(!preg_match("/^[a-zA-Z0-9 ]*$/", $newCategoryName)) { //Regular expression comparison
			//If input first name is invalid::
			$error_newCategoryName = "<span style='color: red;'>Only letters, numbers and space allowed!</span>";
			$errFlag = 1;
		}
		if (!preg_match("/^[a-zA-Z0-9,. ]*$/", $newCategoryDes)) {
			$error_newCategoryDes = "<span style='color: red;'>Only letters, numbers, comma(,), full-stop(.) and space allowed!</span>";
			$errFlag = 1;
		}

		if($errFlag == 0){
			$sql = "INSERT INTO product_category(product_category.category_name, product_category.shop_id, product_category.category_description)
					SELECT '$newCategoryName', shop_table.shop_id, 
						CASE
					    WHEN '$newCategoryDes'='' THEN Null
					    WHEN '$newCategoryDes'!='' THEN '$newCategoryDes'
					    END
					FROM shop_table, registrationtable
                    WHERE shop_table.shop_name='$selectedShopName'
                    AND shop_table.member_id=registrationtable.id
                    AND shop_table.approved='1'
					AND registrationtable.eMail='$user';";
			if ($conn->query($sql) === TRUE) {
				//echo "Error inserting info: " . $conn->error;
				echo "Successfully created new category!";
			}
			else{
				echo "Error inserting info: " . $conn->error;
			}
		}
		else{
			echo "Invalid input!";
		}
	}
	//************************************************************//

	// ADD NEW PRODUCT::
	if (isset($_POST['existingShopMenu_2'])) {
		$selectedShopName_2 = $_POST['existingShopMenu_2'];
		//$_SESSION["shop"] = $selectedShopName_2;
		//selectedShopName_2_static = $selectedShopName_2;
		if ($selectedShopName_2 != "") {
			$_SESSION['existingShopMenu_2'] = $selectedShopName_2;
		}

		$sql = "SELECT product_category.*
				FROM product_category, shop_table
				WHERE product_category.shop_id=shop_table.shop_id
				AND shop_table.shop_name='$selectedShopName_2'
				AND shop_table.approved='1';";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$existingCategoryMenu .= "<option>".$row['category_name']."</option>";
			}
		}
		//-------------------------------------------------------//

		if (isset($_POST['newProductBtn']) and $_POST['newProductName']!="") {
			$selectedShopName_2_session = $_SESSION['existingShopMenu_2'];
			$selectedCategoryName = $_POST['existingCategoryMenu'];
			$newProductName = secureInput($_POST["newProductName"]); //XSS vulnerability checking
			$newProductPrice = secureInput($_POST['newProductPrice']);
			$newProductAmount = secureInput($_POST['newProductAmount']);
			$newProductDes = secureInput($_POST['newProductDes']);
			if(isset($_POST['newProductImage'])){
				$newProductImage = addslashes (file_get_contents($_FILES['newProductImage']['tmp_name']));
			}
			else{
				$newProductImage = 'Image Not Available!';
			}

			if(!preg_match("/^[a-zA-Z0-9 ]*$/", $newProductName)) { //Regular expression comparison
				//If input first name is invalid::
				$error_newCategoryName = "<span style='color: red;'>Only letters, numbers and space allowed!</span>";
				$errFlag = 1;
			}
			if(!preg_match("/^[0-9]*$/", $newProductPrice)) { //Regular expression comparison
				//If input first name is invalid::
				$error_newProductPrice = "<span style='color: blue;'>Only numbers allowed!</span>";;
				$errFlag = 1;
			}
			if(!preg_match("/^[0-9]*$/", $newProductAmount)) { //Regular expression comparison
				//If input first name is invalid::
				$error_newProductAmount = "<span style='color: blue;'>Only numbers allowed!</span>";;
				$errFlag = 1;
			}
			if (!preg_match("/^[a-zA-Z0-9,. ]*$/", $newProductDes)) {
				$error_newCategoryDes = "<span style='color: red;'>Only letters, numbers, comma(,), full-stop(.) and space allowed!</span>";
				$errFlag = 1;
			}

			if($errFlag == 0){
				$sql = "INSERT INTO product_table(product_table.product_name, product_table.product_price,  product_table.product_category_id, product_table.product_amount, product_table.product_description, product_table.product_image)
						SELECT * FROM (SELECT '$newProductName', 
								CASE
                                WHEN '$newProductPrice'='' THEN '0.00'
                                WHEN '$newProductPrice'!='' THEN '$newProductPrice'
                                END,
                                product_category.category_id,
                                CASE
                                WHEN '$newProductAmount'='' THEN '0'
                                WHEN '$newProductAmount'!='' THEN '$newProductAmount'
                                END,
                                CASE
                                WHEN '$newProductDes'='' THEN Null
                                WHEN '$newProductDes'!='' THEN '$newProductDes'
                                END,
                                '$newProductImage'
                            FROM product_category, shop_table, registrationtable
                            WHERE product_category.category_name='$selectedCategoryName'
                            AND product_category.shop_id=shop_table.shop_id
                            AND shop_table.shop_name='$selectedShopName_2_session'
                            AND shop_table.member_id=registrationtable.id
                            AND shop_table.approved='1'
                            AND registrationtable.eMail='$user') AS tmp
                        WHERE NOT EXISTS (
                            SELECT product_table.*
                            FROM product_table, product_category, shop_table, registrationtable
                            WHERE product_table.product_name='$newProductName'
                            AND product_category.category_name='$selectedCategoryName'
                            AND product_category.shop_id=shop_table.shop_id
                            AND shop_table.shop_name='$selectedShopName_2_session'
                            AND shop_table.member_id=registrationtable.id
                            AND shop_table.approved='1'
                            AND registrationtable.eMail='$user'
                        ) LIMIT 1;";
                //echo "sql=".$sql;
				if ($conn->query($sql) === TRUE) {
					//echo "Error inserting info: " . $conn->error;
					echo "Successfully added new product!";
				}
				else{
					echo "Error adding product: " . $conn->error;
				}
			}
			else{
				echo "Invalid input!";
			}
		}

	}
}
?>