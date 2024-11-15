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
    $userName = $_SESSION['user_name'] ?? '';


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
            pc.pcash_fee,
            pc.pcash_total,
            pc.pcash_vat,
            pc.pcash_tax,
            pc.pcash_diff,
            pc.pcash_net_amount,
            GROUP_CONCAT(pcl.pcl_description SEPARATOR ', ') AS list_items,
            c.comp_name,
            d.dep_name
        FROM pettycash_tb AS pc
        INNER JOIN pettycash_list_tb AS pcl ON pc.pcash_id = pcl.pcl_pcash_id 
        LEFT JOIN company_tb AS c ON pc.pcash_comp_id = c.comp_id 
        LEFT JOIN department_tb AS d ON pc.pcash_dep_id = d.dep_id 
        LEFT JOIN payable_tb AS py ON pc.pcash_paya_id = py.paya_id 
        WHERE pc.pcash_comp_id = '$pCashCompId' 
        AND pc.pcash_dep_id = '$pCashDepId'
        AND pc.soft_delete = '0'
        ";

        if (!empty($searchCriteria) && !empty($searchTerm)) {
            $searchTerm = mysqli_real_escape_string($obj_con, $searchTerm);
            $sql_pcash .= "AND $searchCriteria LIKE '%$searchTerm%' ";
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
                    'pCashCompId' => $pCashCompId,
                    'pCashDepId' => $pCashDepId,
                    'listItems' => $row['list_items'],
                    'pCashFee' => number_format($row['pcash_fee'], 2),
                    'pCashVat' => number_format($row['pcash_vat'], 2),
                    'pCashTax' => number_format($row['pcash_tax'], 2),
                    'pCashTotal' => number_format($row['pcash_total'], 2),
                    'pCashDiff' => number_format($row['pcash_diff'], 2),
                    'pCashNetAmount' => number_format($row['pcash_net_amount'], 2),
                ];
            }
        }

        echo json_encode(["status" => "success", "data" => $data]);


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

            // Close the statement
            mysqli_stmt_close($stmt_pcash);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to prepare statement"]);
        }

        exit();
	

    }
    else if($action == 'fetchDetails') {
        if (!empty($pCashId)) {
            echo json_encode(["status" => "error", "message" => "ไม่พบรหัสใบจ่ายเงินสดย่อยนี้"]);
            exit();
        }

        $sql_pcash = "SELECT
            pc.*,
            GROUP_CONCAT(pcl.pcl_id SEPARATOR ', ') AS pcl_id,
            GROUP_CONCAT(pcl.pcl_tax_no SEPARATOR ', ') AS pcl_tax_no,
            GROUP_CONCAT(pcl.pcl_tax_date SEPARATOR ', ') AS pcl_tax_date,
            GROUP_CONCAT(pcl.pcl_tax_refund SEPARATOR ', ') AS pcl_tax_refund,
            GROUP_CONCAT(pcl.pcl_description SEPARATOR ', ') AS pcl_descriptions,
            GROUP_CONCAT(pcl.pcl_tax_refund SEPARATOR ', ') AS pcl_tax_refunds,
            GROUP_CONCAT(pcl.pcl_fee SEPARATOR ', ') AS pcl_fees,
            GROUP_CONCAT(pcl.pcl_vat_percent SEPARATOR ', ') AS pcl_vat_percents,
            GROUP_CONCAT(pcl.pcl_vat SEPARATOR ', ') AS pcl_vats,
            GROUP_CONCAT(pcl.pcl_tax_percent SEPARATOR ', ') AS pcl_tax_percents,
            GROUP_CONCAT(pcl.pcl_tax SEPARATOR ', ') AS pcl_taxs,
            GROUP_CONCAT(pcl.pcl_total SEPARATOR ', ') AS pcl_totals,
            GROUP_CONCAT(pcl.pcl_diff SEPARATOR ', ') AS pcl_diffs,
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
        GROUP BY pc.pcash_id;";



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
                    'pCashUpdatedAt' => $row['user_updated_at'],
                    'pclTaxNo' => $row['pcl_tax_no'],
                    'pclTaxDate' => $row['pcl_tax_date'],
                    'pclDescriptions' => $row['pcl_descriptions'],
                    'pclTaxRefunds' => $row['pcl_tax_refunds'],
                    'pclFees' => $row['pcl_fees'],
                    'pclVatPercents' => $row['pcl_vat_percents'],
                    'pclVats' => $row['pcl_vats'],
                    'pclTaxPercents' => $row['pcl_tax_percents'],
                    'pclTaxs' => $row['pcl_taxs'],
                    'pclTotals' => $row['pcl_totals'],
                    'pclDiffs' => $row['pcl_diffs'],
                    'pclNetAmounts' => $row['pcl_net_amounts'],
                    'pCashFee' => number_format($row['pcash_fee'], 2),
                    'pCashVat' => number_format($row['pcash_vat'], 2),
                    'pCashTax' => number_format($row['pcash_tax'], 2),
                    'pCashTotal' => number_format($row['pcash_total'], 2),
                    'pCashDiff' => number_format($row['pcash_diff'], 2),
                    'pCashNetAmount' => number_format($row['pcash_net_amount'], 2),
                ];
            }
        }

        echo json_encode(["status" => "success", "data" => $data]);
        mysqli_close($obj_con);
    }
    
    
?>
