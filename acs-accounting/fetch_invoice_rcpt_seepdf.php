<?php
    
    include 'connect.php';
    $limit = '10';
    $pageno = 1;
    if($_POST['page'] > 1) {
        $start = (($_POST['page'] - 1) * $limit);
        $pageno = $_POST['page'];
    } else {
        $start = 0;
    }
    $str_sql = "SELECT * FROM invoice_rcpt_tb AS i 
                INNER JOIN company_tb AS c ON i.invrcpt_compid = c.comp_id 
                INNER JOIN customer_tb AS cust ON i.invrcpt_custid = cust.cust_id 
                INNER JOIN department_tb AS d ON i.invrcpt_depid = d.dep_id 
                WHERE invrcpt_depid = '". $_POST['queryDep'] ."' AND invrcpt_year = '". $_POST['queryYear'] ."' AND invrcpt_month = '". $_POST['queryMonth'] ."' ";
    $str_sql .= ' ORDER BY invrcpt_no DESC ';
    $filter_query = $str_sql . 'LIMIT '.$start.', '.$limit.'';
    // echo $filter_query;
    $obj_query = mysqli_query($obj_con, $str_sql);
    $total_data = mysqli_num_rows($obj_query);
    $obj_query = mysqli_query($obj_con, $filter_query);
    $obj_row = mysqli_fetch_array($obj_query);
    $total_filter_data = mysqli_num_rows($obj_query);
    $year = $_POST['queryYear'];
    $month = $_POST['queryMonth'];
    $output = '<table class="table mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th width="28%">เลขที่ใบแจ้งหนี้</th>
                            <th width="42%">รายละเอียด</th>
                            <th width="10%" class="text-center">ฝ่าย</th>
                            <th width="20%"></th>
                        </tr>
                    </thead>
                    <tbody>';
    if($total_data > 0) {
        $i = 1;
        $total = 0;
        foreach($obj_query as $obj_row) {
            if ($obj_row["invrcpt_book"] == '') {
                $invNo = $obj_row["invrcpt_no"];
            } else {
                $invNo = $obj_row["invrcpt_book"].'/'.$obj_row["invrcpt_no"];
            }
            
            $pdf_path = "export/invoice_rcpt_pdf.php?irID=".$obj_row["invrcpt_id"];
            $pdf_view = $pdf_path;
            $pdf_download = $pdf_path."&download=1";
            
            $output .= '<tr> 
                            <td>
                                '. $invNo .'
                            </td>
                            <td>
                                <div class="truncate">
                                    <b>บริษัท : </b>'.$obj_row["cust_name"].'<br>';
                            if($obj_row['invrcpt_stsid'] != 'STS003'){
                                $output .= "<b>รายการ : </b>" . $obj_row["invrcpt_description1"];
                            }else{
                                $output .= "<b>หมายเหตุ : </b>ยกเลิก";
                            }
            
            $output .=   '</div>
                            </td>
                            <td class="text-center text-nowrap">
                                '.$obj_row["dep_name"].'
                            </td>
                            <td>
                                <a href="'.$pdf_view.'" target="blank" " rel="noopener noreferrer" class="btn btn-primary form-control mb-1" name="view">
                                    <i class="icofont-eye-alt"></i>&nbsp;&nbsp;View
                                </a>
                                <a  href="'.$pdf_download.'"  target="blank" " rel="noopener noreferrer" class="btn btn-success form-control mb-1">
                                    <i class="icofont-download-alt"></i>&nbsp;&nbsp;Downlaod
                                </a>
                                <a  href="invoice_rcpt_bil_export.php?irID='. $obj_row['invrcpt_id'] .'&export=pdf"  target="blank" " rel="noopener noreferrer" class="btn btn-secondary form-control mb-1">
                                    <i class="icofont-download-alt"></i>&nbsp;&nbsp;Bill
                                </a>
                               </td>
                         </tr>';
            $i++;
        }
    } else {
        $output .= '</tbody>
                    <tbody>
                        <tr>
                            <td colspan="5" align="center">ไม่มีข้อมูลใบแจ้งหนี้</td>
                        </tr>
                    </tbody>
                    <div class="row">';
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
                        <b>จำนวนใบแจ้งหนี้ทั้งหมด : </b><span id="numINV">'.$total_data.'</span>
                        <input class="form-control d-none" type="number" id="numINV" value="'.$total_data.'">
                    </div>
                </div>';
    echo $output;
?>