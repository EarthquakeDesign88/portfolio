<?php

session_start();
if (!$_SESSION["user_name"]) {

    Header("Location: login.php");
} else {

?>
    <!DOCTYPE html>
    <html>

    <head>
        <?php include 'head.php'; ?>
        <link rel="stylesheet" type="text/css" href="css/checkbox.css">
    </head>

    <body>

        <?php include 'navbar.php'; ?>

        <section>
            <div class="container">
                <form method="POST" name="frmAddInvoice" id="frmAddInvoice" enctype="multipart/form-data">
                    <div class="row py-4 px-1" style="background-color: #E9ECEF">
                        <div class="col-md-12">
                            <h3 class="mb-0">
                                <i class="icofont-plus-circle"></i>&nbsp;&nbsp;เพิ่มใบแจ้งหนี้
                            </h3>
                        </div>
                    </div>

                    <div class="row py-4 px-1" style="background-color: #FFFFFF;">
                        <div class="col-lg-6 col-md-12 pt-1 pb-3" id="showDataComp">
                            <label for="searchCompany" class="mb-1">ชื่อ-นามสกุล / ชื่อบริษัทในเครือ</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <i class="input-group-text">
                                        <i class="icofont-company"></i>
                                    </i>
                                </div>
                                <input type="text" data-type="" name="searchCompany" id="searchCompany" class="form-control" placeholder="กรอกบางส่วนของชื่อ-นามสกุล/ชื่อบริษัท" autocomplete="off" value="" readonly>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-12 pt-1 pb-3" id="">
                            <label for="" class="mb-1">เลขที่ใบแจ้งหนี้</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <i class="input-group-text">
                                        <i class="icofont-company"></i>
                                    </i>
                                </div>
                                <input type="text" data-type="" name="list_number" id="list_number" class="form-control invoice" placeholder="เลขที่ใบแจ้งหนี้" autocomplete="off" value="" readonly>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 pt-1 pb-3" id="showDataPaya">
                            <label for="searchPayable" class="mb-1">ชื่อ-นามสกุล / ชื่อบริษัทผู้ให้บริการ</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <i class="input-group-text">
                                        <i class="icofont-company"></i>
                                    </i>
                                </div>
                                <input type="text" name="searchPayable" id="searchPayable" class="form-control search_payable invoice" placeholder="กรอกบางส่วนของชื่อ-นามสกุล/ชื่อบริษัท" autocomplete="off" autofocus>
                            </div>
                            <div class="list-group" id="show-listPaya"></div>
                        </div>

                        <div class="col-lg-12 col-md-12 pt-1 pb-3">
                            <label for="invdesc" class="mb-1">รายการชำระ</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <i class="input-group-text">
                                        <i class="icofont-list"></i>
                                    </i>
                                </div>
                                <input type="text" class="form-control invoice" name="invdesc" id="invdesc" autocomplete="off" placeholder="รายการชำระใบแจ้งหนี้" readonly>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-12 pt-1 pb-3">
                            <label for="invcount" class="mb-1">จำนวนใบวางบิล</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <i class="input-group-text">
                                        <i class="icofont-numbered"></i>
                                    </i>
                                </div>
                                <input type="text" class="form-control text-right invoice" name="invcount" id="invcount" autocomplete="off" placeholder="กรอกจำนวนใบวางบิล" readonly>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-12 pt-1 pb-3">
                            <label for="invdate" class="mb-1">วันที่ออกใบแจ้งหนี้</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <i class="input-group-text">
                                        <i class="icofont-ui-calendar"></i>
                                    </i>
                                </div>
                                <input type="date" class="form-control invoice" name="invdate" id="invdate" autocomplete="off">
                            </div>
                            <span style="color: #F00;padding-top: 2px" id="altinvdate"></span>
                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-12 pt-1 pb-3">
                            <label for="" class="mb-1">จำนวนใบขอคืนภาษี</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <i class="input-group-text">
                                        <i class="icofont-numbered"></i>
                                    </i>
                                </div>
                                <input type="text" class="form-control text-right invoice" id="tax" name="tax" autocomplete="off" placeholder="กรอกจำนวนใบขอคืนภาษี" readonly>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-12 pt-1 pb-3">
                            <label for="" class="mb-1">จำนวนใบไม่ขอคืนภาษี</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <i class="input-group-text">
                                        <i class="icofont-numbered"></i>
                                    </i>
                                </div>
                                <input type="text" class="form-control text-right invoice" id="no_tax" name="no_tax" autocomplete="off" placeholder="กรอกจำนวนใบไม่ขอคืนภาษี" readonly>
                            </div>
                        </div>

                    </div>
                </form>

                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModal">
                    รายการสำหรับออกใบแจ้งหนี้
                </button>

                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2 class="modal-title" id="exampleModalLabel">รายการเตรียมออกใบแจ้งหนี้</h2>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-bordered">
                                    <thead class="table-secondary">
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
                                    <tbody class="table-group-divider" id="show_list_invoice">

                                    </tbody>
                                </table>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                                <button type="button" class="btn btn-primary" id="add">เพิ่มรายการ</button>
                            </div>
                        </div>
                    </div>
                </div>

                <table class="table table-bordered">
                    <thead class="table-secondary">
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
                    <tbody class="table-group-divider" id="show_add_list_invoice">

                    </tbody>
                </table>


                <div class="row show_result" hidden>
                    <div class="col-lg-7 col-md-3 col-sm-12 pb-1">
                    </div>
                    <div class="col-lg-5 col-md-3 col-sm-12 pb-1">
                        <div class="form-group row">
                            <label for="" class="col-sm-5 col-form-label text-right">จำนวนเงินรวม</label>
                            <div class="col-sm-7">
                                <div class="input-group">
                                    <input type="text" class="form-control text-right invoice" id="amount_all" name="amount" value="0.00" readonly>
                                    <div class="input-group-append">
                                        <i class="input-group-text">
                                            <i class="icofont-baht"></i>
                                        </i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7 col-md-3 col-sm-12 pb-1">
                    </div>
                    <div class="col-lg-5 col-md-3 col-sm-12 pb-1">
                        <div class="form-group row">
                            <label for="" class="col-sm-5 col-form-label text-right">ภาษีมูลค่าเพิ่มรวม 7%</label>
                            <div class="col-sm-7">
                                <div class="input-group">
                                    <input type="text" class="form-control text-right invoice" id="vat_all" name="vat" value="0.00" readonly>
                                    <div class="input-group-append">
                                        <i class="input-group-text">
                                            <i class="icofont-baht"></i>
                                        </i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5 col-md-3 col-sm-12 pb-1">
                    </div>

                    <div class="col-lg-7 col-md-3 col-sm-12 pb-1">
                        <div class="form-group row">
                            <label for="" class="col-sm-4 col-form-label text-right">หักภาษี ณ ที่จ่าย 1%</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <input type="text" class="form-control text-right invoice" id="tax_amount" name="tax_amount" value="0.00" readonly>
                                    <div class="input-group-append">
                                        <i class="input-group-text">
                                            <i class="icofont-baht"></i>
                                        </i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" class="form-control text-right invoice" id="tax_total1" name="tax_total" value="0.00" readonly>
                                    <div class="input-group-append">
                                        <i class="input-group-text">
                                            <i class="icofont-baht"></i>
                                        </i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5 col-md-3 col-sm-12 pb-1">
                    </div>

                    <div class="col-lg-7 col-md-3 col-sm-12 pb-1">
                        <div class="form-group row">
                            <label for="" class="col-sm-4 col-form-label text-right">3%</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <input type="text" class="form-control text-right invoice" id="tax_amount2" name="tax_amount2" value="0.00" readonly>
                                    <div class="input-group-append">
                                        <i class="input-group-text">
                                            <i class="icofont-baht"></i>
                                        </i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" class="form-control text-right invoice" id="tax_total2" name="tax_total2" value="0.00" readonly>
                                    <div class="input-group-append">
                                        <i class="input-group-text">
                                            <i class="icofont-baht"></i>
                                        </i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5 col-md-3 col-sm-12 pb-1">
                    </div>
                    <div class="col-lg-7 col-md-3 col-sm-12 pb-1">
                        <div class="form-group row">
                            <label for="" class="col-sm-4 col-form-label text-right">5%</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <input type="text" class="form-control text-right invoice" id="tax_amount3" name="tax_amount3" value="0.00" readonly>
                                    <div class="input-group-append">
                                        <i class="input-group-text">
                                            <i class="icofont-baht"></i>
                                        </i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" class="form-control text-right invoice" id="tax_total3" name="tax_total3" value="0.00" readonly>
                                    <div class="input-group-append">
                                        <i class="input-group-text">
                                            <i class="icofont-baht"></i>
                                        </i>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-lg-7 col-md-3 col-sm-12 pb-1">
                    </div>
                    <div class="col-lg-5 col-md-3 col-sm-12 pb-1">
                        <div class="form-group row">
                            <label for="" class="col-sm-5 col-form-label text-right">จำนวนยอดสุทธิ</label>
                            <div class="col-sm-7">
                                <div class="input-group">
                                    <input type="text" class="form-control text-right invoice" id="total_all" name="total" value="0.00" readonly>
                                    <div class="input-group-append">
                                        <i class="input-group-text">
                                            <i class="icofont-baht"></i>
                                        </i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 pt-1 pb-3 text-center">
                        <input type="button" class="btn btn-secondary px-3 py-2 mx-1 show_result" id="save_invoice" value="บันทึกข้อมูล" hidden>
                    </div>
                </div>
            </div>
        </section>

        <div id="loading" class=""></div>

        <script src="js/invoice_list.js"></script>
        <script>
            const url = new URL(window.location.href);
            const params = new URLSearchParams(url.search);
            const cid = params.get('cid');
            const dep = params.get('dep');

            const mapList = {
                "id": "invoice_list_id",
                "date_list": "invoice_list_date",
                "list_name": "invoice_list_desc",
                "tax": "invoice_list_return_vat",
                "amount": "invoice_list_amount",
                "percent_vat": "invoice_list_percent_vat",
                "vat": "invoice_list_vat",
                "total": "invoice_list_total",
                "tax_total": "invoice_list_tax_total",
                "tax_total2": "invoice_list_tax_total2",
                "tax_total3": "invoice_list_tax_total3",
                "tax_amount": "invoice_list_tax_amount",
                "tax_amount2": "invoice_list_tax_amount2",
                "tax_amount3": "invoice_list_tax_amount3",
                "list_number": "invoice_list_number",
                "list_desc": "invoice_list_desc"
            }

            function searchShowPayable(data) {
                html = '';

                html += '<ul class="list-unstyled" id="show_payable">'

                if (data.length > 0) {
                    for (let i = 0; i < data.length; i++) {
                        html += `<li class="list-group-item list-group-item-action border-1 text-left select-payable" id=${data[i]['paya_id']}><i class="icofont-building pr-3"></i>${data[i]['paya_name']}</li>`
                    }
                } else {
                    html += `<li class="list-group-item border-1">
							<i class="icofont-building pr-3"></i> ไม่มีข้อมูลบริษัทที่ต้องการค้นหา
						</li>`
                }

                html += '</ul>'

                $("#show-listPaya").append(html)
            }

            function showListInvoice(data, position = "#show_list_invoice") {
                $(position).html("")
                html = ''

                for (let i = 0; i < data.length; i++) {
                    check = data[i]['invoice_list_return_vat'] == "0" || data[i]['invoice_list_return_vat'] == "X" ? "X" : "/";
                    html += `<tr class=${position == "#show_list_invoice" ? "table-item" : ''} data-type="">
                    <th scope="row" class="text-center">${i + 1}</th>
                    <td class="text-center d-none" name="id" data-type="${data[i]['invoice_list_id']}">${data[i]['invoice_list_id']}</td>
                    <td class="text-center d-none" name="list_number" data-type="${data[i]['invoice_list_number']}">${data[i]['invoice_list_number']}</td>
                    <td class="text-center d-none" name="list_desc" data-type="${data[i]['invoice_list_desc']}">${data[i]['invoice_list_desc']}</td>
                    <td class="text-center d-none" name="tax_amount" data-type="${data[i]['invoice_list_tax_amount']}">${data[i]['invoice_list_tax_amount']}</td>
                    <td class="text-center d-none" name="tax_amount2" data-type="${data[i]['invoice_list_tax_amount2']}">${data[i]['invoice_list_tax_amount2']}</td>
                    <td class="text-center d-none" name="tax_amount3" data-type="${data[i]['invoice_list_tax_amount3']}">${data[i]['invoice_list_tax_amount3']}</td>
                    <td class="text-center" name="date_list" data-type="${data[i]['invoice_list_date']}">${convertDateThai(data[i]['invoice_list_date'])}</td>
                    <td class="text-center" name="list_name" data-type="${data[i]['invoice_list_desc']}">${data[i]['invoice_list_desc']}</td>
                    <td class="text-center" name="tax" data-type="${check}">${check}</td>
                    <td class="text-center" name="amount" data-type="${data[i]['invoice_list_amount']}">${numberFormat(data[i]['invoice_list_amount'], 2)}</td>
                    <td class="text-center" name="percent_vat" data-type="${data[i]['invoice_list_percent_vat']}">${numberFormat(data[i]['invoice_list_percent_vat'], 2)}</td>
                    <td class="text-center" name="vat" data-type="${data[i]['invoice_list_vat']}">${numberFormat(data[i]['invoice_list_vat'], 2)}</td>
                    <td class="text-right" name="tax_total" data-type="${data[i]['invoice_list_tax_total']}">${numberFormat(data[i]['invoice_list_tax_total'], 2)}</td>
                    <td class="text-right" name="tax_total2" data-type="${data[i]['invoice_list_tax_total2']}">${numberFormat(data[i]['invoice_list_tax_total2'], 2)}</td>
                    <td class="text-right" name="tax_total3" data-type="${data[i]['invoice_list_tax_total3']}">${numberFormat(data[i]['invoice_list_tax_total3'], 2)}</td>
                    <td class="text-right" name="total" data-type="${data[i]['invoice_list_total']}">${numberFormat(data[i]['invoice_list_total'], 2)}</td>                
                    </tr>`
                }

                $(position).append(html)
            }

            $(document).on("click", '.table-item', function() {
                const currentColor = $(this).css("background-color");
                const paleGoldenRod = "rgb(238, 221, 130)";

                if (currentColor === paleGoldenRod) {
                    $(this).css("background-color", "");
                    $(this).attr("data-type", "0")
                } else {
                    $(this).css("background-color", paleGoldenRod);
                    $(this).attr("data-type", "1")
                }
            });


            $(document).on("click", '.select-payable', function() {
                let id = $(this).attr("id")
                let name = $(this).text()
                $("#searchPayable").val(name)
                $("#searchPayable").attr("data-type", id)
                $("#show_payable").addClass("d-none")
            });

            $(document).on("click", '#add', function() {
                let list = $("#show_list_invoice").find(".table-item[data-type='1']");

                let arr = [];
                amount = 0;
                vat = 0;
                total = 0;
                count = 0;
                count_tax = 0;
                count_no_tax = 0;
                tax1 = 0;
                tax2 = 0;
                tax3 = 0;
                tax_amount = 0;
                tax_amount2 = 0;
                tax_amount3 = 0;
                list_number = '';
                list_desc = '';
                let obj = {}

                for (let j = 0; j < list.length; j++) {
                    let ele = $(list[j]).find("td")
                    for (let i = 0; i < ele.length; i++) {
                        let _attr = $(ele[i]).attr("name")
                        let _val = $(ele[i]).attr("data-type")

                        obj[mapList[_attr]] = _val

                        if (_attr == "amount") {
                            amount += Number(_val)
                        } else if (_attr == "vat") {
                            vat += Number(_val)
                        } else if (_attr == "total") {
                            total += Number(_val)
                        } else if (_attr == "tax") {
                            count += 1
                            if (_attr == "tax" && _val == "/") {
                                count_tax += 1
                            } else {
                                count_no_tax += 1
                            }
                        } else if (_attr == "list_number") {
                            list_number += _val.toString() + ", "
                        } else if (_attr == "list_desc") {
                            list_desc += _val.toString() + ", "
                        } else if (_attr == "tax_amount") {
                            tax_amount += Number(_val)
                        } else if (_attr == "tax_amount2") {
                            tax_amount2 += Number(_val)
                        } else if (_attr == "tax_amount3") {
                            tax_amount3 += Number(_val)
                        } else if (_attr == "tax_total") {
                            tax1 += Number(_val)
                        } else if (_attr == "tax_total2") {
                            tax2 += Number(_val)
                        } else if (_attr == "tax_total3") {
                            tax3 += Number(_val)
                        }

                    }
                    arr.push(obj)
                    obj = {}
                }

                showListInvoice(arr, "#show_add_list_invoice")
                
                if(list.length > 0){
                    $(".show_result").attr("hidden", false)
                }else{
                    $(".show_result").attr("hidden", true)
                }

                $("#list_number").val(list_number.substr(0, list_number.length - 2))
                $("#invdesc").val(list_desc.substr(0, list_desc.length - 2))
                $("#invcount").val(count)
                $("#tax").val(count_tax)
                $("#no_tax").val(count_no_tax)

                $("#amount_all").val(numberFormat(amount, 2))
                $("#amount_all").attr("data-type", amount.toFixed(2))

                $("#vat_all").val(numberFormat(vat, 2))
                $("#vat_all").attr("data-type", vat.toFixed(2))

                $("#tax_amount").val(numberFormat(tax_amount, 2))
                $("#tax_amount").attr("data-type", tax_amount.toFixed(2))

                $("#tax_amount2").val(numberFormat(tax_amount2, 2))
                $("#tax_amount2").attr("data-type", tax_amount2.toFixed(2))

                $("#tax_amount3").val(numberFormat(tax_amount3, 2))
                $("#tax_amount3").attr("data-type", tax_amount3.toFixed(2))

                $("#tax_total1").val(numberFormat(tax1, 2))
                $("#tax_total1").attr("data-type", tax1.toFixed(2))

                $("#tax_total2").val(numberFormat(tax2, 2))
                $("#tax_total2").attr("data-type", tax2.toFixed(2))

                $("#tax_total3").val(numberFormat(tax3, 2))
                $("#tax_total3").attr("data-type", tax3.toFixed(2))

                $("#total_all").val(numberFormat(total, 2))
                $("#total_all").attr("data-type", total.toFixed(2))
            });

            $(document).on("click", '#save_invoice', function() {
                let list_all = $("#show_add_list_invoice").find("tr");
                let paya_id = $("#searchPayable").attr("data-type")

                if (list_all.length == 0) {
                    swal({
                        title: "กรุณาเลือกรายการใบวางบิล",
                        text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
                        type: "warning",
                        closeOnClickOutside: false
                    });

                    return
                }

                let list = $(".invoice")
                obj = {}

                for (let i = 0; i < list.length; i++) {
                    let _attr = $(list[i]).attr("name")

                    if (_attr == "amount" || _attr == "tax_amount" || _attr == "tax_amount2" || _attr == "tax_amount3" || _attr == "tax_total" || _attr == "tax_total2" || _attr == "tax_total3" || _attr == "total" || _attr == "vat") {
                        _val = $(list[i]).attr("data-type")
                    } else {
                        _val = $(list[i]).val()
                    }

                    if (_val == "") {
                        swal({
                            title: "กรุณาเลือกกรอกข้อมูลให้ครบ",
                            text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
                            type: "warning",
                            closeOnClickOutside: false
                        });

                        return
                    }

                    obj[_attr] = _val
                }

                obj['payable_id'] = paya_id

                let check_payable = $("#show_payable").hasClass("d-none")

                if (!check_payable) {
                    swal({
                        title: "กรุณาเลือกชื่อบริษัทผู้ให้บริการ",
                        text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
                        type: "warning",
                        closeOnClickOutside: false
                    });

                    return
                }

                arr = []

                for (let i = 0; i < list_all.length; i++) {
                    let _ele = $(list_all[i]).find('[name="id"]');
                    let id = $(_ele).attr("data-type")
                    arr.push(id)
                }

                obj['tax_total'] > 0 ? obj['tax1'] = "1.00" : obj['tax1'] = "0.00"
                obj['tax_total2'] > 0 ? obj['tax2'] = "3.00" : obj['tax2'] = "0.00"
                obj['tax_total3'] > 0 ? obj['tax3'] = "5.00" : obj['tax3'] = "0.00"

                obj['id'] = arr

                $.ajax({
                    url: "list_invoice_select_action.php?cid=" + cid + "&dep=" + dep,
                    method: "POST",
                    data: {
                        "action": "save_invoice",
                        "data": obj
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $("#loading").addClass("loading")
                    },
                    success: function(data) {
                        if (data.status == "success") {
                            swal({
                                title: data.message,
                                text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
                                type: data.status,
                                closeOnClickOutside: false
                            }, willProceed => {
                                if (willProceed) {
                                    window.location.href = "list_invoice_select.php?cid=" + cid + "&dep=" + dep
                                }
                            });
                        }
                    },
                    error: function(error) {
                        console.error("Error fetching data:", error);
                    },
                    complete: function() {
                        $("#loading").removeClass("loading")
                    }
                });

            });

            $(document).on('keyup', '.search_payable', function() {
                $("#show-listPaya").html("")
                let search = $(this).val()

                if (search != "") {
                    $.ajax({
                        url: "list_invoice_select_action.php?cid=" + cid + "&dep=" + dep,
                        method: "POST",
                        data: {
                            "action": "search_payable",
                            "search_payable": search
                        },
                        dataType: "json",
                        // beforeSend: function() {
                        //     $("#loading").addClass("loading")
                        // },
                        success: function(data) {
                            if (data.status == "success") {
                                searchShowPayable(data.data)
                            }
                        },
                        error: function(error) {
                            console.error("Error fetching data:", error);
                        },
                        // complete: function() {
                        //     $("#loading").removeClass("loading")
                        // }
                    });
                }
            });

            function fetchCompany() {

                $.ajax({
                    url: "list_invoice_select_action.php?cid=" + cid + "&dep=" + dep,
                    method: "GET",
                    data: {
                        "action": "get_company",
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $("#loading").addClass("loading")
                    },
                    success: function(data) {
                        if (data.status == "success") {
                            $("#searchCompany").val(data.data['comp_name'])
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

            function fetchListInvoice() {

                $.ajax({
                    url: "list_invoice_select_action.php?cid=" + cid + "&dep=" + dep,
                    method: "GET",
                    data: {
                        "action": "get_list_invoice",
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $("#loading").addClass("loading")
                    },
                    success: function(data) {
                        if (data.status == "success") {
                            showListInvoice(data.data)
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
                fetchCompany()
                fetchListInvoice()
            });
        </script>
        <?php include 'footer.php'; ?>

    </body>

    </html>
<?php } ?>