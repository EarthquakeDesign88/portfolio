<?php
include 'config/config.php'; 
__check_login();

$user_id = __session_user("id");
$user_level_id = __session_user("level_id");
$user_department_id = __session_user("department_id");

$paramurl_company_id = (isset($_GET["cid"])) ?$_GET["cid"] : 0;
$paramurl_department_id = (isset($_GET["dep"])) ?$_GET["dep"] : 0;


$today = date('Y-m-d');
$ck_error = false;
$text_error = "";

$action = (isset($_GET["action"])) ? $_GET["action"] : "";

$title = "!ผิดพลาด";
if($action=="add"){
    $title = '<i class="icofont-plus-circle"></i>&nbsp;&nbsp;เพิ่มใบสำคัญจ่าย';
}else if($action=="edit"){
    $title = '<i class="icofont-edit"></i>&nbsp;&nbsp;แก้ไขใบสำคัญจ่าย';
}

$arrData = array();
$paymid = 0;
if($action=="add"){
    $data_checked = __invoice_payment_data_checked($paramurl_company_id,$paramurl_department_id);
    $arrayChecked  = $data_checked["arrayChecked"];
    $text_checked = implode("','",$arrayChecked);
    if(!empty(__data_company($paramurl_company_id,"id"))){
          if(!empty(__data_department($paramurl_department_id,"id"))){
                if(count($arrayChecked) >=1){
                    $sql = __invoice_query_select();
                    $sql .= " , GROUP_CONCAT(CONCAT('''',i.inv_id, '''' )) AS inv_id_list ";
                    $sql .= __invoice_query_from();
                    $sql .= " WHERE  i.inv_compid = '". $paramurl_company_id ."' AND  i.inv_depid = '". $paramurl_department_id."'";
                    $sql .= " AND i.inv_id IN ('".$text_checked."') ";
                    $datalist =  $db->query($sql." LIMIT 0,1 ")->row();
                    $arrData = $db->query($sql)->result();
                    $text_checked = trim($datalist["inv_id_list"],"'");
                    
                    if(count($datalist)>=1){
                        include 'variable_invoice.php'; 
                        $dep_name =  $datalist["dep_name"];
                        $paym_date = $today;
                        $paym_typepay = 1;
                        $sts_description = "ค้างจ่าย";
                        
                        $invoice_data_action = __invoice_data($text_checked,0);
    
                        $ck_error = true;
                    }else{
                        $text_error = "ไม่สามารถออกใบสำคัญจ่ายได้ เนื่องจากไม่มีใบแจ้งหนี้";
                    }
                }else{
                      $text_error = "ไม่สามารถออกใบสำคัญจ่ายได้ เนื่องจากไม่มีใบแจ้งหนี้";
                }
          }else{
              $text_error = "ไม่สามารถออกใบสำคัญจ่ายได้ เนื่องจากไม่พบแผนก";
          }
    }else{
        $text_error = "ไม่สามารถออกใบสำคัญจ่ายได้ เนื่องจากไม่พบบริษัท";
    }
}else if($action=="edit"){
    if(!empty($_GET["paymid"])){
         $paymid = $_GET["paymid"];
        
        $sql = __invoice_query_select();
        $sql .=__invoice_payment_query_from();
        $sql .= " WHERE pm.paym_id = '". $paymid ."'";
        $datalist = $db->query($sql." GROUP BY pm.paym_no ")->row();
        $arrData = $db->query($sql)->result();
        
         if(!empty($datalist)){
             include 'variable_payment.php'; 
			 $invoice_data_action = __invoice_data("",$paymid);
			   
             $title .= " (เลขที่ ".$paym_no.")";
             
            $ck_error = true;
        }else{
            $text_error = "ไม่สามารถแก้ไขข้อมูลได้ เนื่องจากไม่มีข้อมูลใบสำคัญจ่าย";
        }
    }else{
         $text_error = "ไม่สามารถแก้ไขข้อมูลได้ กรุณาระบุรหัสใบสำคัญจ่าย";
    }
}else{
    $text_error = "ไม่สามารถทำรายการได้";
}


if($ck_error){
     if($paym_typepay==1){
         $cheque_display = "d-none";
         $cheque_required = "";
     }else  if($paym_typepay==2){
         $cheque_display = "";
         $cheque_required = "required";
     }
                                 
    $sql_bank = "SELECT * FROM bank_tb";
    $result_bank = $db->query($sql_bank)->result();
    $html_option_bank = "";
    foreach ($result_bank as $row_bank) {
       $sel_cheq_bankid = ($row_bank["bank_id"]==$cheq_bankid) ? " selected " : "";
        
       $html_option_bank .= '<option';
       $html_option_bank .= $sel_cheq_bankid;
          $html_option_bank .= ' value="' .$row_bank["bank_id"].'"';
       $html_option_bank .= '>';
       $html_option_bank .= $row_bank["bank_name"];
       $html_option_bank .= '</option>';
    }
}
?>
<!DOCTYPE html>
    <html>
    <head>
        <?php include 'head.php'; ?>
        <link rel="stylesheet" type="text/css" href="css/checkbox.css">
        <style type="text/css">
            .div-header, .div-footer{
                background-color: #E9ECEF;
                padding-top: 16px;
                padding-bottom: 16px;
            }
            
            .div-header{
                font-weight: bold;
                font-size: 22px;
                border-bottom: 1px solid #d4d3d3;
            }
            
            .div-footer{
                border-top: 1px solid #d4d3d3;
                text-align: center;
            }
            
              .div-body{
               background-color: #FFFFFF;
               padding-top: 16px;
               padding-bottom: 16px;
            }
            
            
            .table-detail .thead-light th {
                 color: #000;
            }
            .table-detail  tr:nth-last-child(n) {
                border-bottom: 1px solid #dee2e6;
            }
            .table-bordered.border-black,
            .table-bordered.border-black th {
                border: 1px solid #000;
            }
            .table-detail td.tdborder-black {
                padding: 1rem .75rem;
                border-right: 1px solid #000;
                border-bottom: 1px dotted #000;
            }
            .table-detail td.tdborder-black.black-left {
                border-left: 1px solid #000;
            }
            .table-detail td.tdborder-black.black-bottom {
                border-bottom: 2px dotted #000!important;
            }
        
            .table-sign td{
                 line-height:1;
                 vertical-align: bottom;
            }

          
           td.line-dotted{
                border-bottom: 1px dotted #000;
                font-weight: normal;
            }
            
            .borderless td, .borderless th {
                border: none;
            }
            
            .div-disabled{
                background-color: #e9ecef;
                border: 1px solid #dadada;
                padding: 0.375rem 0.75rem;
            }
            
            .div-res{
                 border-bottom: 1px dotted #000;
            }
            
        </style>
    </head>

    <body class="loadwindow">
        <?php include 'navbar.php'; ?>
        <section>
            <div class="container">
                
                <div class="row div-header">
                    <div class="col-md-12 div-title">
                        <?=$title;?>
                    </div>
                </div>
                
                <div class="row div-body">
                     <div class="col-md-12">
                         <form method="POST" name="action_form" id="action_form" action="">
                            <?php if($ck_error){  ?>
                                <?php if($action=="edit"){  ?>
                                    <input type="hidden" name="paym_id" value="<?=$paymid;?>" />
                                <?php } ?>
                                
                             <div class="row">
                                 <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="mb-1">เลขที่ใบสำคัญจ่าย</label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <i class="input-group-text">
                                                    <i class="icofont-numbered"></i>
                                                </i>
                                            </div>
                                            <input type="text" class="form-control" value="<?=$paym_no;?>" placeholder="เว้นว่างไว้เพื่อสร้างอัตโนมัติ" readonly>
                                        </div>
                                   </div> 
                                </div>
                            
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="mb-1">วันที่ใบสำคัญจ่าย</label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <i class="input-group-text">
                                                    <i class="icofont-ui-calendar"></i>
                                                </i>
                                            </div>
                                            <input type="date" class="form-control" value="<?=$paym_date;?>" readonly>
                                        </div>
                                   </div>
                               </div>
                             
                                
                                <?php if($action=="add"){ ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="mb-1">วันที่ทำจ่าย</label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <i class="input-group-text">
                                                    <i class="icofont-ui-calendar"></i>
                                                </i>
                                            </div>
                                            <input type="date" class="form-control" name="paym_paydate" id="paym_paydate" value="">
                                        </div>
                                    </div> 
                                </div>
                                <?php } ?>
                            
                                <div class="col-lg-1 col-md-3">
                                    <div class="form-group">
                                        <label class="mb-1">ฝ่าย</label>
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control text-center px-0" name="depname" id="depname" value="<?=$dep_name;?>" readonly>
                                        </div>
                                   </div> 
                                </div>
                                
                                <div class="col-lg-2 col-md-3">
                                    <div class="form-group">
                                        <label class="mb-1">สถานะ</label>
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" name="paymstatus" id="paymstatus" value="<?=$sts_description;?>" readonly style="background-color:#F00; color: #FFF; text-align: center; font-weight: 700;">
                                        </div>
                                   </div> 
                                </div>
                             </div>
                             
                             
                             <div class="row mt-4">
                                    <div class="col-md-3">
                                          <div class="form-group">
                                            <label class="mb-1">เลือกการชำระเงิน</label>
                                            <div class="row">
                                                <div class="col-md-4 col-sm-12">
                                                    <div class="input-group-prepend">
                                                        <div class="checkbox">
                                                            <input type="radio" name="paym_typepay" id="paym_typepay_1" value="1" onclick="onCheckedTypePay(1);" <?= ($paym_typepay==1) ? "checked" : ""; ?>  >
                                                            <label for="paym_typepay_1" class="mb-1"><span>เงินสด</span></label>
                                                        </div>
                                                    </div>
                                                 </div>
                                                    
                                                <div class="col-md-4 col-sm-12">
                                                    <div class="input-group-prepend">
                                                        <div class="checkbox">
                                                            <input type="radio" name="paym_typepay" id="paym_typepay_2" value="2" onclick="onCheckedTypePay(2);" <?= ($paym_typepay==2) ? "checked" : ""; ?> >
                                                            <label for="paym_typepay_2" class="mb-1"><span>เช็ค</span></label>
                                                        </div>
                                                     </div>
                                                </div>
                                             </div>
                                       </div> 
                                    </div>
                                    <div class="col-md-9 div-cheque <?=$cheque_display;?>">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="mb-1">เลขที่เช็ค</label>
                                                    <div class="input-group mb-2">
                                                        <input type="text" class="form-control cheque-required" name="cheq_no" id="cheq_no"  placeholder="กรุณากรอกเลขที่เช็ค" autocomplete="off" value="<?=$cheq_no;?>" <?=$cheque_required;?>>
                                                    </div>
                                                 </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="mb-1">ลงวันที่</label>
                                                    <div class="input-group mb-2">
                                                        <input type="date" class="form-control cheque-required" name="cheq_date" id="cheq_date" autocomplete="off" value="<?=$cheq_date;?>" <?=$cheque_required;?>>
                                                    </div>
                                                 </div>
                                            </div>
                                            
                                            <div class="col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label class="mb-1">ธนาคาร</label>
                                                    <select class="form-control cheque-required" name="cheq_bankid" id="cheq_bankid" <?=$cheque_required;?>>
                                                         <option value="">กรุณาเลือกธนาคาร</option>
                                                            <?=$html_option_bank;?>
                                                    </select>
                                                 </div>
                                            </div>
                                        </div>
                                    </div>
                             </div>
                             
                             
                            <div class="row mt-2">   
                                <div class="col-md-6 col-sm-12 pb-4">
                                    <label class="mb-1">ชื่อบริษัทในเครือ</label>
                                    <div class="div-disabled"><?=$comp_name;?></div>
                                </div>
                                <div class="col-md-6 col-sm-12 pb-4">
                                    <label class="mb-1">ชื่อบริษัทผู้ให้บริการ</label>
                                    <div class="div-disabled"><?=$invoice_data_action["text_paya_name_list"];?></div>
                                </div>
                            </div>  
                            
                            <div class="row mt-2">   
                                <div class="col-md-12 col-sm-12 pb-4">
                                    <label class="mb-1">ผู้จัดทำ</label>
                                    <div class="div-disabled"><?=$invoice_data_action["text_create_list"];?></div>
                                </div>
                            </div>  
                            
                             <div class="row mt-1">
                                 <div class="col-md-12">
                                    <div class="table-responsive">
                                    <table class="table table-detail table-bordered mb-0">
                                        <thead class="thead-light">
                                            <tr class="text-center">
                                                <th style="width: 70px;min-width: 70px;"></th>
                                                <th style="vertical-align: middle;width: 200px;">เลขที่ใบแจ้งหนี้</th>
                                                <th style="vertical-align: middle;min-width: 300px;">รายละเอียด</th>
                                                <th style="vertical-align: middle;width: 150px;">จำนวนเงิน</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                         <?php
                                         $html_modal_detail = "";
                                            if(!empty($invoice_data_action["arrDescription"])){
                                                foreach ($invoice_data_action["arrDescription"] as $valDescription) {
                                                    $datalist = $valDescription;
                                                    include 'variable_invoice.php'; 
                                                    $html_modal_detail .= $html_detail;
                                            ?>
                                            <tr>
                                                <td class="text-center"><?=$btn_action_view_short;?></td>
                                                <td class="text-center">
                                                    <input type="hidden" name="inv_id[<?=$valDescription["inv_id"];?>]" value=" <?=$valDescription["inv_id"];?>" />
                                                    <?=$valDescription["inv_no"];?></td>
                                                <td>
                                                    <b>บริษัท : </b><?=$valDescription["paya_name"];?> <br>
                                                    <b>รายการ : </b><?=$valDescription["inv_description"];?>
                                                </td>
                                                <td class="text-right"><?=__price($valDescription["inv_netamount"]);?></td>
                                            </tr>
                                            <?php }} ?>
                                            
                                        </tbody>
                                      </table>
                                      <?=$html_modal_detail;?>
                                      
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="text-right" colspan="3">
                                                            <b>รวมเงินทั้งหมด</b>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-right" style="vertical-align: middle; padding: .25rem" colspan="2">
                                                            <b>จำนวนเงินก่อน : </b>
                                                        </td>
                                                        <td width="20%" style="padding: .25rem">
                                                            <input type="text" class="form-control text-right"  value="<?=$invoice_data_action["text_subtotal"];?>" readonly>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-right" style="vertical-align: middle;">
                                                            <b>ภาษีมูลค่าเพิ่ม :</b>
                                                        </td>
                                                        <td width="10%" style="padding: .25rem">
                                                            <input type="text" class="form-control text-right" value="<?= ($invoice_data_action["text_vatpercent"]!="") ? $invoice_data_action["text_vatpercent"] : "0.00";?>" readonly>
                                                        </td>
                                                        <td width="20%" style="padding: .25rem">
                                                            <input type="text" class="form-control text-right"  value="<?= ($invoice_data_action["text_vat"]!="") ? $invoice_data_action["text_vat"] : "0.00";?>" readonly>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-right" style="vertical-align: middle;" colspan="2">
                                                            <b>หักภาษี ณ ที่จ่าย :</b>
                                                        </td>
                                                        <td width="20%" style="padding: .25rem">
                                                            <input type="text" class="form-control text-right" name="" value="<?= ($invoice_data_action["text_totaltax_all"]!="") ? $invoice_data_action["text_totaltax_all"] : "0.00";?>" readonly>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-right" colspan="2" style="vertical-align: middle; padding: .25rem">
                                                            <b>จำนวนเงินรวม :</b><br><br>
                                                            <b></b><br>
                                                            <b>ส่วนต่าง + / - :</b>
                                                        </td>
                                                        <td style="text-align: center; padding: .25rem">
                                                            <input type="text" class="form-control text-right" name="" value="<?=$invoice_data_action["text_grandtotal"];?>" readonly>
                                                            <b >+</b>
                                                            <input type="text" class="form-control text-right" name="" value="<?=$invoice_data_action["text_difference"];?>" readonly>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-right" style="vertical-align: middle; padding: .25rem" colspan="2">
                                                            <b>ยอดชำระสุทธิ : </b>
                                                        </td>
                                                        <td width="20%" style="padding: .25rem">
                                                            <input type="text" class="form-control text-right" name="" value="<?=$invoice_data_action["text_total_netamount"];?>" readonly>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                             <div class="row mt-1">
                                <div class="col-md-12">
                                    <table class="table table-detail table-bordered border-black">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="text-center">รายการ</th>
                                                <th width="20%" class="text-center">เจ้าหนี้</th>
                                                <th width="20%" class="text-center">ลูกหนี้</th>
                                            </tr>
                                        </thead>
                                       <tbody>
                                        <?php
                                        for ($i=1; $i <=5 ; $i++) { 
                                        ?>
                                        <tr>
                                            <td class="tdborder-black black-left black-bottom"></td>
                                            <td class="tdborder-black black-bottom"></td>
                                            <td class="tdborder-black black-bottom"></td>
                                        </tr>
                                        <?php } ?>
                                        </tbody>
                                      </table>
                                 </div>
                             </div>
                             
                          <div class="row mt-1">   
                            <div class="col-md-12 col-sm-12 pb-4">
                                <div class="table-responsive">
                                    <table class="table" style="background-color: #e9ecef;">
                                        <tbody>
                                            <tr>
                                                <td width="50px" style="border-top: none; vertical-align: middle;"><b>หมายเหตุ</b></td>
                                                <td style="border-top: none;">
                                                    <input type="text" class="form-control" name="paym_note" id="paym_note" autocomplete="off" value="<?=$paym_note;?>">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                          </div>     
                                 
                             <div class="row mt-1">  
                                 <div class="col-md-12 col-sm-12 pb-4">
                                     <?php
                                     $arrSign = __invoice_payment_sign($invoice_data_action["inv_id_main"]);
                                      $count_sign = count($arrSign);
                                      $footer_width = (705/$count_sign);   
                                      $table_sign = "<table border='0' style='width:100%' cellpadding='0' cellspacing='0' >";
                                      $table_sign .= "<tr>";
                                      foreach ($arrSign as $keySign => $valSign) {
                                          $table_sign .= "<td style='width:".$footer_width."px'>";
                                                $table_sign .= "<table border='0' style='width:100%' class='table-sign' cellpadding='0' cellspacing='10' >";
                                                    $table_sign .= "<tr><td colspan='2' class='text-center line-dotted' style='width:".$valSign["position_w"]."px;'>".$valSign["name"]."</td><tr>";
                                                    $table_sign .= "<tr><td style='width:".($valSign["position_w"]+40)."px;'><br><b>".$valSign["position"]." วันที่</b></td><td class='line-dotted text-center'><div style='width:100%;'>".$valSign["date"]."</div></td><tr>";
                                                $table_sign .= "</table>";
                                            $table_sign .= "</td>";
                                            
                                              $table_sign .= "<td style='width:20px'></td>";
                                              
                                            
                                      }
                                     $table_sign .= "</tr>";
                                    $table_sign .= "</table>";
                                     ?>
                                     
                                     <?=$table_sign;?>
                               </div>      
                              </div>
                              
                              
                             <?php }else{ ?>
                                 <font color="red"><?=$text_error;?></font>
                             <?php } ?>
                         </form>
                     </div>
                </div>
                  <?php if($ck_error){  ?>
                <div class="row div-footer">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-success  btn-action bt_save" onclick="onSave(1)"><i class="icofont-save"></i> บันทึกข้อมูล</button>
                    </div>
                </div>
                 <?php } ?>
            </div>
        </section>
        
        <div id="modalSaveForm" class="modal fade modal-detail" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" >
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title py-2"><i class="icofont-save"></i> คุณต้องการบันทึกข้อมูลใช่หรือไม่?</h3>
                    </div>
                    <div class="modal-body" id="saveForm">
                 </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-close" data-dismiss="modal" aria-hidden="true"><i class="icofont-close"></i> ไม่บันทึกข้อมูล</button>
                    <button type="button" class="btn btn-success  btn-action btn-action-save" onclick="onSave(2)"><i class="icofont-save"></i> บันทึกข้อมูล</button>
                 </div>
                </div>
            </div>
        </div>
            
           <script>
                function base_url(){
                    return "r_fetch_payment.php?cid=<?=$paramurl_company_id;?>&dep=<?=$paramurl_department_id;?>";
                }
                
                function contentPdf(preview_url=""){
                    if(preview_url!=""){
                        return '<div class="col-md-12"><object data="'+preview_url+'#toolbar=0&navpanes=0&scrollbar=0" type="application/pdf" width="100%" height="500" style="border: 1px solid"></object><div>';
                    }
                }
                
                
                function onCheckedTypePay(typepay=0){
                    var div = $(".div-cheque");
                    var input_required =  $(".cheque-required"); 
                    if(typepay==1){
                        div.addClass("d-none");
                        input_required.prop("required",false);
                    }else if(typepay==2){
                        div.removeClass("d-none");
                        input_required.prop("required",true);
                        input_required[0].focus();
                    }
                }
                
                
               function onSave(step=1){
                    var form_name = "action_form";
                    var form     = $('#'+form_name);
                    var validator = form.validate();
                    var formData = new FormData(document.forms.namedItem(form_name));
                    var ck_valid     = $("#"+form_name).valid();    
                    var bt_save =  $(".bt_save"); 
                    
                    if(ck_valid == true){
                         if(step==1){
                            var modalSave = $("#modalSaveForm");
                            var div = $("#saveForm");
                        
                            div.html("<br><div align='center'>กรุณารอสักครู่...<br><br><i class='icofont-spinner icofont-spin' style='font-size:24px'></i></div>");
                            modalSave.modal('show');
                            
                            $.ajax({
                                type: 'POST',
                                url: base_url()+"&action=preview_action",
                                data: formData,
                                dataType: 'json',
                                contentType: false,
                                cache: false,
                                processData:false,
                                success:function(data){
                                    var preview_url = data.preview_url;
                                    var preview_path = data.preview_path;
                                    var content = '<object data="'+preview_url+'#toolbar=0&navpanes=0&scrollbar=0" type="application/pdf" width="100%" height="500" style="border: 1px solid"></object>';
                                     
                                     div.html(content);
                                  }
                            }); 
                         }else{
                             var saveForm = function(){
                                var error_text = "";
                                var modalSave = $("#modalSaveForm");
                                var divModal = $("#saveForm");
                                var divTitle = $(".div-title");
                                var divBody = $(".div-body");
                                var divFooter = $(".div-footer");
                                   
                                 $.ajax({
                                    type: "POST",
                                    url: base_url()+"&action=save_<?=$action;?>",
                                    data: formData,
                                    cache:false,
                                    contentType: false,
                                    processData: false,
                                    success: function(data){
                                        if(IsJsonString(data)==true){
                                            var res = JSON.parse(data);  
                                            var response = res.response;
                                            var message = res.message;
                                            var paym_id = res.paym_id;
                                            var paym_no = res.paym_no;
                                            var cheque_id  = res.cheque_id;
                                            var cheque_no  = res.cheque_no;
                                            var paym_typepay = $("input[name='paym_typepay']:checked").val();
                                             
                                             
                                            if(response=="S"){
                                                var html_title = '<font class="text-success"><i class="icofont-check"></i> บันทึกข้อมูลสำเร็จ</font>';
                                                
                                                var html_body = "";
                                                html_body += '<div class="col-md-12"><h4>ใบสำคัญจ่าย (เลขที่ '+paym_no+')</h4></div>';
                                                html_body += contentPdf("export/payment_pdf.php?paymid="+paym_id);
                                                 
                                                if(paym_typepay==2){
                                                    html_body += '<hr><div class="col-md-12"><h4>เช็ค (เลขที่ '+cheque_no+')</h4></div>';
                                                    html_body += contentPdf("export/cheque_pdf.php?cheque="+cheque_id+"&payment="+paym_id);
                                                }
                                                
                                                var url_back = "payment.php?cid=<?=$paramurl_company_id;?>&dep=<?=$paramurl_department_id;?>";
                                                var html_footer = ' <div class="col-md-12"><a href="'+url_back+'" type="button" class="btn btn-warning"><i class="icofont-arrow-left"></i> ย้อนกลับ</a></div>';
                                                
                                                modalSave.modal('hide');
                                                divModal.html('');
                                                divTitle.html(html_title);
                                                divBody.html(html_body);
                                                divFooter.html(html_footer);
                                            }else{
                                                error_text = message
                                            }
                                        }else{
                                             error_text = "กรุณาลองใหม่อีกครั้ง หรือติดต่อโปรแกรมเมอร์";
                                        }   
                                            
                                        if(error_text!=""){
                                            swal_fail({text:error_text,btnclose:1});
                                        }else{
                                            swal_success({title:"บันทึกข้อมูลสำเร็จ",text:"กรุณารอสักครู่..."});
                                        }
                                    }
                                });
                            }
                            
                           swal_save({title:"คุณต้องการบันทึกข้อมูลใช่หรือไม่?",fn:saveForm,btnclose:1});
                         }       
                    }else{
                        validator.focusInvalid();
                        swal_fail({title:"กรุณากรอกข้อมูล",text:" ",btnclose:1});
                    }  
               }
               
           </script>

        <?php include 'footer.php'; ?>
    </body>
</html>
