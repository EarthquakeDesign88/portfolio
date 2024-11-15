<?php

date_default_timezone_set("Asia/Bangkok");
session_start();

if (!$_SESSION["user_name"]) {

    Header("Location: login.php");
} else {
    include "connect.php";
    include "list_invoice_select_function.php";
    include 'config/config.php';
    __check_login();
    $paramurl_company_id = (isset($paramurl_company_id)) ? $paramurl_company_id : ((isset($_GET["cid"])) ? $_GET["cid"] : "");
    $paramurl_department_id = (isset($paramurl_department_id)) ? $paramurl_department_id : ((isset($_GET["dep"])) ? $_GET["dep"] : "");
    $list = new ListInvoiceSelect($obj_con, $paramurl_company_id, $paramurl_department_id);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action'])) {
            $action = $_POST['action'];
            $msg = '';
            if ($action == "search_payable") {
                $search = isset($_POST['search_payable']) ? $_POST['search_payable'] : '';
                $data = $list->searchPayable($search);
            }else if($action == "save_invoice"){
                $check_data = validateInput($_POST['data']);
                $data = $list->createInovice($check_data);
                $msg = 'ออกใบแจ้งหนี้สำเร็จ';
            }

            if($data === false){
                $response = [
                    "status" => "error",
                    "message" => "เกิดข้อผิดพลาด",
                ];
            }else{
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
            if ($action == "get_company") {
                $data = $list->getCompany();
            }elseif($action = "get_list_invoice"){
                $data = $list->getListInvoice();
            }

            if($data === false){
                $response = [
                    "status" => "error",
                    "message" => "เกิดข้อผิดพลาด",
                ];
            }else{
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

function validateInput($data)
{

    if (is_string($data)) {
        $data = json_decode($data, true);
    }

    if (!is_array($data)) {
        return [];
    }

    foreach ($data as $key => $value) {
        if($key == "id") continue;
        $data[$key] = htmlspecialchars(trim($data[$key]), ENT_QUOTES, 'UTF-8');
    }

    return $data;
}
