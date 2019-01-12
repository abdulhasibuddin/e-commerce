<?php
	require '../admin_member/config.php';
	include '../admin_member/session.php';
	session_set_cookie_params(time()+$lifetime, $path, $domain, $secure, $httponly);
	session_start();

	$showExistingShops = "";
	$existingShopIdArray = array();

	$sql = "SELECT shop_table.*
			FROM shop_table
			WHERE shop_table.approved='1';";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$shopName = $row['shop_name'];
			$shopDes = $row['shop_description'];
			$shopId = $row['shop_id'];
			$existingShopIdArray[] = $shopId;

			$showExistingShops .= '<button  class="shopBtn" type="submit" name="selectShopBtn';
			$showExistingShops .= $shopId;
			$showExistingShops .= '">';
			$showExistingShops .= '';
			$showExistingShops .= '<div id="row1">';
			$showExistingShops .= '';
			$showExistingShops .= $shopName;
			$showExistingShops .= '</div>';
			$showExistingShops .= '<div id="row2">';
			$showExistingShops .= $shopDes;
			$showExistingShops .= '';
			$showExistingShops .= '</div>';
			$showExistingShops .= '</button>';
		}
	}

	if ($_SERVER['REQUEST_METHOD']=='POST') {
		for ($i=0; $i < count($existingShopIdArray); $i++){
			$selectedShopIdByUser = $existingShopIdArray[$i];
			$selectedShopIdBtnName = 'selectShopBtn'.$selectedShopIdByUser;

			// APPROVE NEW SHOP REQUEST::
			if (isset($_POST[$selectedShopIdBtnName])) {
				$_SESSION['selectedShopIdByUser'] = $selectedShopIdByUser;
				$redirect = '<script>';
				$redirect .= 'window.location.href = "productCategory.php";';
				$redirect .= '</script>';
				echo $redirect;
			}
		}
	}
?>