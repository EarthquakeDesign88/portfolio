<?php

date_default_timezone_set("Asia/Bangkok");
session_start();

if (!$_SESSION["user_name"]) {

    Header("Location: login.php");
} else {
    include "connect.php";
    include "list_tax_invoice_function.php";
    include 'config/config.php';
    __check_login();
    $paramurl_company_id = (isset($paramurl_company_id)) ? $paramurl_company_id : ((isset($_GET["cid"])) ? $_GET["cid"] : "");
    $paramurl_department_id = (isset($paramurl_department_id)) ? $paramurl_department_id : ((isset($_GET["dep"])) ? $_GET["dep"] : "");
    $list = new TaxInvoice($obj_con, $paramurl_company_id, $paramurl_department_id);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action'])) {
            $action = $_POST['action'];
            $msg = '';
            $path = null;

            if ($action == "save_tax_invoice") {
                if (isset($_FILES['file'])) {
                    $check_file = uploadFile($_FILES['file']);

                    if ($check_file['success'] == true) {
                        $path = $check_file['filePath'];
                    } else {
                        $response = [
                            "status" => "error",
                            "message" => $check_file['message'],
                        ];
                        echo json_encode($response);
                        exit();
                    }
                }

                $check_data = validateInput($_POST['data']);
                $data = $list->createTaxInvoice($check_data, $path);
                $msg = "บันทึกข้อมูลสำเร็จ";
            }else if($action == "check_tax_purchase"){
                $tax_id = isset($_POST['tax_id']) ? $_POST['tax_id'] : '';
                $data = $list->checkTaxPurchase($tax_id);
                
                if(count($data) == 0){
                    $data = $list->checkListInvoice($tax_id);
                }else{
                    $data = [];
                }

            }else if($action == "update_tax"){
                $check_data = validateInput($_POST['data']);
                $data = $list->updateTaxInvoice($check_data);
                $msg = "อัพเดทข้อมูลสำเร็จ";
            }

            if ($data === false) {
                $response = [
                    "status" => "error",
                    "message" => "เกิดข้อผิดพลาด",
                ];
            } else {
                $response = [
                    "status" => "success",
                    "message" => $msg,
                    "data" => $data,
                ];
            }

            echo json_encode($response);
            exit();
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            $msg = '';

            if ($action == "get_invoice") {
                $id = isset($_GET['id']) ? $_GET['id'] : '';
                $data = $list->getTaxInvoice($id);
            }else if($action == "get_tax_invoice"){
                $month = isset($_GET['month']) ? $_GET['month'] : '';
                $data = $list->getTaxInvoiceReturnVat($month);
            }else if($action == "search_tax_invoice"){
                $search = isset($_GET['search']) ? $_GET['search'] : '';
                $data = $list->searchTaxInvoice($search);
            }else if ($action == "get_month_tax_purchase"){
                $data = $list->getMonthTaxPurchase();
            }

            if ($data === false) {
                $response = [
                    "status" => "error",
                    "message" => "เกิดข้อผิดพลาด",
                ];
            } else {    
                $response = [
                    "status" => "success",
                    "message" => $msg,
                    "data" => $data,
                ];
            }

            echo json_encode($response);
            exit();
        }
    }
}

function uploadFile($file)
{
    $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf', 'image/jpg'];
    $maxSize = 2 * 1024 * 1024;
    $uploadDir = 'list_tax_invoice/';

    if (!in_array($file['type'], $allowedTypes)) {
        return ['success' => false, 'message' => "ไฟล์ประเภทนี้ไม่อนุญาต (อนุญาตเฉพาะ jpg, jpeg, png, pdf)"];
    }

    if ($file['size'] > $maxSize) {
        return ['success' => false, 'message' => "ขนาดไฟล์ไม่ควรเกิน " . ($maxSize / 1024 / 1024) . "MB"];
    }

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $uniqueFileName = uniqid('invoice_', true) . '.' . $fileExtension;

    $uploadFile = $uploadDir . $uniqueFileName;

    if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
        return ['success' => true, 'filePath' => $uniqueFileName];
    } else {
        return ['success' => false, 'message' => "เกิดข้อผิดพลาดในการย้ายไฟล์"];
    }
}

function validateInput($data)
{

    if (is_string($data)) {
        $data = json_decode($data, true);
    }

    if (!is_array($data)) {
        return [];
    }

    foreach ($data as $key => $value) {
        if($key == "list_id") continue;
        $data[$key] = htmlspecialchars(trim($data[$key]), ENT_QUOTES, 'UTF-8');
    }

    return $data;
}
