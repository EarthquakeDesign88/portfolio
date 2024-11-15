<?php

date_default_timezone_set("Asia/Bangkok");
session_start();

if (!$_SESSION["user_name"]) {

    Header("Location: login.php");
} else {

    class ListInvoiceSelect
    {
        private $conn;
        private $comp_id;
        private $dep_id;

        public function __construct($dbConnection, $comp_id, $dep_id)
        {
            $this->conn = $dbConnection;
            $this->comp_id = $comp_id;
            $this->dep_id = $dep_id;
        }

        public function countListInvoice()
        {
            $sql = "SELECT COUNT(*) as count FROM invoice_list WHERE invoice_list_deleted_at IS NULL AND invoice_list_inv_id IS NULL AND invoice_list_comp_id = ? AND invoice_list_dep_id = ?";

            try {
                $stmt = mysqli_prepare($this->conn, $sql);

                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, 'ss', $this->comp_id, $this->dep_id);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $row = mysqli_fetch_assoc($result);

                    mysqli_stmt_close($stmt);

                    return $row['count'];
                }
            } catch (mysqli_sql_exception $e) {
                return false;
            }

            return false;
        }

        public function getCompany()
        {
            $sql = "SELECT comp_id, comp_name
                    FROM company_tb
                    WHERE comp_id = ? 
                    LIMIT 1";

            try {
                $stmt = mysqli_prepare($this->conn, $sql);

                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, 's', $this->comp_id);

                    mysqli_stmt_execute($stmt);

                    $result = mysqli_stmt_get_result($stmt);
                    $comp = mysqli_fetch_assoc($result);

                    mysqli_stmt_close($stmt);

                    return $comp;
                }
            } catch (mysqli_sql_exception $e) {
                return false;
            }

            return false;
        }

        public function getListInvoice()
        {
            $sql = "SELECT invoice_list_desc, 
                            invoice_list_amount, 
                            invoice_list_percent_vat, 
                            invoice_list_vat, 
                            invoice_list_total, 
                            invoice_list_return_vat, 
                            invoice_list_created_at, 
                            invoice_list_date, 
                            invoice_list_id,
                            invoice_list_tax_amount,
                            invoice_list_tax_percent,
                            invoice_list_tax_total,
                            invoice_list_tax_amount2,
                            invoice_list_tax_percent2,
                            invoice_list_tax_total2,
                            invoice_list_tax_amount3,
                            invoice_list_tax_percent3,
                            invoice_list_tax_total3,
                            invoice_list_number

                    FROM invoice_list 
                    WHERE invoice_list_deleted_at IS NULL 
                    AND invoice_list_inv_id IS NULL 
                    AND invoice_list_comp_id = ? 
                    AND invoice_list_dep_id = ?
                    ORDER BY invoice_list_id";

            try {
                
                $stmt = mysqli_prepare($this->conn, $sql);

                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, 'ss', $this->comp_id, $this->dep_id);

                    mysqli_stmt_execute($stmt);

                    $result = mysqli_stmt_get_result($stmt);
                    $invoices = mysqli_fetch_all($result, MYSQLI_ASSOC);

                    mysqli_stmt_close($stmt);

                    return $invoices;
                }
            } catch (mysqli_sql_exception $e) {

                return $e->getMessage();
            }

            return false;
        }

        public function searchPayable($search)
        {
            $sql = "SELECT * FROM payable_tb WHERE paya_name LIKE ? ORDER BY paya_name LIMIT 10";

            try {
                $stmt = mysqli_prepare($this->conn, $sql);

                if ($stmt) {
                    $query = '%' . $search . '%';
                    mysqli_stmt_bind_param($stmt, 's', $query);

                    mysqli_stmt_execute($stmt);

                    $result = mysqli_stmt_get_result($stmt);
                    $payables = mysqli_fetch_all($result, MYSQLI_ASSOC);

                    mysqli_stmt_close($stmt);

                    return $payables;
                }
            } catch (mysqli_sql_exception $e) {
                return false;
            }
            return [];
        }

        public function createInovice($obj) {
            $sql = "INSERT INTO invoice_tb (
                        inv_no, inv_type, inv_typepcash, inv_count, 
                        inv_date, inv_duedate, inv_compid, inv_payaid, 
                        inv_description, inv_description_short, inv_subtotalNoVat, 
                        inv_subtotal, inv_vatpercent, inv_vat, inv_differencevat, 
                        inv_tax1, inv_taxpercent1, inv_taxtotal1, inv_differencetax1, 
                        inv_tax2, inv_taxpercent2, inv_taxtotal2, inv_differencetax2, 
                        inv_tax3, inv_taxpercent3, inv_taxtotal3, inv_differencetax3, 
                        inv_grandtotal, inv_difference, inv_netamount, inv_balancetotal, 
                        inv_rev, inv_salary, inv_taxrefund, inv_userid_create, inv_createdate, 
                        inv_userid_edit, inv_editdate, inv_statusMgr, inv_apprMgrno, inv_statusCEO,
                        inv_apprCEOno, inv_year, inv_month, inv_depid, inv_paymid,
                        inv_statusPaym, inv_NostatusPaym, inv_runnumber) 
                    VALUES (
                        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                        ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $currentDateTime = date('Y-m-d H:i:s');
            $invyear = date("Y")+543;
            $invm = date("m");

            try {
                mysqli_begin_transaction($this->conn);

                $stmt = mysqli_prepare($this->conn, $sql);

                if ($stmt) {

                    $inv_no = $obj['list_number']; $inv_type = '0'; $inv_typepcash = '0'; $inv_count = $obj['invcount']; $inv_date = $obj['invdate'];
                    $inv_duedate = $obj['invdate']; $inv_compid = $this->comp_id; $inv_payaid = $obj['payable_id']; $inv_description = $obj['invdesc'];
                    $inv_description_short = '-'; $inv_subtotalNoVat = '0.00'; $inv_subtotal = $obj['amount']; $inv_vatpercent = '0.00';
                    $inv_vat = $obj['vat']; $inv_differencevat = '0.00'; $inv_tax1 = $obj['tax_amount']; $inv_taxpercent1 = $obj['tax1']; $inv_taxtotal1 = $obj['tax_total'];
                    $inv_differencetax1 = '0.00'; $inv_tax2 = $obj['tax_amount2']; $inv_taxpercent2 = $obj['tax2']; $inv_taxtotal2 = $obj['tax_total2'];
                    $inv_differencetax2 = '0.00'; $inv_tax3 = $obj['tax_amount3']; $inv_taxpercent3 = $obj['tax3']; $inv_taxtotal3 = $obj['tax_total3'];
                    $inv_differencetax3 = '0.00'; $inv_grandtotal = $obj['total']; $inv_difference = '0.00'; $inv_netamount = $obj['total'];
                    $inv_balancetotal = $obj['total']; $inv_rev = '0'; $inv_salary = '0'; $inv_taxrefund = $obj['tax'];
                    $inv_userid_create = $_SESSION["user_name"]; $inv_createdate = $currentDateTime; $inv_userid_edit = '';
                    $inv_editdate = $currentDateTime; $inv_statusMgr = '0'; $inv_apprMgrno = ''; $inv_statusCEO = '0'; $inv_apprCEOno = '';
                    $inv_year = $invyear; $inv_month = $invm; $inv_depid = $this->dep_id; $inv_paymid = ''; $inv_statusPaym = '0';
                    $inv_NostatusPaym = ''; $inv_runnumber = $this->invoiceRunNumber();

                    mysqli_stmt_bind_param($stmt, 'sssssssssssssssssssssssssssssssssssssssssssssssss',
                    $inv_no, $inv_type, $inv_typepcash, $inv_count, $inv_date, $inv_duedate, $inv_compid, $inv_payaid, 
                    $inv_description, $inv_description_short, $inv_subtotalNoVat, $inv_subtotal, $inv_vatpercent, $inv_vat, 
                    $inv_differencevat, $inv_tax1, $inv_taxpercent1, $inv_taxtotal1, $inv_differencetax1, 
                    $inv_tax2, $inv_taxpercent2, $inv_taxtotal2, $inv_differencetax2, 
                    $inv_tax3, $inv_taxpercent3, $inv_taxtotal3, $inv_differencetax3, 
                    $inv_grandtotal, $inv_difference, $inv_netamount, $inv_balancetotal, 
                    $inv_rev, $inv_salary, $inv_taxrefund, $inv_userid_create, $inv_createdate, 
                    $inv_userid_edit, $inv_editdate, $inv_statusMgr, $inv_apprMgrno, $inv_statusCEO,
                    $inv_apprCEOno, $inv_year, $inv_month, $inv_depid, $inv_paymid,
                    $inv_statusPaym, $inv_NostatusPaym, $inv_runnumber
                );
            
                    $result = mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);

                    if ($result) {
                        $invoiceId = mysqli_insert_id($this->conn);
                        $placeholders = implode(',', array_fill(0, count($obj['id']), '?'));

                        $sqlUpdate = "UPDATE invoice_list SET invoice_list_inv_id = ? WHERE invoice_list_id in ($placeholders)";
                        $stmtUpdate = mysqli_prepare($this->conn, $sqlUpdate);
            
                        if ($stmtUpdate) {

                            $params = array_merge([$invoiceId], $obj['id']);

                            $types = str_repeat('i', count($params));
                        
                            mysqli_stmt_bind_param($stmtUpdate, $types, ...$params);
                            mysqli_stmt_execute($stmtUpdate);
                            mysqli_stmt_close($stmtUpdate);
                        }
            
                        mysqli_commit($this->conn);
                        return true;
                    } else {

                        mysqli_rollback($this->conn);
                        return false;
                    }
            
                }
            } catch (mysqli_sql_exception $e) {
                mysqli_rollback($this->conn);
                return false;
            }

            return false;
            
        }

        private function invoiceRunNumber(){
            $RNcode = "RNIV";
			$year = substr(date("Y")+543,-2);
			$month = date("m");
			$str_sql_ivRN = "SELECT MAX(inv_runnumber) AS last_id FROM invoice_tb WHERE inv_month = '". $month ."'";
			$obj_rs_ivRN = mysqli_query($this->conn, $str_sql_ivRN);
			$obj_row_ivRN = mysqli_fetch_array($obj_rs_ivRN);
			$maxRNId = substr($obj_row_ivRN['last_id'], -3);
			if ($maxRNId== "") {
				$maxRNId = "001";
			} else {
				$maxRNId = ($maxRNId + 1);
				$maxRNId = substr("000".$maxRNId, -3);
			}
			$nextRN = $RNcode.$year.$month.$maxRNId;

            return $nextRN;
        }
    }
}
