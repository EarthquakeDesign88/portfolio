<?php
	
	header('Content-Type: application/json');
	session_start();
	if (!$_SESSION["user_name"]) { 

		Header("Location: login.php"); 

	} 

	if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)) {

		include 'connect.php';

		$pCashId = $_POST["pCashId"] ?? '';
		$pCashNo = $_POST["pCashNo"] ?? '';
		$pCashCompId = $_POST["pCashCompId"] ?? '';
		$pCashDepId = $_POST["pCashDepId"] ?? '';
		$pCashDate = $_POST["pCashDate"] ?? '';
		$pCashPayaId = $_POST["pCashPayaId"] ?? '';
		$pCashType = $_POST["pCashType"] ?? '';
		$pCashFee = $_POST["pCashFee"] ?? '';
		$pCashVat = $_POST["pCashVat"] ?? '';
		$pCashVatDiff = $_POST["pCashVatDiff"] ?? '';
		$pCashTax = $_POST["pCashTax"] ?? '';
		$pCashTaxDiff = $_POST["pCashTaxDiff"] ?? '';
		$pCashTotal = $_POST["pCashTotal"] ?? '';
		$pCashTotalDiff = $_POST["pCashTotalDiff"] ?? '';
		$pCashNetAmount = $_POST["pCashNetAmount"] ?? '';
		$departmentId = $pCashDepId;
		$dateTime = date('Y-m-d H:i:s');
		$userName = $_SESSION["user_name"] ?? '';

		$sql_pettycash = "UPDATE pettycash_tb SET 
			pcash_type = ?, 
			pcash_paya_id = ?, 
			pcash_comp_id = ?, 
			pcash_dep_id = ?, 
			pcash_date = ?, 
			pcash_fee = ?, 
			pcash_vat = ?, 
			pcash_vat_diff = ?, 
			pcash_tax = ?, 
			pcash_tax_diff = ?, 
			pcash_total = ?, 
			pcash_total_diff = ?, 
			pcash_net_amount = ?, 
			updated_at = ?, 
			user_updated_at = ? 
		WHERE pcash_id = ?";

		$stmt_pettycash = mysqli_prepare($obj_con, $sql_pettycash);
		mysqli_stmt_bind_param($stmt_pettycash, "ssssssssssssssss", 
			$pCashType, $pCashPayaId, $pCashCompId, $pCashDepId, $pCashDate, 
			$pCashFee, $pCashVat, $pCashVatDiff, $pCashTax, $pCashTaxDiff, 
			$pCashTotal, $pCashTotalDiff, $pCashNetAmount, $dateTime, $userName, 
			$pCashId
		);

		if (!mysqli_stmt_execute($stmt_pettycash)) {
			echo json_encode(["status" => "error", "message" => "Failed to update petty cash: " . mysqli_error($obj_con)]);
			return;
		}

		
		$existingListIds = [];
		$query = "SELECT pcl_id FROM pettycash_list_tb WHERE pcl_pcash_id = ?";
		$stmt = mysqli_prepare($obj_con, $query);
		mysqli_stmt_bind_param($stmt, "s", $pCashId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while ($row = mysqli_fetch_assoc($result)) {
			$existingListIds[] = $row['pcl_id'];
		}


		$newListIds = [];
		foreach ($_POST['pclDescriptions'] as $index => $description) {
			$pclTaxNo = $_POST['pclTaxNos'][$index] ?? null;
			$pclTaxDate = $_POST['pclTaxDates'][$index] ?? null;
			if (empty($pclTaxDate) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $pclTaxDate)) {
				$pclTaxDate = null; // Set to NULL if empty or invalid
			}
            $pclTaxMonth = $_POST['pclTaxMonths'][$index] ?? null;
            $pclTaxRefund = $_POST['pclTaxRefunds'][$index] ?? '';
            $pclFee = $_POST['pclFees'][$index] ?? '';
            $pclVatPercent = $_POST['pclVatPercents'][$index] ?? '';
            $pclVat = $_POST['pclVats'][$index] ?? '';
            $pclVatDiff = $_POST['pclVatDiffs'][$index] ?? '';
            $pclTaxPercent = $_POST['pclTaxPercents'][$index] ?? '';
            $pclTax = $_POST['pclTaxs'][$index] ?? '';
            $pclTaxDiff = $_POST['pclTaxDiffs'][$index] ?? '';
            $pclTotal = $_POST['pclTotals'][$index] ?? '';
            $pclTotalDiff = $_POST['pclTotalDiffs'][$index] ?? '0.00'; 
            $pclNetAmount = $_POST['pclNetAmounts'][$index] ?? '';

			
			if (isset($_POST['pcl_ids'][$index]) && in_array($_POST['pcl_ids'][$index], $existingListIds)) {
				$listId = $_POST['pcl_ids'][$index];

				$sql_list_update = "UPDATE pettycash_list_tb SET 
					pcl_tax_no = ?, 
					pcl_tax_date = ?, 
					pcl_tax_month = ?, 
					pcl_description = ?, 
					pcl_tax_refund = ?, 
					pcl_fee = ?, 
					pcl_vat_percent = ?,
					pcl_vat = ?,
					pcl_vat_diff = ?, 
					pcl_tax_percent = ?,
					pcl_tax = ?,
					pcl_tax_diff = ?, 
					pcl_total = ?, 
					pcl_total_diff = ?, 
					pcl_net_amount = ?
					WHERE pcl_id = ?";

				    $stmt_list_update = mysqli_prepare($obj_con, $sql_list_update);
					mysqli_stmt_bind_param($stmt_list_update, "ssssssssssssssss", 
						$pclTaxNo, $pclTaxDate, $pclTaxMonth, $description, $pclTaxRefund, 
						$pclFee, $pclVatPercent, $pclVat, $pclVatDiff, 
						$pclTaxPercent, $pclTax, $pclTaxDiff, $pclTotal, 
						$pclTotalDiff, $pclNetAmount, $listId
					);
					mysqli_stmt_execute($stmt_list_update);
					$newListIds[] = $listId;
			} else {
				$sql_list_insert = "INSERT INTO pettycash_list_tb 
					(pcl_pcash_id, pcl_tax_no, pcl_tax_date, pcl_tax_month, pcl_description, 
					pcl_tax_refund, pcl_fee, pcl_vat_percent, pcl_vat, pcl_vat_diff, pcl_tax_percent, 
					pcl_tax, pcl_tax_diff, pcl_total, pcl_total_diff, pcl_net_amount) 
					VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
					
				$stmt_list_insert = mysqli_prepare($obj_con, $sql_list_insert);
				mysqli_stmt_bind_param($stmt_list_insert, "ssssssssssssssss", 
					$pCashId, $pclTaxNo, $pclTaxDate, $pclTaxMonth, $description, $pclTaxRefund, 
					$pclFee, $pclVatPercent, $pclVat, $pclVatDiff, $pclTaxPercent, 
					$pclTax, $pclTaxDiff, $pclTotal, $pclTotalDiff, $pclNetAmount
				);
				mysqli_stmt_execute($stmt_list_insert);
				$newListIds[] = mysqli_insert_id($obj_con);
			}
		}

	
		$listIdsToDelete = array_diff($existingListIds, $newListIds);
		if (!empty($listIdsToDelete)) {
			$placeholders = implode(',', array_fill(0, count($listIdsToDelete), '?'));
			$sql_delete = "DELETE FROM pettycash_list_tb WHERE pcl_id IN ($placeholders)";
			$stmt_delete = mysqli_prepare($obj_con, $sql_delete);
			mysqli_stmt_bind_param($stmt_delete, str_repeat('i', count($listIdsToDelete)), ...$listIdsToDelete);
			mysqli_stmt_execute($stmt_delete);
		}

		$keyMessage = $pCashType == 1 ? "จ่ายเงินสดย่อย" : "ทดลองจ่าย";

		echo json_encode([
			"status" => "success",
			"message" => "บันทึกใบ" . $keyMessage . "เรียบร้อยแล้ว",
			"pCashNo" => $pCashNo
		]);
	}


?>