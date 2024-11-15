<?php
include 'config/config.php';
__check_login();

$user_id = __session_user("id");
$user_level_id = __session_user("level_id");
$user_department_id = __session_user("department_id");

$paramurl_company_id = (isset($_GET["cid"])) ? $_GET["cid"] : 0;
$paramurl_department_id = 0;

$authority_comp_count_dep = __authority_company_count_department($user_id, $paramurl_company_id);
$authority_dep_text_list = __authority_department_text_list($user_id, $paramurl_company_id);
$authority_dep_check = __authority_department_check($user_id, $paramurl_company_id, $paramurl_department_id);
$arrDepAll = __authority_department_list($user_id, $paramurl_company_id);

$invoice_step = __invoice_step_company_list($user_id, $paramurl_company_id, $paramurl_department_id);
$html_title = '<b>รายจ่าย</b><i class="icofont-caret-right"></i> ใบแจ้งหนี้ <i class="icofont-caret-right"></i> เลือกฝ่าย';
$arrInvoiceStep = $invoice_step;
$valueInvoiceStep = $arrInvoiceStep["invoice"];
$invoice_step_name = $valueInvoiceStep["name"];
$invoice_step_class_box = $valueInvoiceStep["class_box"];
$invoice_step_icon = $valueInvoiceStep["icon"];
$invoice_step_page = $valueInvoiceStep["page"];
$invoice_step_con_where = $valueInvoiceStep["query_where"];
$html_dep_box = __html_dep_box($html_title, $invoice_step_page, $invoice_step_icon, " AND " . $invoice_step_con_where, $arrDepAll, str_replace("FROM", "", __invoice_query_from()), "i.inv_depid");

__page_seldep($html_dep_box);
?>
<!DOCTYPE html>
<html>

<head>
    <?php include 'head.php'; ?>
    <link rel="stylesheet" type="text/css" href="css/checkbox.css">
    <style>
        .checkbox-wrapper-18 .round {
            position: relative;
        }

        .checkbox-wrapper-18 .round label {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 50%;
            cursor: pointer;
            height: 28px;
            width: 28px;
            display: block;
        }

        .checkbox-wrapper-18 .round label:after {
            border: 2px solid #fff;
            border-top: none;
            border-right: none;
            content: "";
            height: 6px;
            left: 8px;
            opacity: 0;
            position: absolute;
            top: 9px;
            transform: rotate(-45deg);
            width: 12px;
        }

        .checkbox-wrapper-18 .round input[type="checkbox"] {
            visibility: hidden;
            display: none;
            opacity: 0;
        }

        .checkbox-wrapper-18 .round input[type="checkbox"]:checked+label {
            background-color: #66bb6a;
            border-color: #66bb6a;
        }

        .checkbox-wrapper-18 .round input[type="checkbox"]:checked+label:after {
            opacity: 1;
        }

        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }


        input[type="number"] {
            -moz-appearance: textfield;
        }
    </style>

    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>
    <section>
        <div class="container">
            <form method="POST" name="frmAddInvoice" id="frmAddInvoice" enctype="multipart/form-data">
                <div class="row py-4 px-1" style="background-color: #E9ECEF">
                    <div class="col-md-12">
                        <h3 class="mb-0">
                            <i class="icofont-plus-circle"></i>&nbsp;&nbsp;เพิ่มรายการเพื่อออกใบแจ้งหนี้
                        </h3>
                    </div>
                </div>

                <div class="row py-4 px-1" style="background-color: #FFFFFF;" id="form-create">

                    <div class="col-lg-6 col-md-8 pt-1 pb-3">
                        <label for="searchCompany" class="mb-1">เล่มที่/เลขที่</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <i class="input-group-text">
                                    <i class="icofont-company"></i>
                                </i>
                            </div>
                            <input type="text" name="invoice_number" class="form-control input" placeholder="กรอกเลขใบวางบิล" autocomplete="off" />
                        </div>
                    </div>


                    <input type="text" name="id" class="form-control input d-none" />
                    <div class="col-lg-6 col-md-8 pt-1">
                        <label for="searchCompany" class="mb-1">ชื่อรายการ</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <i class="input-group-text">
                                    <i class="icofont-company"></i>
                                </i>
                            </div>
                            <input type="text" name="list_name" class="form-control input" placeholder="กรอกชื่อรายการ" autocomplete="off" />
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-4 pt-1">
                        <label for="invdate" class="mb-1">วันที่ใบรายการ</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <i class="input-group-text">
                                    <i class="icofont-ui-calendar"></i>
                                </i>
                            </div>
                            <input type="date" class="form-control input" name="date_list" id="" autocomplete="off">
                        </div>
                        <span style="color: #F00;padding-top: 2px" id=""></span>
                    </div>

                    <div class="col-lg-9 col-md-8 pt-1">
                        <label for="searchCompany" class="mb-1">อัพโหลดใบวางบิล</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                            </div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                                <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                        <div class="row">
                            <div class="col-lg-1 col-md-1 col-sm-1 mr-4">
                                <label class="salary-tax"></label>
                                <div class="input-group-prepend">
                                    <div class="checkbox">
                                        <input type="radio" id="tax" class="input" name="tax" value="1">
                                        <label for="tax"><span style="width: 100px;">ขอคืนภาษี</span></label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-1 col-md-1 col-sm-1">
                                <label class="salary-tax"></label>
                                <div class="input-group-prepend">
                                    <div class="checkbox">
                                        <input type="radio" id="no-tax" class="input" name="tax" value="0">
                                        <label for="no-tax"><span style="width: 100px;">ไม่ขอคืนภาษี</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-12 pt-1 pb-3"></div>

                    <div class="col-lg-3 col-md-3 col-sm-12 pt-1 pb-3">
                        <label for="invsub" class="mb-1">จำนวนเงิน</label>
                        <div class="input-group">
                            <input type="number" class="form-control text-right input amount" id="amount" name="amount" autocomplete="off" value="0.00">
                            <div class="input-group-append">
                                <i class="input-group-text">
                                    <i class="icofont-baht"></i>
                                </i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-5 pt-1 pb-3">
                        <label for="invperc" class="mb-1">%</label>
                        <div class="input-group">
                            <input type="number" class="form-control text-right input amount" id="percent_vat" name="percent_vat" autocomplete="off" value="0.00">
                            <div class="input-group-append">
                                <i class="input-group-text">
                                    <i class="icofont-sale-discount"></i>
                                </i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-7 pt-1 pb-3">
                        <label for="invvat" class="mb-1">ภาษีมูลค่าเพิ่ม</label>
                        <div class="input-group">
                            <input type="number" class="form-control text-right input" id="vat" name="vat" autocomplete="off" value="0.00" readonly>
                            <div class="input-group-append">
                                <i class="input-group-text">
                                    <i class="icofont-baht"></i>
                                </i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-5 pt-1 pb-3">
                        <label for="invvat" class="mb-1">+ / -</label>
                        <div class="input-group">
                            <input type="number" class="form-control text-right diff input" name="diff_vat" id="" autocomplete="off" value="0.00" readonly>
                            <div class="input-group-append">
                                <i class="input-group-text">
                                    <i class="icofont-baht"></i>
                                </i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-5 pt-1 pb-3"></div>
                    <div class="col-lg-1 col-md-2 col-sm-7 pt-1 pb-3 pl-5">
                        <label for="" class=""></label>
                        <div class="checkbox-wrapper-18 mt-2">
                            <div class="round">
                                <input type="checkbox" class="check" id="checkbox-18" value="1"/>
                                <label for="checkbox-18" id="label-18"></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-12 pt-1 pb-3">
                        <label for="invsub" class="mb-1">จำนวนเงินหักภาษี ณ ที่จ่าย</label>
                        <div class="input-group">
                            <input type="number" class="form-control text-right tax input" id="tax_amount" name="tax_amount" autocomplete="off" value="0.00" readonly>
                            <div class="input-group-append">
                                <i class="input-group-text">
                                    <i class="icofont-baht"></i>
                                </i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-5 pt-1 pb-3">
                        <label for="invperc" class="mb-1">%</label>
                        <div class="input-group">
                            <input type="number" class="form-control text-right input" id="tax_percent" name="tax_percent" autocomplete="off" value="1.00" readonly>
                            <div class="input-group-append">
                                <i class="input-group-text">
                                    <i class="icofont-sale-discount"></i>
                                </i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-7 pt-1 pb-3">
                        <label for="invvat" class="mb-1"> หักภาษี ณ ที่จ่าย </label>
                        <div class="input-group">
                            <input type="number" class="form-control text-right input" id="tax_total" name="tax_total" autocomplete="off" value="0.00" readonly>
                            <div class="input-group-append">
                                <i class="input-group-text">
                                    <i class="icofont-baht"></i>
                                </i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-5 pt-1 pb-3">
                        <label for="invvat" class="mb-1">+ / -</label>
                        <div class="input-group">
                            <input type="number" class="form-control text-right diff input" name="diff_tax" id="" autocomplete="off" value="0.00" readonly>
                            <div class="input-group-append">
                                <i class="input-group-text">
                                    <i class="icofont-baht"></i>
                                </i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-5 pt-1 pb-3"></div>
                    <div class="col-lg-1 col-md-2 col-sm-7 pt-1 pb-3 pl-5">
                        <div class="checkbox-wrapper-18 mt-2">
                            <div class="round">
                                <input type="checkbox" class="check" id="checkbox-19" value="3" />
                                <label for="checkbox-19" id="label-19"></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-12 pt-1 pb-3">
                        <div class="input-group">
                            <input type="number" class="form-control text-right tax input" id="tax_amount2" name="tax_amount2" autocomplete="off" value="0.00" readonly>
                            <div class="input-group-append">
                                <i class="input-group-text">
                                    <i class="icofont-baht"></i>
                                </i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-5 pt-1 pb-3">
                        <div class="input-group">
                            <input type="number" class="form-control text-right input" id="tax_percent2" name="tax_percent2" autocomplete="off" value="3.00" readonly>
                            <div class="input-group-append">
                                <i class="input-group-text">
                                    <i class="icofont-sale-discount"></i>
                                </i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-7 pt-1 pb-3">
                        <div class="input-group">
                            <input type="number" class="form-control text-right input" id="tax_total2" name="tax_total2" autocomplete="off" value="0.00" readonly>
                            <div class="input-group-append">
                                <i class="input-group-text">
                                    <i class="icofont-baht"></i>
                                </i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-5 pt-1 pb-3">
                        <div class="input-group">
                            <input type="number" class="form-control text-right diff input" name="diff_tax2" id="" autocomplete="off" value="0.00" readonly>
                            <div class="input-group-append">
                                <i class="input-group-text">
                                    <i class="icofont-baht"></i>
                                </i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-5 pt-1 pb-3"></div>
                    <div class="col-lg-1 col-md-2 col-sm-7 pt-1 pb-3 pl-5">
                        <div class="checkbox-wrapper-18 mt-2">
                            <div class="round">
                                <input type="checkbox" class="check" id="checkbox-20" value="5" />
                                <label for="checkbox-20" id="label-20"></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-12 pt-1 pb-3">
                        <div class="input-group">
                            <input type="number" class="form-control text-right tax input" id="tax_amount3" name="tax_amount3" autocomplete="off" value="0.00" readonly>
                            <div class="input-group-append">
                                <i class="input-group-text">
                                    <i class="icofont-baht"></i>
                                </i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-5 pt-1 pb-3">
                        <div class="input-group">
                            <input type="number" class="form-control text-right input" id="tax_percent3" name="tax_percent3" autocomplete="off" value="5.00" readonly>
                            <div class="input-group-append">
                                <i class="input-group-text">
                                    <i class="icofont-sale-discount"></i>
                                </i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-7 pt-1 pb-3">
                        <div class="input-group">
                            <input type="number" class="form-control text-right input" id="tax_total3" name="tax_total3" autocomplete="off" value="0.00" readonly>
                            <div class="input-group-append">
                                <i class="input-group-text">
                                    <i class="icofont-baht"></i>
                                </i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-5 pt-1 pb-3">
                        <div class="input-group">
                            <input type="number" class="form-control text-right diff input" name="diff_tax3" id="" autocomplete="off" value="0.00" readonly>
                            <div class="input-group-append">
                                <i class="input-group-text">
                                    <i class="icofont-baht"></i>
                                </i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-3 col-sm-12 pt-1 pb-3">
                        <!-- <label for="invsub" class="mb-1">จำนวนเงินทั้งหมด</label>
                        <div class="input-group">
                            <input type="text" class="form-control text-right" id="" name="" autocomplete="off" value="0.00" readonly>
                            <div class="input-group-append">
                                <i class="input-group-text">
                                    <i class="icofont-baht"></i>
                                </i>
                            </div>
                        </div> -->
                    </div>

                    <div class="col-lg-4 col-md-3 col-sm-12 col-xs-12 mr-auto pt-1 pb-3">
                        <label for="invnet" class="mb-1">ยอดชำระสุทธิ</label>
                        <div class="input-group">
                            <input type="number" class="form-control text-right input" id="total" name="total" autocomplete="off" value="0.00" readonly>
                            <div class="input-group-append">
                                <i class="input-group-text">
                                    <i class="icofont-baht"></i>
                                </i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 pt-1">
                        <input type="button" class="btn btn-secondary px-3 py-2 mx-1" data-type="create" id="insert_form" value="บันทึกข้อมูล">
                        <input type="button" class="btn btn-secondary px-3 py-2 mx-1" data-type="reset" id="reset" value="รีเซ็ทข้อมูล">
                    </div>
                </div>
            </form>
            <!-- <div class="row div-tabs">
            <div class="col-12">
                <ul id="tabs" class="nav nav-tabs nav-fill">
                    <li style="background-color: #E9ECEF;" class="nav-item border">
                        <div class="nav-link p-3"><i class="icofont-plus"></i> รายการที่ยังไม่ได้ออกใบแจ้งหนี้</div>
                    </li>
                    <li style="background-color: #E9ECEF;" class="nav-item border">
                        <div class="nav-link p-3"><i class="icofont-tick-boxed"></i> รายการที่ยังออกใบแจ้งหนี้</div>
                    </li>
                </ul>
            </div>
        </div> -->
            <table class="table table-bordered">
                <thead class="table-dark">
                    <!-- <tr>
                        <th scope="col" width="5%">ลำดับ</th>
                        <th scope="col" width="10%" class="text-center">วันที่ใบรายการ</th>
                        <th scope="col" rowspan="2" colspan="3">วันที่ใบรายการ</th>
                        <th scope="col" width="20%" class="text-center">ชื่อรายการ</th>
                        <th scope="col" width="7%" class="text-center">ขอคืนภาษี</th>
                        <th scope="col" width="20%" class="text-center">จำนวนเงิน</th>
                        <th scope="col" width="5%" class="text-center">ภาษี(%)</th>
                        <th scope="col" width="10%" class="text-center">ภาษีมูลค่าเพิ่ม</th>
                        <th scope="col" width="10%" class="text-right">รวม</th>
                    </tr> -->
                    <tr>
                        <th width="5%" class="align-middle text-center" rowspan="2">ลำดับ</th>
                        <th width="" class="align-middle text-center" rowspan="2">วันที่ใบรายการ</th>
                        <th width="20%" class="align-middle text-center" rowspan="2">ชื่อรายการ</th>
                        <th width="" class="align-middle text-center" rowspan="2">ขอคืนภาษี</th>
                        <th width="" class="align-middle text-center" rowspan="2">จำนวนเงิน</th>
                        <th width="" class="text-center" colspan="2">ภาษีมูลค่าเพิ่ม</th>
                        <th width="" class="text-center" colspan="3">หักภาษี ณ ที่จ่าย</th>
                        <th width="" class="align-middle text-center" rowspan="2">จำนวนยอดสุทธิ</th>
                    </tr>
                    <tr>
                        <th scope="col" class="text-center">%</th>
                        <th scope="col" class="text-center">รวม</th>
                        <th scope="col" class="text-center">1%</th>
                        <th scope="col" class="text-center">3%</th>
                        <th scope="col" class="text-center">5%</th>
                    </tr>

                </thead>
                <tbody class="table-group-divider" id="showdata">
                </tbody>
            </table>
            <nav class="pb-1" aria-label="...">
                <ul class="pagination">

                    <!-- <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1">Previous</a>
                </li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item active">
                    <a class="page-link" href="#">2 <span class="sr-only">(current)</span></a>
                </li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">...</a></li>

                <li class="page-item"><a class="page-link" href="#">5</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                </li> -->
                </ul>
            </nav>
        </div>
    </section>

    <div id="loading" class=""></div>

    <script>
        const url = new URL(window.location.href);
        const params = new URLSearchParams(url.search);
        const cid = params.get('cid');
        const dep = params.get('dep');
        var today = new Date().toISOString().split('T')[0];
        var page = 1
        $("input[name='date_list']").attr("type", "date").val(today);
        var tax1, tax2, tax3 = 0

        function pageLink(count) {

            $(".pagination").html("")
            html = ''

            html += `<li class="page-item ${page == 1 ? 'disabled' : ''}">
                    <a class="page-link" tabindex="-1">Previous</a>
                </li>`

            for (let i = 0; i < count; i++) {
                html += `<li class="page-item ${page - 1 == i ? 'active' : ''}"><a class="page-link">${i + 1}</a></li>`

                // if (i < page + 2) {
                //     html += `<li class="page-item ${page - 1 == i ? 'active' : ''}"><a class="page-link">${i + 1}</a></li>`
                // } else if (i == page + 2) {
                //     html += `<li class="page-item disabled"><a class="page-link">...</a></li>`
                // } else if (i > count - 2) {
                //     html += `<li class="page-item ${page - 1 == i ? 'active disabled' : ''}"><a class="page-link">${i + 1}</a></li>`
                // }
            }

            html += `<li class="page-item ${page == count ? 'disabled' : ''}">
                    <a class="page-link" >Next</a>
                </li>`

            $(".pagination").append(html)
        }

        function clear(mode = "") {
            if (mode !== "create") {
                $("#form-create input[name='list_name']").val("")
            }

            $("#form-create input[name='id']").val("")
            $("#form-create input[name='invoice_number']").val("")
            $("#form-create input[name='date_list']").attr("type", "date").val(today);
            $("#form-create input[name='tax']").prop("checked", false);
            $("#form-create input[name='amount']").val("0.00")
            $("#form-create input[name='percent_vat']").val("0.00")
            $("#form-create input[name='vat']").val("0.00")
            $("#form-create input[name='total']").val("0.00")
            $("#form-create input[name='tax_amount']").val("0.00")
            $("#form-create input[name='tax_amount']").attr("readonly", true)
            $("#form-create input[name='tax_amount2']").val("0.00")
            $("#form-create input[name='tax_amount2']").attr("readonly", true)
            $("#form-create input[name='tax_amount3']").val("0.00")
            $("#form-create input[name='tax_amount3']").attr("readonly", true)
            $("#form-create input[name='tax_percent']").val("1.00")
            $("#form-create input[name='tax_percent2']").val("3.00")
            $("#form-create input[name='tax_percent3']").val("5.00")
            $("#form-create input[name='tax_total']").val("0.00")
            $("#form-create input[name='tax_total2']").val("0.00")
            $("#form-create input[name='tax_total3']").val("0.00")
            $("#form-create input[name='diff_vat']").val("0.00")
            $("#form-create input[name='diff_vat']").attr("readonly", true)
            $("#form-create input[name='diff_tax']").val("0.00")
            $("#form-create input[name='diff_tax']").attr("readonly", true)
            $("#form-create input[name='diff_tax2']").val("0.00")
            $("#form-create input[name='diff_tax2']").attr("readonly", true)
            $("#form-create input[name='diff_tax3']").val("0.00")
            $("#form-create input[name='diff_tax3']").attr("readonly", true)
            $("#checkbox-18").prop('checked', false)
            $("#checkbox-19").prop('checked', false)
            $("#checkbox-20").prop('checked', false)
            $("#insert_form").val("บันทึกข้อมูล")
            $("#insert_form").attr("data-type", "create")
            $("#reset").val("รีเซ็ทข้อมูล")
            $("#reset").attr("data-type", "reset")
            $('#inputGroupFile01').val('');
            $('.custom-file-label').text('Choose file');;
        }

        function showdata(data) {
            $("#showdata").html("")
            html = ''

            for (let i = 0; i < data.length; i++) {
                check = data[i]['invoice_list_return_vat'] == "0" ? "X" : "/";
                html += `<tr class="table-item">
                    <th scope="row" class="text-center">${((page - 1) * 10) + (i + 1)}</th>
                    <td class="text-center d-none" name="id" data-type="${data[i]['invoice_list_id']}">${data[i]['invoice_list_id']}</td>
                    <td class="text-center d-none" name="invoice_number" data-type="${data[i]['invoice_list_number']}">${data[i]['invoice_list_number']}</td>

                    <td class="d-none" name="tax_amount" data-type="${data[i]['invoice_list_tax_amount']}">${data[i]['invoice_list_tax_amount']}</td>
                    <td class="d-none" name="tax_percent" data-type="${data[i]['invoice_list_tax_percent']}">${data[i]['invoice_list_tax_percent']}</td>
                    <td class="d-none" name="tax_amount2" data-type="${data[i]['invoice_list_tax_amount2']}">${data[i]['invoice_list_tax_amount2']}</td>
                    <td class="d-none" name="tax_percent2" data-type="${data[i]['invoice_list_tax_percent2']}">${data[i]['invoice_list_tax_percent2']}</td>
                    <td class="d-none" name="tax_amount3" data-type="${data[i]['invoice_list_tax_amount3']}">${data[i]['invoice_list_tax_amount3']}</td>
                    <td class="d-none" name="tax_percent3" data-type="${data[i]['invoice_list_tax_percent3']}">${data[i]['invoice_list_tax_percent3']}</td>
                    <td class="d-none" name="diff_vat" data-type="${data[i]['invoice_list_diff_vat']}">${data[i]['invoice_list_diff_vat']}</td>
                    <td class="d-none" name="diff_tax" data-type="${data[i]['invoice_list_diff_tax']}">${data[i]['invoice_list_diff_tax']}</td>
                    <td class="d-none" name="diff_tax2" data-type="${data[i]['invoice_list_diff_tax2']}">${data[i]['invoice_list_diff_tax2']}</td>
                    <td class="d-none" name="diff_tax3" data-type="${data[i]['invoice_list_diff_tax3']}">${data[i]['invoice_list_diff_tax3']}</td>

                    <td class="text-center" name="date_list" data-type="${data[i]['invoice_list_date']}">${convertDateThai(data[i]['invoice_list_date'])}</td>
                    <td class="text-center" name="list_name" data-type="${data[i]['invoice_list_desc']}">${data[i]['invoice_list_desc']}</td>
                    <td class="text-center" name="tax" data-type="${check}">${check}</td>
                    <td class="text-right" name="amount" data-type="${data[i]['invoice_list_amount']}">${numberFormat(data[i]['invoice_list_amount'], 2)}</td>
                    <td class="text-right" name="percent_vat" data-type="${data[i]['invoice_list_percent_vat']}">${numberFormat(data[i]['invoice_list_percent_vat'], 2)}</td>
                    <td class="text-right" name="vat" data-type="${data[i]['invoice_list_vat']}">${numberFormat(data[i]['invoice_list_vat'], 2)}</td>
                    <td class="text-right" name="tax_total" data-type="${data[i]['invoice_list_tax_total']}">${numberFormat(data[i]['invoice_list_tax_total'], 2)}</td>
                    <td class="text-right" name="tax_total2" data-type="${data[i]['invoice_list_tax_total2']}">${numberFormat(data[i]['invoice_list_tax_total2'], 2)}</td>
                    <td class="text-right" name="tax_total3" data-type="${data[i]['invoice_list_tax_total3']}">${numberFormat(data[i]['invoice_list_tax_total3'], 2)}</td>
                    <td class="text-right" name="total" data-type="${data[i]['invoice_list_total']}">${numberFormat(data[i]['invoice_list_total'], 2)}</td>
                </tr>`
            }

            $("#showdata").append(html)
        }

        function formatAmount(check_amount) {
            const new_value = check_amount
                .replace(/[^0-9.]/g, '')
                .replace(/(\..*?)\..*/g, '$1');

            return new_value;
        }

        function convertDateThai(date) {

            month_th = ["", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค."];

            data = date.split("-");
            y = Number(data[0]) + 543
            m = month_th[Number(data[1])]
            d = Number(data[2])
            y = y.toString().substr(2, 4)


            return `${d} ${m} ${y}`

        }

        function checkAmountNumber(check_amount) {
            new_value = ''
            count = 0

            for (let i = 0; i < check_amount.length; i++) {
                if (!isNaN(check_amount[i]) || check_amount[i] == ".") {
                    if (check_amount[i] == "." && count == 0) {
                        new_value += check_amount[i];
                        count++;
                    } else if (check_amount[i] == "." && count > 0) {
                        continue;
                    } else {
                        new_value += check_amount[i];
                    }
                }
            }

            return new_value
        }

        function fetch(page = 1) {
            $.ajax({
                url: "list_invoice_action.php?cid=" + cid + "&dep=" + dep,
                method: "POST",
                data: {
                    "action": "get_all_list",
                    page
                },
                dataType: "json",
                beforeSend: function() {
                    $("#loading").addClass("loading")
                },
                success: function(data) {
                    if (data['status'] == "success") {
                        if (data['count'] == 0) {
                            swal({
                                title: "ไม่มีข้อมูลที่จะแสดง",
                                text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
                                type: "warning",
                                closeOnClickOutside: false
                            });
                        } else {
                            showdata(data['data'])
                            pageLink(data['count'])
                        }
                    }
                },
                error: function(error) {
                    console.error("Error fetching data:", error);
                },
                complete: function() {
                    $("#loading").removeClass("loading")
                }
            });
        }

        $(document).ready(function() {
            fetch()
        });

        $(document).on("click", '.page-item', function() {
            page = Number($(this).text())
            $(this).addClass("active").siblings().removeClass("active");
            fetch(page)
        });

        $(document).on("click", '.table-item', function() {
            const currentColor = $(this).css("background-color");
            const paleGoldenRod = "rgb(238, 221, 130)";

            if (currentColor === paleGoldenRod) {
                $(this).css("background-color", "");
                clear()

            } else {
                clear()
                $(this).css("background-color", paleGoldenRod);
                $(this).siblings().css({
                    "background-color": ""
                });

                $("#insert_form").val("อัพเดทข้อมูล")
                $("#insert_form").attr("data-type", "update")

                $("#reset").val("ลบข้อมูล")
                $("#reset").attr("data-type", "delete")

                let ele = $(this).find("td")
                let form = $("#form-create").find(".input")

                for (let i = 0; i < ele.length; i++) {
                    let _attr = $(ele[i]).attr("name")
                    let _val = $(ele[i]).attr("data-type")

                    if (_attr == "tax") {
                        let inputWithName = $('#form-create input:radio[name="' + _attr + '"]');
                        _val == '/' ? $(inputWithName[0]).prop("checked", true) : $(inputWithName[1]).prop("checked", true);

                    } else {
                        let inputWithName = form.filter("[name='" + _attr + "']");
                        $(inputWithName).val(_val)
                    }

                    if (_attr == "tax_amount" || _attr == "tax_amount2" || _attr == "tax_amount3" || _attr == "percent_vat") {
                        if (_attr == "tax_amount" && _val > 0) {
                            $("#checkbox-18").prop('checked', true)
                            $("#form-create input[name='diff_tax']").attr("readonly", false)
                        }

                        if (_attr == "tax_amount2" && _val > 0) {
                            $("#checkbox-19").prop('checked', true)
                            $("#form-create input[name='diff_tax2']").attr("readonly", false)
                        }

                        if (_attr == "tax_amount3" && _val > 0) {
                            $("#checkbox-20").prop('checked', true)
                            $("#form-create input[name='diff_tax3']").attr("readonly", false)
                        }

                        if (_attr == "percent_vat" && _val > 0) {
                            $("#form-create input[name='diff_vat']").attr("readonly", false)
                        }

                    } else if (_attr == "tax_total") {
                        tax1 = Number(_val)
                    } else if (_attr == "tax_total2") {
                        tax2 = Number(_val)
                    } else if (_attr == "tax_total3") {
                        tax3 = Number(_val)
                    }
                }

            }
        });

        $(document).on('keyup', '.amount', function() {
            check = $(this).val();
            check_number = Number(check);

            if (typeof(check_number) !== "number" || isNaN(check_number)) {

                _val = checkAmountNumber(check);
                $(this).val(_val);

                return
            }

            amount = Number($("#amount").val())
            percent_vat = Number($("#percent_vat").val())
            vat = (amount * percent_vat) / 100

            // if(amount > 0){
            //     $("#label-18").attr("hidden", false)
            //     $("#label-19").attr("hidden", false)
            //     $("#label-20").attr("hidden", false)
            // }else{
            //     $("#label-18").attr("hidden", true)
            //     $("#label-19").attr("hidden", true)
            //     $("#label-20").attr("hidden", true)
            //     $("#checkbox-20").prop("checked", false)
            //     $("#checkbox-19").prop("checked", false)
            //     $("#checkbox-18").prop("checked", false)
            // }

            if (vat > 0) {
                $("input[name='diff_vat']").attr("readonly", false);
            } else {
                $("input[name='diff_vat']").attr("readonly", true);
                $("input[name='diff_vat']").val("0.00");
            }

            $("#vat").val(vat.toFixed(2))

            tax1 = Number($("#tax_total").val())
            tax2 = Number($("#tax_total2").val())
            tax3 = Number($("#tax_total3").val())

            tax_total_all = tax1 + tax2 + tax3
            total = (amount + vat) - tax_total_all
            $("#total").val(total.toFixed(2))
        });

        $(document).on('change', '.check', function() {
            amount = Number($("#amount").val())
            percent_vat = Number($("#percent_vat").val())
            diff_vat = Number($("input[name='diff_vat']").val())
            vat = ((amount * percent_vat) / 100) + diff_vat
            count = $("#checkbox-18:checked").length + $("#checkbox-19:checked").length + $("#checkbox-20:checked").length

            if (count > 2) {
                swal({
                    title: "เลือกสูงสุดได้แค่ 2 ตัวเลือก",
                    text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
                    type: "warning",
                    closeOnClickOutside: false
                });
                $(this).prop('checked', false)
                return
            }

            if (count >= 0 && count <= 1) {

                $("#tax_amount").attr("readonly", true)
                $("#tax_amount2").attr("readonly", true)
                $("#tax_amount3").attr("readonly", true)


                if ($("#checkbox-18:checked").length) {
                    $("#tax_amount").val(amount)
                    tax_amount = $("#tax_amount").val()
                    tax_percent = Number($("#tax_percent").val())
                    tax1 = Number((tax_amount * tax_percent) / 100)
                    $("#tax_total").val(tax1.toFixed(2))
                    $("input[name='diff_tax']").attr("readonly", false);
                } else {
                    tax1 = 0
                    $("#tax_amount").val('0.00')
                    $("#tax_total").val('0.00')
                    $("input[name='diff_tax']").val("0.00");
                    $("input[name='diff_tax']").attr("readonly", true);
                }

                if ($("#checkbox-19:checked").length) {
                    $("#tax_amount2").val(amount)
                    tax_amount = $("#tax_amount2").val()
                    tax_percent = Number($("#tax_percent2").val())
                    tax2 = Number((tax_amount * tax_percent) / 100)
                    $("#tax_total2").val(tax2.toFixed(2))
                    $("input[name='diff_tax2']").attr("readonly", false);
                } else {
                    tax2 = 0
                    $("#tax_amount2").val('0.00')
                    $("#tax_total2").val('0.00')
                    $("input[name='diff_tax2']").val("0.00");
                    $("input[name='diff_tax2']").attr("readonly", true);
                }

                if ($("#checkbox-20:checked").length) {
                    $("#tax_amount3").val(amount)
                    tax_amount = $("#tax_amount3").val()
                    tax_percent = Number($("#tax_percent3").val())
                    tax3 = Number((tax_amount * tax_percent) / 100)
                    $("#tax_total3").val(tax3.toFixed(2))
                    $("input[name='diff_tax3']").attr("readonly", false);
                } else {
                    tax3 = 0
                    $("#tax_amount3").val('0.00')
                    $("#tax_total3").val('0.00')
                    $("input[name='diff_tax3']").val("0.00");
                    $("input[name='diff_tax3']").attr("readonly", true);
                }

            } else if (count > 1) {

                tax1 = 0
                tax3 = 0
                tax2 = 0

                $("#tax_amount").val('0.00')
                $("#tax_total").val('0.00')
                $("input[name='diff_tax']").attr("readonly", true);
                $("input[name='diff_tax']").val('0.00')

                $("#tax_amount2").val('0.00')
                $("#tax_total2").val('0.00')
                $("input[name='diff_tax2']").attr("readonly", true);
                $("input[name='diff_tax2']").val('0.00')

                $("#tax_amount3").val('0.00')
                $("#tax_total3").val('0.00')
                $("input[name='diff_tax3']").attr("readonly", true);
                $("input[name='diff_tax3']").val('0.00')

                if ($("#checkbox-18:checked").length) {
                    $("#tax_amount").attr("readonly", false)
                }

                if ($("#checkbox-19:checked").length) {
                    $("#tax_amount2").attr("readonly", false)
                }

                if ($("#checkbox-20:checked").length) {
                    $("#tax_amount3").attr("readonly", false)
                }
            }

            tax_total_all = (tax1 + tax2 + tax3)
            total = (amount + vat) - tax_total_all
            $("#total").val(total.toFixed(2))

        })

        $(".tax").on("keyup", function() {
            let _val = $(this).val()
            let _attr = $(this).attr("name")
            amount = Number($("#amount").val())
            percent_vat = Number($("#percent_vat").val())
            diff_vat = Number($("input[name='diff_vat']").val())
            vat = ((amount * percent_vat) / 100) + diff_vat

            if (_attr == "tax_amount") {
                tax_percent = Number($("#tax_percent").val())
                tax1 = (_val * tax_percent) / 100

                if (tax1 > 0) {
                    $("input[name='diff_tax']").attr("readonly", false);
                } else {
                    $("input[name='diff_tax']").val("0.00");
                    $("input[name='diff_tax']").attr("readonly", true);
                }

                $("#tax_total").val(tax1.toFixed(2))
            } else if (_attr == "tax_amount2") {
                tax_percent2 = Number($("#tax_percent2").val())
                tax2 = (_val * tax_percent2) / 100

                if (tax2 > 0) {
                    $("input[name='diff_tax2']").attr("readonly", false);
                } else {
                    $("input[name='diff_tax2']").val("0.00");
                    $("input[name='diff_tax2']").attr("readonly", true);
                }

                $("#tax_total2").val(tax2.toFixed(2))
            } else if (_attr == "tax_amount3") {
                tax_percent3 = Number($("#tax_percent3").val())
                tax3 = (_val * tax_percent3) / 100

                if (tax3 > 0) {
                    $("input[name='diff_tax3']").attr("readonly", false);
                } else {
                    $("input[name='diff_tax3']").val("0.00");
                    $("input[name='diff_tax3']").attr("readonly", true);
                }

                $("#tax_total3").val(tax3.toFixed(2))
            }

            tax_total_all = Number(tax1 + tax2 + tax3)
            total = (amount + vat) - tax_total_all
            $("#total").val(total.toFixed(2))
        })

        $(".diff").on("keyup", function() {
            let _val = Number($(this).val())
            let _attr = $(this).attr("name")
            amount = Number($("#amount").val())
            percent_vat = Number($("#percent_vat").val())
            diff_vat = Number($("input[name='diff_vat']").val())
            vat = ((amount * percent_vat) / 100) + diff_vat

            if (typeof(_val) !== "number" || isNaN(_val)) {

                current_diff = checkAmountNumber($(this).val());
                $(this).val(current_diff);

                return
            }

            if (_attr == "diff_vat") {
                // amount = Number($("#amount").val())
                // percent_vat = Number($("#percent_vat").val())
                // vat = (amount * percent_vat) / 100
                // diff_vat = vat + _val
                $("#vat").val(vat.toFixed(2))
            } else if (_attr == "diff_tax") {
                tax_amount = Number($("#tax_amount").val())
                tax_percent = Number($("#tax_percent").val())
                tax_total = (tax_amount * tax_percent) / 100
                tax1 = tax_total + _val
                $("#tax_total").val(tax1.toFixed(2))
            } else if (_attr == "diff_tax2") {
                tax_amount2 = Number($("#tax_amount2").val())
                tax_percent2 = Number($("#tax_percent2").val())
                tax_total2 = (tax_amount2 * tax_percent2) / 100
                tax2 = tax_total2 + _val
                $("#tax_total2").val(tax2.toFixed(2))
            } else if (_attr == "diff_tax3") {
                tax_amount3 = Number($("#tax_amount3").val())
                tax_percent3 = Number($("#tax_percent3").val())
                tax_total3 = (tax_amount3 * tax_percent3) / 100
                tax3 = tax_total3 + _val
                $("#tax_total3").val(tax3.toFixed(2))
            }

            tax_total_all = (tax1 + tax2 + tax3)
            total = (amount + vat) - tax_total_all
            $("#total").val(total.toFixed(2))
        })

        $('#reset').on("click", function() {
            let check_del = $("#reset").attr("data-type")
            let id = $("input[name='id']").val()

            if (check_del == "reset") {
                clear()
            } else {
                swal({
                        title: "คุณต้องการลบใช่หรือไม่?",
                        text: "เมื่อลบแล้วไม่สามารถกู้คืนได้",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        cancelButtonText: "ยกเลิก",
                        confirmButtonText: "ตกลง",
                        closeOnConfirm: false
                    },
                    function() {
                        $.ajax({
                            url: "list_invoice_action.php?cid=" + cid + "&dep=" + dep,
                            method: "POST",
                            data: {
                                "action": "del_list",
                                id
                            },
                            dataType: "json",
                            beforeSend: function() {
                                $("#loading").addClass("loading")
                            },
                            success: function(data) {
                                if (data['status'] == "success") {
                                    swal({
                                        title: data.message,
                                        text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
                                        type: data.status,
                                        closeOnClickOutside: false
                                    });
                                    clear()
                                    fetch()
                                } else {
                                    swal({
                                        title: data.message,
                                        text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
                                        type: data.status,
                                        closeOnClickOutside: false
                                    });
                                }
                            },
                            error: function(error) {
                                console.error("Error fetching data:", error);
                            },
                            complete: function() {
                                $("#loading").removeClass("loading")
                            },
                        });

                    });
            }
        });

        $('#inputGroupFile01').on('change', function() {
            const file = this.files[0];
            const validTypes = ['image/jpeg', 'image/png', 'application/pdf'];

            if (file) {
                const fileName = file.name;
                $(this).next('.custom-file-label').html(fileName);

                if (!validTypes.includes(file.type)) {
                    swal({
                        title: "กรุณาใส่ประเภทไฟล์ให้ถูกต้อง (JPG, JPEG, PNG, or PDF).",
                        text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
                        type: "warning",
                        closeOnClickOutside: false
                    });
                    $(this).val('');
                    $(this).next('.custom-file-label').html('Choose file');
                    return
                }
            }
        });

        $('#insert_form').on("click", function() {
            let check_btn = $("#insert_form").attr("data-type")
            let ele = $(".input")
            let obj = {}

            for (let i = 0; i < ele.length; i++) {
                let _attr = $(ele[i]).attr("name")
                let _val = $(ele[i]).val()

                if ($(ele[i]).attr("type") == "radio") {
                    _val = $("input[name='tax']:checked").val();
                }

                if (_val == "" && _attr != "tax" && _attr != "id") {
                    swal({
                        title: "กรุณากรอกข้อมูลให้ครบ",
                        text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
                        type: "warning",
                        closeOnClickOutside: false
                    });

                    return

                }

                if (_val == undefined && _attr == "tax") {
                    swal({
                        title: "กรุณาเลือกคืนภาษี หรือ ไม่คืนภาษี",
                        text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
                        type: "warning",
                        closeOnClickOutside: false
                    });

                    return
                }

                obj[_attr] = _val
            }

            action = check_btn == "create" ? "save_list" : "update_list";
            action == "save_list" ? obj["id"] = "" : obj["id"]

            const formData = new FormData();
            const fileInput = document.getElementById('inputGroupFile01');

            formData.append('action', action);
            formData.append('data', JSON.stringify(obj));
            formData.append('file', fileInput.files[0]);

            $.ajax({
                url: "list_invoice_action.php?cid=" + cid + "&dep=" + dep,
                method: "POST",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                beforeSend: function() {
                    $("#loading").addClass("loading")
                },
                success: function(data) {
                    if (data['status'] == "success") {
                        // swal({
                        //     title: data.message,
                        //     text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
                        //     type: data.status,
                        //     closeOnClickOutside: false
                        // });
                        action == "save_list" ? clear("create") : clear()
                        fetch()

                    } else {
                        swal({
                            title: data.message,
                            text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
                            type: data.status,
                            closeOnClickOutside: false
                        });
                    }
                },
                error: function(error) {
                    console.error("Error fetching data:", error);
                },
                complete: function() {
                    $("#loading").removeClass("loading")
                },
            });
        });

        function numberFormat(number, decimals = 0, dec_point = '.', thousands_sep = ',') {
            number = parseFloat(number);

            if (isNaN(number)) return NaN;

            const fixedNumber = number.toFixed(decimals);

            const [integerPart, decimalPart] = fixedNumber.split('.');

            const formattedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, thousands_sep);

            if (decimals > 0) {
                return formattedInteger + dec_point + decimalPart;
            } else {
                return formattedInteger;
            }
        }
    </script>
</body>

</html>