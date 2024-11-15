<?php

session_start();
if (!$_SESSION["user_name"]) {  //check session

	Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

} else {

	include 'connect.php';

	$cid = $_GET["cid"];
	$dep = $_GET["dep"];

	$str_sql_user = "SELECT * FROM user_tb AS u 
						INNER JOIN level_tb AS l ON u.user_levid = l.lev_id 
						INNER JOIN department_tb AS d ON u.user_depid = d.dep_id 
						WHERE user_id = '" . $_SESSION["user_id"] . "'";
	$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
	$obj_row_user = mysqli_fetch_array($obj_rs_user);

	// echo $obj_row_user["lev_name"];

	$str_sql_dep = "SELECT * FROM department_tb WHERE dep_id = '" . $dep . "'";
	$obj_rs_dep = mysqli_query($obj_con, $str_sql_dep);
	$obj_row_dep = mysqli_fetch_array($obj_rs_dep);

	$str_sql_comp = "SELECT * FROM company_tb WHERE comp_id = '" . $cid . "'";
	$obj_rs_comp = mysqli_query($obj_con, $str_sql_comp);
	$obj_row_comp = mysqli_fetch_array($obj_rs_comp);

?>

	<!DOCTYPE html>
	<html>

	<head>

		<?php include 'head.php'; ?>

		<link rel="stylesheet" type="text/css" href="css/checkbox.css">
		<script type="text/javascript" src="js/calinvoice.js"></script>
		<script type="text/javascript" src="js/script_company.js"></script>
		<script type="text/javascript" src="js/script_customer.js"></script>

		<style type="text/css">
			@media only screen and (max-width: 992px) {
				.descbtn {
					display: none;
				}
			}

			@media (min-width: 576px) {
				.modal-dialog {
					/* max-width: 500px; */
					margin: 1.75rem auto;
				}
			}

			td {
				word-wrap: break-word !important;
			}

			div#show-listComp,
			div#show-listPaya,
			div#show-listCust,
			div#show-listPurchase {
				position: absolute;
				z-index: 99;
				width: 100%;
				margin-left: -15px !important;
			}

			.list-unstyled {
				position: absolute;
				z-index: 100;
				background-color: #FFFF;
				cursor: pointer;
				margin-right: 15px;
				-webkit-box-shadow: 0 2px 5px 0 rgb(0 0 0 / 16%), 0 2px 10px 0 rgb(0 0 0 / 12%);
				box-shadow: 0 2px 5px 0 rgb(0 0 0 / 16%), 0 2px 10px 0 rgb(0 0 0 / 12%);
			}

			.list-group-item {
				font-family: 'Sarabun', sans-serif;
				cursor: pointer;
				border: 1px solid #eaeaea;
				list-style: none;
				top: 50%;
				padding: .75rem !important;
			}

			.list-group-item:hover {
				background-color: #f5f5f5;
			}

			@media (min-width: 992px) {
				.salary-tax {
					display: none;
				}
			}

			@media (min-width: 468.98px) {
				.salary-tax {
					display: block;
					padding-bottom: 0px !important;
				}
			}

			@media (min-width: 572px) {
				.salary-tax {
					display: block;
					padding-bottom: 0px !important;
				}
			}

			input[type=file] {
				padding: 0px !important;
			}

			input[type=file]::file-selector-button {
				border: none;
				border-right: 1px solid #dadada;
				padding: .55em;
				border-radius: 0em;
				transition: 1s;
			}
		</style>

	</head>

	<body>

		<?php include 'navbar.php'; ?>

		<section id="tax_data" class="p-3 mt-5">
			<div class="container-fluid">

				<form method="POST" name="frmAddInvoice" id="frmAddInvoice" enctype="multipart/form-data">
					<div class="row py-4 px-1" style="background-color: #E9ECEF">
						<div class="col-md-12">
							<h3 class="mb-0">
								<i class="icofont-plus-circle"></i>&nbsp;&nbsp;เพิ่มใบภาษีซื้อ
							</h3>
						</div>
					</div>

					<div class="row py-4 px-1" style="background-color: #FFFFFF;">
						<div class="col-lg-6 col-md-6 pt-1 pb-3" id="showDataComp">
							<label for="searchCompany" class="mb-1">ชื่อผู้ประกอบการ</label>
							<div class="input-group">
								<div class="input-group-prepend">
									<i class="input-group-text">
										<i class="icofont-company"></i>
									</i>
								</div>
								<input type="text" name="searchCompany" id="searchCompany2" class="form-control" placeholder="กรอกบางส่วนของชื่อ-นามสกุล/ชื่อบริษัท" autocomplete="off" value="<?= $obj_row_comp["comp_name"]; ?>" readonly>

								<input type="text" class="form-control d-none" id="invcompid" name="invcompid" value="<?= $obj_row_comp["comp_id"]; ?>">
							</div>
						</div>

						<div class="col-lg-6 col-md-6 pt-1 pb-3" id="showDataPaya">
							<label for="searchPayable" class="mb-1">ชื่อสถานประกอบการ</label>
							<div class="input-group">
								<div class="input-group-prepend">
									<i class="input-group-text">
										<i class="icofont-company"></i>
									</i>
								</div>
								<input type="text" name="searchCompany" id="searchCompany" class="form-control" placeholder="กรอกบางส่วนของชื่อ-นามสกุล/ชื่อบริษัท" autocomplete="off" value="<?= $obj_row_comp["comp_name"]; ?>" readonly>

								<input type="text" class="form-control d-none" id="invpayaid2" name="invpayaid">

							</div>
							<div class="list-group" id="show-listComp"></div>
						</div>

						<div class="col-lg-3 col-md-4 col-sm-12 pt-1 pb-3">
							<label for="SelectDep" class="mb-1">ฝ่าย</label>
							<select class="custom-select form-control" id="SelectDep" name="SelectDep" disabled style="background-color: #FFF; color: #000;">
								<option value="<?= $obj_row_dep["dep_id"] ?>" selected>
									<?= $obj_row_dep["dep_name"] ?>
								</option>
								<input type="text" class="form-control d-none" name="invdepid" id="invdepid" value="<?= $dep; ?>">
							</select>
						</div>

						<div class="col-lg-3 col-md-4 col-sm-12 pt-1 pb-3">
							<label for="invdate" class="mb-1">วันที่ใบภาษีซื้อ</label>
							<div class="input-group">
								<div class="input-group-prepend">
									<i class="input-group-text">
										<i class="icofont-ui-calendar"></i>
									</i>
								</div>
								<input type="date" class="form-control" name="invdate" id="invdate" autocomplete="off">
							</div>
							<span style="color: #F00;padding-top: 2px" id="altinvdate"></span>
						</div>

						<div class="col-lg-6 col-md-4 col-sm-12 pt-1 pb-3" id="showDataComp">
							<label for="searchCompany" class="mb-1">เลขประจำตัวผู้เสียภาษีอากร</label>
							<div class="input-group">
								<input type="text" name="searchCompany" id="searchTax" class="form-control" placeholder="กรอกบางส่วนของชื่อ-นามสกุล/ชื่อบริษัท" autocomplete="off" value="<?= $obj_row_comp["comp_taxno"]; ?>" readonly>
							</div>
						</div>

						<div class="col-lg-4 col-md-4 col-sm-12 pt-1 pt-3">
							<div class="input-group">
								<select class="custom-select" id="tax_month">
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
							</div>
						</div>
					</div>

					<div class="row py-4 px-1" style="background-color: #FFFFFF">
						<div class="col-md-12">
							<table class="table mb-0">
								<thead class="thead-light">
									<tr>
										<th width="5%">ลำดับ</th>
										<th width="9%" class="text-center">วันที่</th>
										<th width="14%" class="text-center">เล่มที่/เลขที่</th>
										<th width="18%" class="text-center">รายการ</th>
										<th width="18%" class="text-center">ชื่อผู้ซื้อ/ชื่อผู้รับบริการ</th>
										<th width="9%" class="text-center">มูลค่าสินค้า</th>
										<th width="9%" class="text-center">ภาษีมูลค่าเพิ่ม</th>
										<th width="14%" class="text-center">รวม</th>
										<th width="4%" class="text-center">
										</th>
										<th></th>
									</tr>
								</thead>
								<tbody id="multi_input">
								</tbody>
							</table>
							<table class="result_add">
								<tr>
									<td width="5%"></td>
									<td width="10%"></td>
									<td width="10%"></td>
									<td width="15%"></td>
									<td width="15%">รวมยอด</td>
									<td width="8%">
										<input type="text" class="form-control text-right" autocomplete="off" id="price_all" value="0" readonly>
										<input type="text" class="form-control text-right d-none" autocomplete="off" id="price_nf" value="0" readonly>

									</td>
									<td width="10%">
										<input type="text" class="form-control text-right" autocomplete="off" id="vat_all" value="0" readonly>
										<input type="text" class="form-control text-right d-none" autocomplete="off" id="vat_nf" value="0" readonly>

									</td>
									<td width="8%">

										<input type="text" class="form-control text-right" autocomplete="off" id="result_all" value="0" readonly>
										<input type="text" class="form-control text-right d-none" autocomplete="off" id="result_nf" value="0" readonly>
									</td>
								</tr>
							</table>
							<!-- <a class="btn btn-primary" id="add_tax_input"><i class="icofont-plus-circle"></i></a> -->
							<!-- <button type="button" name="addPayable" id="addPayable" class="btn btn-primary" data-toggle="modal" data-target="#add_data_Payable" data-controls-modal="add_data_Payable" data-backdrop="static" data-keyboard="false"> -->
							<!-- <i class="icofont-plus-circle"></i>&nbsp;&nbsp;บริษัทเจ้าหนี้ -->
							<!-- </button> -->

						</div>
					</div>


					<div class="row py-4 px-1" style="background-color: #FFFFFF">
						<div class="col-md-12 pb-4 text-center">
							<input type="button" class="btn btn-success px-5 py-2 mx-1" name="btnInsert" id="btnInsert_tax" value="preview" disabled>
						</div>
					</div>

				</form>
			</div>
		</section>
		<section id="tax_preview">

		</section>

		<div id="add_data_Payable" class="modal fade">
			<div class="modal-dialog modal-lg modal-dialog-centered">
				<div class="modal-content">

					<form method="POST" id="insert_form" name="insert_form">

						<div class="modal-header">
							<h3 class="modal-title py-2">
								รายละเอียดบริษัท
							</h3>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
						</div>

						<div class="modal-body">
							<div class="row">
								<div class="col-md-6 col-sm-12 pt-1 pb-2">
									<label for="exampleInputEmail1" class="mb-1">รหัส</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<i class="input-group-text">
												<i class="icofont-numbered"></i>
											</i>
										</div>
										<input type="text" class="form-control" name="txtpayaid" id="txtpayaid" value="" placeholder="กรุณาเว้นว่างไว้เพื่อสร้างอัตโนมัติ" readonly>
									</div>
								</div>

								<div class="col-md-12 col-sm-12 pt-1 pb-2">
									<label for="exampleInputEmail1" class="mb-1">ชื่อ นามสกุล/ชื่อบริษัท</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<i class="input-group-text">
												<i class="icofont-building"></i>
											</i>
										</div>
										<input type="text" class="form-control" name="payaname" id="payaname" placeholder="กรอกชื่อบริษัท" autocomplete="off">
									</div>
								</div>

								<div class="col-md-12 col-sm-12 pt-1 pb-2">
									<label for="exampleInputEmail1" class="mb-1">ที่อยู่บริษัท</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<i class="input-group-text">
												<i class="icofont-location-pin"></i>
											</i>
										</div>
										<input type="text" class="form-control" name="payaaddress" id="payaaddress" placeholder="กรอกที่อยู่บริษัท" autocomplete="off">
									</div>
								</div>

								<div class="col-md-6 col-sm-12 pt-1 pb-2">
									<label for="exampleInputEmail1" class="mb-1">เลขประจำตัวผู้เสียภาษี</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<i class="input-group-text">
												<i class="icofont-id"></i>
											</i>
										</div>
										<input type="text" class="form-control" name="payataxno" id="payataxno" placeholder="กรอกเลขประจำตัวผู้เสียภาษีบริษัท" autocomplete="off">
									</div>
								</div>

								<div class="col-md-6 col-sm-12 pt-1 pb-2"></div>

								<div class="col-md-6 col-sm-12 pt-1 pb-2">
									<label for="exampleInputEmail1" class="mb-1">เบอร์โทรศัพท์</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<i class="input-group-text">
												<i class="icofont-phone"></i>
											</i>
										</div>
										<input type="text" class="form-control" name="payatel" id="payatel" placeholder="กรอกเบอร์โทรศัพท์บริษัท (ถ้ามี)" autocomplete="off">
									</div>
								</div>

								<div class="col-md-6 col-sm-12 pt-1 pb-2">
									<label for="exampleInputEmail1" class="mb-1">เบอร์โทรสาร</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<i class="input-group-text">
												<i class="icofont-fax"></i>
											</i>
										</div>
										<input type="text" class="form-control" name="payafax" id="payafax" placeholder="กรอกเบอร์โทรสารบริษัท (ถ้ามี)" autocomplete="off">
									</div>
								</div>

								<div class="col-md-6 col-sm-12 pt-1 pb-2">
									<label for="exampleInputEmail1" class="mb-1">อีเมล</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<i class="input-group-text">
												<i class="icofont-ui-email"></i>
											</i>
										</div>
										<input type="text" class="form-control" name="payaemail" id="payaemail" placeholder="กรอกอีเมลบริษัท (ถ้ามี)" autocomplete="off">
									</div>
								</div>

								<div class="col-md-6 col-sm-12 pt-1 pb-2">
									<label for="exampleInputEmail1" class="mb-1">เว็บไซต์</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<i class="input-group-text">
												<i class="icofont-web"></i>
											</i>
										</div>
										<input type="text" class="form-control" name="payawebsite" id="payawebsite" placeholder="กรอกเว็บไซต์บริษัท (ถ้ามี)" autocomplete="off">
									</div>
								</div>

							</div>
						</div>

						<div class="modal-footer">
							<input type="hidden" name="payaid_edit" id="payaid_edit" value="" />
							<input type="submit" class="btn btn-success px-4 py-2" name="payaid_addinsert" id="payaid_addinsert" value="บันทึก" />
							<input type="button" class="btn btn-danger px-4 py-2" name="payaid_cancel" id="payaid_cancel" value="ยกเลิก" data-dismiss="modal">
						</div>

					</form>
				</div>
			</div>
		</div>

		<script>
			const url = new URL(window.location.href);
			const params = new URLSearchParams(url.search);
			const cid = params.get('cid');
			const dep = params.get('dep');

			function fetchMonth() {
				$.ajax({
					url: "list_tax_invoice_action.php?cid=" + cid + '&dep=' + dep,
					type: 'get',
					dataType: "json",
					data: {
						"action": "get_month_tax_purchase",
					},
					success: function(result) {
						let option = $("#tax_month").find('option')
						let m = [];
						html = ''
						html += '<option selected disabled>กรุณาเลือกเดือนที่จะออกภาษีซื้อ</option>'
						for(let i = 0; i < result.data.length; i++){
							let date = result.data[i]['tax_created_at']
							let year = date.split('-')[0]
							let month = Number(date.split('-')[1])
							$(option[month]).remove()

						}
					},
					error: function(error) {
						console.log(error)
					}
				})
			}

			$(document).ready(function() {
				let month = new Date().getMonth() + 1
				fetchMonth()

				$(document).on('change', '#tax_month', function() {
					let month = $(this).val()
					fetchTaxInvoice(month)
				})

				function fetchTaxInvoice(month = '') {
					$.ajax({
						url: "list_tax_invoice_action.php?cid=" + cid + '&dep=' + dep,
						type: 'get',
						dataType: "json",
						data: {
							"action": "get_tax_invoice",
							month
						},
						success: function(result) {
							if (result.data.length == 0) {
								$("#multi_input").html("")
								$(".result_add").hide()
								$("#btnInsert_tax").attr('disabled', true)
							} else {
								console.log(result.data)
								showTaxInvoice(result.data)
							}
						},
						error: function(error) {
							console.log(error)
						}
					})
				}

				function showTaxInvoice(data) {
					$("#multi_input").html("")
					if (data != undefined || data != null) {
						for (let i = 0; i < data.length; i++) {
							add_input("create", data[i])
						}
					}
				}

				function add_input(mode, data) {
					let id = uuidv4()
					var c_tr = $("#multi_input tr");

					if (mode === "update") {
						index_add_input++
					}

					if (c_tr.length === 0) {
						index_add_input = 1
					}

					if (c_tr.length > 0) {
						index_add_input = c_tr.length + 1
					}

					html = ''
					html += `
							<tr id=${id}>
								<td width="5%" class="text-center t_index">
									${index_add_input++}
								</td>
								<td width="9%" class="text-center">
									<input type="text" class="form-control _multi" name="date_input" autocomplete="off" value="${data['tax_invoice_date']}" readonly>
								</td>
								<td width="14%" class="text-center">
									<input type="text" class="form-control _multi" name="book_number_input" autocomplete="off" value="${data['tax_invoice_number']}" readonly>
								</td>
								<td width="18%" class="text-center">
									<input type="text" class="form-control _multi search_list_desc" name="list_input" autocomplete="off" value=${data['invoice_list_desc']} readonly>
								</td>
								<td width="18%" class="text-center">
									<input type="text" class="form-control _multi search_paya_row" name="company_input" autocomplete="off" value="${data['paya_name']}" readonly>
								</td>
								<td width="9%" class="text-center">
									<input type="text" class="form-control _multi text-right" name="price_input" autocomplete="off" value="${data['total_invoice_amount']}" readonly>	
								</td>
								<td width="9%" class="text-center">	
									<input type="text" class="form-control _multi text-right" name="vat_input" autocomplete="off" value="${data['total_invoice_vat']}" readonly>
								</td>
								<td width="14%" class="text-center">
									<input type="text" class="form-control _multi text-right" name="result_input" autocomplete="off" value="${data['total_invoice_total']}" readonly>
								</td>
								<td width="4%" class="text-center t_index_del">
									<a class="btn btn-danger" onclick="del_input('${id}');"><i class="icofont-ui-delete"></i></a>
								</td>
								<td width="0%">
									<input type="hidden" class="paya_id _multi" name="paya_id" value="${data['paya_id']}"/>
								</td>
								<td width="0%">
									<input type="hidden" class="_multi" name="tax_invoice_id" value="${data['tax_invoice_id']}"/>
								</td>
							</tr>`

					add_input_i++

					$("#multi_input").append(html);
					_resultall()
					if (document.querySelectorAll("#multi_input tr").length > 0) {
						$(".result_add").show()
						$("#btnInsert_tax").attr('disabled', false)
					}

				}
			})

			// $(document).on('keyup', '.search_paya_row', function() {
			// 	let parent_td = $(this).parent()
			// 	let ele = $(this).val()
			// 	let id = $(parent_td).children("div").attr("id")
			// 	let show_list = $(parent_td).children("div")
			// 	if (ele != "") {
			// 		$.ajax({
			// 			url: "action_script_payable_all.php",
			// 			method: "post",
			// 			data: {
			// 				query_payable: ele,
			// 			},
			// 			success: function(data) {
			// 				$(show_list).fadeIn();
			// 				$(show_list).html(data)
			// 			},
			// 		});
			// 	} else {
			// 		$(show_list).fadeOut();
			// 		$(show_list).html("");
			// 	}
			// })

			// $(document).on("click", "li.payable_row", function() {
			// 	let text = $(this).text()
			// 	let id_paya = $(this).attr("id")
			// 	let parent_ul = $(this).parent().parent().parent()
			// 	let parent_tr = $(this).parent().parent().parent().parent()
			// 	let ele_txt = $(parent_ul).find(".search_paya_row")
			// 	let ele_id = $(parent_tr).find(".paya_id")
			// 	let list_v = $(parent_ul).children("div")
			// 	$(ele_txt).val(text)
			// 	$(ele_id).val(id_paya)
			// 	$(list_v).html("");
			// 	$(list_v).fadeOut();
			// });

			// $(document).on('keyup', '.search_list_desc', function() {
			// 	let parent_td = $(this).parent()
			// 	let ele = $(this).val()
			// 	let show_list = $(parent_td).children("div")
			// 	if (ele != "") {
			// 		$.ajax({
			// 			url: "action_script_list_desc.php",
			// 			method: "post",
			// 			data: {
			// 				query_list: ele,
			// 			},
			// 			success: function(data) {
			// 				$(show_list).fadeIn();
			// 				$(show_list).html(data)
			// 			},
			// 		});
			// 	} else {
			// 		$(show_list).fadeOut();
			// 		$(show_list).html("");
			// 	}
			// });

			// $(document).on("click", "li.list_desc_row", function() {
			// 	let text = $(this).text()
			// 	let parent_ul = $(this).parent().parent().parent()
			// 	let parent_tr = $(this).parent().parent().parent().parent()
			// 	let ele_txt = $(parent_ul).find(".search_list_desc")
			// 	let list_v = $(parent_ul).children("div")
			// 	$(ele_txt).val(text)
			// 	$(list_v).html("");
			// 	$(list_v).fadeOut();
			// });

			$(document).on('keyup', '._multi', function() {
				var _att = $(this).attr("name");
				var check = $(this).parent().parent();
				var v_result = $(check).children().children()

				var num = $(v_result[6]).val();
				var num2 = $(v_result[7]).val();

				var sum = parseFloat(num) + parseFloat(num2);
				var result = sum.toFixed(2);

				if (isNaN(result)) {
					if (num == "") {
						$(v_result[8]).val(num2);
					} else if (num2 == "") {
						$(v_result[8]).val(num);
					}
				} else {
					$(v_result[8]).val(result);
				}
				_resultall()
			});

			$('#insert_form').on("submit", function(event) {
				event.preventDefault();
				var comp_id = $(this).attr("id");
				if ($('#payaname').val() == '') {
					swal({
						title: "กรุณากรอกชื่อบริษัท",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						insert_form.payaname.focus();
					});
				} else if ($('#payaaddress').val() == '') {
					swal({
						title: "กรุณากรอกที่อยู่บริษัท",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						insert_form.payaaddress.focus();
					});
				} else if ($('#payataxno').val() == '') {
					swal({
						title: "กรุณากรอกเลขประจำตัวผู้เสียภาษี",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						insert_form.payataxno.focus();
					});
				} else {
					$.ajax({
						url: "r_payable_add.php",
						type: "POST",
						data: $('#insert_form').serialize(),
						dataType: "Text",
						success: function(data) {
							$('#add_data_Payable').modal('hide');
							$(".modal-backdrop").remove();
							swal({
								title: "บันทึกข้อมูลสำเร็จ",
								text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
								type: "success",
								closeOnClickOutside: false
							});
						}
					});
				}
			});
		</script>
		<script type="text/javascript" src="js/script_tax_purchase.js"></script>

		<?php include 'footer.php'; ?>

	</body>

	</html>
<?php } ?>