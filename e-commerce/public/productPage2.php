<?php
	require '../admin_member/config.php';
	include '../admin_member/session.php';
	session_set_cookie_params(time()+$lifetime, $path, $domain, $secure, $httponly);
	session_start();

	$titleCategory = $showExistingProducts = $cartMsg = "";
	//$existingProductIdArray = array();

	if (isset($_SESSION['selectedCategoryIdByUser'])){
		$selectedCategoryIdByUser = $_SESSION['selectedCategoryIdByUser'];

		$sql = "SELECT product_category.category_name, product_table.*
				FROM product_table, product_category, shop_table
				WHERE product_table.product_category_id=product_category.category_id
				AND product_category.shop_id=shop_table.shop_id
				AND shop_table.approved='1'
				AND product_table.product_category_id='1';";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$titleCategory = $row['category_name'];
				$productId = $row['product_id'];
				$productName = $row['product_name'];
				$productPrice = $row['product_price'];
				$productAmount = $row['product_amount'];
				$productDes = $row['product_description'];
				//$existingProductIdArray[] = $productId;

				$showExistingProducts .= '<div class="productDiv">';
				$showExistingProducts .= '<button class="productBtn" type="submit" name="selectProductBtn';
				$showExistingProducts .= $productId;
				$showExistingProducts .= '">';
				$showExistingProducts .= '<input class="productCheckBox" type="checkbox" name="checkedProductId[]" value="';
				$showExistingProducts .= $productId;
				$showExistingProducts .= '" />';

				$showExistingProducts .= '<div id="row1">';
				$showExistingProducts .= '';
				$showExistingProducts .= $productName;
				$showExistingProducts .= '</div>';

				$showExistingProducts .= '<div id="row2">';
				$showExistingProducts .= 'Price: ';
				$showExistingProducts .= $productPrice;
				$showExistingProducts .= '</div>';

				$showExistingProducts .= '<div id="row3">';
				$showExistingProducts .= 'Availability: ';
				$showExistingProducts .= $productAmount;
				$showExistingProducts .= '</div>';

				$showExistingProducts .= '<div id="row4">';
				$showExistingProducts .= $productDes;
				$showExistingProducts .= '';
				$showExistingProducts .= '</div>';
				$showExistingProducts .= '</button>';

				// Select Quantity::
				$showExistingProducts .= '<div id="row5">';
				$showExistingProducts .= 'Qty: ';
				$showExistingProducts .= '<select class="productSelect" name="selectQtyProduct';
				$showExistingProducts .= $productId;
				$showExistingProducts .= '">';
				
				if ($productAmount>0) {
					for ($i=1; $i <= $productAmount; $i++) { 
						$showExistingProducts .= '<option value="';
						$showExistingProducts .= $i;
						$showExistingProducts .= '">';
						$showExistingProducts .= $i;
						$showExistingProducts .= '</option>';
					}
				}
				
				$showExistingProducts .= '';
				$showExistingProducts .= '';
				$showExistingProducts .= '';
				$showExistingProducts .= '';
				$showExistingProducts .= '';
				$showExistingProducts .= '';
				$showExistingProducts .= '</select>';
				$showExistingProducts .= '</div>';
				$showExistingProducts .= '</div>';
			}
		}

		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			# code...

			// RESET ORDER::
			if (isset($_POST['resetOrder']) and isset($_SESSION['checkedProductIdQtyArray'])) {
				unset($_SESSION['checkedProductIdQtyArray']);
			}

			// ADD TO CART::
			if (isset($_POST['addToCartBtn']) and isset($_POST['checkedProductId'])) {
				$checkedProductIdArray = $_POST['checkedProductId'];

				$totalCheckedProductItems = count($checkedProductIdArray);
				if ($totalCheckedProductItems > 0) {
					print_r($checkedProductIdArray);

					$checkedProductIdQtyArray = array();
					for ($i=0; $i < $totalCheckedProductItems; $i++) { 
						$checkedProductId = $checkedProductIdArray[$i];
						$selectedQtyName = 'selectQtyProduct'.$checkedProductId;
						$selectedProductQuantity = $_POST[$selectedQtyName];
						$checkedProductIdQtyArray[$checkedProductId] = $selectedProductQuantity;
						//echo "<br>checkedProductId = ".$checkedProductId."; selectedProductQuantity = ".$selectedProductQuantity;
					}
					print_r($checkedProductIdQtyArray);
					
					$arrayOfCheckedProductIdQtyArray = array();
					if (isset($_SESSION['checkedProductIdQtyArray'])) {
						//$tmpArray = array();
						$tmpArray = $_SESSION['checkedProductIdQtyArray'];

						foreach ($tmpArray as $key1 => $value1) {
							foreach ($value1 as $key => $value) {
								$arrayOfCheckedProductIdQtyArray[][] = $value;
							}
						}
						
						$arrayOfCheckedProductIdQtyArray[][] = $checkedProductIdQtyArray;
						$_SESSION['checkedProductIdQtyArray'] = $arrayOfCheckedProductIdQtyArray;
					}
					else{
						$arrayOfCheckedProductIdQtyArray[][] = $checkedProductIdQtyArray;
						$_SESSION['checkedProductIdQtyArray'] = $arrayOfCheckedProductIdQtyArray;
					}
				}
			}
			elseif (isset($_POST['addToCartBtn'])) {
				$cartMsg = "<label style='color: red;'>Cart is empty!</label><br>";
			}
			////////////////////////////////////////////////////////////
			////////////////////////////////////////////////////////////

			// ORDER::
			if (isset($_POST['orderNowBtn']) and isset($_SESSION['checkedProductIdQtyArray'])) {
				$redirect = '<script>';
				$redirect .= 'window.location.href = "confirmOrder.php";';
				$redirect .= '</script>';
				echo $redirect;
			}
			elseif (isset($_POST['orderNowBtn'])) {
				$cartMsg = "<label style='color: red;'>Please, click on 'Add to Cart' first!</label><br>";
			}
		}
	}
?>