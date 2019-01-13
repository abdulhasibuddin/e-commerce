<?php
	require '../admin_member/config.php';
	require '../admin_member/session.php';
	require '../admin_member/secureInput.php';
	session_set_cookie_params(time()+$lifetime, $path, $domain, $secure, $httponly);
	session_start();

	$fName = $eMail = $address = $contact = "";
	$fNameErr = "<span style='color: black;'>Only letters and space allowed!</span>";
	$eMailErr = "<span style='color: black;'>example: mail@email.com</span>";
	$addressErr = "<span style='color: black;'>Only letters, numbers, space and (,.;/-) allowed!</span>";
	$contactErr = "<span style='color: black;'>Only numbers allowed!</span>";
	$captchaErr = "<span style='color: black;'>Enter captcha given in the above image.</span>";
	$errFlag = 0; //If any error occures, this flag would be valued 1

	$selectedProductIdArray = array();
	$showOrderTable = $quantityExceededMsg = $errorMsg = "";
	$grandTotal = 0;

	if (isset($_SESSION['checkedProductIdQtyArray'])) {
		$showOrderTable .= '<table style="width:100%" border="1px" cellpadding="5px">';
		$showOrderTable .= '<tr>';

		$showOrderTable .= '<th>';
		$showOrderTable .= 'Product Id';
		$showOrderTable .= '</th>';

		$showOrderTable .= '<th>';
		$showOrderTable .= 'Name';
		$showOrderTable .= '</th>';

		$showOrderTable .= '<th>';
		$showOrderTable .= 'Price';
		$showOrderTable .= '</th>';

		$showOrderTable .= '<th>';
		$showOrderTable .= 'Quantity';
		$showOrderTable .= '</th>';

		$showOrderTable .= '<th>';
		$showOrderTable .= 'Total Amount to be Paid';
		$showOrderTable .= '</th>';

		$showOrderTable .= '<th>';
		$showOrderTable .= 'Action';
		$showOrderTable .= '</th>';

		$showOrderTable .= '</tr>';

		$checkedProductIdQtyArray = $_SESSION['checkedProductIdQtyArray'];
		//print_r($checkedProductIdQtyArray);
		foreach ($checkedProductIdQtyArray as $key => $value) {
			foreach ($value as $key2 => $value2) {
				foreach ($value2 as $prodId => $prodQty) {
					$deleteOrderedProd = 'deleteOrderedProdBtn'.$prodId;
					if (isset($_POST[$deleteOrderedProd])) {
						unset($checkedProductIdQtyArray[$key][$key2][$prodId]);
						$_SESSION['checkedProductIdQtyArray'] = $checkedProductIdQtyArray;
					}
					elseif (array_key_exists($prodId, $selectedProductIdArray)) {
						$selectedProductIdArray[$prodId] += $prodQty;
					}
					else{
						$selectedProductIdArray[$prodId] = $prodQty;
					}
				}
			}
		}
		////////////////////////////////////////////////////

		$orderedProductIdQtyArray = array();
		foreach ($selectedProductIdArray as $prodId => $prodQty) {
			//echo "<br>prodId = ";
			//print_r($prodId);
			//echo "; prodQty = ";
			//print_r($prodQty);

			$sql = "SELECT product_table.*
					FROM product_table
					WHERE product_table.product_id='$prodId';";
			$result = $conn->query($sql);

			while ($row = $result->fetch_assoc()) {
				if ($prodQty > $row['product_amount']) {
					$quantityExceededMsg .= "<label style='color: red;'>Quantity exceeded for Product Id ".$prodId." !</label><br>";
					$showOrderTable .= '<tr>';
					$showOrderTable .= '<td style="color: red;">';
					$showOrderTable .= $prodId;
					$showOrderTable .= '</td>';

					$showOrderTable .= '<td style="color: red;">';
					$showOrderTable .= $row['product_name'];
					$showOrderTable .= '</td>';

					$showOrderTable .= '<td style="color: red;">';
					$showOrderTable .= $row['product_price'];
					$showOrderTable .= '</td>';

					$showOrderTable .= '<td style="color: red;">';
					$showOrderTable .= $prodQty;
					$showOrderTable .= '</td>';

					$showOrderTable .= '<td style="color: red;">';
					$showOrderTable .= 'Quantity Exceeded!<br>[will be excluded from order list!]';
					$showOrderTable .= '</td>';
					$showOrderTable .= '</tr>';
					continue;
				}

				$orderedProductIdQtyArray[$prodId] = $prodQty;
				$total = $row['product_price']*$prodQty;
				$grandTotal += $total;

				$showOrderTable .= '<tr>';
				$showOrderTable .= '<td>';
				$showOrderTable .= $prodId;
				$showOrderTable .= '</td>';

				$showOrderTable .= '<td>';
				$showOrderTable .= $row['product_name'];
				$showOrderTable .= '</td>';

				$showOrderTable .= '<td>';
				$showOrderTable .= $row['product_price'];
				$showOrderTable .= '</td>';

				$showOrderTable .= '<td>';
				$showOrderTable .= $prodQty;
				$showOrderTable .= '</td>';

				$showOrderTable .= '<td>';
				$showOrderTable .= $total;
				$showOrderTable .= '</td>';

				$showOrderTable .= '<td>';
				$showOrderTable .= '<input style="background-color: #ff9999; width: 100%; height: 5vh; font-weight: bold;" type="submit" name="deleteOrderedProdBtn';
				$showOrderTable .= $prodId;
				$showOrderTable .= '" value="Delete!">';
				$showOrderTable .= '</td>';

				$showOrderTable .= '';
				$showOrderTable .= '';

				$showOrderTable .= '</tr>';
			}
		}
		$showOrderTable .= '<tr>';
		$showOrderTable .= '<td style="background-color: grey;"></td>';
		$showOrderTable .= '<td style="background-color: grey;"></td>';
		$showOrderTable .= '<td style="background-color: grey;"></td>';
		$showOrderTable .= '<td style="background-color: grey;"></td>';
		$showOrderTable .= '<td style="background-color: grey;"></td>';
		$showOrderTable .= '<td style="background-color: grey;"></td>';
		$showOrderTable .= '</tr>';

		$showOrderTable .= '<tr>';
		$showOrderTable .= '<td style="color: #0000ff; font: italic bold 24px/30px Georgia, serif">';
		$showOrderTable .= 'Grand Total:';
		$showOrderTable .= '</td>';
		$showOrderTable .= '<td></td>';
		$showOrderTable .= '<td></td>';
		$showOrderTable .= '<td></td>';
		$showOrderTable .= '<td style="color: #0000ff; font: italic bold 24px/30px Georgia, serif">';
		$showOrderTable .= $grandTotal;
		$showOrderTable .= '</td>';
		$showOrderTable .= '</tr>';
		$showOrderTable .= '</table>';

		//print_r($orderedProductIdQtyArray);
		$_SESSION['orderedProductIdQtyArray'] = $orderedProductIdQtyArray;
	}
	///////////////////////////////////////////////////
	///////////////////////////////////////////////////

	if ($_SERVER['REQUEST_METHOD'] == "POST"){
		// RESET ORDER::
		if (isset($_POST['resetOrder'])) {
			if (isset($_SESSION['checkedProductIdQtyArray'])) {
				unset($_SESSION['checkedProductIdQtyArray']);
			}
			$redirect = '<script>';
			$redirect .= 'window.location.href = "productPage.php";';
			$redirect .= '</script>';
			echo $redirect;
		}

		// CONFIRM ORDER::
		if (isset($_POST['confirmOrderBtn']) and isset($_SESSION['orderedProductIdQtyArray'])) {

			$fName = secureInput($_POST["fullName"]); //XSS vulnerability checking of input first name
			if(!preg_match("/^[a-zA-Z ]*$/", $fName)) { //Regular expression comparison
				//If input first name is invalid::
				$fNameErr = "<span style='color: red;'>Only letters and space allowed!</span>"; 
				$errFlag = 1;
			}

			$eMail = secureInput($_POST["email"]); //XSS vulnerability checking of input email
			if(!filter_var($eMail, FILTER_VALIDATE_EMAIL)) { //Validating email using PHP's own function
				//If input email format is not valid::
				$eMailErr = "<span style='color: red;'>Invalid email format!</span>"; 
				$errFlag = 1;
			}

			$address = secureInput($_POST["address"]); //XSS vulnerability checking of input first name
			if(!preg_match("/^[a-zA-Z0-9,.;\-\/ ]*$/", $address)) { //Regular expression comparison
				//If input first name is invalid::
				$addressErr = "<span style='color: red;'>Only letters, numbers, space and (,.;/-) allowed!bbb</span>"; 
				$errFlag = 1;
			}

			$contact = secureInput($_POST["contact"]); //XSS vulnerability checking of input first name
			if(!preg_match("/^[0-9]*$/", $contact)) { //Regular expression comparison
				//If input first name is invalid::
				$contactErr = "<span style='color: red;'>Only numbers allowed!</span>"; 
				$errFlag = 1;
			}

			if (isset($_SESSION["vercode"])) {
				if($_POST["vercode"] != $_SESSION["vercode"] OR $_SESSION["vercode"] == ""){
					//If the captcha input is not correct or if the session 'vercode' does not contain any captcha::
					$captchaErr =  "<span style='color: red;'>Incorrect captcha code!</span>";
					$errFlag = 1;
				}
			}

			if (($fName == "" || $eMail == "" || $address == "" || $contact == "") || $errFlag == 1) { 
				$errorMsg = "<div style='color: red;'>*All fields must be filled!</div>";
			} //Check for if any required field is empty or there is any error...
			elseif ($grandTotal == 0) {
				$errorMsg = "<div style='color: red;'>*No product to deliver!</div>";
			}
			else{ //If everything is ok...
				$orderedProductIdQtyArray = $_SESSION['orderedProductIdQtyArray'];

				// UPDATE PRODUCT QUANTITY::
				try {
					//$conn->query("START TRANSACTION");
					$conn->begin_transaction();

					foreach ($orderedProductIdQtyArray as $prodId => $prodQty) {
						$sql = "UPDATE product_table
								SET product_table.product_amount=
								(SELECT product_table.product_amount
								WHERE product_table.product_id='$prodId')-'$prodQty'
								WHERE product_table.product_id='$prodId';";
						$conn->query($sql);
					}

					$conn->commit();					
				} 
				catch (Exception $e) {
					$conn->rollback();
				}
				

				// INSERT ORDER INTO TABLE::
				$stringFromArray_orderedProductIdQty = serialize($orderedProductIdQtyArray);
				$sql = "INSERT INTO order_table (
							order_table.serialized_orderedProductIdQtyArray,
						    order_table.customer_fullName, 
						    order_table.customer_eMail, 
						    order_table.customer_address, 
						    order_table.customer_contact
						)
						VALUES(
							'$stringFromArray_orderedProductIdQty',
						    '$fName',
						    '$eMail',
						    '$address',
						    '$contact'
						);";
				if ($conn->query($sql) === TRUE) {
					echo "<div style='color: green;'>Your order is in the queue.<br>Thanks for shopping with us!</div>";
					unset($_SESSION['checkedProductIdQtyArray']);

					$redirect = '<script>';
					$redirect .= 'window.location.href = "index.php";';
					$redirect .= '</script>';
					echo $redirect;
				}
				else{
					echo "<div style='color: red;'>Something is wrong!<br>Sorry for this inconvenience!</div>";
				}
				//echo "<br>sql= ".$sql."<br>";
				//$arrayFromString_orderedProductIdQty = unserialize($stringFromArray_orderedProductIdQty);
				//print_r($arrayFromString_orderedProductIdQty);
				//echo "<br>Unserialized = ".$arrayFromString_orderedProductIdQty;
			}
		}
	}
?>
