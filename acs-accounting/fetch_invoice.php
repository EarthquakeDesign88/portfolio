<?php
include 'config/config.php'; 
__check_login();
$user_id = __session_user("id");
$user_level_id = __session_user("level_id");
$user_department_id = __session_user("department_id");
$paramurl_company_id = (isset($_POST["queryComp"])) ?$_POST["queryComp"] : 0;
$paramurl_department_id = (isset($_POST["queryDep"])) ?$_POST["queryDep"] : 0;
$invoice_step = __invoice_step_company_list($user_id,$paramurl_company_id,$paramurl_department_id);
$arrInvoiceStep = $invoice_step;
$valueInvoiceStep = $arrInvoiceStep["invoice"];
$invoice_step_con_where = $valueInvoiceStep["query_where"];
?>
<?php
    include 'connect.php';
    function MonthThaiShort($strDate) {
        $strYear = substr(date("Y",strtotime($strDate))+543,-2);
        $strMonth= date("n",strtotime($strDate));
        $strDay= date("j",strtotime($strDate));
        $strHour= date("H",strtotime($strDate));
        $strMinute= date("i",strtotime($strDate));
        $strSeconds= date("s",strtotime($strDate));
        $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
        $strMonthThai = $strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear";
    }
    $limit = '10';
    $pageno = 1;
    if($_POST['page'] > 1) {
        $start = (($_POST['page'] - 1) * $limit);
        $pageno = $_POST['page'];
    } else {
        $start = 0;
    }
    
    
   
    $str_sql = __invoice_query_select();
    $str_sql .= __invoice_query_from();
    $str_sql .= " WHERE ".$invoice_step_con_where."
    AND inv_compid = '". $_POST["queryComp"] ."' AND inv_depid = '". $_POST['queryDep'] ."' ";
    if($_POST['querySearch'] != '') {
        if($_POST['query'] != '') {
            $str_sql .= ' AND '. $_POST['querySearch'] .' LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" ';
        }
    }
    $str_sql .= ' GROUP BY inv_runnumber ';
    if($_POST['queryFil'] != '') {
        if($_POST['queryFilVal'] != '') {
            $str_sql .= ' ORDER BY '. $_POST["queryFil"] .' '. $_POST["queryFilVal"];
        }
    } else {
        if($_POST['queryFilVal'] != '') {
            $str_sql .= ' ORDER BY inv_id ' . $_POST["queryFilVal"];
        } else {
            $str_sql .= ' ORDER BY inv_id DESC ';
        }
    }
    $filter_query = $str_sql . ' LIMIT '.$start.', '.$limit.'';
    
    // echo $filter_query;
    $obj_query = mysqli_query($obj_con, $str_sql);
    $total_data = mysqli_num_rows($obj_query);
    $obj_query = mysqli_query($obj_con, $filter_query);
    $obj_row = mysqli_fetch_array($obj_query);
    $total_filter_data = mysqli_num_rows($obj_query);
    $output = '<table class="table mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th width="18%">เลขที่ใบแจ้งหนี้</th>
                            <th width="37%">รายละเอียด</th>
                            <th width="15%" class="text-center">วันครบชำระ</th>
                            <th width="15%" class="text-center">จำนวนเงิน</th>
                            <th width="8%" class="text-center">ฝ่าย</th>
                            <th width="5%" class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody>';
                    
    $html_modal_detail = "";                
    
    if($total_data > 0) {
        $i = 1;
        $total = 0;
        foreach($obj_query as $obj_row) {
            $total += (isset($obj_row["inv_netamount"])) ? $obj_row["inv_netamount"] : 0;
            
            $text = '';
            $bg = '';
            if($obj_row['inv_duedate']!=""){
                if($obj_row['inv_duedate'] < date('Y-m-d')) {
                    $bg = 'color: #F00; text-align: center; font-size: 15px;font-weight: 700;';
                    $text = 'เกินกำหนดชำระ';
                }
            }
            
            $output .= '<tr>
                            <td>
                                <div class="truncate-id text-nowrap">
                                    '. $obj_row['inv_no'] .'
                                </div>
                            </td>
                            <td>
                                <div class="truncate">
                                    <b>บริษัท : </b> '. $obj_row['paya_name'] .'<br>
                                    <b>รายการ : </b> '. $obj_row['inv_description'] .'
                                </div>
                            </td>
                            <td class="text-center">
                                '. MonthThaiShort($obj_row['inv_duedate']) .'
                                <div style="'.$bg.'">
                                    '.$text.'
                                </div>
                            </td>                   
                            <td class="text-right">
                                <input type="text" class="form-control text-right numInvNet" value="'. number_format($obj_row['inv_netamount'],2) .'" readonly>
                                <input type="text" class="form-control text-right d-none numInvNet" value="'. $obj_row['inv_netamount'] .'" readonly>
                            </td>
                            <td class="text-center text-nowrap">
                                '. $obj_row['dep_name'] .'
                            </td>
                            <td>
                                <div class="btn-group btn-group-toggle">
                                    <a href="invoice_edit.php?cid='. $obj_row['comp_id'] .'&dep='. $obj_row["inv_depid"] .'&invid='. $obj_row['inv_id'] .'&invrev='. $obj_row["inv_rev"] .'" class="btn btn-warning edit_data" type="button" name="edit" title="แก้ไข / Edit">
                                        <i class="icofont-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-primary" name="view" data-toggle="modal" data-target="#modalDetailInvoice_'.$obj_row['inv_id'].'" title="ดู / View">
                                        <i class="icofont-eye-alt"></i>
                                    </button>
                                    <button class="btn btn-danger delete_data" type="button" name="delete" id="'. $obj_row['inv_id'] .'" title="ลบ / Delete">
                                        <i class="icofont-ui-delete"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="d-none">
                            <td colspan="2"></td>
                            <td class="text-right">
                                <input type="text" class="form-control text-right" value="'. $total .'" readonly>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>';
            
			$datalist = $obj_row;
			include 'variable_invoice.php'; 
			$html_modal_detail .= $html_detail;
			
			 $i++;
			
        }
    } else {
        $output .= '</tbody>
                    <tbody>
                        <tr width="100%">
                            <td colspan="6" align="center">ไม่มีข้อมูลใบแจ้งหนี้</td>
                        </tr>
                    </tbody>';
    }
    $total_links = ceil($total_data/$limit);
    $previous_link = '';
    $next_link = '';
    $page_link = '';
    $output .= '</table>
                <div class="row mx-0 my-0">
                    <div class="col-md-6 px-0 py-4">
                        <ul class="pagination">';
    $page_array = array();
    if($total_links > 4) {
        if($pageno < 5) {
            for($count = 1; $count <= 5; $count++) {
                $page_array[] = $count;
            }
            $page_array[] = '...';
            $page_array[] = $total_links;
        } else {
            $end_limit = $total_links - 5;
            if($pageno > $end_limit) {
                $page_array[] = 1;
                $page_array[] = '...';
                for($count = $end_limit; $count <= $total_links; $count++) {
                    $page_array[] = $count;
                }
            } else {
                $page_array[] = 1;
                $page_array[] = '...';
                for($count = $pageno - 1; $count <= $pageno + 1; $count++) {
                    $page_array[] = $count;
                }
                $page_array[] = '...';
                $page_array[] = $total_links;
            }
        }
    } else {
        for($count = 1; $count <= $total_links; $count++) {
            $page_array[] = $count;
        }
    }
    for($count = 0; $count < count($page_array); $count++) {
        if($pageno == $page_array[$count]) {
            $page_link .= '<li class="page-item active">
                            <a class="page-link" href="#"> '.$page_array[$count].' <span class="sr-only">(current)</span></a>
                        </li> ';
            $previous_id = $page_array[$count] - 1;
            if($previous_id > 0) {
                $previous_link = '<li class="page-item">
                                    <a class="page-link" href="javascript:void(0)" data-page_number="'.$previous_id.'">
                                        <i class="icofont-rounded-left"></i>
                                    </a>
                                </li>';
            } else {
                $previous_link = '<li class="page-item disabled">
                                    <a class="page-link" href="#">
                                        <i class="icofont-rounded-left"></i>
                                    </a>
                                </li>';
            }
            $next_id = $page_array[$count] + 1;
            if($next_id > $total_links) {
                $next_link = '<li class="page-item disabled">
                                <a class="page-link" href="#">
                                    <i class="icofont-rounded-right"></i>
                                </a>
                            </li>';
            } else {
                $next_link = '<li class="page-item">
                                <a class="page-link" href="javascript:void(0)" data-page_number="'.$next_id.'">
                                    <i class="icofont-rounded-right"></i>
                                </a>
                            </li>';
            }
        } else {
            if($page_array[$count] == '...') {
                $page_link .= '<li class="page-item disabled">
                                <a class="page-link" href="#">...</a>
                            </li> ';
            } else {
                $page_link .= '<li class="page-item">
                                <a class="page-link" href="javascript:void(0)" data-page_number="'.$page_array[$count].'">'.$page_array[$count].'</a>
                            </li>';
            }
        }
    }
    $output .= $previous_link . $page_link . $next_link;
            $output .= '</ul>
                    </div>
                    <div class="col-md-6 px-0 py-4 text-right">
                        <b>จำนวนใบแจ้งหนี้ทั้งหมด : </b>
                        <span id="numINV">'.$total_data.'</span>
                        <input class="form-control d-none" type="number" id="numINV" value="'.$total_data.'">
                    </div>
                </div>';
    echo $output;
	echo $html_modal_detail;
?>