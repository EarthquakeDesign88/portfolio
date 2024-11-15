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

		
	</style>

</head>
<body>

	<?php include 'navbar.php'; ?>

	<section>
		<div class="container-fluid">
			<form id="addPettyCashForm" method="POST">
				<div class="row py-4 px-1" style="background-color: #EDEDEDFF">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-plus-circle"></i>&nbsp;&nbsp;เพิ่มใบจ่ายเงินสดย่อย
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
							<input type="hidden" name="pcash_fee" id="pcash_fee" class="form-control d-inline-block w-auto" readonly>
							<input type="hidden" name="pcash_vat" id="pcash_vat" class="form-control d-inline-block w-auto" readonly>
							<input type="hidden" name="pcash_tax" id="pcash_tax" class="form-control d-inline-block w-auto" readonly>
							<input type="hidden" name="pcash_total" id="pcash_total" class="form-control d-inline-block w-auto" readonly>
							<input type="hidden" name="pcash_diff" id="pcash_diff" class="form-control d-inline-block w-auto" readonly>
							
						</label>
					</div>

					<div class="col-md-9 pt-4">
						<div class="checkbox">
							<input type="checkbox" class="form-check-input" id="pcash_type" name="pcash_type">
							<label class="form-check-label" for="pcash_type">ทดลองจ่าย</label>
						</div>
					</div>


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
					<h3 class="modal-title py-2">ตัวอย่าง</h3>
					<button type="button" class="close" name="pcash_cancel" id="pcash_cancel" data-dismiss="modal" aria-label="Close">&times;</button>
				</div>
				<div class="modal-body" id="pettyCashDetail">
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
		let itemCount = 0;


		function addNewInput() {
			itemCount++;


			const inputTemplate = `
				<div class="input-group mt-4 custom-border-top item-box" id="item-${itemCount}">
					<div class="col-md-1">
						<label>ลำดับ</label>
						<input type="text" class="form-control" id="item_no-${itemCount}" readonly>
					</div>
					
					<div class="col-md-3">
						<label for="pcl_description-${itemCount}">รายการ</label>
						<input type="text" class="form-control" name="pcl_description[]" id="pcl_description-${itemCount}" autocomplete="off">
					</div>

					<div class="col-md-2">
						<label for="pcl_fee-${itemCount}">ค่าบริการ</label>
						<input type="text" class="form-control" name="pcl_fee[]" id="pcl_fee-${itemCount}" oninput="calculateItem(${itemCount})" autocomplete="off">
					</div>

					<div class="col-md-1">
						<label for="pcl_vat_percent-${itemCount}">VAT %</label>
						<input type="text" class="form-control" name="pcl_vat_percent[]" id="pcl_vat_percent-${itemCount}" oninput="calculateItem(${itemCount})" autocomplete="off">
					</div>
					
					<div class="col-md-1">
						<label for="pcl_vat-${itemCount}">VAT</label>
						<input type="text" class="form-control" name="pcl_vat[]" id="pcl_vat-${itemCount}" readonly>
					</div>
					
					<div class="col-md-1">
						<label for="pcl_tax_percent-${itemCount}">TAX %</label>
						<input type="text" class="form-control" name="pcl_tax_percent[]" id="pcl_tax_percent-${itemCount}" oninput="calculateItem(${itemCount})" autocomplete="off">
					</div>

					<div class="col-md-1">
						<label for="pcl_tax-${itemCount}">TAX</label>
						<input type="text" class="form-control" name="pcl_tax[]" id="pcl_tax-${itemCount}" readonly>
					</div>

					<div class="col-md-1">
						<label for="pcl_diff-${itemCount}">+ / -</label>
						<input type="text" class="form-control" name="pcl_diff[]" id="pcl_diff-${itemCount}" oninput="calculateItem(${itemCount})" autocomplete="off">
					</div>

					<div class="col-md-2 pt-3">
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

					<div class="col-md-2 pt-3">
						<label for="pcl_tax_no-${itemCount}">เลขที่ใบกำกับภาษี</label>
						<input type="text" class="form-control" name="pcl_tax_no[]" id="pcl_tax_no-${itemCount}" autocomplete="off">
					</div>

					
					<div class="col-md-2 pt-3">
						<label>ภาษีคืน</label>
						<div class="form-check">
							<input class="form-check-input" type="radio" name="pcl_tax_refund-${itemCount}"" id="pcl_tax_refund-${itemCount}" value="1">
							<label class="form-check-label" for="pcl_tax_refund_yes-${itemCount}">ขอคืนภาษี</label>
						</div>
						<div class="form-check">
							<input class="form-check-input" type="radio" name="pcl_tax_refund-${itemCount}"" id="pcl_tax_refund-${itemCount}" value="0">
							<label class="form-check-label" for="pcl_tax_refund_no-${itemCount}">ไม่ขอคืนภาษี</label>
						</div>
            		</div>

					<div class="col-md-2 pt-3">
						<label for="pcl_total-${itemCount}">ยอดชำระ</label>
						<input type="text" class="form-control" name="pcl_total[]" id="pcl_total-${itemCount}" readonly>
					</div>

					
					<div class="col-md-2 pt-3">
						<label for="pcl_net_amount-${itemCount}">ยอดชำระสุทธิ</label>
						<input type="text" class="form-control" name="pcl_net_amount[]" id="pcl_net_amount-${itemCount}" readonly>
					</div>

					
					<div class="col-md-1 d-flex align-items-center justify-content-end pt-3 mt-4">
						<button type="button" class="btn btn-danger" onclick="removeInput(${itemCount})" id="pcl_id-${itemCount}"><i class="icofont-ui-delete"></i></button>
					</div>

				</div>
			`;

			document.getElementById('inputContainer').insertAdjacentHTML('beforeend', inputTemplate);
			updateIndexes();
		}


		function calculateItem(itemNum) {
			const fee = parseFloat(document.getElementById(`pcl_fee-${itemNum}`).value) || 0;
			const vatPercent = parseFloat(document.getElementById(`pcl_vat_percent-${itemNum}`).value) || 0;
			const taxPercent = parseFloat(document.getElementById(`pcl_tax_percent-${itemNum}`).value) || 0;
			const diff = parseFloat(document.getElementById(`pcl_diff-${itemNum}`).value) || 0;


			const vat = (fee * vatPercent) / 100;
			const tax = (fee * taxPercent) / 100;


			const vatFixed = parseFloat(vat.toFixed(2));
			const taxFixed = parseFloat(tax.toFixed(2));


			document.getElementById(`pcl_vat-${itemNum}`).value = vatFixed.toFixed(2);
			document.getElementById(`pcl_tax-${itemNum}`).value = taxFixed.toFixed(2);


			const total = parseFloat((fee + vatFixed - taxFixed).toFixed(2));
			document.getElementById(`pcl_total-${itemNum}`).value = total.toFixed(2);


			const netAmount = parseFloat((fee + vatFixed - taxFixed + diff).toFixed(2));
			document.getElementById(`pcl_net_amount-${itemNum}`).value = netAmount.toFixed(2);

			
			updatePettyCash();
		}

		function updateIndexes() {
			const itemBoxes = document.querySelectorAll('.item-box');
			itemCount = itemBoxes.length;

			itemBoxes.forEach((box, index) => {
				const newIndex = index + 1;

				box.id = `item-${newIndex}`;
				box.querySelector(`[id^="item_no-"]`).value = newIndex; 
				box.querySelector(`[id^="item_no-"]`).id = `item_no-${newIndex}`;
				
				box.querySelector(`[id^="pcl_description-"]`).id = `pcl_description-${newIndex}`;
				box.querySelector(`[id^="pcl_fee-"]`).id = `pcl_fee-${newIndex}`;
				box.querySelector(`[id^="pcl_vat_percent-"]`).id = `pcl_vat_percent-${newIndex}`;
				box.querySelector(`[id^="pcl_vat-"]`).id = `pcl_vat-${newIndex}`;
				box.querySelector(`[id^="pcl_tax_percent-"]`).id = `pcl_tax_percent-${newIndex}`;
				box.querySelector(`[id^="pcl_tax-"]`).id = `pcl_tax-${newIndex}`;
				box.querySelector(`[id^="pcl_diff-"]`).id = `pcl_diff-${newIndex}`;
				box.querySelector(`[id^="pcl_tax_date-"]`).id = `pcl_tax_date-${newIndex}`;
				box.querySelector(`[id^="pcl_tax_no-"]`).id = `pcl_tax_no-${newIndex}`;
				box.querySelector(`[id^="pcl_tax_refund-"]`).id = `pcl_tax_refund-${newIndex}`;
				box.querySelector(`[id^="pcl_total-"]`).id = `pcl_total-${newIndex}`;
				box.querySelector(`[id^="pcl_net_amount-"]`).id = `pcl_net_amount-${newIndex}`;
				box.querySelector(`[id^="pcl_id-"]`).id = `pcl_id-${newIndex}`;

				box.querySelector(`[id^="pcl_fee-"]`).setAttribute('oninput', `calculateItem(${newIndex})`);
				box.querySelector(`[id^="pcl_vat_percent-"]`).setAttribute('oninput', `calculateItem(${newIndex})`);
				box.querySelector(`[id^="pcl_tax_percent-"]`).setAttribute('oninput', `calculateItem(${newIndex})`);
				box.querySelector(`[id^="pcl_diff-"]`).setAttribute('oninput', `calculateItem(${newIndex})`);

				box.querySelector(`[id^="pcl_id-"]`).setAttribute('onclick', `removeInput(${newIndex})`);


				const radioButtons = box.querySelectorAll(`[id^="pcl_tax_refund-"]`);
				radioButtons.forEach((radio) => {
					radio.setAttribute('name', `pcl_tax_refund-${newIndex}`);
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
			let pettyCashTax = 0;
			let pettyCashTotal = 0;
			let pettyCashNetAmount = 0;
			let pettyCashDiff = 0;

			
			if(itemCount > 0) {	
				for (let i = 1; i <= itemCount; i++) {
					const fee = parseFloat(document.getElementById(`pcl_fee-${i}`).value) || 0;
					const vat = parseFloat(document.getElementById(`pcl_vat-${i}`).value) || 0;
					const tax = parseFloat(document.getElementById(`pcl_tax-${i}`).value) || 0;
					const total = parseFloat(document.getElementById(`pcl_total-${i}`).value) || 0;
					const netAmount = parseFloat(document.getElementById(`pcl_net_amount-${i}`).value) || 0;
					const diff = parseFloat(document.getElementById(`pcl_diff-${i}`).value) || 0;

				
            		document.getElementById(`item_no-${i}`).value = i; 
    
					pettyCashFee += fee;
					pettyCashVat += vat;
					pettyCashTax += tax;
					pettyCashTotal += total;
					pettyCashNetAmount += netAmount;
					pettyCashDiff += diff;
				}


				document.getElementById('pcash_fee').value = pettyCashFee.toFixed(2);
				document.getElementById('pcash_vat').value = pettyCashVat.toFixed(2);
				document.getElementById('pcash_tax').value = pettyCashTax.toFixed(2);
				document.getElementById('pcash_total').value = pettyCashTotal.toFixed(2);
				document.getElementById('pcash_diff').value = pettyCashDiff.toFixed(2);

				document.getElementById('pcash_net_amount').classList.remove('d-none');
				document.getElementById('pcash_net_amount').value = pettyCashNetAmount.toFixed(2);
			}
			else {
				document.getElementById('pcash_fee').value = '';
				document.getElementById('pcash_vat').value = '';
				document.getElementById('pcash_tax').value = '';
				document.getElementById('pcash_total').value = '';
				document.getElementById('pcash_diff').value = '';

				document.getElementById('pcash_net_amount').classList.add('d-none');
            	document.getElementById('pcash_net_amount').value = ''; 
			}

		}


		function formatDateTH(date) {
			const dateParts = date.split('-'); 
			const day = parseInt(dateParts[2], 10);
			const month = parseInt(dateParts[1], 10);
			const year = parseInt(dateParts[0], 10) + 543;
			// กำหนดชื่อเดือน
			const monthNames = [
				"มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน",
				"กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"
			];

			const monthName = monthNames[month - 1]; 
			return `${day} ${monthName} ${year}`;
		}

		function formatNumber(num) {
			const value = parseFloat(num);
			return isNaN(value) ? '0.00' : value.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
		}


		$(document).ready(function() {
			$('#previewPettyCash').click(function(e) {
				e.preventDefault(); 

				//Preview
				var departmentName = <?php echo json_encode($departName); ?> || 'ไม่พบข้อมูล';
				var pettyCashType = document.getElementById('pcash_type').checked ? 'ทดลองจ่าย' : '';
				var pettyCashDate = document.getElementById('pcash_date').value; 
				var pettyCashDateTH = pettyCashDate != '' ? formatDateTH(pettyCashDate) : 'ไม่พบข้อมูล';
				var payableName = document.getElementById('searchPayable').value || 'ไม่พบข้อมูล'; 

				var pettyCashFee = document.getElementById('pcash_fee').value || '0.00';
				var pettyCashVat = document.getElementById('pcash_vat').value || '0.00';
				var pettyCashTax = document.getElementById('pcash_tax').value || '0.00';
				var pettyCashTotal = document.getElementById('pcash_total').value || '0.00';
				var pettyCashDiff = document.getElementById('pcash_diff').value || '0.00';
				var pettyCashNetAmount = document.getElementById('pcash_net_amount').value || '0.00';

				var userCreate = <?php echo json_encode($_SESSION["user_name"]); ?> || 'ไม่พบข้อมูล';
				var createDate = new Date().toLocaleDateString('th-TH', { 
					year: 'numeric',
					month: '2-digit', 
					day: '2-digit'
				});

				let itemsHtml = '';

				if (itemCount > 0) {
					let descriptions = [];
					let fees = [];
					let vats = [];
					let taxes = [];
					let totals = [];
					let netAmounts = [];


					for (let i = 1; i <= itemCount; i++) {
						const descriptionValue = document.getElementById(`pcl_description-${i}`) ? document.getElementById(`pcl_description-${i}`).value : null;
						const feeValue = document.getElementById(`pcl_fee-${i}`) ? document.getElementById(`pcl_fee-${i}`).value : null;
						const vatValue = document.getElementById(`pcl_vat-${i}`) ? document.getElementById(`pcl_vat-${i}`).value : null;
						const taxValue = document.getElementById(`pcl_tax-${i}`) ? document.getElementById(`pcl_tax-${i}`).value : null;
						const totalValue = document.getElementById(`pcl_total-${i}`) ? document.getElementById(`pcl_total-${i}`).value : null;
						const netAmountValue = document.getElementById(`pcl_net_amount-${i}`) ? document.getElementById(`pcl_net_amount-${i}`).value : null;

						// Add values to arrays
						descriptions.push(descriptionValue);
						fees.push(feeValue);
						vats.push(vatValue);
						taxes.push(taxValue);
						totals.push(totalValue);
						netAmounts.push(netAmountValue);
					}

					
					itemsHtml = descriptions.map((desc, index) => `
						<tr>
							<td>${index + 1}</td>
							<td>${desc}</td>
							<td class="text-right">${formatNumber(fees[index])}</td>
							<td class="text-right">${formatNumber(vats[index])}</td>
							<td class="text-right">${formatNumber(taxes[index])}</td>
							<td class="text-right">${formatNumber(totals[index])}</td>
							<td class="text-right">${formatNumber(pettyCashDiff)}</td>
							<td class="text-right">${formatNumber(netAmounts[index])}</td>
						</tr>
					`).join('');
				}
				else {
					itemsHtml = `
						<tr>
							<td colspan="8" class="text-center"><b>ไม่พบข้อมูล</b></td>
						</tr>
					`;
				}
				

				let content = `
					<div class="container py-4 px-4">
						<div class="row">
							<div class="col-lg-6 col-md-6 ml-auto px-1">
								<h3 class="mb-0">ใบจ่ายเงินสดย่อย<br>Petty Cash Payment</h3>
								<span class="text-info">${pettyCashType}</span>
							</div>
							<div class="col-lg-6 col-md-6 ml-auto px-1">
								<div class="row py-1" style="margin-right: 1px">
									<div class="col-xs-7 col-md-7 text-right px-1"><b>เลขที่ / No.</b></div>
									<div class="col-xs-5 col-md-5 text-center" style="border-bottom: 1px dotted #333;"><b>Cash-xx-xxxxxxx</b></div>
								</div>
								<div class="row py-1" style="margin-right: 1px">
									<div class="col-xs-7 col-md-7 text-right px-1"><b>ฝ่าย / Dept</b></div>
									<div class="col-xs-5 col-md-5 text-center" style="border-bottom: 1px dotted #333;"><b>${departmentName}</b></div>
								</div>
								<div class="row py-1" style="margin-right: 1px">
									<div class="col-xs-7 col-md-7 text-right px-1"><b>วันที่/ Date</b></div>
									<div class="col-xs-5 col-md-5 text-center" style="border-bottom: 1px dotted #333;"><b>${pettyCashDateTH}</b></div>
								</div>
							</div>
						</div>
						<div class="row py-2">
							<div class="col-xs-1 col-md-1 px-1"><b>ชื่อผู้รับ</b></div>
							<div class="col-xs-11 col-md-11" style="border-bottom: 1px dotted #333;"><b>${payableName}</b></div>
						</div>
						<div class="row py-2">
							<table class="table table-bordered">
								<thead>
									<tr class="text-center">
										<th width="3%">ที่</th>
										<th width="35%">รายการ</th>
										<th width="10%">ค่าบริการ</th>
										<th width="8%">VAT</th>
										<th width="10%">TAX</th>
										<th width="10%">ยอดชำระ</th>
										<th width="7%">+/-</th>
										<th width="15%">ยอดชำระสุทธิ</th>
									</tr>
								</thead>
								<tbody>
									${itemsHtml}
								</tbody>
								<tfoot>
									<tr>
										<td colspan="2" class="text-right"><b>รวมเป็นเงิน</b></td>
										<td class="text-right">${formatNumber(pettyCashFee)}</td>
										<td class="text-right">${formatNumber(pettyCashVat)}</td>
										<td class="text-right">${formatNumber(pettyCashTax)}</td>
										<td class="text-right">${formatNumber(pettyCashTotal)}</td>
										<td class="text-right">${formatNumber(pettyCashDiff)}</td>
										<td class="text-right">${formatNumber(pettyCashNetAmount)}</td>
									</tr>
								</tfoot>
							</table>
						</div>
						<div class="row px-4">
							<div class="col-lg-12 col-md-12 pt-5">
								<div class="row">
									<div class="col-lg-1 col-md-12 mr-auto"></div>
									<div class="col-lg-3 col-md-12 px-4 mr-auto mt-4">
										<div class="row px-2">
											<div class="col-lg-12 col-md-12" style="border-bottom: 1px dashed #333; text-align: center;"></div>
											<div class="col-lg-12 col-md-12">
												<div class="row">
													<div class="col-lg-4 col-md-12 px-0"><b>ผู้อนุมัติ</b></div>
													<div class="col-lg-8 col-md-12 text-center" style="border-bottom: 1px dashed #333;"></div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-lg-3 col-md-12 px-4 mr-auto">
										<div class="row px-2">
											<div class="col-lg-12 col-md-12 px-0 text-center" style="border-bottom: 1px dashed #333;">${createDate}</div>
											<div class="col-lg-12 col-md-12">
												<div class="row">
													<div class="col-lg-4 col-md-12 px-0"><b>ผู้จัดทำ</b></div>
													<div class="col-lg-8 col-md-12" style="border-bottom: 1px dashed #333; text-align: center;">${userCreate}</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-lg-3 col-md-12 px-4 mr-auto mt-4">
										<div class="row px-2">
											<div class="col-lg-12 col-md-12" style="border-bottom: 1px dashed #333;"></div>
											<div class="col-lg-12 col-md-12">
												<div class="row">
													<div class="col-lg-4 col-md-12 px-0"><b>ผู้รับเงิน</b></div>
													<div class="col-lg-8 col-md-12 text-center" style="border-bottom: 1px dashed #333;"></div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-lg-1 col-md-12 mr-auto"></div>
								</div>
							</div>
						</div>
					</div>
				`;


				$('#pettyCashDetail').html(content);	
				$('#pettyCashModal').modal('show');
			});


			$('#savePettyCashForm').click(function(e) {
				e.preventDefault();
				var formData = new FormData($('#addPettyCashForm')[0]);

				var companyId = document.getElementById('pcash_comp_id').value; 
				var departmentId = document.getElementById('pcash_dep_id').value; 
				var pettyCashDate = document.getElementById('pcash_date').value; 
				var payableId = document.getElementById("invpayaid").value;


				var pettyCashType = document.getElementById('pcash_type').checked ? 2 : 1; //2 = ทดลองจ่าย

				//Petty Cash 
				var pettyCashFee = document.getElementById('pcash_fee').value;
				var pettyCashVat = document.getElementById('pcash_vat').value;
				var pettyCashTax = document.getElementById('pcash_tax').value;
				var pettyCashTotal = document.getElementById('pcash_total').value;
				var pettyCashDiff = document.getElementById('pcash_diff').value;
				var pettyCashNetAmount = document.getElementById('pcash_net_amount').value;

				//Petty Cash Lists
				let pclDescriptions = []; 
				let pclFees = [];
				let pclVatPercents = [];
				let pclVats = [];
				let pclTaxPercents = [];
				let pclTaxs = [];
				let pclDiffs = [];
				let pclTaxDates = [];
				let pclTaxNos = [];
				let pclTaxRefunds = [];
				let pclTotals = [];
				let pclNetAmounts = [];

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
					const taxPercentValue = document.getElementById(`pcl_tax_percent-${i}`) ? document.getElementById(`pcl_tax_percent-${i}`).value : null; 
					const taxValue = document.getElementById(`pcl_tax-${i}`) ? document.getElementById(`pcl_tax-${i}`).value : null;
					const diffValue = document.getElementById(`pcl_diff-${i}`) ? document.getElementById(`pcl_diff-${i}`).value : null; 
					const taxDateValue = document.getElementById(`pcl_tax_date-${i}`) ? document.getElementById(`pcl_tax_date-${i}`).value : null; 
					const taxNoValue = document.getElementById(`pcl_tax_no-${i}`) ? document.getElementById(`pcl_tax_no-${i}`).value : null; 
					const taxRefundValue = taxRefundElement ? taxRefundElement.value : null;
					const totalValue = document.getElementById(`pcl_total-${i}`) ? document.getElementById(`pcl_total-${i}`).value : null; 
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
					pclTaxPercents.push((parseFloat(taxPercentValue) || 0).toFixed(2)); 
					pclTaxs.push((parseFloat(taxValue) || 0).toFixed(2)); 
					pclDiffs.push((parseFloat(diffValue) || 0).toFixed(2)); 
					pclTaxDates.push(taxDateValue);
					pclTaxNos.push(taxNoValue);
					pclTaxRefunds.push(taxRefundValue);
					pclTotals.push((parseFloat(totalValue) || 0).toFixed(2));
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

					formData.append('pCashCompId', companyId);
					formData.append('pCashDepId', departmentId);
					formData.append('pCashDate', pettyCashDate);
					formData.append('pCashPayaId', payableId);
					formData.append('pCashType', pettyCashType);


					formData.append('pCashFee', pettyCashFee);
					formData.append('pCashVat', pettyCashVat);
					formData.append('pCashTax', pettyCashTax);
					formData.append('pCashTotal', pettyCashTotal);
					formData.append('pCashDiff', pettyCashDiff);
					formData.append('pCashNetAmount', pettyCashNetAmount);



					pclDescriptions.forEach((desc, index) => formData.append(`pclDescriptions[${index}]`, desc));
					pclFees.forEach((fee, index) => formData.append(`pclFees[${index}]`, fee));
					pclVatPercents.forEach((vatPercent, index) => formData.append(`pclVatPercents[${index}]`, vatPercent));
					pclVats.forEach((vat, index) => formData.append(`pclVats[${index}]`, vat));
					pclTaxPercents.forEach((taxPercent, index) => formData.append(`pclTaxPercents[${index}]`, taxPercent));
					pclTaxs.forEach((tax, index) => formData.append(`pclTaxs[${index}]`, tax));
					pclDiffs.forEach((diff, index) => formData.append(`pclDiffs[${index}]`, diff));
					pclTaxDates.forEach((taxDate, index) => formData.append(`pclTaxDates[${index}]`, taxDate));
					pclTaxNos.forEach((taxNo, index) => formData.append(`pclTaxNos[${index}]`, taxNo));
					pclTaxRefunds.forEach((taxRefund, index) => formData.append(`pclTaxRefunds[${index}]`, taxRefund));
					pclTotals.forEach((total, index) => formData.append(`pclTotals[${index}]`, total));
					pclNetAmounts.forEach((netAmount, index) => formData.append(`pclNetAmounts[${index}]`, netAmount));


					// for(let [key, value] of formData.entries()) {
					// 	console.log(`${key}: ${value}`);
					// } 

					$.ajax({
						type: "POST",
						url: "r_pettycash_add.php",
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
							$('#addPettyCashForm')[0].reset(); 
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