<?php

header('Content-Type: application/json');
session_start();

if (!isset($_SESSION["user_name"])) { 
    header("Location: login.php"); 
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)) {
    include 'connect.php';

    // Petty Cash
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
    $createdAt = date('Y-m-d H:i:s');
    $userName = $_SESSION["user_name"] ?? '';

    $str_sql_dep = "SELECT * FROM department_tb WHERE dep_id = ?";
    $stmt_dep = mysqli_prepare($obj_con, $str_sql_dep);
    mysqli_stmt_bind_param($stmt_dep, "s", $departmentId);
    mysqli_stmt_execute($stmt_dep);
    $obj_rs_dep = mysqli_stmt_get_result($stmt_dep);
    
    if (mysqli_num_rows($obj_rs_dep) === 0) {
        echo json_encode(["status" => "error", "message" => "ไม่พบข้อมูลแผนก"]);
        return;
    }
    
    $obj_row_dep = mysqli_fetch_assoc($obj_rs_dep);
    mysqli_stmt_close($stmt_dep);

    $year = substr(date("Y") + 543, -2);
    $month = date("m");
    $Pcode = $obj_row_dep["dep_name"];

    $sql_pcash = "SELECT MAX(pcash_no) as last_id FROM pettycash_tb WHERE pcash_dep_id = ? AND pcash_type = ? AND soft_delete = '0'";
    $stmt_pcash = mysqli_prepare($obj_con, $sql_pcash);
  
	mysqli_stmt_bind_param($stmt_pcash, "ss", $pCashDepId, $pCashType);
    mysqli_stmt_execute($stmt_pcash);
    $obj_rs_pcash = mysqli_stmt_get_result($stmt_pcash);
    $obj_row_pcash = mysqli_fetch_array($obj_rs_pcash);
    mysqli_stmt_close($stmt_pcash);
    $maxPId = isset($obj_row_pcash['last_id']) ? substr($obj_row_pcash['last_id'], -3) : null;

    
    $prefix = $pCashType == '2' ? "Adv-" : "Cash-";
    if (is_null($maxPId) || $maxPId === "") {
        $maxPId = "001";
    } else {
        $maxPId = (int)$maxPId + 1;
        $maxPId = str_pad($maxPId, 3, "0", STR_PAD_LEFT);
    }

    $pCashNo = $prefix . $Pcode . "-" . $year . $month . $maxPId;
 
    $sql_pettycash = "INSERT INTO pettycash_tb 
        (pcash_no, pcash_type, pcash_paya_id, pcash_comp_id, pcash_dep_id, pcash_date, pcash_fee, pcash_vat, pcash_vat_diff, pcash_tax, 
        pcash_tax_diff, pcash_total, pcash_total_diff, pcash_net_amount, created_at, user_created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_pettycash = mysqli_prepare($obj_con, $sql_pettycash);
    mysqli_stmt_bind_param($stmt_pettycash, "ssssssssssssssss", 
        $pCashNo, 
        $pCashType, 
        $pCashPayaId, 
        $pCashCompId, 
        $pCashDepId, 
        $pCashDate, 
        $pCashFee, 
        $pCashVat, 
        $pCashVatDiff, 
        $pCashTax, 
        $pCashTaxDiff, 
        $pCashTotal, 
        $pCashTotalDiff, 
        $pCashNetAmount, 
        $createdAt, 
        $userName
    );

    if (!mysqli_stmt_execute($stmt_pettycash)) {
        echo json_encode(["status" => "error", "message" => "Failed to insert petty cash: " . mysqli_error($obj_con)]);
        mysqli_stmt_close($stmt_pettycash);
        return;
    }
    
    $lastPcashId = mysqli_insert_id($obj_con);
    mysqli_stmt_close($stmt_pettycash);


    if ($lastPcashId) {
        $sql_list = "INSERT INTO pettycash_list_tb 
            (
            pcl_pcash_id, 
            pcl_tax_no,
            pcl_tax_date, 
            pcl_tax_month, 
            pcl_description, 
            pcl_tax_refund, 
            pcl_fee, 
            pcl_vat_percent, 
            pcl_vat,
            pcl_vat_diff,
            pcl_tax_percent,
            pcl_tax, 
            pcl_tax_diff, 
            pcl_total, 
            pcl_total_diff, 
            pcl_net_amount
            ) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_list = mysqli_prepare($obj_con, $sql_list);

        foreach ($_POST['pclDescriptions'] as $index => $description) {
            $pclTaxNo = $_POST['pclTaxNos'][$index] ?? null;
            $pclTaxDate = $_POST['pclTaxDates'][$index] ?? '';
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
            $pclTotalDiff = $_POST['pclTotalDiffs'][$index] ?? '';
            $pclNetAmount = $_POST['pclNetAmounts'][$index] ?? '';

            if (empty($pclTaxDate) || !strtotime($pclTaxDate)) {
                $pclTaxDate = null; 
            } else {
                $pclTaxDate = date('Y-m-d', strtotime($pclTaxDate));
            }

            mysqli_stmt_bind_param($stmt_list, "ssssssssssssssss", 
                $lastPcashId,
                $pclTaxNo,
                $pclTaxDate,
                $pclTaxMonth,
                $description, 
                $pclTaxRefund,
                $pclFee,
                $pclVatPercent, 
                $pclVat,
                $pclVatDiff,
                $pclTaxPercent,
                $pclTax,
                $pclTaxDiff,
                $pclTotal, 
                $pclTotalDiff,
                $pclNetAmount
            );

            if (!mysqli_stmt_execute($stmt_list)) {
                echo json_encode(["status" => "error", "message" => "Failed to insert list: " . mysqli_error($obj_con)]);
                mysqli_stmt_close($stmt_list);
                return;
            }
        }
        mysqli_stmt_close($stmt_list);

        
		$keyMessage = $pCashType == 1 ? "จ่ายเงินสดย่อย" : "ทดลองจ่าย";

	
		echo json_encode([
			"status" => "success",
			"message" => "บันทึกใบ" . $keyMessage . "เรียบร้อยแล้ว",
			"pCashNo" => $pCashNo
		]);
    } else {
        echo json_encode(["status" => "error", "message" => "ไม่พบข้อมูล โปรดลองใหม่อีกครั้งภายหลัง"]);
    }

} else {
    echo json_encode(["status" => "error", "message" => "Invalid POST request"]);
}
?>
