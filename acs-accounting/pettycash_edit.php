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
						WHERE user_id = '". $_SESSION["user_id"] ."'";
		$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
		$obj_row_user = mysqli_fetch_array($obj_rs_user);

		// echo $obj_row_user["lev_name"];

		$str_sql_dep = "SELECT * FROM department_tb WHERE dep_id = '".$dep."'";
		$obj_rs_dep = mysqli_query($obj_con, $str_sql_dep);
		$obj_row_dep = mysqli_fetch_array($obj_rs_dep);

		$departName = $obj_row_dep['dep_name'];

		$str_sql_comp = "SELECT * FROM company_tb WHERE comp_id = '".$cid."'";
		$obj_rs_comp = mysqli_query($obj_con, $str_sql_comp);
		$obj_row_comp = mysqli_fetch_array($obj_rs_comp);


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
		@media only screen and (max-width: 992px)  {
			.descbtn {
				display: none;
			}
		}
		div#show-listPaya, div#show-listPettycash {
			position: absolute;
			z-index: 99;
			width: 100%;
			margin-left: -15px!important;
		}
		.list-unstyled {
			position: relative;
			background-color:#FFFF;
			cursor:pointer;
			margin-left: 15px;
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
			padding: .75rem!important;
		}
		.list-group-item:hover {
			background-color: #f5f5f5;
		}

		.custom-border-top {
			border-top: 2px solid #0F3C6DFF; 
			padding-top: 25px;
		}

		.alert {
			padding: 15px;
			margin: 10px 20px;
			border: 1px solid transparent;
			border-radius: 4px;
			position: relative;
		}
		.alert-danger {
			background-color: #f8d7da;
			border-color: #f5c6cb;
			color: #721c24;
		}
		.alert .close {
			position: absolute;
			top: 5px;
			right: 10px;
			font-size: 20px;
			line-height: 20px;
			color: #721c24;
			cursor: pointer;
		}

		.spinner-container {
			display: none;
			position: absolute;
			left: 50%;
			top: 50%;
			transform: translate(-50%, -50%);
			z-index: 10;
			text-align: center;
		}
		.spinner-border {
			width: 50px;
			height: 50px;
			border-width: 4px;
			margin-bottom: 10px;
		}

		.loading-text {
			font-size: 16px;
			color: #17a2b8;
			font-weight: bold;
		}

		#pdfViewer {
			display: none;
			box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
			border-radius: 8px;
			margin-top: 20px;
		}

		.modal-body {
			transition: all 0.3s ease;
		}

	</style>

</head>
<body>

	<?php include 'navbar.php'; ?>

	<section>
		<div class="container-fluid">
			<form id="editPettyCashForm" method="POST">
				<div class="row py-4 px-1" style="background-color: #EDEDEDFF">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-plus-circle"></i>&nbsp;&nbsp;แก้ไขใบจ่ายเงินสดย่อย <span id="showPCashNo" class="text-info"><h5></h5></span>
							<input type="hidden" name="pcash_id" id="pcash_id">
						</h3>
					</div>

					<div class="col-md-12 alert alert-danger mt-2" id="errorMessages" style="display:none;">
						<strong>ข้อผิดพลาด:</strong> <br>
						<span class="error-text"></span>
					</div>


					<div class="col-md-8 pt-3" id="showDataComp">
						<label for="searchCompany" class="mb-1">ชื่อ-นามสกุล / ชื่อบริษัทในเครือ</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-company"></i>
								</i>
							</div>
							<input type="text" class="form-control" value="<?=$obj_row_comp["comp_name"];?>" readonly>

							<input type="hidden" class="form-control" name="pcash_comp_id" id="pcash_comp_id" value="<?=$cid;?>">
						</div>
					</div>

					<input type="hidden" name="pcash_no" id="pcash_no">

					<div class="col-md-4 pt-3">
						<label for="pcash_date" class="mb-1">วันที่ออกใบจ่ายเงินสดย่อย</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-ui-calendar"></i>
								</i>
							</div>
							<input type="date" class="form-control" name="pcash_date" id="pcash_date">
						</div>
					</div>


					<input type="hidden" class="form-control" name="pcash_dep_id" id="pcash_dep_id" value="<?=$dep;?>">

					<div class="col-md-12 pt-3" id="showDataPaya">
						<label for="searchPayable" class="mb-1">ชื่อผู้รับ</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-company"></i>
								</i>
							</div>
							<input type="text" name="searchPayable" id="searchPayable" class="form-control" placeholder="กรอกบางส่วนของชื่อ-นามสกุล/ชื่อบริษัท" autocomplete="off" autofocus>

							<input type="hidden" name="invpayaid" id="invpayaid">

							<div class="input-group-append">
								<button type="button" class="btn btn-info" onclick="
								document.getElementById('searchPayable').value = ''; 
								document.getElementById('invpayaid').value = '';
								document.getElementById('searchPayable').focus();
								document.getElementById('show-listPaya').style.display = 'none';" title="Clear">
									<i class="icofont-close-circled"></i>
									<span class="descbtn">&nbsp;&nbsp;Clear</span>
								</button>
								<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#AddPayable" data-backdrop="static" data-keyboard="false" title="เพิ่มผู้รับ">
									<i class="icofont-plus-circle"></i>
									<span class="descbtn">&nbsp;&nbsp;เพิ่มผู้รับ</span>
								</button>
							</div>
						</div>
						<div class="list-group" id="show-listPaya"></div>
					</div>

					<div class="col-md-3 pt-3">
						<label for="pettyCashList" class="mb-1 d-flex align-items-center">
							รายการค่าใช้จ่าย 
							<span class="ml-2">
								<input type="text" name="pcash_net_amount" id="pcash_net_amount" class="form-control d-inline-block w-auto d-none" readonly>
							</span>
							<input type="hidden" name="pcash_type" id="pcash_type" value="1">
							<input type="hidden" name="pcash_fee" id="pcash_fee">
							<input type="hidden" name="pcash_vat" id="pcash_vat">
							<input type="hidden" name="pcash_tax" id="pcash_tax">
							<input type="hidden" name="pcash_total" id="pcash_total">
							<input type="hidden" name="pcash_vat_diff" id="pcash_vat_diff">
							<input type="hidden" name="pcash_tax_diff" id="pcash_tax_diff">
							<input type="hidden" name="pcash_total_diff" id="pcash_total_diff">
						</label>
					</div>

					<div class="col-md-9"></div>

					<div id="inputContainer"></div>

					<div class="button-group ml-3">
						<button type="button" class="btn btn-secondary mt-3" onclick="addNewInput()">เพิ่มรายการ</button>
						<button type="button" class="btn btn-info mt-3" id="previewPettyCash">ดูตัวอย่าง</button>
					</div>


				</div>

		
			</form>
			
		</div>
	</section>

	<div id="pettyCashModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="pettyCashModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title py-2">
						ตัวอย่าง
					</h3>
					<span class="text-info" id="pCashType"></span>
					<button type="button" class="close" name="pcash_cancel" id="pcash_cancel" data-dismiss="modal" aria-label="Close">&times;</button>
				</div>
				<div class="modal-body" id="pettyCashDetail">
					<div id="loadingSpinner" class="spinner-container">
						<div class="spinner-border text-info" role="status">
						</div>
						<div class="loading-text">กำลังโหลด...</div> 
					</div>

					<iframe id="pdfViewer" width="100%" height="600px" style="display: none; border: none; box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);"></iframe>
				
					<div class="row ml-3 mt-2">
						<div class="col-6">
							<p class="text-success">
								<i class="icofont-check-circled"></i> ขอคืนภาษีทั้งหมด: 
								<span><b id="totalTaxRefund"></b></span> รายการ
							</p>
							<p class="text-danger">
								<i class="icofont-close-circled"></i> ไม่ขอคืนภาษีทั้งหมด: 
								<span><b id="totalNoTaxRefund"></b></span> รายการ
							</p>
						</div>
						<div class="col-6">
							<p>
								<i class="icofont-file-document"></i> มีใบกำกับภาษี <span><b id="totalTaxList"></b></span> รายการ
							</p>
							<ul id="taxList" class="d-none">
							</ul>
						</div>
					</div>
				</div>
				
				
				<div class="modal-footer">
					<button type="submit" class="btn btn-success" id="savePettyCashForm">บันทึก</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
				</div>
			</div>
		</div>
	</div>


	<?php include 'invoice_add_payable.php'; ?>

	<script type="text/javascript">
		const urlParams = new URLSearchParams(window.location.search);

		const pCashId = urlParams.get('pCashId');

		let itemCount = 0;
		const MAX_ITEMS = 5; 

		function formatDateTH(date, type = "full") {
			const monthNames = [
				"มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน",
				"กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"
			];

			// Check if date is valid
			if (!date) {
				return type === "month" ? 'ไม่ได้เลือกเดือน' : 'ไม่ได้เลือกวันที่';
			}

			if (type === "month") {
				const monthIndex = parseInt(date, 10) - 1; 
				return monthNames[monthIndex] || 'ไม่ได้เลือกเดือน';
			} else if (type === "full") {
				const dateParts = date.split('-');
				if (dateParts.length !== 3) return 'ไม่ได้เลือกวันที่';
				
				const day = parseInt(dateParts[2], 10);
				const month = parseInt(dateParts[1], 10);
				const year = parseInt(dateParts[0], 10) + 543;
				const monthName = monthNames[month - 1];

				return `${day} ${monthName} ${year}`;
			}
		}


		function fetchDataById(pCashId) {
			let itemCount = 0;
			$.ajax({
				url: "pettycash_controller.php",
				type: "POST",
				data: {
					action: "fetchDataById",
					pCashId: pCashId
				},
				beforeSend: function() {
					console.log('loading...');
				},
				success: function(response) {
					if (response.status === 'success') {
						var data = response.data;
						console.log(data);
				
						document.getElementById('pcash_id').value = data.pCashId;
						document.getElementById('pcash_date').value = data.pCashDate;
						document.getElementById('invpayaid').value = data.pCashPayaId;
						document.getElementById('searchPayable').value = data.pCashPayaName;


						document.getElementById('pcash_no').value = data.pCashNo;

						document.getElementById('pcash_fee').value = data.pCashFee;
						document.getElementById('pcash_vat').value = data.pCashVat;
						document.getElementById('pcash_vat_diff').value = data.pCashVatDiff;
						document.getElementById('pcash_tax').value = data.pCashTax;
						document.getElementById('pcash_tax_diff').value = data.pCashTaxDiff;
						document.getElementById('pcash_total').value = data.pCashTotal;
						document.getElementById('pcash_total_diff').value = data.pCashTotalDiff;
						document.getElementById('pcash_net_amount').value = data.pCashNetAmount;

						document.getElementById('showPCashNo').innerHTML = data.pCashNo;
						
						

						data.items.forEach((item, index) => {
							const itemCount = index + 1;
							const inputTemplate = `
								<div class="input-group mt-4 custom-border-top item-box" id="item-${itemCount}">
									<div class="col-md-1">
										<label>ลำดับ</label>
										<input type="text" class="form-control" id="item_no-${itemCount}" readonly value="${itemCount}">
									</div>
									
									<div class="col-md-4">
										<label for="pcl_description-${itemCount}">รายการ</label>
										<input type="text" class="form-control" name="pcl_description[]" id="pcl_description-${itemCount}"  value="${item.pcl_descriptions || ''}" autocomplete="off">
									</div>

									<div class="col-md-2">
										<label for="pcl_tax_no-${itemCount}">เลขที่ใบกำกับภาษี</label>
										<input type="text" class="form-control" name="pcl_tax_no[]" id="pcl_tax_no-${itemCount}"  value="${item.pcl_tax_nos || ''}" autocomplete="off">
									</div>

									<div class="col-md-2">
										<label for="pcl_tax_date-${itemCount}" class="mb-1">วันที่ใบกำกับภาษี</label>
										<div class="input-group">
											<div class="input-group-prepend">
												<i class="input-group-text">
													<i class="icofont-ui-calendar"></i>
												</i>
											</div>
											<input type="date" class="form-control" name="pcl_tax_date[]" id="pcl_tax_date-${itemCount}" value="${item.pcl_tax_dates || ''}">
										</div>
									</div>

									<div class="col-md-2">
										<label for="pcl_tax_month-${itemCount}">เดือนภาษีมูลค่าเพิ่ม</label>
										<select class="form-control" name="pcl_tax_month[]" id="pcl_tax_month-${itemCount}">
											<option value="">เลือกเดือนภาษีมูลค่าเพิ่ม</option>
											<option value="1" ${item.pcl_tax_months == '1' ? 'selected' : ''}>มกราคม</option>
											<option value="2" ${item.pcl_tax_months == '2' ? 'selected' : ''}>กุมภาพันธ์</option>
											<option value="3" ${item.pcl_tax_months == '3' ? 'selected' : ''}>มีนาคม</option>
											<option value="4" ${item.pcl_tax_months == '4' ? 'selected' : ''}>เมษายน</option>
											<option value="5" ${item.pcl_tax_months == '5' ? 'selected' : ''}>พฤษภาคม</option>
											<option value="6" ${item.pcl_tax_months == '6' ? 'selected' : ''}>มิถุนายน</option>
											<option value="7" ${item.pcl_tax_months == '7' ? 'selected' : ''}>กรกฎาคม</option>
											<option value="8" ${item.pcl_tax_months == '8' ? 'selected' : ''}>สิงหาคม</option>
											<option value="9" ${item.pcl_tax_months == '9' ? 'selected' : ''}>กันยายน</option>
											<option value="10" ${item.pcl_tax_months == '10' ? 'selected' : ''}>ตุลาคม</option>
											<option value="11" ${item.pcl_tax_months == '11' ? 'selected' : ''}>พฤศจิกายน</option>
											<option value="12" ${item.pcl_tax_months == '12' ? 'selected' : ''}>ธันวาคม</option>
										</select>
									</div>

									<div class="col-md-2 pt-3">
										<label for="pcl_fee-${itemCount}">ค่าบริการ</label>
										<input type="text" class="form-control" name="pcl_fee[]" id="pcl_fee-${itemCount}" oninput="calculateItem(${itemCount})" value="${item.pcl_fees || ''}" autocomplete="off">
									</div>

									<div class="col-md-1 pt-3">
										<label for="pcl_vat_percent-${itemCount}">VAT %</label>
										<input type="text" class="form-control" name="pcl_vat_percent[]" id="pcl_vat_percent-${itemCount}" oninput="calculateItem(${itemCount})" value="${item.pcl_vat_percents || ''}" autocomplete="off">
									</div>

									<div class="col-md-1 pt-3">
										<label for="pcl_vat_diff-${itemCount}">+ / -</label>
										<input type="text" class="form-control" name="pcl_vat_diff[]" id="pcl_vat_diff-${itemCount}" oninput="calculateItem(${itemCount})" value="${item.pcl_vat_diffs || ''}" autocomplete="off">
									</div>
									

									<div class="col-md-1 pt-3">
										<label for="pcl_tax_percent-${itemCount}">TAX %</label>
										<input type="text" class="form-control" name="pcl_tax_percent[]" id="pcl_tax_percent-${itemCount}" oninput="calculateItem(${itemCount})" value="${item.pcl_tax_percents || ''}" autocomplete="off">
									</div>


									<div class="col-md-1 pt-3">
										<label for="pcl_tax_diff-${itemCount}">+ / -</label>
										<input type="text" class="form-control" name="pcl_tax_diff[]" id="pcl_tax_diff-${itemCount}" oninput="calculateItem(${itemCount})" value="${item.pcl_tax_diffs || ''}" autocomplete="off">
									</div>

									
								 	<div class="col-md-3 pt-3">
										<label class="d-block">ภาษีคืน</label>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="pcl_tax_refund-${itemCount}" id="pcl_tax_refund_yes-${itemCount}" value="1" ${item.pcl_tax_refunds == '1' ? 'checked' : ''}>
											<label class="form-check-label" for="pcl_tax_refund_yes-${itemCount}">ขอคืนภาษี</label>
										</div>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="pcl_tax_refund-${itemCount}" id="pcl_tax_refund_no-${itemCount}" value="0" ${item.pcl_tax_refunds == '0' ? 'checked' : ''}>
											<label class="form-check-label" for="pcl_tax_refund_no-${itemCount}">ไม่ขอคืนภาษี</label>
										</div>
									</div>

									<div class="col-md-3 pt-3"></div>

									<div class="col-md-2 pt-3">
										<label for="pcl_total-${itemCount}">ยอดชำระ</label>
										<input type="text" class="form-control" name="pcl_total[]" id="pcl_total-${itemCount}" value="${item.pcl_totals || ''}" readonly>
									</div>

									<div class="col-md-2 pt-3">
										<label for="pcl_vat-${itemCount}">VAT</label>
										<input type="text" class="form-control" name="pcl_vat[]" id="pcl_vat-${itemCount}" value="${item.pcl_vats || ''}" readonly>
									</div>

									<div class="col-md-2 pt-3">
										<label for="pcl_tax-${itemCount}">TAX</label>
										<input type="text" class="form-control" name="pcl_tax[]" id="pcl_tax-${itemCount}" value="${item.pcl_taxs || ''}" readonly>
									</div>

									<div class="col-md-2 pt-3">
										<label for="pcl_total_diff-${itemCount}">+ / -</label>
										<input type="text" class="form-control" name="pcl_total_diff[]" id="pcl_total_diff-${itemCount}" value="${item.pcl_total_diffs || ''}" oninput="calculateItem(${itemCount})" autocomplete="off">
									</div>


									<div class="col-md-2 pt-3">
										<label for="pcl_net_amount-${itemCount}">ยอดชำระสุทธิ</label>
										<input type="text" class="form-control" name="pcl_net_amount[]" id="pcl_net_amount-${itemCount}" value="${item.pcl_net_amounts || ''}" readonly>
									</div>

									
									<div class="col-md-1 d-flex align-items-center justify-content-left pt-3" style="margin-top: 32px">
										<button type="button" class="btn btn-danger" onclick="removeInput(${itemCount})" id="pcl_id-${itemCount}"><i class="icofont-ui-delete"></i></button>
									</div>

								</div>
							`;

							document.getElementById('inputContainer').insertAdjacentHTML('beforeend', inputTemplate);
						});
					} else {
						swal({
							title: "พบข้อผิดพลาด",
							text: response.message,
							type: "error",
							closeOnClickOutside: false
						});
					}
				},
				error: function(xhr, status, error) {
					swal({
						title: "พบข้อผิดพลาด",
						text: error,
						type: "error",
						closeOnClickOutside: false
					});
				}
			});
		}



		function addNewInput() {
			const itemBoxes = document.querySelectorAll('.item-box');
			itemCount = itemBoxes.length;

			itemCount++;

			if(itemCount > MAX_ITEMS) {	
				swal({
					title: "พบข้อผิดพลาด",
					text: `เพิ่มรายการได้ไม่เกิน ${MAX_ITEMS} รายการ`,
					type: "warning",
					closeOnClickOutside: false
				});
				itemCount--;
				return;
			}


			const inputTemplate = `
				<div class="input-group mt-4 custom-border-top item-box" id="item-${itemCount}">
					<div class="col-md-1">
						<label>ลำดับ</label>
						<input type="text" class="form-control" id="item_no-${itemCount}" readonly>
					</div>
					
					<div class="col-md-4">
						<label for="pcl_description-${itemCount}">รายการ</label>
						<input type="text" class="form-control" name="pcl_description[]" id="pcl_description-${itemCount}" autocomplete="off">
					</div>

					<div class="col-md-2">
						<label for="pcl_tax_no-${itemCount}">เลขที่ใบกำกับภาษี</label>
						<input type="text" class="form-control" name="pcl_tax_no[]" id="pcl_tax_no-${itemCount}" autocomplete="off">
					</div>

					<div class="col-md-2">
						<label for="pcl_tax_date-${itemCount}" class="mb-1">วันที่ใบกำกับภาษี</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-ui-calendar"></i>
								</i>
							</div>
							<input type="date" class="form-control" name="pcl_tax_date[]" id="pcl_tax_date-${itemCount}">
						</div>
					</div>

					<div class="col-md-2">
						<label for="pcl_tax_month-${itemCount}">เดือนภาษีมูลค่าเพิ่ม</label>
						<select class="form-control" name="pcl_tax_month[]" id="pcl_tax_month-${itemCount}">
							<option value="">เลือกเดือนภาษีมูลค่าเพิ่ม</option>
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

					<div class="col-md-2 pt-3">
						<label for="pcl_fee-${itemCount}">ค่าบริการ</label>
						<input type="text" class="form-control" name="pcl_fee[]" id="pcl_fee-${itemCount}" oninput="calculateItem(${itemCount})" autocomplete="off">
					</div>

					<div class="col-md-1 pt-3">
						<label for="pcl_vat_percent-${itemCount}">VAT %</label>
						<input type="text" class="form-control" name="pcl_vat_percent[]" id="pcl_vat_percent-${itemCount}" oninput="calculateItem(${itemCount})" autocomplete="off">
					</div>

					<div class="col-md-1 pt-3">
						<label for="pcl_vat_diff-${itemCount}">+ / -</label>
						<input type="text" class="form-control" name="pcl_vat_diff[]" id="pcl_vat_diff-${itemCount}" oninput="calculateItem(${itemCount})" autocomplete="off">
					</div>
					

					<div class="col-md-1 pt-3">
						<label for="pcl_tax_percent-${itemCount}">TAX %</label>
						<input type="text" class="form-control" name="pcl_tax_percent[]" id="pcl_tax_percent-${itemCount}" oninput="calculateItem(${itemCount})" autocomplete="off">
					</div>


					<div class="col-md-1 pt-3">
						<label for="pcl_tax_diff-${itemCount}">+ / -</label>
						<input type="text" class="form-control" name="pcl_tax_diff[]" id="pcl_tax_diff-${itemCount}" oninput="calculateItem(${itemCount})" autocomplete="off">
					</div>

					
					<div class="col-md-3 pt-3">
						<label class="d-block">ภาษีคืน</label>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="pcl_tax_refund-${itemCount}" id="pcl_tax_refund_yes-${itemCount}" value="1">
							<label class="form-check-label" for="pcl_tax_refund_yes-${itemCount}">ขอคืนภาษี</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="pcl_tax_refund-${itemCount}" id="pcl_tax_refund_no-${itemCount}" value="0">
							<label class="form-check-label" for="pcl_tax_refund_no-${itemCount}">ไม่ขอคืนภาษี</label>
						</div>
					</div>

					<div class="col-md-3 pt-3"></div>

					<div class="col-md-2 pt-3">
						<label for="pcl_total-${itemCount}">ยอดชำระ</label>
						<input type="text" class="form-control" name="pcl_total[]" id="pcl_total-${itemCount}" readonly>
					</div>

					<div class="col-md-2 pt-3">
						<label for="pcl_vat-${itemCount}">VAT</label>
						<input type="text" class="form-control" name="pcl_vat[]" id="pcl_vat-${itemCount}" readonly>
					</div>

					<div class="col-md-2 pt-3">
						<label for="pcl_tax-${itemCount}">TAX</label>
						<input type="text" class="form-control" name="pcl_tax[]" id="pcl_tax-${itemCount}" readonly>
					</div>

					<div class="col-md-2 pt-3">
						<label for="pcl_total_diff-${itemCount}">+ / -</label>
						<input type="text" class="form-control" name="pcl_total_diff[]" id="pcl_total_diff-${itemCount}" oninput="calculateItem(${itemCount})" autocomplete="off">
					</div>


					<div class="col-md-2 pt-3">
						<label for="pcl_net_amount-${itemCount}">ยอดชำระสุทธิ</label>
						<input type="text" class="form-control" name="pcl_net_amount[]" id="pcl_net_amount-${itemCount}" readonly>
					</div>

					
					<div class="col-md-1 d-flex align-items-center justify-content-left pt-3" style="margin-top: 32px">
						<button type="button" class="btn btn-danger" onclick="removeInput(${itemCount})" id="pcl_id-${itemCount}"><i class="icofont-ui-delete"></i></button>
					</div>

				</div>
			`;

			document.getElementById('inputContainer').insertAdjacentHTML('beforeend', inputTemplate);
			updateIndexes();
		}



		function calculateItem(itemNum) {
			const getFieldValue = (key) => parseFloat(document.getElementById(`${key}-${itemNum}`).value) || 0;

			const fee = getFieldValue('pcl_fee');
			const vatPercent = getFieldValue('pcl_vat_percent');
			const taxPercent = getFieldValue('pcl_tax_percent');
			
			const vatDiff = getFieldValue('pcl_vat_diff');
			const taxDiff = getFieldValue('pcl_tax_diff');
			const totalDiff = getFieldValue('pcl_total_diff');

			const vat = ((fee * vatPercent) / 100) + vatDiff;
			const tax = ((fee * taxPercent) / 100) + taxDiff;


			const vatFixed = parseFloat(vat.toFixed(2));
			const taxFixed = parseFloat(tax.toFixed(2));


			document.getElementById(`pcl_vat-${itemNum}`).value = vatFixed.toFixed(2);
			document.getElementById(`pcl_tax-${itemNum}`).value = taxFixed.toFixed(2);


			const total = parseFloat((fee + vatFixed - taxFixed).toFixed(2));
			document.getElementById(`pcl_total-${itemNum}`).value = total.toFixed(2);


			const netAmount = parseFloat((fee + vatFixed - taxFixed + totalDiff).toFixed(2));
			document.getElementById(`pcl_net_amount-${itemNum}`).value = netAmount.toFixed(2);

			
			updatePettyCash();
		}


		function updateIndexes() {
			const itemBoxes = document.querySelectorAll('.item-box');
			itemCount = itemBoxes.length;

			itemBoxes.forEach((box, index) => {
				const newIndex = index + 1;

				box.id = `item-${newIndex}`;

				const itemNo = box.querySelector(`[id^="item_no-"]`);
				if (itemNo) {
					itemNo.value = newIndex;
					itemNo.id = `item_no-${newIndex}`;
				}

				
				const fields = [
					{ selector: 'pcl_description', newId: `pcl_description-${newIndex}` },
					{ selector: 'pcl_fee', newId: `pcl_fee-${newIndex}`, onInput: true },
					{ selector: 'pcl_vat_percent', newId: `pcl_vat_percent-${newIndex}`, onInput: true },
					{ selector: 'pcl_vat', newId: `pcl_vat-${newIndex}` },
					{ selector: 'pcl_vat_diff', newId: `pcl_vat_diff-${newIndex}`, onInput: true },
					{ selector: 'pcl_tax_percent', newId: `pcl_tax_percent-${newIndex}`, onInput: true },
					{ selector: 'pcl_tax', newId: `pcl_tax-${newIndex}` },
					{ selector: 'pcl_tax_diff', newId: `pcl_tax_diff-${newIndex}`, onInput: true },
					{ selector: 'pcl_tax_date', newId: `pcl_tax_date-${newIndex}` },
					{ selector: 'pcl_tax_month', newId: `pcl_tax_month-${newIndex}` },
					{ selector: 'pcl_tax_no', newId: `pcl_tax_no-${newIndex}` },
					{ selector: 'pcl_tax_refund', newId: `pcl_tax_refund-${newIndex}` },
					{ selector: 'pcl_total', newId: `pcl_total-${newIndex}` },
					{ selector: 'pcl_total_diff', newId: `pcl_total_diff-${newIndex}`, onInput: true },
					{ selector: 'pcl_net_amount', newId: `pcl_net_amount-${newIndex}` },
					{ selector: 'pcl_id', newId: `pcl_id-${newIndex}`, onClick: true }
				];

				fields.forEach(field => {
					const element = box.querySelector(`[id^="${field.selector}-"]`);
					if (element) {
						element.id = field.newId;
						if (field.onInput) element.setAttribute('oninput', `calculateItem(${newIndex})`);
						if (field.onClick) element.setAttribute('onclick', `removeInput(${newIndex})`);
					}
				});

				const radioButtons = box.querySelectorAll(`[id^="pcl_tax_refund-"]`);
				radioButtons.forEach((radio) => {
					if (radio) {
						radio.setAttribute('name', `pcl_tax_refund-${newIndex}`);
					}
				});
			});

			updatePettyCash();
		}
		

		function removeInput(itemId) {
			const itemElement = document.getElementById(`item-${itemId}`);

			if (itemElement) {
				itemElement.remove();

				itemCount--;
				updateIndexes();
			}
		}

		function updatePettyCash() {
			let pettyCashFee = 0;
			let pettyCashVat = 0;
			let pettyCashVatDiff = 0;
			let pettyCashTax = 0;
			let pettyCashTaxDiff = 0;
			let pettyCashTotal = 0;
			let pettyCashTotalDiff = 0;
			let pettyCashNetAmount = 0;
			let pettyCashDiff = 0;

			const itemBoxes = document.querySelectorAll('.item-box');
			itemCount = itemBoxes.length;

			
			if(itemCount > 0) {	
				for (let i = 1; i <= itemCount; i++) {
					const fee = parseFloat(document.getElementById(`pcl_fee-${i}`).value) || 0;
					const vat = parseFloat(document.getElementById(`pcl_vat-${i}`).value) || 0;
					const tax = parseFloat(document.getElementById(`pcl_tax-${i}`).value) || 0;
					const total = parseFloat(document.getElementById(`pcl_total-${i}`).value) || 0;
					const netAmount = parseFloat(document.getElementById(`pcl_net_amount-${i}`).value) || 0;
					const vatDiff = parseFloat(document.getElementById(`pcl_vat_diff-${i}`).value) || 0;
					const taxDiff = parseFloat(document.getElementById(`pcl_tax_diff-${i}`).value) || 0;
					const totalDiff = parseFloat(document.getElementById(`pcl_total_diff-${i}`).value) || 0;
				
            		document.getElementById(`item_no-${i}`).value = i; 
    
					pettyCashFee += fee;
					pettyCashVat += vat;
					pettyCashVatDiff += vatDiff;
					pettyCashTax += tax;
					pettyCashTaxDiff += taxDiff;
					pettyCashTotal += total;
					pettyCashTotalDiff += totalDiff;
					pettyCashNetAmount += netAmount;
				}


				document.getElementById('pcash_fee').value = pettyCashFee.toFixed(2);
				document.getElementById('pcash_vat').value = pettyCashVat.toFixed(2);
				document.getElementById('pcash_vat_diff').value = pettyCashVatDiff.toFixed(2);
				document.getElementById('pcash_tax').value = pettyCashTax.toFixed(2);
				document.getElementById('pcash_tax_diff').value = pettyCashTaxDiff.toFixed(2);
				document.getElementById('pcash_total').value = pettyCashTotal.toFixed(2);
				document.getElementById('pcash_total_diff').value = pettyCashTotalDiff.toFixed(2);

				document.getElementById('pcash_net_amount').classList.remove('d-none');
				document.getElementById('pcash_net_amount').value = pettyCashNetAmount.toFixed(2);
			}
			else {
				document.getElementById('pcash_fee').value = '';
				document.getElementById('pcash_vat').value = '';
				document.getElementById('pcash_vat_diff').value = '';
				document.getElementById('pcash_tax').value = '';
				document.getElementById('pcash_tax_diff').value = '';
				document.getElementById('pcash_total').value = '';
				document.getElementById('pcash_total_diff').value = '';

				document.getElementById('pcash_net_amount').classList.add('d-none');
            	document.getElementById('pcash_net_amount').value = ''; 
			}

		}

		$(document).ready(function() {
			console.log(pCashId);
			fetchDataById(pCashId);

			$('#previewPettyCash').click(function(e) {
				e.preventDefault(); 

				//Preview
				var departmentName = <?php echo json_encode($departName); ?> || '';
				var pettyCashType = document.getElementById('pcash_type').value;
				var pettyCashDate = document.getElementById('pcash_date').value; 
				var payableName = document.getElementById('searchPayable').value || 'ไม่พบข้อมูล'; 

				//Petty Cash 
				var pettyCashFee = document.getElementById('pcash_fee').value;
				var pettyCashVat = document.getElementById('pcash_vat').value;
				var pettyCashVatDiff = document.getElementById('pcash_vat_diff').value;
				var pettyCashTax = document.getElementById('pcash_tax').value;
				var pettyCashTaxDiff = document.getElementById('pcash_tax_diff').value;
				var pettyCashTotal = document.getElementById('pcash_total').value;
				var pettyCashTotalDiff = document.getElementById('pcash_total_diff').value;
				var pettyCashNetAmount = document.getElementById('pcash_net_amount').value;

				var pettyCashNo = document.getElementById('pcash_no').value;

				let descriptions = [];
				let fees = [];
				let vats = [];
				let vatDiffs = [];
				let taxs = [];
				let taxDiffs = [];
				let totals = [];
				let totalDiffs = [];
				let netAmounts = [];
				let taxNos = [];
				let taxDates = [];
				let taxMonths = [];
				let taxRefunds = [];
				
				const listCount = 5;
				for (let i = 1; i <= listCount; i++) {
					const descriptionValue = document.getElementById(`pcl_description-${i}`) ? document.getElementById(`pcl_description-${i}`).value : null;
					const feeValue = document.getElementById(`pcl_fee-${i}`) ? document.getElementById(`pcl_fee-${i}`).value : null;
					const vatValue = document.getElementById(`pcl_vat-${i}`) ? document.getElementById(`pcl_vat-${i}`).value : null;
					const vatDiffValue = document.getElementById(`pcl_vat_diff-${i}`) ? document.getElementById(`pcl_vat_diff-${i}`).value : null;
					const taxValue = document.getElementById(`pcl_tax-${i}`) ? document.getElementById(`pcl_tax-${i}`).value : null;
					const taxDiffValue = document.getElementById(`pcl_tax_diff-${i}`) ? document.getElementById(`pcl_tax_diff-${i}`).value : null;
					const totalValue = document.getElementById(`pcl_total-${i}`) ? document.getElementById(`pcl_total-${i}`).value : null;
					const totalDiffValue = document.getElementById(`pcl_total_diff-${i}`) ? document.getElementById(`pcl_total_diff-${i}`).value : null;
					const netAmountValue = document.getElementById(`pcl_net_amount-${i}`) ? document.getElementById(`pcl_net_amount-${i}`).value : null;

					const taxNoValue = document.getElementById(`pcl_tax_no-${i}`) ? document.getElementById(`pcl_tax_no-${i}`).value : null;
					const TaxDateValue = document.getElementById(`pcl_tax_date-${i}`) ? document.getElementById(`pcl_tax_date-${i}`).value : null;
					const TaxMonthValue = document.getElementById(`pcl_tax_month-${i}`) ? document.getElementById(`pcl_tax_month-${i}`).value : null;
					const taxRefundValue = document.querySelector(`input[name="pcl_tax_refund-${i}"]:checked`) ? document.querySelector(`input[name="pcl_tax_refund-${i}"]:checked`).value : null;


					descriptions.push(descriptionValue);
					fees.push(feeValue);
					vats.push(vatValue);
					vatDiffs.push(vatDiffValue);
					taxs.push(taxValue);
					taxDiffs.push(taxDiffValue);
					totals.push(totalValue);
					totalDiffs.push(totalDiffValue);
					netAmounts.push(netAmountValue);
					taxNos.push(taxNoValue);
					taxDates.push(TaxDateValue);
					taxMonths.push(TaxMonthValue);
					taxRefunds.push(taxRefundValue);
				}
				
				let totalTaxRefund = 0;
				let totalNoTaxRefund = 0;
				let totalTaxList = 0;

				taxNos.forEach((taxNo, index) => {
					if (taxNo) totalTaxList++;
				});


				taxRefunds.forEach(taxRefund => {
					if (taxRefund === '1') {
						totalTaxRefund++;
					} 
					else if (taxRefund === '0') {
						totalNoTaxRefund++;
					}
				});


				$('#totalTaxList').text(totalTaxList);
				$('#totalTaxRefund').text(totalTaxRefund);
				$('#totalNoTaxRefund').text(totalNoTaxRefund);

				$('#taxList').empty()
				if(totalTaxList > 0) {
					$('#taxList').removeClass('d-none');
					taxNos.forEach((taxNo, index) => {
						if (taxNo) {
							const taxListItem = `<li style="font-size: 12px;">
													เลขที่ใบกำกับภาษี 
													<span><b>${taxNo}</b></span> 
													(<span>${taxDates[index] && taxDates[index] !== '' ? formatDateTH(taxDates[index]) : 'ไม่ได้เลือกวันที่'}</span>)<br>
													<span><b>เดือนภาษีมูลค่าเพิ่ม ${taxMonths[index] && taxMonths[index] !== '' ? formatDateTH(taxMonths[index], "month") : 'ไม่ได้เลือกเดือน'}</b></span>
												</li>`;
							$('#taxList').append(taxListItem);
						}
					});
				}
				

				$.ajax({
					url: "pettycash_pdf.php", 
					type: "POST",
					data: {
						action: "previewEdit",
						pettyCashNo: pettyCashNo,
						pettyCashDate: pettyCashDate,
						pettyCashType: pettyCashType,
						departmentName: departmentName,
						payableName: payableName,
						pettyCashFee: pettyCashFee,
						pettyCashVat: pettyCashVat,
						pettyCashVatDiff: pettyCashVatDiff,
						pettyCashTax: pettyCashTax,
						pettyCashTaxDiff: pettyCashTaxDiff,
						pettyCashTotal: pettyCashTotal,
						pettyCashTotalDiff: pettyCashTotalDiff,
						pettyCashNetAmount: pettyCashNetAmount,
						descriptions: descriptions,
						fees: fees,
						vats: vats,
						vatDiffs: vatDiffs,
						taxs: taxs,
						taxDiffs: taxDiffs,
						totals: totals,
						totalDiffs: totalDiffs,
						netAmounts: netAmounts
					},
					xhrFields: {
						responseType: 'blob'
					},
					beforeSend: function () {
						$('#pettyCashModal').modal('show');
						$('#loadingSpinner').fadeIn();
					},
					success: function(response) {
						$('#loadingSpinner').fadeOut();


						const url = URL.createObjectURL(response);
						document.getElementById('pdfViewer').src = url;
					
						$('#pdfViewer').fadeIn();
					},
					error: function(xhr, status, error) {
						swal({
							title: "พบข้อผิดพลาด",
							text: error,
							type: "error",
							closeOnClickOutside: false
						});
					}
				});

				$('#pettyCashModal').modal('show');
			});


			$('#savePettyCashForm').click(function(e) {
				e.preventDefault();
				var formData = new FormData($('#editPettyCashForm')[0]);

				var companyId = document.getElementById('pcash_comp_id').value; 
				var departmentId = document.getElementById('pcash_dep_id').value; 
				var pettyCashDate = document.getElementById('pcash_date').value; 
				var payableId = document.getElementById("invpayaid").value;

				var pettyCashId = document.getElementById('pcash_id').value; 
				var pettyCashNo = document.getElementById('pcash_no').value; 
				var pettyCashType = document.getElementById('pcash_type').value; 

				//Petty Cash 
				var pettyCashFee = document.getElementById('pcash_fee').value;
				var pettyCashVat = document.getElementById('pcash_vat').value;
				var pettyCashVatDiff = document.getElementById('pcash_vat_diff').value;
				var pettyCashTax = document.getElementById('pcash_tax').value;
				var pettyCashTaxDiff = document.getElementById('pcash_tax_diff').value;
				var pettyCashTotal = document.getElementById('pcash_total').value;
				var pettyCashTotalDiff = document.getElementById('pcash_total_diff').value;
				var pettyCashNetAmount = document.getElementById('pcash_net_amount').value;
				

				//Petty Cash Lists
				let pclDescriptions = []; 
				let pclFees = [];
				let pclVatPercents = [];
				let pclVats = [];
				let pclVatDiffs = []; 
				let pclTaxPercents = [];
				let pclTaxs = [];
				let pclTaxDiffs = [];
				let pclTaxDates = [];
				let pclTaxMonths = [];
				let pclTaxNos = [];
				let pclTaxRefunds = [];
				let pclTotals = [];
				let pclTotalDiffs = [];
				let pclNetAmounts = [];

				const itemBoxes = document.querySelectorAll('.item-box');
				itemCount = itemBoxes.length;

				//Error Messages
				var errorMessages = [];

				if (!companyId) {
					errorMessages.push("ไม่พบบริษัท");
				}
				if (!departmentId) {
					errorMessages.push("ไม่พบแผนก");
				}
				if (!pettyCashDate) {
					errorMessages.push("กรุณาเลือกวันที่ออกใบจ่ายเงินสดย่อย");
				}
				if (!payableId) {
					errorMessages.push("กรุณาเลือกชื่อผู้รับ");
				}

				if (itemCount === 0) {
					errorMessages.push("กรุณาเพิ่มรายการอย่างน้อย 1 รายการ");
				}


				
				for (let i = 1; i <= itemCount; i++) {
					const descriptionElement = document.getElementById(`pcl_description-${i}`);
					const taxRefundElement = document.querySelector(`input[name="pcl_tax_refund-${i}"]:checked`);

					// Check if description element exists
					const descriptionValue = descriptionElement ? descriptionElement.value : null; 
					const feeValue = document.getElementById(`pcl_fee-${i}`) ? document.getElementById(`pcl_fee-${i}`).value : null;
					const vatPercentValue = document.getElementById(`pcl_vat_percent-${i}`) ? document.getElementById(`pcl_vat_percent-${i}`).value : null; 
					const vatValue = document.getElementById(`pcl_vat-${i}`) ? document.getElementById(`pcl_vat-${i}`).value : null;
					const vatDiffValue = document.getElementById(`pcl_vat_diff-${i}`) ? document.getElementById(`pcl_vat_diff-${i}`).value : null;
					const taxPercentValue = document.getElementById(`pcl_tax_percent-${i}`) ? document.getElementById(`pcl_tax_percent-${i}`).value : null; 
					const taxValue = document.getElementById(`pcl_tax-${i}`) ? document.getElementById(`pcl_tax-${i}`).value : null;
					const taxDiffValue = document.getElementById(`pcl_tax_diff-${i}`) ? document.getElementById(`pcl_tax_diff-${i}`).value : null;
					const taxDateValue = document.getElementById(`pcl_tax_date-${i}`) ? document.getElementById(`pcl_tax_date-${i}`).value : null; 
					const taxMonthValue = document.getElementById(`pcl_tax_month-${i}`) ? document.getElementById(`pcl_tax_month-${i}`).value : null; 
					const taxNoValue = document.getElementById(`pcl_tax_no-${i}`) ? document.getElementById(`pcl_tax_no-${i}`).value : null; 
					const taxRefundValue = taxRefundElement ? taxRefundElement.value : null;
					const totalValue = document.getElementById(`pcl_total-${i}`) ? document.getElementById(`pcl_total-${i}`).value : null; 
					const totalDiffValue = document.getElementById(`pcl_total_diff-${i}`) ? document.getElementById(`pcl_total_diff-${i}`).value : null; 
					const netAmountValue = document.getElementById(`pcl_net_amount-${i}`) ? document.getElementById(`pcl_net_amount-${i}`).value : null;

					// Validation
					if (!descriptionValue) {
						errorMessages.push(`กรุณากรอกรายการ ลำดับที่ ${i}`);
					}

					if (!feeValue || isNaN(feeValue) || feeValue < 0) {
						errorMessages.push(`กรุณากรอกค่าบริการ ลำดับที่ ${i}`);
					}
							
					if (!taxRefundValue) {
						errorMessages.push(`กรุณาเลือกภาษีคืน ลำดับที่ ${i}`);
					}


					pclDescriptions.push(descriptionValue); 
					pclFees.push((parseFloat(feeValue) || 0).toFixed(2)); 
					pclVatPercents.push((parseFloat(vatPercentValue) || 0).toFixed(2)); 
					pclVats.push((parseFloat(vatValue) || 0).toFixed(2)); 
					pclVatDiffs.push((parseFloat(vatDiffValue) || 0).toFixed(2)); 
					pclTaxPercents.push((parseFloat(taxPercentValue) || 0).toFixed(2)); 
					pclTaxs.push((parseFloat(taxValue) || 0).toFixed(2)); 
					pclTaxDiffs.push((parseFloat(taxDiffValue) || 0).toFixed(2)); 
					pclTaxDates.push(taxDateValue);
					pclTaxMonths.push(taxMonthValue);
					pclTaxNos.push(taxNoValue);
					pclTaxRefunds.push(taxRefundValue);
					pclTotals.push((parseFloat(totalValue) || 0).toFixed(2));
					pclTotalDiffs.push((parseFloat(totalDiffValue) || 0).toFixed(2));
					pclNetAmounts.push((parseFloat(netAmountValue) || 0).toFixed(2));

				}


				if (errorMessages.length > 0) {
					$('#errorMessages .error-text').html(errorMessages.join('<br>'));
					$('#pettyCashModal').modal('hide');
					$('#errorMessages').fadeIn(300, function() {
						$('html, body').animate({
							scrollTop: $('#errorMessages').offset().top
						}, 300);
					}); 
				} else {
					$('#errorMessages').fadeOut(300);

					formData.append('pCashId', pettyCashId)
					formData.append('pCashNo', pettyCashNo)
					formData.append('pCashCompId', companyId);
					formData.append('pCashDepId', departmentId);
					formData.append('pCashDate', pettyCashDate);
					formData.append('pCashPayaId', payableId);
					formData.append('pCashType', pettyCashType);


					formData.append('pCashFee', pettyCashFee);
					formData.append('pCashVat', pettyCashVat);
					formData.append('pCashVatDiff', pettyCashVatDiff);
					formData.append('pCashTax', pettyCashTax);
					formData.append('pCashTaxDiff', pettyCashTaxDiff);
					formData.append('pCashTotal', pettyCashTotal);
					formData.append('pCashTotalDiff', pettyCashTotalDiff);
					formData.append('pCashNetAmount', pettyCashNetAmount);



					pclDescriptions.forEach((desc, index) => formData.append(`pclDescriptions[${index}]`, desc));
					pclFees.forEach((fee, index) => formData.append(`pclFees[${index}]`, fee));
					pclVatPercents.forEach((vatPercent, index) => formData.append(`pclVatPercents[${index}]`, vatPercent));
					pclVats.forEach((vat, index) => formData.append(`pclVats[${index}]`, vat));
					pclVatDiffs.forEach((vatDiff, index) => formData.append(`pclVatDiffs[${index}]`, vatDiff));
					pclTaxPercents.forEach((taxPercent, index) => formData.append(`pclTaxPercents[${index}]`, taxPercent));
					pclTaxs.forEach((tax, index) => formData.append(`pclTaxs[${index}]`, tax));
					pclTaxDiffs.forEach((taxDiff, index) => formData.append(`pclTaxDiffs[${index}]`, taxDiff));
					pclTaxDates.forEach((taxDate, index) => formData.append(`pclTaxDates[${index}]`, taxDate));
					pclTaxMonths.forEach((taxMonth, index) => formData.append(`pclTaxMonths[${index}]`, taxMonth));
					pclTaxNos.forEach((taxNo, index) => formData.append(`pclTaxNos[${index}]`, taxNo));
					pclTaxRefunds.forEach((taxRefund, index) => formData.append(`pclTaxRefunds[${index}]`, taxRefund));
					pclTotals.forEach((total, index) => formData.append(`pclTotals[${index}]`, total));
					pclTotalDiffs.forEach((totalDiff, index) => formData.append(`pclTotalDiffs[${index}]`, totalDiff));
					pclNetAmounts.forEach((netAmount, index) => formData.append(`pclNetAmounts[${index}]`, netAmount));


					for(let [key, value] of formData.entries()) {
						console.log(`${key}: ${value}`);
					} 

					$.ajax({
						type: "POST",
						url: "r_pettycash_edit.php",
						data: formData,
						cache: false,
						processData: false,
						contentType: false,
						processData: false,
						success: function(response) {
							if(response.status == 'success') {
								swal({
									title: response.message,
									text: "เลขที่ใบจ่ายเงินสดย่อย : " + response.pCashNo,
									type: "success",
									closeOnClickOutside: false
								},function() {
									window.location.href = "pettycash.php?cid=<?=$cid?>&dep=<?=$dep?>";
								});
							}
							else {
								swal({
									title: "พบข้อผิดพลาด",
									text: response.message,
									type: "warning",
									closeOnClickOutside: false
								});
							}

							$('#pettyCashModal').modal('hide');
							$('#editPettyCashForm')[0].reset(); 
						},
						error: function(error) {
							swal({
								title: "พบข้อผิดพลาด",
								text: response.message,
								type: "error",
								closeOnClickOutside: false
							});
						}
					})
				}
			
			});

		});

	</script>


	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>