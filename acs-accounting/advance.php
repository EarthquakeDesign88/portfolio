<?php
include 'config/config.php'; 
__check_login();

$user_id = __session_user("id");
$user_level_id = __session_user("level_id");
$user_department_id = __session_user("department_id");

$paramurl_company_id = (isset($_GET["cid"])) ?$_GET["cid"] : 0;
$paramurl_department_id = (isset($_GET["dep"])) ?$_GET["dep"] : 0;

$authority_comp_count_dep = __authority_company_count_department($user_id,$paramurl_company_id);
$authority_dep_text_list = __authority_department_text_list($user_id,$paramurl_company_id);
$authority_dep_check = __authority_department_check($user_id,$paramurl_company_id,$paramurl_department_id);
$arrDepAll = __authority_department_list($user_id,$paramurl_company_id);

$html_title = '<b>รายจ่าย</b><i class="icofont-caret-right"></i> ใบทดลองจ่าย <i class="icofont-caret-right"></i> เลือกฝ่าย';
$icon = '<i class="icofont-paper"></i>';
$sql = " pettycash_tb AS pcash ";

$html_dep_box = __html_dep_box($html_title,'advance.php',$icon,"AND pcash.pcash_type = 2",$arrDepAll,$sql,"pcash.pcash_dep_id");
	
__page_seldep($html_dep_box);
?>
<?php
	 if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
	 
	if (!$_SESSION["user_name"]){  //check session

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
		<div class="container">
			<div class="row py-4 px-1" style="background-color: #E9ECEF">
				<div class="col-md-12 pb-4">
					<h3 class="mb-0">
						<i class="icofont-papers"></i>&nbsp;&nbsp;ทดลองจ่าย
					</h3>
				</div>

				<div class="col-md-12 d-none">
					<input type="text" class="form-control" name="compid" id="compid" value="<?=$cid;?>">
				</div>

				<div class="col-md-6 text-right">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group row">
								<label for="staticEmail" class="col-sm-3 col-form-label text-left">
									เรียงลำดับตาม : 
								</label>
								<div class="col-sm-5">
									<select class="custom-select form-control" id="orderBy">
										<option value="pcash_id" selected>ลำดับที่</option>
										<option value="pcash_no">เลขที่ใบทดลองจ่าย</option>
										<option value="pcash_date">วันที่</option>
									</select>
									
								</div>
								<div class="col-sm-4">
									<div class="input-group">
										<select class="custom-select form-control" id="orderDirection">
											<option value="DESC" selected>มากไปน้อย</option>
											<option value="ASC">น้อยไปมาก</option>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-6">
					<div class="row text-right">
						<?php if ($_GET["cid"] == 'C001') { ?>
						<div class="col-md-12 mb-4">
							<a href="pettycash.php?cid=<?=$cid;?>&dep=" type="button" class="btn btn-warning" name="btnBack" id="btnBack">
								<i class="icofont-history"></i>&nbsp;&nbsp;ย้อนกลับ
							</a>
						</div>
						<?php } ?>
					</div>
				</div>

				<div class="col-md-12">
					<div class="row">
						<div class="col-md-2 mb-0">
							<label style="margin-top: .25rem">ฝ่าย</label>
							<div class="input-group">
								<?php
									$str_sql = "SELECT * FROM department_tb WHERE dep_id = '".$dep."'";
									$obj_rs = mysqli_query($obj_con, $str_sql);
									$obj_row = mysqli_fetch_array($obj_rs);
								?>
								<input type="text" class="form-control" name="depname" id="depname" value="<?=$obj_row["dep_name"];?>" readonly style="background-color: #FFF">
								<input type="text" class="form-control d-none" name="depid" id="depid" value="<?=$dep;?>">
							</div>
						</div>

						<div class="col-md-10">
							<div class="row">
								<div class="col-auto mb-0">
									<label style="margin-top: .25rem">ค้นหาโดย : </label>
								</div>
								<div class="col-md-3 mb-0">
									<div class="checkbox">
										<input type="radio" name="searchCriteria" id="PCpcashno" value="pcash_no" checked="checked">
										<label for="PCpcashno"><span>เลขที่ใบทดลองจ่าย</span></label>
									</div>
								</div>
								<div class="col-md-3 mb-0">
									<div class="checkbox">
										<input type="radio" name="searchCriteria" id="PCpayaname" value="paya_name">
										<label for="PCpayaname"><span>ชื่อผู้รับ</span></label>
									</div>
								</div>
				
							</div>

							<div class="input-group">
								<input type="text" name="search_box" id="searchInput" class="form-control" placeholder="กรอกเลขที่ใบทดลองจ่ายที่ต้องการค้นหา" autocomplete="off">
								<div class="input-group-append">
									<a href="advance_add.php?cid=<?=$cid;?>&dep=<?=$dep;?>" class="btn btn-primary form-control" title="เพิ่ม / Add">
										<i class="icofont-plus-circle"></i>&nbsp;&nbsp;เพิ่มใบทดลองจ่าย
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row py-4 px-1" style="background-color: #FFFFFF">
				<div class="col-md-12">
					<div id="resultContainer">
						<div id="loadingTable" class="spinner-container" style="display: none;">
							<div class="spinner-border text-info" role="status">
							</div>
							<div class="loading-text">กำลังโหลด...</div> 
						</div>
					</div>
					<div id="paginationContainer"></div>
				</div>
			</div>


		</div>
	</section>

	<div id="pettyCashModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title py-2">รายละเอียดใบทดลองจ่าย <span id="pcashHeader"></span></h3>
					<button type="button" class="close" name="pcash_cancel" id="pcash_cancel" data-dismiss="modal" aria-label="Close">&times;</button>
				</div>

				<div class="modal-body" id="pettyCashDetail">
					<div id="loadingSpinner" class="spinner-container">
						<div class="spinner-border text-info" role="status">
						</div>
						<div class="loading-text">กำลังโหลด...</div> 
					</div>

					<iframe id="pdfViewer" width="100%" height="600px" style="display: none; border: none; box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);"></iframe>

					<div class="row ml-3 mt-2" id="pettyCashInDetails">
						<div class="col-3">
							<p class="text-success">
								<i class="icofont-check-circled"></i> ขอคืนภาษีทั้งหมด: 
								<span><b id="totalTaxRefund"></b></span> รายการ
							</p>
							<p class="text-danger">
								<i class="icofont-close-circled"></i> ไม่ขอคืนภาษีทั้งหมด: 
								<span><b id="totalNoTaxRefund"></b></span> รายการ
							</p>	
						</div>
						<div class="col-5">
							<p>
								<i class="icofont-file-document"></i> มีใบกำกับภาษี <span><b id="totalTaxList"></b></span> รายการ
							</p>
							<ul id="taxList" class="d-none">
							</ul>
						</div>
						<div class="col-4">
							<p><i class="icofont-ui-clock"></i> ประวัติการทำรายการ (ใบทดลองจ่าย)</p>
							<p style="font-size: 12px">
								<i class="icofont-ui-add"></i> จัดทำเอกสาร โดย:
								<span id="userCreatedAt"></span> <br>
								วันเวลา : <span id="createdAt"></span>
							</p>
							<p style="font-size: 12px">
								<i class="icofont-ui-edit"></i> แก้ไขเอกสาร โดย:
								<span><b id="userUpdatedAt"></b></span>  <br>
								วันเวลา : <span  id="updatedAt"></span>
							</p>
						
						</div>
					</div>
				</div>


				<div class="modal-footer" id="footerModal">>
				
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		var pCashCompId = document.getElementById('compid').value;
		var pCashDepId = document.getElementById('depid').value;
		let currentPage = 1;
		const itemsPerPage = 10; 

		function formatDateTH(date, type = "full") {
			const monthNames = [
				"มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน",
				"กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"
			];

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



		function renderContent(data, totalItems) {
			let table = `
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>เลขที่ใบทดลองจ่าย</th>
						<th>รายการ</th>
						<th>ยอดชำระ</th>
						<th>VAT</th>
						<th>TAX</th>
						<th>+/-</th>
						<th>ยอดชำระสุทธิ</th>
						<th>จัดการ</th>
					</tr>
				</thead>
				<tbody>`;

			if (data.length > 0) {
				data.forEach(item => {
					let countItems = item.listItems ? item.listItems.split(', ').length : 0;
					table += `
						<tr>
							<td>${item.pCashNo}</td>
							<td class="text-right">${countItems}</td>
							<td class="text-right">${item.pCashFee}</td>
							<td class="text-right">${item.pCashVat}</td>
							<td class="text-right">${item.pCashTax}</td>
							<td class="text-right">${item.pCashTotalDiff}</td>
							<td class="text-right">${item.pCashNetAmount}</td>
							<td class="text-center">
								<div class="btn-group btn-group-toggle">
									<a href="advance_edit.php?cid=C001&dep=D003&pCashId=${item.pCashId}" class="btn btn-warning edit_data" title="แก้ไข / Edit">
										<i class="icofont-edit"></i>
									</a>
									<button type="button" class="btn btn-primary" onclick="fetchDetails('${item.pCashId}', '${item.pCashNo}')" title="ดู / View">
										<i class="icofont-eye-alt"></i>
									</button>
									<button type="button" class="btn btn-danger" onclick="softDelete('${item.pCashId}', '${item.pCashNo}')" title="ลบ / Delete">
										<i class="icofont-ui-delete"></i>
									</button>
								</div>
							</td>
						</tr>`;
				});
			} else {
				table += '<tr><td colspan="8" class="text-center">ไม่พบข้อมูล</td></tr>';
			}

			table += `</tbody></table>`;
			$('#resultContainer').html(table);

			renderPagination(totalItems);
		}


		function renderPagination(totalItems) {
			const totalPages = Math.ceil(totalItems / itemsPerPage);
			let pagination = `
				<nav aria-label="Page navigation example">
					<ul class="pagination">`;

			pagination += `<li class="page-item${currentPage === 1 ? ' disabled' : ''}">
				<a class="page-link" href="#" onclick="fetchData(${currentPage - 1})" aria-label="Previous">
					<span aria-hidden="true">&laquo;</span>
				</a>
			</li>`;

			for (let i = 1; i <= totalPages; i++) {
				pagination += `<li class="page-item${currentPage === i ? ' active' : ''}">
					<a class="page-link" href="#" onclick="fetchData(${i})">${i}</a>
				</li>`;
			}

			pagination += `<li class="page-item${currentPage === totalPages ? ' disabled' : ''}">
				<a class="page-link" href="#" onclick="fetchData(${currentPage + 1})" aria-label="Next">
					<span aria-hidden="true">&raquo;</span>
				</a>
			</li>
			</ul>
			</nav>`;

			$('#paginationContainer').html(pagination);
		}

		function fetchInDetails(pCashId) {	
			$.ajax({
				url: "pettycash_controller.php", 
				type: "POST",
				data:{
					action: "fetchInDetails",
					pCashId: pCashId
				},
				beforeSend: function () {
					$('#pettyCashInDetails').hide();
				},
				success: function(response) {
					var data = response.data[0];
		
					var taxNos = data.pclTaxNos ? data.pclTaxNos.split(', ') : [];
					var taxDates = data.pclTaxDates ? data.pclTaxDates.split(', ') : [];
					var taxMonths = data.pclTaxMonths ? data.pclTaxMonths.split(', ') : [];
					var taxRefunds = data.pclTaxRefunds ? data.pclTaxRefunds.split(', ') : [];
					var userCreatedAt = data.pCashUserCreatedAt ? data.pCashUserCreatedAt : '-';
					var createdAt = data.pCashCreatedAt ? data.pCashCreatedAt : '-';
					var userUpdatedAt = data.pCashUserUpdatedAt	? data.pCashUserUpdatedAt : '-';
					var updatedAt = data.pCashUpdatedAt ? data.pCashUpdatedAt : '-';

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
					$('#userCreatedAt').text(userCreatedAt);
					$('#createdAt').text(createdAt);
					$('#userUpdatedAt').text(userUpdatedAt);
					$('#updatedAt').text(updatedAt);

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

					$('#pettyCashInDetails').fadeIn();
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


		function fetchDetails(pCashId, pCashNo) {	
			$.ajax({
				url: "pettycash_pdf.php", 
				type: "POST",
				data:{
					action: "fetchDetails",
					pCashId: pCashId
				},
				xhrFields: {
					responseType: 'blob'
				},
				beforeSend: function () {
					$('#pettyCashModal').modal('show');
					$('#loadingSpinner').fadeIn();
					$('#pdfViewer').hide();
				},
				success: function(response) {
					let footer = `
						<button type="submit" class="btn btn-info" onclick="printPettyCash('${pCashId}', '${pCashNo}')">ดาวน์โหลด</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
					`

					$('#loadingSpinner').fadeOut();
					const url = URL.createObjectURL(response);
					$('#pdfViewer').attr('src', url).fadeIn();

					fetchInDetails(pCashId);

					$('#footerModal').html(footer); 
					$('#pcashHeader').html(pCashNo);

				},
				error: function(xhr, status, error) {
					$('#loadingSpinner').fadeOut();
					swal({
						title: "พบข้อผิดพลาด",
						text: error,
						type: "error",
						closeOnClickOutside: false
					});
				}
			});
		}

		function printPettyCash(pCashId, pCashNo) {
			$.ajax({
				url: "pettycash_pdf.php",
				type: "POST",
				data: { 
					action: "generatePDF", 
					pCashId: pCashId 
				},
				xhrFields: {
					responseType: 'blob'
				},
				success: function(response) {
					
					var blobUrl = window.URL.createObjectURL(response);
					
					var a = document.createElement('a');
					a.href = blobUrl;
					a.download = 'ใบทดลองจ่าย ' + pCashNo + '.pdf';
					document.body.appendChild(a);
					a.click();
					

					setTimeout(() => {
						document.body.removeChild(a);
						window.URL.revokeObjectURL(blobUrl);
					}, 100);
				},
				error: function(error) {
					swal({
						title: "พบข้อผิดพลาด",
						text: error,
						type: "error",
						closeOnClickOutside: false
					});
				}
			});

		}


		function softDelete(pCashId, pCashNo) {
			swal({
				title: "ยืนยันการลบ?",
				text: "คุณต้องการลบใบทดลองจ่าย เลขที่: " + pCashNo + " หรือไม่?",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "ใช่, ลบเลย",
				cancelButtonText: "ยกเลิก",
				closeOnConfirm: false,
				closeOnCancel: true
			},
			function(isConfirm) {
				if (isConfirm) {
					$.ajax({
						url: "pettycash_controller.php", 
						type: "POST",
						data: {
							action: "deleteData",
							pCashId: pCashId,
							pCashNo: pCashNo
						},
						success: function(response) {
							if (response.status === "success") {
								swal({
									title: response.message,
									text: "เลขที่ใบทดลองจ่าย : " + pCashNo,
									type: "success",
									closeOnClickOutside: false
								}, function() {
									fetchPettyCash(pCashCompId, pCashDepId);
								});
							} else {
								swal({
									title: "พบข้อผิดพลาด",
									text: response.message,
									type: "warning",
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
			});
		}


		function fetchPettyCash(pCashCompId, pCashDepId, searchCriteria = '', searchTerm = '', orderBy = '', orderDirection = '', page = 1) {
			$.ajax({
				url: "pettycash_controller.php",
				type: "POST",
				data: {
					action: "fetchContent",
					pCashType: "2", //Advance
					pCashCompId: pCashCompId,
					pCashDepId: pCashDepId,
					searchCriteria: searchCriteria,
					searchTerm: searchTerm,
					orderBy: orderBy,
					orderDirection: orderDirection,
					page: page,
				},
				beforeSend: function() {
					$('#loadingTable').fadeIn();
				},
				success: function(response) {
					if (response.status === "success") {
						renderContent(response.data, response.totalItems); 
						updatePagination(response.totalItems); 
					} else {
						swal({
							title: "พบข้อผิดพลาด",
							text: response.message,
							type: "warning",
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
				},
				complete: function() {
					$('#loadingTable').fadeOut();
				}
			});
		}


		function updatePagination(totalItems, currentPage) {
			const totalPages = Math.ceil(totalItems / itemsPerPage); 
			$('#pageInfo').text(`Page ${currentPage} of ${totalPages}`);

			$('#prevPage').prop('disabled', currentPage === 1);
			$('#nextPage').prop('disabled', currentPage === totalPages);
		}

		function fetchData(page = 1) {
			let searchCriteria = $('input[name="searchCriteria"]:checked').val(); 
			let searchTerm = $('#searchInput').val(); 
			let orderBy = $('#orderBy').val(); 
			let orderDirection = $('#orderDirection').val(); 

			currentPage = page;
			fetchPettyCash(pCashCompId, pCashDepId, searchCriteria, searchTerm, orderBy, orderDirection, currentPage);
		}


		$(document).ready(function() {
			fetchData(1);

			$('#searchInput').on('input', function() {
				currentPage = 1; // Reset to first page
				fetchData(currentPage);
			});

			$('input[name="searchCriteria"]').on('change', function() {
				currentPage = 1; // Reset to first page
				fetchData(currentPage);
			});

			$(document).on('change', '#orderBy', function() {
				currentPage = 1; // Reset to first page
				fetchData(currentPage);
			});

			$(document).on('change', '#orderDirection', function() {
				currentPage = 1; // Reset to first page
				fetchData(currentPage);
			});
			

			$('#prevPage').on('click', function() {
				if (currentPage > 1) {
					currentPage--;
					fetchData(currentPage);
				}
			});


			$('#nextPage').on('click', function() {
				currentPage++;
				fetchData(currentPage);
			});

		});
	</script>
	

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>