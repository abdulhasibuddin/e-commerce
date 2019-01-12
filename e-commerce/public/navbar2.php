<?php
	require '../admin_member/config.php';

	$showExistingShopList = "";

	$sql = "SELECT shop_table.*
			FROM shop_table
			WHERE shop_table.approved='1';";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {

		while ($row = $result->fetch_assoc()) {

		}
	}
?>