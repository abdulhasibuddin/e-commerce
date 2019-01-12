<?php
	require '../admin_member/config.php';
	include '../admin_member/session.php';
	session_set_cookie_params(time()+$lifetime, $path, $domain, $secure, $httponly);
	session_start();

	$titleShop = $showExistingCategories = "";
	$existingCategoryIdArray = array();

	if (isset($_SESSION['selectedShopIdByUser'])) {
		$selectedShopIdByUser = $_SESSION['selectedShopIdByUser'];

		$sql = "SELECT shop_table.shop_name, product_category.*
				FROM shop_table, product_category
				WHERE shop_table.shop_id=product_category.shop_id
				AND shop_table.approved='1'
				AND shop_table.shop_id='$selectedShopIdByUser';";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$titleShop = $row['shop_name'];
				$categoryId = $row['category_id'];
				$categoryName = $row['category_name'];
				$categoryDes = $row['category_description'];
				$existingCategoryIdArray[] = $categoryId;

				$showExistingCategories .= '<button  class="categoryBtn" type="submit" name="selectCategoryBtn';
				$showExistingCategories .= $categoryId;
				$showExistingCategories .= '">';
				$showExistingCategories .= '';
				$showExistingCategories .= '<div id="row1">';
				$showExistingCategories .= '';
				$showExistingCategories .= $categoryName;
				$showExistingCategories .= '</div>';
				$showExistingCategories .= '<div id="row2">';
				$showExistingCategories .= $categoryDes;
				$showExistingCategories .= '';
				$showExistingCategories .= '</div>';
				$showExistingCategories .= '</button>';
			}
		}

		for ($i=0; $i < count($existingCategoryIdArray); $i++){
			$selectedCategoryIdByUser = $existingCategoryIdArray[$i];
			$selectedSCategorydBtnName = 'selectCategoryBtn'.$selectedCategoryIdByUser;

			// APPROVE NEW SHOP REQUEST::
			if (isset($_POST[$selectedSCategorydBtnName])) {
				$_SESSION['selectedCategoryIdByUser'] = $selectedCategoryIdByUser;
				$redirect = '<script>';
				$redirect .= 'window.location.href = "productPage.php";';
				$redirect .= '</script>';
				echo $redirect;
			}
		}
	}
?>