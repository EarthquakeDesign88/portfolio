<?php

date_default_timezone_set("Asia/Bangkok");
session_start();

if (!$_SESSION["user_name"]) {

    Header("Location: login.php");
} else {
    include "connect.php";
    include "list_invoice_function.php";
    include 'config/config.php';
    __check_login();
    $paramurl_company_id = (isset($paramurl_company_id)) ? $paramurl_company_id : ((isset($_GET["cid"])) ? $_GET["cid"] : "");
    $paramurl_department_id = (isset($paramurl_department_id)) ? $paramurl_department_id : ((isset($_GET["dep"])) ? $_GET["dep"] : "");
    $list = new ListInvoice($obj_con, $paramurl_company_id, $paramurl_department_id);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['action'])) {
            $action = $_POST['action'];
            $msg = '';
            $path = null;
            $count = 0;

            if ($action == "get_all_list") {
                $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
                $limit = 10;
                $offset = ($page - 1) * $limit;
                $count = ceil($list->countListInvoice() / $limit);
                $data = $list->getAllListInvoice($limit, $offset);

            } elseif ($action == "save_list" || $action == "update_list") {
                
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

                if($action == "save_list"){
                    $data = $list->createListInvoice($check_data, $path);
                    $msg = "บันทึกข้อมูลสำเร็จ";
                }else{
                    $data = $list->updateListInvoice($check_data, $path);
                    $msg = "อัพเดทข้อมูลสำเร็จ";
                }

            } elseif ($action == "del_list") {
                $data = $list->delListInvoice($_POST['id']);
                $msg = "ลบข้อมูลสำเร็จ";
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
                    "count" => $count
                ];
            }

            echo json_encode($response);
            exit();
        }
    } elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
        }
    }
}

function uploadFile($file)
{
    $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf', 'image/jpg'];
    $maxSize = 2 * 1024 * 1024;
    $uploadDir = 'list_invoice/';

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

function validateInput($data) {

    if (is_string($data)) {
        $data = json_decode($data, true);
    }

    if (!is_array($data)) {
        return [];
    }

    foreach ($data as $key => $value){
        $data[$key] = htmlspecialchars(trim($data[$key]), ENT_QUOTES, 'UTF-8');
    }

    return $data;
}