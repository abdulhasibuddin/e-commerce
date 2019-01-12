<?php
	require 'config.php';
	require 'session.php';
	session_set_cookie_params(time()+$lifetime, $path, $domain, $secure, $httponly);
	session_start();

	$orderNoArray = array();

	$showOrderTable = '<table style="width:100%" border="1px" cellpadding="5px">';
	$showOrderTable .= '<tr>';

	$showOrderTable .= '<th>';
	$showOrderTable .= 'Order No.';
	$showOrderTable .= '</th>';

	$showOrderTable .= '<th>';
	$showOrderTable .= 'Order Statistics';
	$showOrderTable .= '</th>';

	$showOrderTable .= '<th>';
	$showOrderTable .= 'Total Payment';
	$showOrderTable .= '</th>';

	$showOrderTable .= '<th>';
	$showOrderTable .= 'Customer Name';
	$showOrderTable .= '</th>';

	$showOrderTable .= '<th>';
	$showOrderTable .= 'Email';
	$showOrderTable .= '</th>';

	$showOrderTable .= '<th>';
	$showOrderTable .= 'Address';
	$showOrderTable .= '</th>';

	$showOrderTable .= '<th>';
	$showOrderTable .= 'Contact';
	$showOrderTable .= '</th>';

	$showOrderTable .= '<th>';
	$showOrderTable .= 'Order Placement Time';
	$showOrderTable .= '</th>';

	$showOrderTable .= '<th>';
	$showOrderTable .= 'Action';
	$showOrderTable .= '</th>';

	$showOrderTable .= '</tr>';


	$sql = "SELECT order_table.*
			FROM order_table
			WHERE order_table.orderDeliveryComplete='0';";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$orderNo = $row['order_no'];
			$orderNoArray[] = $orderNo;

			$showOrderTable .= '<tr>';
			$showOrderTable .= '<td>';
			$showOrderTable .= $orderNo;
			$showOrderTable .= '</td>';

			$serialized_orderedProductIdQtyArray = $row['serialized_orderedProductIdQtyArray'];
			$arrayFromString_orderedProductIdQty = unserialize($serialized_orderedProductIdQtyArray);
			$totalPayment = 0;

			$showOrderTable .= '<td>';
			$showOrderTable .= '<table style="width:100%" border="1px">';
			$showOrderTable .= '<tr>';
			$showOrderTable .= '<th>Product Id</th>';
			$showOrderTable .= '<th>Qty</th>';
			$showOrderTable .= '</tr>';			
			foreach ($arrayFromString_orderedProductIdQty as $prodId => $prodQty) {
				$sql_prodIdQty = "SELECT product_table.product_price
								  FROM product_table
								  WHERE product_table.product_id='$prodId';";
				$prodPriceObject = $conn->query($sql_prodIdQty);
				$prodPrice = $prodPriceObject->fetch_assoc();
				$totalPayment += $prodQty*$prodPrice['product_price'];

				$showOrderTable .= '<tr>';
				$showOrderTable .= '<td>';
				$showOrderTable .= $prodId;
				$showOrderTable .= '</td>';
				$showOrderTable .= '<td>';
				$showOrderTable .= $prodQty;
				$showOrderTable .= '</td>';
				$showOrderTable .= '</tr>';
			}			
			$showOrderTable .= '</table>';
			$showOrderTable .= '</td>';

			$showOrderTable .= '<td>';
			$showOrderTable .= $totalPayment;
			$showOrderTable .= '</td>';

			$showOrderTable .= '<td>';
			$showOrderTable .= $row['customer_fullName'];
			$showOrderTable .= '</td>';

			$showOrderTable .= '<td>';
			$showOrderTable .= $row['customer_eMail'];
			$showOrderTable .= '</td>';

			$showOrderTable .= '<td>';
			$showOrderTable .= $row['customer_address'];
			$showOrderTable .= '</td>';

			$showOrderTable .= '<td>';
			$showOrderTable .= $row['customer_contact'];
			$showOrderTable .= '</td>';

			$showOrderTable .= '<td>';
			$showOrderTable .= $row['orderTime'];
			$showOrderTable .= '</td>';

			$showOrderTable .= '<td>';
			$showOrderTable .= '<input style="background-color: #00cc00; width: 100%; height: 100%; font-weight: bold;" type="submit" name="orderDoneBtn';
			$showOrderTable .= $orderNo;
			$showOrderTable .= '" value="Done">';
			$showOrderTable .= '</td>';

			$showOrderTable .= '</tr>';

			$showOrderTable .= '<tr>';
			$showOrderTable .= '<td style="background-color: grey;"></td>';
			$showOrderTable .= '<td style="background-color: grey;"></td>';
			$showOrderTable .= '<td style="background-color: grey;"></td>';
			$showOrderTable .= '<td style="background-color: grey;"></td>';
			$showOrderTable .= '<td style="background-color: grey;"></td>';
			$showOrderTable .= '<td style="background-color: grey;"></td>';
			$showOrderTable .= '<td style="background-color: grey;"></td>';
			$showOrderTable .= '<td style="background-color: grey;"></td>';
			$showOrderTable .= '<td style="background-color: grey;"></td>';
			$showOrderTable .= '</tr>';
		}
	}

	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		for ($i=0; $i < count($orderNoArray); $i++) { 
			$orderNo = $orderNoArray[$i];
			$orderBtnName = 'orderDoneBtn'.$orderNo;

			if (isset($_POST[$orderBtnName])) {
				$sql = "UPDATE order_table
						SET order_table.orderDeliveryComplete='1'
						WHERE order_table.order_no='$orderNo';";
				if ($conn->query($sql) === TRUE) {
					echo "<div style='color: green'>Information updated successfully!</div>";

					$redirect = '<script>';
					$redirect .= 'window.location.href = "showOrderPage.php";';
					$redirect .= '</script>';
					echo $redirect;
				}
				else{
					echo "<div style='color: red'>Error updating info!</div>";
				}
			}
		}
	}
?>