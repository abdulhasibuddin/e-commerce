<?php
	require 'config.php';

	$showShopRequestTable = $showMemberRequestTable = "";
	$requestedShopIdArray = array();
	$requestedMemberIdArray = array();
	$totalNewShopReq = $totalNewMemberReq = 0;

	// GET NEW SHOP REQUESTS::
	$sql = "SELECT shop_table.*, registrationtable.eMail
			FROM shop_table, registrationtable
			WHERE shop_table.approved='0'
			AND registrationtable.id=shop_table.member_id;";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$totalNewShopReq += 1;
			$shop_id = $row['shop_id'];
			$requestedShopIdArray[] = $shop_id;
		}
	}

	// GET NEW MEMBER REQUESTS::
	$sql = "SELECT registrationtable.*
			FROM registrationtable
			WHERE registrationtable.approved='0'
            AND registrationtable.status='1';";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$totalNewMemberReq += 1;
			$id = $row['id'];
			$requestedMemberIdArray[] = $id;
		}
	}
	//////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////

	if($_SERVER["REQUEST_METHOD"]=="POST"){
		if (isset($_POST['showShopRequestsBtn'])) {
			$sql = "SELECT shop_table.*, registrationtable.eMail
					FROM shop_table, registrationtable
					WHERE shop_table.approved='0'
					AND registrationtable.id=shop_table.member_id;";
			$result = $conn->query($sql);

			$showShopRequestTable .= '<table style="width:100%" border="1px" cellpadding="5px">';
			$showShopRequestTable .= '<th>Shop Id</th>';
			$showShopRequestTable .= '<th>Membership Id</th>';
			$showShopRequestTable .= '<th>Email</th>';
			$showShopRequestTable .= '<th>Shop Name</th>';
			$showShopRequestTable .= '<th>Shop Description</th>';
			$showShopRequestTable .= '<th>Added Time</th>';
			$showShopRequestTable .= '<th>Approve/Reject</th>';

			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
			        $shop_id = $row['shop_id'];
			        $member_id = $row['member_id'];
			        $eMail = $row['eMail'];
			        $shop_name = $row['shop_name'];
			        $shop_description = $row['shop_description'];
			        $added_time = $row['added_time'];

			        $showShopRequestTable .= '<tr>';
					$showShopRequestTable .= '<td>';
					$showShopRequestTable .= $shop_id;
					$showShopRequestTable .= '</td>';
					$showShopRequestTable .= '<td>';
					$showShopRequestTable .= $member_id;
					$showShopRequestTable .= '</td>';
					$showShopRequestTable .= '<td>';
					$showShopRequestTable .= $eMail;
					$showShopRequestTable .= '</td>';
					$showShopRequestTable .= '<td>';
					$showShopRequestTable .= $shop_name;
					$showShopRequestTable .= '</td>';
					$showShopRequestTable .= '<td>';
					$showShopRequestTable .= $shop_description;
					$showShopRequestTable .= '</td>';
					$showShopRequestTable .= '<td>';
					$showShopRequestTable .= $added_time;
					$showShopRequestTable .= '</td>';
					$showShopRequestTable .= '<td>';
					$showShopRequestTable .= '<div>';
					$showShopRequestTable .= '<input style="color: green;" type="submit" name="';
					$showShopRequestTable .= 'approveNewShop'.$shop_id;
					$showShopRequestTable .= '" value="Approve">';
					$showShopRequestTable .= '<span style="float: right;">';
					$showShopRequestTable .= '<input style="color: red;" type="submit" name="';
					$showShopRequestTable .= 'rejectNewShop'.$shop_id;
					$showShopRequestTable .= '" value="Reject">';
					$showShopRequestTable .= '</span>';
					$showShopRequestTable .= '</div>';
					$showShopRequestTable .= '</td>';
					$showShopRequestTable .= '</tr>';
			    }
			}
			$showShopRequestTable .= '</table>';
		}

		// APPROVE/REJECT NEW SHOP REQUESTS::
		for ($i=0; $i < count($requestedShopIdArray); $i++) { 
			$newShopRequestId=$requestedShopIdArray[$i];
			$approveNewShop = 'approveNewShop'.$newShopRequestId;
			$rejectNewShop = 'rejectNewShop'.$newShopRequestId;

			// APPROVE NEW SHOP REQUEST::
			if (isset($_POST[$approveNewShop])) {
				$sql = "UPDATE shop_table
						SET shop_table.approved='1'
						WHERE shop_table.shop_id='$newShopRequestId';";					

				if ($conn->query($sql) === TRUE) {
					echo "Successfully approved new shop!";
				}
				else{
					echo "Error updating info: " . $conn->error;
				}
			}

			// REJECT NEW SHOP REQUEST::
			if (isset($_POST[$rejectNewShop])) {
				$sql = "UPDATE shop_table
						SET shop_table.approved='-1'
						WHERE shop_table.shop_id='$newShopRequestId';";					

				if ($conn->query($sql) === TRUE) {
					echo "Rejected new shop request!";
				}
				else{
					echo "Error updating info: " . $conn->error;
				}
			}
		}
		////////////////////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////

		// SHOW NEW MEMBER REQUESTS::
		if (isset($_POST['showMemberRequestsBtn'])) {
			$sql = "SELECT registrationtable.*
					FROM registrationtable
					WHERE registrationtable.approved='0'
		            AND registrationtable.status='1';";
			$result = $conn->query($sql);

			$showMemberRequestTable .= '<table style="width:100%" border="1px" cellpadding="5px">';
			$showMemberRequestTable .= '<th>Member Id</th>';
			$showMemberRequestTable .= '<th>Full Name</th>';
			$showMemberRequestTable .= '<th>Email</th>';
			$showMemberRequestTable .= '<th>Registration Date/Time</th>';
			$showMemberRequestTable .= '<th>Approve/Reject</th>';

			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
			        $id = $row['id'];
			        $fullName = $row['fullName'];
			        $eMail = $row['eMail'];
			        $reg_date = $row['reg_date'];

			        $showMemberRequestTable .= '<tr>';
					$showMemberRequestTable .= '<td>';
					$showMemberRequestTable .= $id;
					$showMemberRequestTable .= '</td>';
					$showMemberRequestTable .= '<td>';
					$showMemberRequestTable .= $fullName;
					$showMemberRequestTable .= '</td>';
					$showMemberRequestTable .= '<td>';
					$showMemberRequestTable .= $eMail;
					$showMemberRequestTable .= '</td>';
					$showMemberRequestTable .= '<td>';
					$showMemberRequestTable .= $reg_date;
					$showMemberRequestTable .= '</td>';
					$showMemberRequestTable .= '<td>';
					$showMemberRequestTable .= '<div>';
					$showMemberRequestTable .= '<input style="color: green;" type="submit" name="';
					$showMemberRequestTable .= 'approveNewMember'.$id;
					$showMemberRequestTable .= '" value="Approve">';
					$showMemberRequestTable .= '<span style="float: right;">';
					$showMemberRequestTable .= '<input style="color: red;" type="submit" name="';
					$showMemberRequestTable .= 'rejectNewMember'.$id;
					$showMemberRequestTable .= '" value="Reject">';
					$showMemberRequestTable .= '</span>';
					$showMemberRequestTable .= '</div>';
					$showMemberRequestTable .= '</td>';
					$showMemberRequestTable .= '</tr>';
			    }
			}
			$showMemberRequestTable .= '</table>';
		}

		// APPROVE/REJECT NEW MEMBER REQUESTS::
		for ($i=0; $i < count($requestedMemberIdArray); $i++) { 
			$newMemberRequestId=$requestedMemberIdArray[$i];
			$approveNewMember = 'approveNewMember'.$newMemberRequestId;
			$rejectNewMember = 'rejectNewMember'.$newMemberRequestId;

			// APPROVE NEW MEMBER REQUEST::
			if (isset($_POST[$approveNewMember])) {
				$sql = "UPDATE registrationtable
						SET registrationtable.approved='1'
						WHERE registrationtable.id='$newMemberRequestId';";					

				if ($conn->query($sql) === TRUE) {
					echo "Successfully approved new member!";
				}
				else{
					echo "Error updating info: " . $conn->error;
				}
			}

			// REJECT NEW MEMBER REQUEST::
			if (isset($_POST[$rejectNewMember])) {
				$sql = "UPDATE registrationtable
						SET registrationtable.approved='-1'
						WHERE registrationtable.id='$newMemberRequestId';";					

				if ($conn->query($sql) === TRUE) {
					echo "Rejected new member request!";
				}
				else{
					echo "Error updating info: " . $conn->error;
				}
			}
		}
	}
?>