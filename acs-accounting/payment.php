<?php
include 'config/config.php';
__check_login();
$user_id = __session_user("id");
$user_level_id = __session_user("level_id");
$user_department_id = __session_user("department_id");
$paramurl_company_id = (isset($_GET["cid"])) ? $_GET["cid"] : 0;
$paramurl_department_id = (isset($_GET["dep"])) ? $_GET["dep"] : 0;
$authority_comp_count_dep = __authority_company_count_department($user_id, $paramurl_company_id);
$authority_dep_text_list = __authority_department_text_list($user_id, $paramurl_company_id);
$authority_dep_check = __authority_department_check($user_id, $paramurl_company_id, $paramurl_department_id);
$arrDepAll = __authority_department_list($user_id, $paramurl_company_id);
$invoice_step = __invoice_step_company_list($user_id, $paramurl_company_id);
$html_invoice_title = '<b>รายจ่าย</b><i class="icofont-caret-right"></i> ใบสำคัญจ่าย <i class="icofont-caret-right"></i> เลือกฝ่าย';
$arrInvoiceStep = $invoice_step;
$valueInvoiceStep = $arrInvoiceStep["payment"];
$invoice_step_name = $valueInvoiceStep["name"];
$invoice_step_class_box = $valueInvoiceStep["class_box"];
$invoice_step_icon = $valueInvoiceStep["icon"];
$invoice_step_page = $valueInvoiceStep["page"];
$invoice_step_con_where = $valueInvoiceStep["query_where"];

$html_dep_box = __html_dep_box($html_invoice_title, $invoice_step_page, $invoice_step_icon, " AND " . $invoice_step_con_where, $arrDepAll, str_replace("FROM", "", __invoice_query_from()), "i.inv_depid");
__page_seldep($html_dep_box);
?>
<!DOCTYPE html>
<html>

<head>
    <?php include 'head.php'; ?>
    <link rel="stylesheet" type="text/css" href="css/checkbox.css">

    <style type="text/css">
        .table .thead-light th {
            color: #000;
        }

        tr:nth-last-child(n) {
            border-bottom: 1px solid #dee2e6;
        }

        .truncate-des {
            width: auto;
            min-width: 0;
            max-width: 400px;
            display: inline-block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: 14px;
        }

        .truncate-id {
            width: auto;
            min-width: 0;
            max-width: 180px;
            display: inline-block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }


        .title-amount {
            font-size: 25px;
            font-weight: bold;
            text-align: right;
        }

        .amount {
            font-size: 25px;
            height: calc(1.5em + .75rem + 2px);
            padding: .375rem .75rem;
            text-align: right;
            background-color: #e9ecef;
            border-bottom: 5px double #bbbbbb !important;
        }

        tr.row-checked>td {
            background-color: rgb(218, 255, 204);
        }

        .modal-detail {
            z-index: 2001;
        }

        .modal-view {
            z-index: 2000;
        }

        tfoot tr td {
            border-top: 2px solid #bbbbbb !important;
            border-bottom: 2px solid #bbbbbb !important;
        }

        .swal-table {
            font-size: 18px;
        }

        .swal-count {
            background-color: #e9ecef;
            text-align: right;
        }

        .swal-amount {
            background-color: #e9ecef;
            text-align: right;
        }
    </style>

    <style>
        .div-tabs {
            background-color: #E9ECEF;
            border-top: 1px solid #d4d3d3;
            border-bottom: 1px solid #d4d3d3;
        }

        .nav-tabs a.nav-link {
            font-size: 20px;
            font-weight: bold;
            padding: 14px;
            background-color: #E9ECEF;
            border: none !important;
        }

        .nav-tabs .nav-item a:hover {
            color: #28a7e9;
        }

        .nav-tabs .nav-item a.active {
            border-bottom: 5px solid #28a7e9 !important;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>
    <section>
        <div class="container" style="padding-bottom: 20px">
            <div id="divShowAll"><?php include 'fetch_payment.php'; ?></div>
        </div>
    </section>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="exampleModalLabel"></h2>
                    <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-body-1"></div>
                    <div class="modal-body-2"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning back-menu" hidden>ย้อนกลับ</button>
                    <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

    <div id="loading" class=""></div>

    <script>
        const url = new URL(window.location.href);
        const params = new URLSearchParams(url.search);
        const cid = params.get('cid');
        const dep = params.get('dep');

        function formInputTax(data) {
            html = ''
            html += ``

            return html
        }

        function showInvoiceDetail(data, tax_invoice) {
            $(".modal-body .modal-body-1").hide()
            $(".modal-body .modal-body-2").html('')
            html = '';
            html += `<div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between p-1">
                                <div class="h4 pl-3">เลขที่ใบแจ้งหนี้</div> 
                                <div class="h4 pr-3">${data['number']}</div>
                            </div>
                            <div class="d-flex justify-content-between p-1">
                                <div class="h4 pl-3">วันที่ใบแจ้งหนี้</div> 
                                <div class="h4 pr-3">${convertDateThai(data['date'])}</div>
                            </div>
                            <div class="d-flex justify-content-between p-1">
                                <div class="h4 pl-3">รายละเอียด</div> 
                                <div class="h4 pr-3">${data['desc']}</div>
                            </div>
                            <div class="d-flex justify-content-between p-1">
                                <div class="h4 pl-3">สถานะ</div> 
                                <div class="h4 pr-3"><span class="btn btn-info">${data['return']}</span></div>
                            </div>
                            <div class="d-flex justify-content-between p-1">
                                <div class="h4 pl-3">จำนวนเงิน</div> 
                                <div class="h4 pr-3">${numberFormat(data['amount'], 2)} <span>บาท</span></div>
                            </div>
                            <div class="d-flex justify-content-between p-1">
                                <div class="h4 pl-3">ภาษี</div> 
                                <div class="h4 pr-3">${numberFormat(data['vat'], 2)} <span>%</span></div>
                            </div>
                            <div class="d-flex justify-content-between p-1">
                                <div class="h4 pl-3">รวม</div> 
                                <div class="h4 pr-3">${numberFormat(data['total'], 2)} <span>บาท</span></div>
                            </div>`

            if (data['tax_id'] == null) {

                html += `<hr><h4>เพิ่มใบกำกับภาษี</h4>
                        <input type="text" class="d-none input_tax" name="list_id" value="${data['id']}"/>
                        <input type="text" class="d-none paym_id" data-type="${data['paym_id']}"/>
                        <div class="d-flex justify-content-between">
                            <input type="text" class="form-control input_tax" name="tax_number" autocomplete="off" placeholder="กรุณากรอกเลขที่ใบกำกับภาษี">
                            <input type="date" class="form-control input_tax" name="tax_date" autocomplete="off">
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <select class="custom-select input_tax" name="tax_month">
                                <option selected disabled>กรุณาเลือกเดือนที่จะออกภาษีซื้อ</option>
                                <option value="1">มกราคม</option>
                                <option value="2">กุมภาพันธ์</option>
                                <option value="3">มีนาคม</option>
                                <option value="4">เมษายน</option>
                                <option value="5">พฤษภาคม</option>
                                <option value="6">มิถุนายน</option>
                                <option value="7">กรกฎาคม</option>
                                <option value="8">สิงหาคม</option>
                                <option value="9">กันยายน</option>
                                <option value="10">ตุลาคม</option>
                                <option value="11">พฤศจิกายน</option>
                                <option value="12">ธันวาคม</option>
                            </select>
                            &nbsp;
                            <span class="btn btn-success" id="save_tax_invoice">บันทึก</span>
                        </div>`
            } else {
                html += `<div class="d-flex justify-content-between p-1">
                            <div class="h4 pl-3">เลขที่ใบกำกับภาษี</div> 
                            <div class="h4 pr-3">${data['tax_number']}</div>
                        </div>
                        <div class="d-flex justify-content-between p-1">
                            <div class="h4 pl-3">วันที่ใบกำกับภาษี</div> 
                            <div class="h4 pr-3">${convertDateThai(data['tax_date'])}</div>
                        </div>
                        <div class="d-flex justify-content-between p-1">
                            <div class="h4 pl-3">เดือนที่จะออกใบภาษีซื้อ</div> 
                            <div class="h4 pr-3">${data['tax_invoice_purchase_month']}</div>
                        </div>`
            }
            html += `</div></div>`

            $('.back-menu').removeAttr('hidden');
            $(".modal-body .modal-body-2").append(html)

            // <div class="d-flex justify-content-between mt-2">
            //                     <input type="file" class="form-control" id="file" autocomplete="off">&nbsp;
            //                     <span class="btn btn-success" id="save_tax_invoice">บันทึก</span>
            //                 </div>`
        }

        function showUpdateTax(data) {
            let count_inv = $(".check-invoice")
            let arr = []

            for (let i = 0; i < count_inv.length; i++) {
                if ($(count_inv[i]).is(':checked')) {
                    let _id = $(count_inv[i]).attr("data-type")
                    arr.push(_id)
                }
            }

            if (arr.length === 0) {
                swal({
                    title: "กรุณาเลือกใบวางบิล",
                    text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
                    type: "warning",
                    closeOnClickOutside: false
                });

                return
            }

            $(".modal-body .modal-body-1").hide()
            $(".modal-body .modal-body-2").html('')

            html = ''
            if (data['tax_id'] == null) {
                html += `<h4>เพิ่มใบกำกับภาษี</h4>
                        <input type="text" class="d-none input_tax" name="list_id" value="${arr}"/>
                        <input type="text" class="d-none paym_id" data-type="${data['paym_id']}"/>
                        <div class="d-flex justify-content-between">
                            <input type="text" class="form-control input_tax" name="tax_number" autocomplete="off" placeholder="กรุณากรอกเลขที่ใบกำกับภาษี">
                            <input type="date" class="form-control input_tax" name="tax_date" autocomplete="off">
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <select class="custom-select input_tax" name="tax_month">
                                <option selected disabled>กรุณาเลือกเดือนที่จะออกภาษีซื้อ</option>
                                <option value="1">มกราคม</option>
                                <option value="2">กุมภาพันธ์</option>
                                <option value="3">มีนาคม</option>
                                <option value="4">เมษายน</option>
                                <option value="5">พฤษภาคม</option>
                                <option value="6">มิถุนายน</option>
                                <option value="7">กรกฎาคม</option>
                                <option value="8">สิงหาคม</option>
                                <option value="9">กันยายน</option>
                                <option value="10">ตุลาคม</option>
                                <option value="11">พฤศจิกายน</option>
                                <option value="12">ธันวาคม</option>
                            </select>
                            &nbsp;
                            <span class="btn btn-success" id="save_tax_invoice">บันทึก</span>
                        </div>`
            }

            $('.back-menu').removeAttr('hidden');
            $(".modal-body .modal-body-2").append(html)

        }

        $(document).on('change', '#file', function() {
            const file = this.files[0];
            const validTypes = ['image/jpeg', 'image/png', 'application/pdf'];

            if (file) {
                const fileName = file.name;

                if (!validTypes.includes(file.type)) {
                    swal({
                        title: "กรุณาใส่ประเภทไฟล์ให้ถูกต้อง (JPG, JPEG, PNG, or PDF).",
                        text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
                        type: "warning",
                        closeOnClickOutside: false
                    });
                    $(this).val('')
                    return
                }
            }
        });

        $(".back-menu").on("click", function() {
            $(".modal-body .modal-body-2").html('')
            $(".modal-body .modal-body-1").show()
            $('.back-menu').attr('hidden', true);
        })

        $(".close-modal").on("click", function() {
            $(".modal-body .modal-body-1").html('')
            $(".modal-body .modal-body-2").html('')
            $(".modal-body .modal-body-1").css('display', 'block')
            $('.back-menu').attr('hidden', true);
        })

        $(document).on("click", "#save_tax_invoice", function() {
            let paym_id = $(".paym_id").attr("data-type")
            let ele = $(".input_tax")
            let obj = {}

            for (let i = 0; i < ele.length; i++) {
                let _attr = $(ele[i]).attr("name")
                let _val = $(ele[i]).val()

                if (_val == "") {
                    swal({
                        title: "กรุณากรอกข้อมูลให้ครบ",
                        text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
                        type: "warning",
                        closeOnClickOutside: false
                    });
                    return
                }

                obj[_attr] = _val
            }

            obj['list_id'] = obj['list_id'].split(',')

            const formData = new FormData();
            const fileInput = document.getElementById('file');

            formData.append('action', 'save_tax_invoice');
            formData.append('data', JSON.stringify(obj));
            // formData.append('file', fileInput.files[0]);

            $.ajax({
                url: "list_tax_invoice_action.php?cid=" + cid + '&dep=' + dep,
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
                        swal({
                            title: data.message,
                            text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
                            type: data.status,
                            closeOnClickOutside: false
                        }, willProceed => {
                            if (willProceed) {
                                $(".modal-body .modal-body-2").html('')
                                $(".modal-body .modal-body-1").css('display', 'block')
                                $('.back-menu').attr('hidden', true);
                                getInvoice(paym_id)
                            }
                        });

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
                }
            });

        })

        function showDataInvoice(data) {
            $(".modal-body .modal-body-1").html('')
            html = '';
            obj = {}
            let invoice = {}
            let tax_invoice = {}

            for (let i = 0; i < data.length; i++) {
                invoice['paym_id'] = data[i]['paym_id']
                invoice['id'] = data[i]['invoice_list_id']
                invoice['number'] = data[i]['invoice_list_number']
                invoice['date'] = data[i]['invoice_list_date']
                invoice['desc'] = data[i]['invoice_list_desc']
                invoice['amount'] = data[i]['invoice_list_amount']
                invoice['vat'] = data[i]['invoice_list_percent_vat']
                invoice['total'] = data[i]['invoice_list_total']
                invoice['return'] = data[i]['invoice_list_return_vat'] == '1' ? "ขอคืนภาษี" : "ไม่ขอคืนภาษี"

                invoice['tax_id'] = data[i]['tax_invoice_id']
                invoice['tax_number'] = data[i]['tax_invoice_number']
                invoice['tax_date'] = data[i]['tax_invoice_date']
                invoice['tax_invoice_purchase_month'] = data[i]['tax_invoice_purchase_month']

                if (obj[data[i]['inv_id']] == data[i]['inv_id']) {
                    if (data[i]['invoice_list_id'] != null) {
                        html += `<h4>${invoice['tax_id'] == null ? `<input class="check-invoice" data-type="${invoice['id']}" type="checkbox" />` : ''} 
                                เลขที่ใบวางบิล ${data[i]['invoice_list_number'] != null ? data[i]['invoice_list_number'] : '- (ไม่มีใบวางบิล)'} 
                                ---> <span class="btn btn-info" onClick=showInvoiceDetail(${JSON.stringify(invoice)})>คลิกดู</span> 
                                ${invoice['tax_id'] != null ? `<span class="btn btn-warning" onClick=checkTaxPurchase(${JSON.stringify(invoice)})>แก้ไข</span>` : ''}
                                </h4>`
                    }
                } else {
                    if (data[i]['invoice_list_id'] != null) {
                        obj[data[i]['inv_id']] = data[i]['inv_id']
                        html += `<h3>ใบแจ้งหนี้เลขที่ ${data[i]['inv_no']} 
                                ${invoice['tax_id'] === null ? `<span class="btn btn-primary" onClick=showUpdateTax(${JSON.stringify(invoice)})>เพิ่มใบกำกับภาษีทั้งหมด</span>` : ''}</h3>`
                        html += `<h4>${invoice['tax_id'] == null ? `<input class="check-invoice" data-type="${invoice['id']}" type="checkbox" />` : ''} 
                                เลขที่ใบวางบิล ${data[i]['invoice_list_number'] != null ? data[i]['invoice_list_number'] : '- (ไม่มีใบวางบิล)'} 
                                ---> <span class="btn btn-info" onClick=showInvoiceDetail(${JSON.stringify(invoice)})>คลิกดู</span>
                                ${invoice['tax_id'] != null ? `<span class="btn btn-warning" onClick=checkTaxPurchase(${JSON.stringify(invoice)})>แก้ไข</span>` : ''}
                                </h4>`
                    } else {
                        html += `<h3>ใบแจ้งหนี้เลขที่ ${data[i]['inv_no']}</h3>`
                    }
                }
            }

            $(".modal-body .modal-body-1").append(html)
        }

        function checkTaxPurchase(invoice) {
            $.ajax({
                url: "list_tax_invoice_action.php?cid=" + cid + '&dep=' + dep,
                method: "POST",
                data: {
                    "action": "check_tax_purchase",
                    "tax_id": invoice['tax_id'],
                },
                dataType: 'json',
                beforeSend: function() {
                    $("#loading").addClass("loading")
                },
                success: function(data) {
                    if (data['status'] == "success") {
                        if (data['data'].length === 0) {
                            swal({
                                title: 'ใบกำกับภาษีถูกออกภาษีซื้อแล้ว',
                                text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
                                type: 'warning',
                                closeOnClickOutside: false
                            });
                            return
                        } else {
                            showForm(invoice, data['data'])
                        }
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
                }
            });
        }

        function showForm(data, list = []) {

            $(".modal-body .modal-body-1").hide()
            $(".modal-body .modal-body-2").html('')

            html = ''

            if (list.length > 1) {
                html += '<h3>ใบแจ้งหนี้ในใบกำกับภาษีทั้งหมด<h4>'
                for (let i = 0; i < list.length; i++) {
                    html += `<h4>- เลขที่ใบแจ้งหนี้ ${list[i]['invoice_list_number']}</h4>`
                }
                html += '<hr>'
            } else {
                for (let i = 0; i < list.length; i++) {
                    html += `<h3>เลขที่ใบแจ้งหนี้ ${list[i]['invoice_list_number']}</h3><br>`
                }
            }

            if (data['tax_id'] != null) {
                html += `<h4>แก้ไขใบกำกับภาษี</h4>
                        <input type="text" class="d-none update_tax" name="tax_id" value="${data['tax_id']}"/>
                        <input type="text" class="d-none paym_id" data-type="${data['paym_id']}"/>
                        <div class="d-flex justify-content-between">
                            <input type="text" class="form-control update_tax" name="tax_number" value="${data['tax_number']}" autocomplete="off" placeholder="กรุณากรอกเลขที่ใบกำกับภาษี">
                            <input type="date" class="form-control update_tax" name="tax_date" value="${data['tax_date']}" autocomplete="off">
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <select class="custom-select update_tax" name="tax_month">
                                <option disabled>กรุณาเลือกเดือนที่จะออกภาษีซื้อ</option>
                                <option value="1">มกราคม</option>
                                <option value="2">กุมภาพันธ์</option>
                                <option value="3">มีนาคม</option>
                                <option value="4">เมษายน</option>
                                <option value="5">พฤษภาคม</option>
                                <option value="6">มิถุนายน</option>
                                <option value="7">กรกฎาคม</option>
                                <option value="8">สิงหาคม</option>
                                <option value="9">กันยายน</option>
                                <option value="10">ตุลาคม</option>
                                <option value="11">พฤศจิกายน</option>
                                <option value="12">ธันวาคม</option>
                            </select>
                            &nbsp;
                            <span class="btn btn-success" id="update_tax">อัพเดท</span>
                        </div>`
            }

            $('.back-menu').removeAttr('hidden');
            $(".modal-body .modal-body-2").append(html)

            let selectedMonth = data['tax_invoice_purchase_month'];
            $('select[name="tax_month"]').val(selectedMonth);
        }

        $(document).on("click", "#update_tax", function() {
            let paym_id = $(".paym_id").attr("data-type")
            let ele = $(".update_tax")
            let obj = {}

            for (let i = 0; i < ele.length; i++) {
                let _attr = $(ele[i]).attr("name")
                let _val = $(ele[i]).val()

                if (_val == "") {
                    swal({
                        title: "กรุณากรอกข้อมูลให้ครบ",
                        text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
                        type: "warning",
                        closeOnClickOutside: false
                    });
                    return
                }

                obj[_attr] = _val
            }

            $.ajax({
                url: "list_tax_invoice_action.php?cid=" + cid + '&dep=' + dep,
                method: "POST",
                data: {
                    "action": "update_tax",
                    "data": obj
                },
                dataType: 'json',
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
                        }, willProceed => {
                            if (willProceed) {
                                $(".modal-body .modal-body-2").html('')
                                $(".modal-body .modal-body-1").css('display', 'block')
                                $('.back-menu').attr('hidden', true);
                                getInvoice(paym_id)
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

        })


        $(document).on('click', '.truncate-id', function() {
            $(".modal-body .modal-body-1").html('')
            $(".modal-body .modal-body-2").html('')
            $(".modal-body .modal-body-1").css('display', 'block')
            $('.back-menu').attr('hidden', true);
            let title = $(this).text()
            let id = $(this).attr("data-type");

            getInvoice(id)
        });

        function getInvoice(id) {
            $.ajax({
                url: "list_tax_invoice_action.php?cid=" + cid + '&dep=' + dep,
                method: "GET",
                data: {
                    "action": "get_invoice",
                    id
                },
                dataType: "json",
                beforeSend: function() {
                    $("#loading").addClass("loading")
                },
                success: function(data) {
                    console.log(data)
                    if (data['status'] == "success") {
                        showDataInvoice(data['data'])
                        $("#exampleModalLabel").text("เลขที่ใบสำคัญจ่าย " + data['data'][0]['paym_no'])
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
    </script>
</body>

</html>