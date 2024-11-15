<?php

date_default_timezone_set("Asia/Bangkok");
session_start();

if (!$_SESSION["user_name"] || $_SESSION["user_levid"] != '8') {

    Header("Location: login.php");
} else {

    class ListInvoice
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
            $sql = "SELECT COUNT(*) as count 
                    FROM invoice_list 
                    WHERE invoice_list_deleted_at IS NULL 
                    AND invoice_list_inv_id IS NULL 
                    AND invoice_list_comp_id = ? 
                    AND invoice_list_dep_id = ?";

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

        public function getAllListInvoice($limit = 5, $offset = 0)
        {
            $sql = "SELECT  invoice_list_number, 
                            invoice_list_desc, 
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
                            invoice_list_diff_vat,
                            invoice_list_diff_tax,
                            invoice_list_diff_tax2,
                            invoice_list_diff_tax3

                    FROM invoice_list 
                    WHERE invoice_list_deleted_at IS NULL 
                    AND invoice_list_inv_id IS NULL 
                    AND invoice_list_comp_id = ? 
                    AND invoice_list_dep_id = ?
                    ORDER BY invoice_list_id 
                    LIMIT ? OFFSET ?";

            try {

                $stmt = mysqli_prepare($this->conn, $sql);

                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, 'ssii', $this->comp_id, $this->dep_id, $limit, $offset);

                    mysqli_stmt_execute($stmt);

                    $result = mysqli_stmt_get_result($stmt);
                    $invoices = mysqli_fetch_all($result, MYSQLI_ASSOC);

                    mysqli_stmt_close($stmt);

                    return $invoices;
                }
            } catch (mysqli_sql_exception $e) {

                return false;
            }

            return false;
        }

        public function createListInvoice($obj, $path = null)
        {
            $currentDateTime = date('Y-m-d H:i:s');

            $sql = "INSERT INTO invoice_list (
                        invoice_list_number, 
                        invoice_list_desc, 
                        invoice_list_file, 
                        invoice_list_amount, 
                        invoice_list_percent_vat, 
                        invoice_list_vat, 
                        invoice_list_total, 
                        invoice_list_return_vat, 
                        invoice_list_date, 
                        invoice_list_created_at, 
                        invoice_list_updated_at, 
                        invoice_list_dep_id, 
                        invoice_list_comp_id,
                        invoice_list_tax_amount,
                        invoice_list_tax_percent,
                        invoice_list_tax_total,
                        invoice_list_tax_amount2,
                        invoice_list_tax_percent2,
                        invoice_list_tax_total2,
                        invoice_list_tax_amount3,
                        invoice_list_tax_percent3,
                        invoice_list_tax_total3,
                        invoice_list_diff_vat,
                        invoice_list_diff_tax,
                        invoice_list_diff_tax2,
                        invoice_list_diff_tax3
                        ) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            try {
                $stmt = mysqli_prepare($this->conn, $sql);  

                if ($stmt) {
                    mysqli_stmt_bind_param(
                        $stmt, 
                        'sssddddssssssddddddddddddd', 
                        $obj['invoice_number'], 
                        $obj['list_name'], 
                        $path, 
                        $obj['amount'], 
                        $obj['percent_vat'], 
                        $obj['vat'], 
                        $obj['total'], 
                        $obj['tax'], 
                        $obj['date_list'], 
                        $currentDateTime, 
                        $currentDateTime, 
                        $this->dep_id, 
                        $this->comp_id,
                        $obj['tax_amount'],
                        $obj['tax_percent'],
                        $obj['tax_total'],
                        $obj['tax_amount2'],
                        $obj['tax_percent2'],
                        $obj['tax_total2'],
                        $obj['tax_amount3'],
                        $obj['tax_percent3'],
                        $obj['tax_total3'],
                        $obj['diff_vat'],
                        $obj['diff_tax'],
                        $obj['diff_tax2'],
                        $obj['diff_tax3'],
                    );

                    $result = mysqli_stmt_execute($stmt);

                    mysqli_stmt_close($stmt);

                    return $result;
                }
            } catch (mysqli_sql_exception $e) {

                return false;
            }

            return false;
        }

        public function updateListInvoice($obj, $path = null)
        {
            $currentDateTime = date('Y-m-d H:i:s');

            $sql = "UPDATE invoice_list 
                    SET invoice_list_number = ?,
                        invoice_list_desc = ?, 
                        invoice_list_file = ?,
                        invoice_list_amount = ?, 
                        invoice_list_percent_vat = ?, 
                        invoice_list_vat = ?, 
                        invoice_list_total = ?, 
                        invoice_list_return_vat = ?, 
                        invoice_list_date = ?,
                        invoice_list_updated_at = ?,
                        invoice_list_tax_amount = ?,
                        invoice_list_tax_percent = ?,
                        invoice_list_tax_total = ?,
                        invoice_list_tax_amount2 = ?,
                        invoice_list_tax_percent2 = ?,
                        invoice_list_tax_total2 = ?,
                        invoice_list_tax_amount3 = ?,
                        invoice_list_tax_percent3 = ?,
                        invoice_list_tax_total3 = ?,
                        invoice_list_diff_vat = ?,
                        invoice_list_diff_tax = ?,
                        invoice_list_diff_tax2 = ?,
                        invoice_list_diff_tax3 = ?

                    WHERE invoice_list_id = ?";

            try {
                $stmt = mysqli_prepare($this->conn, $sql);

                if ($stmt) {
                    mysqli_stmt_bind_param(
                        $stmt, 
                        'sssddddsssdddddddddddddi',
                        $obj['invoice_number'], 
                        $obj['list_name'], 
                        $path, 
                        $obj['amount'], 
                        $obj['percent_vat'], 
                        $obj['vat'], 
                        $obj['total'], 
                        $obj['tax'], 
                        $obj['date_list'], 
                        $currentDateTime, 
                        $obj['tax_amount'],
                        $obj['tax_percent'],
                        $obj['tax_total'],
                        $obj['tax_amount2'],
                        $obj['tax_percent2'],
                        $obj['tax_total2'],
                        $obj['tax_amount3'],
                        $obj['tax_percent3'],
                        $obj['tax_total3'],
                        $obj['diff_vat'],
                        $obj['diff_tax'],
                        $obj['diff_tax2'],
                        $obj['diff_tax3'],
                        $obj['id']
                    );
                    
                    $result = mysqli_stmt_execute($stmt);

                    mysqli_stmt_close($stmt);

                    return $result;
                }
            } catch (mysqli_sql_exception $e) {
                return false;
            }

            return false;
        }

        public function delListInvoice($id)
        {
            $sql = "UPDATE invoice_list SET invoice_list_deleted_at = NOW() WHERE invoice_list_id = ?";

            try {
                $stmt = mysqli_prepare($this->conn, $sql);

                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, 'i', $id);
                    $result = mysqli_stmt_execute($stmt);

                    mysqli_stmt_close($stmt);

                    return $result;
                }
            } catch (mysqli_sql_exception $e) {
                return false;
            }

            return false;
        }

        public function getCompany($cid)
        {
            $sql = "SELECT comp_id, comp_name
                    FROM company_tb
                    WHERE comp_id = ? 
                    LIMIT 1";

            try {
                $stmt = mysqli_prepare($this->conn, $sql);

                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, 's', $cid);

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
    }
}
