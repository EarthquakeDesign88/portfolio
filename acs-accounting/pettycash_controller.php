<?php
    session_start();

    header('Content-Type: application/json');

    if (!isset($_SESSION["user_name"])) { 
        header("Location: login.php"); 
        exit();
    }

    include 'connect.php';


    //Validate 
    $action = $_POST['action'] ?? '';
    $pCashCompId = $_POST['pCashCompId'] ?? '';
    $pCashDepId = $_POST['pCashDepId'] ?? '';
    $pCashId = $_POST['pCashId'] ?? '';
    $pCashNo = $_POST['pCashNo'] ?? '';
    $pCashType = $_POST['pCashType'] ?? '';
    $userName = $_SESSION['user_name'] ?? '';
    $filter = $_POST['filter'] ?? '';


    if($action == 'fetchContent') {
        $searchCriteria = !empty($_POST['searchCriteria']) ? $_POST['searchCriteria'] : '';
        $searchTerm = !empty($_POST['searchTerm']) ? $_POST['searchTerm'] : '';
        $orderBy = !empty($_POST['orderBy']) ? $_POST['orderBy'] : 'pcash_id';
        $orderDirection = !empty($_POST['orderDirection']) ? $_POST['orderDirection'] : 'DESC';
        $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
        $itemsPerPage = isset($_POST['itemsPerPage']) ? (int)$_POST['itemsPerPage'] : 10;

        $offset = ($page - 1) * $itemsPerPage;

        $sql_pcash = "SELECT 
            pc.pcash_id,
            pc.pcash_no,
            GROUP_CONCAT(pcl.pcl_description SEPARATOR ', ') AS list_items,
            pc.pcash_fee,
            pc.pcash_vat,
            pc.pcash_tax,
            pc.pcash_total,
            pc.pcash_total_diff, 
            pc.pcash_net_amount,
            c.comp_name,
            d.dep_name,
            py.paya_name
        FROM pettycash_tb AS pc
        INNER JOIN pettycash_list_tb AS pcl ON pc.pcash_id = pcl.pcl_pcash_id 
        LEFT JOIN company_tb AS c ON pc.pcash_comp_id = c.comp_id 
        LEFT JOIN department_tb AS d ON pc.pcash_dep_id = d.dep_id 
        LEFT JOIN payable_tb AS py ON pc.pcash_paya_id = py.paya_id 
        WHERE pc.pcash_comp_id = '$pCashCompId' 
        AND pc.pcash_dep_id = '$pCashDepId'
        AND pc.soft_delete = '0' AND pc.pcash_type = '$pCashType'";


        $sql_count = "SELECT COUNT(*) AS totalItems
        FROM pettycash_tb AS pc
        WHERE pc.pcash_comp_id = '$pCashCompId' 
        AND pc.pcash_dep_id = '$pCashDepId'
        AND pc.soft_delete = '0' AND pc.pcash_type = '$pCashType'";


        if($filter == 'noInvoice') {
            $sql_pcash .= " AND pc.pcash_inv_id IS NULL ";
            $sql_count .= " AND pc.pcash_inv_id IS NULL ";
        }

        if (!empty($searchCriteria) && !empty($searchTerm)) {
            $searchTerm = mysqli_real_escape_string($obj_con, $searchTerm);
            $sql_pcash .= "AND $searchCriteria LIKE '%$searchTerm%' ";
            $sql_count .= "AND $searchCriteria LIKE '%$searchTerm%' ";
        }

        $sql_pcash .= "GROUP BY pc.pcash_id ";
        $sql_pcash .= "ORDER BY $orderBy $orderDirection ";
        $sql_pcash .= "LIMIT $offset, $itemsPerPage";
      

        $result = mysqli_query($obj_con, $sql_pcash);

        if (!$result) {
            echo json_encode(["status" => "error", "message" => mysqli_error($obj_con)]);
            exit();
        }

        $data = [];
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = [
                    'pCashId' => $row['pcash_id'],
                    'pCashNo' => $row['pcash_no'],
                    'pCashPayaName' => $row['paya_name'],
                    'pCashCompId' => $pCashCompId,
                    'pCashDepId' => $pCashDepId,
                    'listItems' => $row['list_items'],
                    'pCashFee' => number_format($row['pcash_fee'], 2),
                    'pCashVat' => number_format($row['pcash_vat'], 2),
                    'pCashTax' => number_format($row['pcash_tax'], 2),
                    'pCashTotal' => number_format($row['pcash_total'], 2),
                    'pCashTotalDiff' => number_format($row['pcash_total_diff'], 2),
                    'pCashNetAmount' => number_format($row['pcash_net_amount'], 2),
                ];
            }
        }


        $result_count = mysqli_query($obj_con, $sql_count);

        if (!$result_count) {
            echo json_encode(["status" => "error", "message" => mysqli_error($obj_con)]);
            exit();
        }

        $totalItems = 0;
        if ($result_count) {
            $row_count = mysqli_fetch_assoc($result_count);
            $totalItems = $row_count['totalItems'];
        }


        echo json_encode([
            "status" => "success",
             "data" => $data,
             "totalItems" => $totalItems
        ]);


        mysqli_close($obj_con);
    }
    else if($action == 'deleteData') {
        if (empty($pCashId) && empty($pCashNo)) {
            echo json_encode(["status" => "error", "message" => "ไม่พบรหัสใบจ่ายเงินสดย่อยนี้"]);
            exit();
        }
   
        $sql_pcash = "UPDATE pettycash_tb SET soft_delete = '1', deleted_at = ?, user_deleted_at = ? WHERE pcash_id = ?";
        $stmt_pcash = mysqli_prepare($obj_con, $sql_pcash);

        if ($stmt_pcash) {
            $deleted_at = date('Y-m-d H:i:s');

            mysqli_stmt_bind_param($stmt_pcash, "sss", $deleted_at, $userName, $pCashId);

            // Execute the statement
            if (mysqli_stmt_execute($stmt_pcash)) {
                echo json_encode([
                    "status" => "success", 
                    "message" => "ลบใบจ่ายเงินสดย่อยเรียบร้อยแล้ว",
                    "pCashNo" => $pCashNo
                ]);
            } else {
                echo json_encode(["status" => "error", "message" => "ไม่สามารถลบข้อมูลได้ โปรดลองใหม่อีกครั้ง"]);
            }

            mysqli_stmt_close($stmt_pcash);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to prepare statement"]);
        }

        exit();
	
    }
    else if($action == 'fetchInDetails') {
        if (empty($pCashId)) {
            echo json_encode(["status" => "error", "message" => "ไม่พบรหัสใบจ่ายเงินสดย่อยนี้"]);
            exit();
        }
    
        $sql_pcash = "SELECT
            pc.*,
            GROUP_CONCAT(pcl.pcl_id SEPARATOR ', ') AS pcl_ids,
            GROUP_CONCAT(pcl.pcl_tax_no SEPARATOR ', ') AS pcl_tax_nos,
            GROUP_CONCAT(pcl.pcl_tax_date SEPARATOR ', ') AS pcl_tax_dates,
			GROUP_CONCAT(pcl.pcl_tax_month SEPARATOR ', ') AS pcl_tax_months,
            GROUP_CONCAT(pcl.pcl_tax_refund SEPARATOR ', ') AS pcl_tax_refunds,
            GROUP_CONCAT(pcl.pcl_description SEPARATOR ', ') AS pcl_descriptions,
            GROUP_CONCAT(pcl.pcl_tax_refund SEPARATOR ', ') AS pcl_tax_refunds,
            GROUP_CONCAT(pcl.pcl_fee SEPARATOR ', ') AS pcl_fees,
            GROUP_CONCAT(pcl.pcl_vat_percent SEPARATOR ', ') AS pcl_vat_percents,
            GROUP_CONCAT(pcl.pcl_vat SEPARATOR ', ') AS pcl_vats,
			GROUP_CONCAT(pcl.pcl_vat_diff SEPARATOR ', ') AS pcl_vat_diffs,
            GROUP_CONCAT(pcl.pcl_tax_percent SEPARATOR ', ') AS pcl_tax_percents,
            GROUP_CONCAT(pcl.pcl_tax SEPARATOR ', ') AS pcl_taxs,
			GROUP_CONCAT(pcl.pcl_tax_diff SEPARATOR ', ') AS pcl_tax_diffs,
            GROUP_CONCAT(pcl.pcl_total SEPARATOR ', ') AS pcl_totals,
            GROUP_CONCAT(pcl.pcl_total_diff SEPARATOR ', ') AS pcl_total_diffs,
            GROUP_CONCAT(pcl.pcl_net_amount SEPARATOR ', ') AS pcl_net_amounts,
            c.comp_name,
            d.dep_name,
            py.paya_name
        FROM pettycash_tb AS pc
        INNER JOIN pettycash_list_tb AS pcl ON pc.pcash_id = pcl.pcl_pcash_id 
        LEFT JOIN company_tb AS c ON pc.pcash_comp_id = c.comp_id 
        LEFT JOIN department_tb AS d ON pc.pcash_dep_id = d.dep_id 
        LEFT JOIN payable_tb AS py ON pc.pcash_paya_id = py.paya_id 
        WHERE pc.pcash_id = '$pCashId' 
        AND pc.soft_delete = '0'
        GROUP BY pc.pcash_id";
    
        $result = mysqli_query($obj_con, $sql_pcash);
    
        if (!$result) {
            echo json_encode(["status" => "error", "message" => mysqli_error($obj_con)]);
            exit();
        }
    
        $data = [];
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = [
                    'pCashId' => $row['pcash_id'],
                    'pCashNo' => $row['pcash_no'],
                    'pCashDepartment' => $row['dep_name'],
                    'pCashPayaName' => $row['paya_name'],
                    'pCashType' => $row['pcash_type'],
                    'pCashDate' => $row['pcash_date'],
                    'pCashCreatedAt' => $row['created_at'],
                    'pCashUserCreatedAt' => $row['user_created_at'],
                    'pCashUpdatedAt' => $row['updated_at'],
                    'pCashUserUpdatedAt' => $row['user_updated_at'],
                    'pclIds' => $row['pcl_ids'],
                    'pclTaxNos' => $row['pcl_tax_nos'],
                    'pclTaxDates' => $row['pcl_tax_dates'],
                    'pclTaxMonths' => $row['pcl_tax_months'],
                    'pclDescriptions' => $row['pcl_descriptions'],
                    'pclTaxRefunds' => $row['pcl_tax_refunds'],
                    'pclFees' => $row['pcl_fees'],
                    'pclVatPercents' => $row['pcl_vat_percents'],
                    'pclVats' => $row['pcl_vats'],
                    'pclTaxPercents' => $row['pcl_tax_percents'],
                    'pclTaxs' => $row['pcl_taxs'],
                    'pclTotals' => $row['pcl_totals'],
                    'pclTotalDiffs' => $row['pcl_total_diffs'],
                    'pclNetAmounts' => $row['pcl_net_amounts'],
                    'pCashFee' => number_format($row['pcash_fee'], 2),
                    'pCashVat' => number_format($row['pcash_vat'], 2),
                    'pCashTax' => number_format($row['pcash_tax'], 2),
                    'pCashTotal' => number_format($row['pcash_total'], 2),
                    'pCashTotalDiff' => number_format($row['pcash_total_diff'], 2),
                    'pCashNetAmount' => number_format($row['pcash_net_amount'], 2),
                ];
            }
        }
    
        echo json_encode(["status" => "success", "data" => $data]);
        mysqli_close($obj_con);
    }
    else if($action == 'fetchDataById') {
        if (empty($pCashId)) {
            echo json_encode(["status" => "error", "message" => "ไม่พบรหัสใบจ่ายเงินสดย่อยนี้"]);
            exit();
        }
    
        $sql_pcash = "SELECT
            pc.*,
            GROUP_CONCAT(pcl.pcl_id SEPARATOR ', ') AS pcl_id,
            GROUP_CONCAT(pcl.pcl_tax_no SEPARATOR ', ') AS pcl_tax_nos,
            GROUP_CONCAT(pcl.pcl_tax_date SEPARATOR ', ') AS pcl_tax_dates,
			GROUP_CONCAT(pcl.pcl_tax_month SEPARATOR ', ') AS pcl_tax_months,
            GROUP_CONCAT(pcl.pcl_description SEPARATOR ', ') AS pcl_descriptions,
            GROUP_CONCAT(pcl.pcl_tax_refund SEPARATOR ', ') AS pcl_tax_refunds,
            GROUP_CONCAT(pcl.pcl_fee SEPARATOR ', ') AS pcl_fees,
            GROUP_CONCAT(pcl.pcl_vat_percent SEPARATOR ', ') AS pcl_vat_percents,
            GROUP_CONCAT(pcl.pcl_vat SEPARATOR ', ') AS pcl_vats,
			GROUP_CONCAT(pcl.pcl_vat_diff SEPARATOR ', ') AS pcl_vat_diffs,
            GROUP_CONCAT(pcl.pcl_tax_percent SEPARATOR ', ') AS pcl_tax_percents,
            GROUP_CONCAT(pcl.pcl_tax SEPARATOR ', ') AS pcl_taxs,
			GROUP_CONCAT(pcl.pcl_tax_diff SEPARATOR ', ') AS pcl_tax_diffs,
            GROUP_CONCAT(pcl.pcl_total SEPARATOR ', ') AS pcl_totals,
            GROUP_CONCAT(pcl.pcl_total_diff SEPARATOR ', ') AS pcl_total_diffs,
            GROUP_CONCAT(pcl.pcl_net_amount SEPARATOR ', ') AS pcl_net_amounts,
            c.comp_name,
            d.dep_name,
            py.paya_id,
            py.paya_name
        FROM pettycash_tb AS pc
        INNER JOIN pettycash_list_tb AS pcl ON pc.pcash_id = pcl.pcl_pcash_id 
        LEFT JOIN company_tb AS c ON pc.pcash_comp_id = c.comp_id 
        LEFT JOIN department_tb AS d ON pc.pcash_dep_id = d.dep_id 
        LEFT JOIN payable_tb AS py ON pc.pcash_paya_id = py.paya_id 
        WHERE pc.pcash_id = '$pCashId' 
        AND pc.soft_delete = '0'
        GROUP BY pc.pcash_id";
    
        $result = mysqli_query($obj_con, $sql_pcash);
    
        if (!$result) {
            echo json_encode(["status" => "error", "message" => mysqli_error($obj_con)]);
            exit();
        }

        $row = mysqli_fetch_assoc($result);
        if (!$row) {
            echo json_encode(["status" => "error", "message" => "No data found for the provided ID"]);
            exit();
        }
    
        $pCashData = [
            'pCashId' => $row['pcash_id'],
            'pCashNo' => $row['pcash_no'],
            'pCashDepartment' => $row['dep_name'],
            'pCashPayaId' => $row['paya_id'],
            'pCashPayaName' => $row['paya_name'],
            'pCashType' => $row['pcash_type'],
            'pCashDate' => $row['pcash_date'],
            'pCashFee' => $row['pcash_fee'],
            'pCashVat' => $row['pcash_vat'],
            'pCashVatDiff' => $row['pcash_vat_diff'],
            'pCashTax' => $row['pcash_tax'],
            'pCashTaxDiff' => $row['pcash_tax_diff'],
            'pCashTotal' => $row['pcash_total'],
            'pCashTotalDiff' => $row['pcash_total_diff'],
            'pCashNetAmount' => $row['pcash_net_amount'],
            'pCashCreatedAt' => $row['created_at'],
            'pCashUserCreatedAt' => $row['user_created_at'],
            'pCashUpdatedAt' => $row['updated_at'],
            'pCashUserUpdatedAt' => $row['user_updated_at'],
            'items' => []
        ];


        $itemFields = [
            'pcl_id', 'pcl_tax_nos', 'pcl_tax_dates', 'pcl_tax_months', 
            'pcl_descriptions', 'pcl_tax_refunds', 'pcl_fees', 
            'pcl_vat_percents', 'pcl_vats', 'pcl_vat_diffs', 
            'pcl_tax_percents', 'pcl_taxs', 'pcl_tax_diffs', 
            'pcl_totals', 'pcl_total_diffs', 'pcl_net_amounts'
        ];

        
        
        foreach ($itemFields as $field) {
            $values = isset($row[$field]) ? explode(', ', $row[$field]) : [];
            foreach ($values as $index => $value) {
                $pCashData['items'][$index][$field] = $value;
            }
        }
        
        
        echo json_encode(['status' => 'success', 'data' => $pCashData]);
        
        mysqli_close($obj_con);
    }
    else if($action == 'processInvoice') {
        $pcashIds = $_POST['pCashIds'] ?? [];


        // $sql_pcash = "SELECT 
        //     pc.*,
        //     pl.*
        //     c.comp_name,
        //     d.dep_name,
        //     py.paya_name
        // FROM pettycash_tb AS pc
        // INNER JOIN pettycash_list_tb AS pcl ON pc.pcash_id = pcl.pcl_pcash_id 
        // LEFT JOIN company_tb AS c ON pc.pcash_comp_id = c.comp_id 
        // LEFT JOIN department_tb AS d ON pc.pcash_dep_id = d.dep_id 
        // LEFT JOIN payable_tb AS py ON pc.pcash_paya_id = py.paya_id 
        // WHERE pcl.pcl_pcash_id
        // AND pc.soft_delete = '0' AND pc.pcash_type = '$pCashType'";
    }
    
    
?>
