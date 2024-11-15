<?php

date_default_timezone_set("Asia/Bangkok");
session_start();

if (!$_SESSION["user_name"]) {

    Header("Location: login.php");
} else {

    class TaxInvoice
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

        public function getTaxInvoice($id)
        {
            $sql = "SELECT * 
            FROM payment_tb as p
            INNER JOIN invoice_tb as i ON p.paym_id = i.inv_paymid
            LEFT JOIN invoice_list as il ON i.inv_id = il.invoice_list_inv_id
            LEFT JOIN taxinvoice_tb as ti ON il.invoice_list_tax_id = ti.tax_invoice_id
            WHERE i.inv_paymid = ? ";

            try {

                $stmt = mysqli_prepare($this->conn, $sql);

                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, 'i', $id);

                    mysqli_stmt_execute($stmt);

                    $result = mysqli_stmt_get_result($stmt);
                    $payment = mysqli_fetch_all($result, MYSQLI_ASSOC);

                    mysqli_stmt_close($stmt);

                    return $payment;
                }
            } catch (mysqli_sql_exception $e) {

                return false;
            }

            return false;
        }

        public function createTaxInvoice($obj, $path = null)
        {
            $currentDateTime = date('Y-m-d H:i:s');

            $data = $obj['list_id'];
            $placeholders = implode(',', array_fill(0, count($data), '?'));
            $taxNumber =  $obj['tax_number'];
            $taxDate = $obj['tax_date'];
            $taxMonth = $obj['tax_month'];

            try {
                $this->conn->begin_transaction();

                $sql_insert = "INSERT INTO taxinvoice_tb (
                                    tax_invoice_number, 
                                    tax_invoice_date,
                                    tax_invoice_file,
                                    tax_invoice_created_at,
                                    tax_invoice_purchase_month
                                ) VALUES (?, ?, ?, ?, ?)";

                $stmt = mysqli_prepare($this->conn, $sql_insert);

                if ($stmt === false) {
                    return false;
                }

                mysqli_stmt_bind_param($stmt, "sssss", $taxNumber, $taxDate, $path, $currentDateTime, $taxMonth);

                if (mysqli_stmt_execute($stmt)) {
                    $lastInsertId = $this->conn->insert_id;

                    $sql_update = 'UPDATE invoice_list SET invoice_list_tax_id = ? WHERE invoice_list_id in (' . $placeholders . ')';

                    $stmt_update = mysqli_prepare($this->conn, $sql_update);

                    if ($stmt_update === false) {
                        return false;
                    }

                    if (count($data) > 0) {
                        $types = str_repeat('i', count($data));
                        mysqli_stmt_bind_param($stmt_update, 'i' . $types, $lastInsertId, ...$data);
                    } else {
                        return false;
                    }

                    if (mysqli_stmt_execute($stmt_update)) {
                        $this->conn->commit();
                    } else {
                        $this->conn->rollback();
                        return false;
                    }

                    mysqli_stmt_close($stmt_update);
                } else {
                    $this->conn->rollback();
                    return false;
                }

                mysqli_stmt_close($stmt);
            } catch (mysqli_sql_exception $e) {
                $this->conn->rollback();
                // error_log('Error: ' . $e->getMessage());
                return false;
            }

            return true;
        }

        public function searchTaxInvoice($search)
        {

            $sql = "SELECT *, 
                            SUM(li.invoice_list_amount) AS total_invoice_amount, 
                            SUM(li.invoice_list_vat) AS total_invoice_vat, 
                            SUM(li.invoice_list_total) AS total_invoice_total
                    FROM taxinvoice_tb as ti
                    INNER JOIN invoice_list as li ON ti.tax_invoice_id = li.invoice_list_tax_id
                    INNER JOIN invoice_tb as i ON li.invoice_list_inv_id = i.inv_id
                    INNER JOIN payable_tb as p ON p.paya_id = i.inv_payaid
                    LEFT JOIN taxpurchaselist_tb as tp ON ti.tax_invoice_id = tp.list_tax_invoice_id
                    WHERE ti.tax_invoice_number = ?
                    AND tp.list_tax_invoice_id IS NULL
                    AND li.invoice_list_comp_id = ? 
                    AND li.invoice_list_dep_id = ?
                    GROUP BY li.invoice_list_tax_id";

            try {
                $stmt = mysqli_prepare($this->conn, $sql);

                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, 'sss', $search, $this->comp_id, $this->dep_id);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $row = mysqli_fetch_assoc($result);

                    mysqli_stmt_close($stmt);

                    return $row;
                }
            } catch (mysqli_sql_exception $e) {

                return $e->getMessage();
            }

            return false;
        }

        public function updateTaxInvoice($obj)
        {
            $currentDateTime = date('Y-m-d H:i:s');

            $sql = "UPDATE taxinvoice_tb 
                    SET tax_invoice_number = ?,
                        tax_invoice_date = ?,
                        tax_invoice_purchase_month = ?,
                        tax_invoice_updated_at = ?   
                    WHERE tax_invoice_id = ?";

            try {
                $stmt = mysqli_prepare($this->conn, $sql);

                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, 'ssssi', $obj['tax_number'], $obj['tax_date'], $obj['tax_month'], $currentDateTime, $obj['tax_id']);
                    $result = mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);

                    return $result;
                }
            } catch (mysqli_sql_exception $e) {
                return $e->getMessage();
            }

            return false;
        }


        public function getTaxInvoiceReturnVat($month)
        {

            if($month == '') return false;

            $sql = "SELECT *, 
                            GROUP_CONCAT(li.invoice_list_id) AS invoice_list_ids,
                            SUM(li.invoice_list_amount) AS total_invoice_amount, 
                            SUM(li.invoice_list_vat) AS total_invoice_vat, 
                            SUM(li.invoice_list_total) AS total_invoice_total,
                            ti.tax_invoice_purchase_month

            FROM invoice_list as li
            INNER JOIN invoice_tb as i ON li.invoice_list_inv_id = i.inv_id 
            INNER JOIN payable_tb as p ON i.inv_payaid = p.paya_id
            INNER JOIN taxinvoice_tb as ti ON li.invoice_list_tax_id = ti.tax_invoice_id
            LEFT JOIN taxpurchaselist_tb as tp ON ti.tax_invoice_id = tp.list_tax_invoice_id
            WHERE tp.list_tax_invoice_id IS NULL 
            AND li.invoice_list_return_vat = '1' 
            AND li.invoice_list_comp_id = ? 
            AND li.invoice_list_dep_id = ?
            AND ti.tax_invoice_purchase_month = ?
            GROUP BY ti.tax_invoice_number";

            try {

                $stmt = mysqli_prepare($this->conn, $sql);

                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, 'sss', $this->comp_id, $this->dep_id, $month);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $tax = mysqli_fetch_all($result, MYSQLI_ASSOC);

                    mysqli_stmt_close($stmt);

                    return $tax;
                }
            } catch (mysqli_sql_exception $e) {

                return false;
            }

            return false;
        }

        public function checkTaxPurchase($tax_id)
        {
            $sql = "SELECT *
                    FROM taxinvoice_tb as ti
                    LEFT JOIN taxpurchaselist_tb as tp ON ti.tax_invoice_id = tp.list_tax_invoice_id
                    WHERE tp.list_tax_invoice_id = ?";

            try {

                $stmt = mysqli_prepare($this->conn, $sql);

                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, 'i', $tax_id);

                    mysqli_stmt_execute($stmt);

                    $result = mysqli_stmt_get_result($stmt);
                    $tax = mysqli_fetch_all($result, MYSQLI_ASSOC);

                    mysqli_stmt_close($stmt);

                    return $tax;
                }
            } catch (mysqli_sql_exception $e) {

                return false;
            }

            return false;
        }

        public function checkListInvoice($id)
        {
            $sql = "SELECT *
            FROM invoice_list as li
            INNER JOIN taxinvoice_tb as tp ON li.invoice_list_tax_id = tp.tax_invoice_id
            WHERE li.invoice_list_tax_id = ?";

            try {

                $stmt = mysqli_prepare($this->conn, $sql);

                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, 'i', $id);

                    mysqli_stmt_execute($stmt);

                    $result = mysqli_stmt_get_result($stmt);
                    $invoice = mysqli_fetch_all($result, MYSQLI_ASSOC);

                    mysqli_stmt_close($stmt);

                    return $invoice;
                }
            } catch (mysqli_sql_exception $e) {

                return false;
            }

            return false;
        }

        public function getMonthTaxPurchase()
        {
            $sql = "SELECT tax_created_at
                    FROM taxpurchase_tb LIMIT 12";

            try {

                $stmt = mysqli_prepare($this->conn, $sql);

                if ($stmt) {

                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $date = mysqli_fetch_all($result, MYSQLI_ASSOC);

                    mysqli_stmt_close($stmt);

                    return $date;
                }
            } catch (mysqli_sql_exception $e) {

                return $e->getMessage();
            }

            return false;
        }
    }
}
