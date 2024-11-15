<?php
session_start();
if (!$_SESSION["user_name"]) {  //check session
	Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
} else {
	include 'connect.php';
	$cid = $_GET["cid"];
	$dep = $_GET["dep"];
	$tax_id = $_GET['taxid'];
	$str_sql_user = "SELECT * FROM user_tb AS u 
						INNER JOIN level_tb AS l ON u.user_levid = l.lev_id 
						INNER JOIN department_tb AS d ON u.user_depid = d.dep_id 
						WHERE user_id = '" . $_SESSION["user_id"] . "'";
	$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
	$obj_row_user = mysqli_fetch_array($obj_rs_user);
	$sql_query = "SELECT *,tp.tax_result,tp.tax_vat,tp.tax_price,c.comp_name,d.dep_id,d.dep_name,tp.tax_number,tp.tax_created_at
					FROM taxpurchase_tb as tp 
					INNER JOIN company_tb as c ON tp.tax_comp_id = c.comp_id
					INNER JOIN department_tb as d ON tp.tax_dep_id = d.dep_id
					INNER JOIN taxpurchaselist_tb as tpl ON tp.tax_id = tpl.list_tax_id
					INNER JOIN payable_tb as p ON tpl.list_paya_id = p.paya_id
					WHERE tp.tax_id = '$tax_id' AND tp.tax_comp_id = '$cid' AND tp.tax_dep_id = '$dep'";

	$sql_obj = mysqli_query($obj_con, $sql_query);
	$sql_obj_row = mysqli_fetch_array($sql_obj);

	if (!$sql_obj_row) {
		Header("Location: index.php");
		exit();
	}

	$str_sql_comp = "SELECT * FROM company_tb WHERE comp_id = '" . $cid . "'";
	$obj_rs_comp = mysqli_query($obj_con, $str_sql_comp);
	$obj_row_comp = mysqli_fetch_array($obj_rs_comp);
}
?>
<!DOCTYPE html>
<html>

<head>
	<?php include 'head.php'; ?>
	<link rel="stylesheet" type="text/css" href="css/checkbox.css">
	<script type="text/javascript" src="js/calinvoice.js"></script>
	<script type="text/javascript" src="js/script_tax_purchase.js"></script>
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
			<input type="text" class="form-control d-none" id="taxid" value="<?= $tax_id ?>">
			<div class="row py-4 px-1" style="background-color: #E9ECEF">
				<div class="col-md-12">
					<h3 class="mb-0">
						<i class="icofont-plus-circle"></i>&nbsp;&nbsp;แก้ไขใบภาษีซื้อ
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
						<input type="text" name="searchCompany" id="searchCompany2" class="form-control" placeholder="กรอกบางส่วนของชื่อ-นามสกุล/ชื่อบริษัท" autocomplete="off" value="<?= $sql_obj_row['comp_name']; ?>" readonly>
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
						<input type="text" name="searchCompany" id="searchCompany" class="form-control" placeholder="กรอกบางส่วนของชื่อ-นามสกุล/ชื่อบริษัท" value="<?= $sql_obj_row['comp_name']; ?>" autocomplete="off" readonly>
						<input type="text" class="form-control d-none" id="invcompid2" value="<?= $obj_row_comp["comp_id"]; ?>">
					</div>
					<div class="list-group" id="show-listComp"></div>
				</div>
				<div class="col-lg-3 col-md-4 col-sm-12 pt-1 pb-3">
					<label for="SelectDep" class="mb-1">ฝ่าย</label>
					<select class="custom-select form-control" id="SelectDep" name="SelectDep" disabled style="background-color: #FFF; color: #000;">
						<option value="<?= $sql_obj_row['dep_name']; ?>" selected>
							<?= $sql_obj_row['dep_name']; ?>
						</option>
						<input type="text" class="form-control d-none" id="tax_dep_name" value="<?= $sql_obj_row['dep_name']; ?>">
						<input type="text" class="form-control d-none" id="invdepid" value="<?= $sql_obj_row['dep_id']; ?>">
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
						<input type="date" class="form-control" name="invdate" id="invdate" value="<?= $sql_obj_row["tax_created_at"]; ?>" autocomplete="off">
					</div>
					<span style="color: #F00;padding-top: 2px" id="altinvdate"></span>
				</div>
				<div class="col-lg-6 col-md-4 col-sm-12 pt-1 pb-3" id="showDataComp">
					<label for="searchCompany" class="mb-1">เลขประจำตัวผู้เสียภาษีอากร</label>
					<div class="input-group">
						<input type="text" name="searchCompany" id="searchTax" class="form-control" placeholder="กรอกบางส่วนของชื่อ-นามสกุล/ชื่อบริษัท" value="<?= $sql_obj_row['tax_number']; ?>" autocomplete="off" readonly>
					</div>
				</div>

				<div class="row pt-3 mt-3" style="border-top: 3px solid #e9ecef;">
					<div class="col-lg-4 col-md-4 col-sm-12 pt-1 pb-1">
						<input type="text" name="" id="tax_number" class="form-control" placeholder="ค้นหาเลขที่ใบกำกับภาษี" value="" autocomplete="off">
					</div>

					<fieldset class="" style="border: 1px solid #e9ecef; width: 65%;">
						<legend style="font-size: 14px; width: auto; margin-bottom: 0% !important">&nbsp;ข้อมูลใบกำกับภาษี&nbsp;</legend>
						<div class="row p-2">
							<div class="col-lg-4 col-md-4 col-sm-12 pt-1 pb-1">
								<input type="text" name="tax_invoice_number" class="form-control search_tax" placeholder="เล่มที่/เลขที่ใบกำกับภาษี" autocomplete="off" readonly>
							</div>
							<div class="col-lg-2 col-md-4 col-sm-12 pt-1 pb-1">
								<input type="text" name="tax_invoice_date" class="form-control search_tax" placeholder="วันที่ใบกำกับภาษี" autocomplete="off" readonly>
							</div>
							<div class="col-lg-6 col-md-4 col-sm-12 pt-1 pb-1">
								<input type="text" name="paya_name" class="form-control search_tax" placeholder="ชื่อผู้ซื้อ/ชื่อผู้รับบริการ" autocomplete="off" readonly>
								<input type="text" name="inv_payaid" class="form-control d-none search_tax" autocomplete="off" readonly>
							</div>
							<div class="col-lg-12 col-md-4 col-sm-12 pt-1 pb-1">
								<input type="text" name="invoice_list_desc" class="form-control search_tax" placeholder="รายการ" autocomplete="off" readonly>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-12 pt-1 pb-1">
								<div class="input-group">
									<input type="text" name="total_invoice_amount" class="form-control text-right search_tax" placeholder="มูลค่าสินค้า" autocomplete="off" readonly>
									<div class="input-group-append">
										<i class="input-group-text">
											<i class="icofont-baht"></i>
										</i>
									</div>
								</div>
							</div>

							<div class="col-lg-3 col-md-4 col-sm-12 pt-1 pb-1">
								<div class="input-group">
									<input type="text" name="total_invoice_vat" class="form-control text-right search_tax" placeholder="ภาษีมูลค่าเพิ่ม" autocomplete="off" readonly>
									<div class="input-group-append">
										<i class="input-group-text">
											<i class="icofont-baht"></i>
										</i>
									</div>
								</div>
							</div>

							<div class="col-lg-3 col-md-4 col-sm-12 pt-1 pb-1">
								<div class="input-group">
									<input type="text" name="total_invoice_total" class="form-control text-right search_tax" placeholder="รวม" autocomplete="off" readonly>
									<div class="input-group-append">
										<i class="input-group-text">
											<i class="icofont-baht"></i>
										</i>
									</div>
								</div>
							</div>
							<input type="text" name="paya_id" class="form-control d-none search_tax" autocomplete="off" readonly>
							<input type="text" name="tax_invoice_id" class="form-control d-none search_tax" autocomplete="off" readonly>

							<div class="col-lg-2 col-md-4 col-sm-12 pt-1 pb-1">
								<input type="button" class="btn btn-info" id="add_invoice" value="เพิ่ม">
							</div>

						</div>


					</fieldset>
				</div>
			</div>

			<div class="row pb-4 px-1" style="background-color: #FFFFFF">
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
								<th width="0%"></th>
							</tr>
						</thead>
						<tbody id="multi_input">
							<?php $i = 1; ?>
							<?php foreach ($sql_obj as $row) { ?>
								<tr id="<?= $row['list_id']; ?>">
									<td width="5%" class="text-center t_index">
										<?= $i++; ?>
									</td>
									<td width="9%" class="text-center">
										<input type="date" class="form-control _multi" name="date_input" autocomplete="off" value="<?= $row['created_at']; ?>" readonly>
									</td>
									<td width="14%" class="text-center">
										<input type="text" class="form-control _multi" name="book_number_input" autocomplete="off" value="<?= $row['list_no']; ?>" readonly>
									</td>
									<td width="18%" class="text-center">
										<input type="text" class="form-control _multi search_list_desc" name="list_input" autocomplete="off" value="<?= $row['list_desc']; ?>" readonly>
									</td>
									<td width="18%" class="text-center">
										<input type="text" class="form-control _multi search_paya_row" name="company_input" autocomplete="off" value="<?= $row['paya_name']; ?>" readonly>
									</td>
									<td width="9%" class="text-center">
										<input type="text" class="form-control _multi text-right" name="price_input" autocomplete="off" value="<?= $row['list_price']; ?>" readonly>
									</td>
									<td width="9%" class="text-center">
										<input type="text" class="form-control _multi text-right" name="vat_input" autocomplete="off" value="<?= $row['list_vat']; ?>" readonly>
									</td>
									<td width="14%" class="text-center">
										<input type="text" class="form-control _multi text-right" name="result_input" autocomplete="off" value="<?= $row['list_result']; ?>" readonly>
									</td>
									<td width="4%" class="text-center t_index_del">
										<a class="btn btn-danger del_data" id="<?= $row['list_id']; ?>"><i class="icofont-ui-delete"></i></a>
									</td>
									<td width="0%">
										<input type="hidden" class="paya_id _multi" name="paya_id" value="<?= $row['paya_id']; ?>" />
									</td>
									<td width="0%">
										<input type="hidden" class="_multi" name="tax_invoice_id" value="<?= $row['list_tax_invoice_id']; ?>" />
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
					<table>
						<tr>
							<td width="5%"></td>
							<td width="10%"></td>
							<td width="10%"></td>
							<td width="15%"></td>
							<td width="15%">รวมยอด</td>
							<td width="8%">
								<input type="text" class="form-control text-right" autocomplete="off" id="price_all" value="<?= $sql_obj_row["tax_price"]; ?>" readonly>
								<input type="text" class="form-control text-right d-none" autocomplete="off" id="price_nf" value="<?= $sql_obj_row["tax_price"]; ?>" readonly>
							</td>
							<td width="10%">
								<input type="text" class="form-control text-right" autocomplete="off" id="vat_all" value="<?= $sql_obj_row["tax_vat"]; ?>" readonly>
								<input type="text" class="form-control text-right d-none" autocomplete="off" id="vat_nf" value="<?= $sql_obj_row["tax_vat"]; ?>" readonly>
							</td>
							<td width="8%">

								<input type="text" class="form-control text-right" autocomplete="off" id="result_all" value="<?= $sql_obj_row["tax_result"]; ?>" readonly>
								<input type="text" class="form-control text-right d-none" autocomplete="off" id="result_nf" value="<?= $sql_obj_row["tax_result"]; ?>" readonly>
							</td>
							<td width="4%">
							</td>
						</tr>
					</table>
				</div>
			</div>

			<div class="row py-4 px-1" style="background-color: #FFFFFF">
				<div class="col-md-12 pb-4 text-center">
					<input type="button" class="btn btn-success px-5 py-2 mx-1" id="btnUpdate_tax" value="preview">
				</div>
			</div>
		</div>
	</section>
	<section id="tax_preview">
	</section>

	<script>
		const url = new URL(window.location.href);
		const params = new URLSearchParams(url.search);
		const cid = params.get('cid');
		const dep = params.get('dep');

		del_list = [];

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
				$("#btnUpdate_tax").attr('disabled', false)
			}

		}


		function showTaxSearch(data) {
			let ele = $(".search_tax")

			for (let i = 0; i < ele.length; i++) {
				let _attr = $(ele[i]).attr("name")
				let _val = data[_attr]

				$(ele[i]).val(_val)
			}
		}

		function clear() {
			let ele = $(".search_tax")
			for (let i = 0; i < ele.length; i++) {
				let _attr = $(ele[i]).attr("name")

				$(ele[i]).val('')
			}
		}

		$(document).on('click', '#add_invoice', function() {
			let ele = $(".search_tax")
			let obj = {}

			for (let i = 0; i < ele.length; i++) {
				let _attr = $(ele[i]).attr("name")
				let _val = $(ele[i]).val()

				obj[_attr] = _val
			}

			add_input("update", obj)
		})

		$(document).on('keydown', '#tax_number', function() {
			if (event.key === 'Enter') {
				searchTax = $("#tax_number").val()
				$.ajax({
					url: "list_tax_invoice_action.php?cid=" + cid + '&dep=' + dep,
					type: "get",
					data: {
						"action": "search_tax_invoice",
						"search": searchTax
					},
					dataType: "json",
					success: function(data) {
						console.log(data)
						if (data['status'] == "success") {
							if (data['data'] == null) {
								swal({
									title: "ไม่พบข้อมูลหรือข้อมูลถูกบันทึกแล้ว",
									text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
									type: "warning",
									closeOnClickOutside: false
								})
								clear()
								return
							} else {
								showTaxSearch(data['data'])
							}
						} else {
							swal({
								title: data.message,
								text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
								type: data.status,
								closeOnClickOutside: false
							})
							clear()
							return
						}
					}
				});
			}
		})

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

		$(document).on('click', '.del_data', function() {
			var del_id = $(this).attr("id");
			var element = this;
			del_list.push(del_id);
			$(element).closest('tr').fadeOut(0, function() {
				$(this).remove();
			});

			count_tr()
			_resultall()

			if (document.querySelectorAll("#multi_input tr").length === 0) {
				$("#btnUpdate_tax").attr('disabled', true)
			}
		});

	</script>
	<?php include 'footer.php'; ?>

</body>

</html>