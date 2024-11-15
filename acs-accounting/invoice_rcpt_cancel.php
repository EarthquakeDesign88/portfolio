<?php

	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
		
	} else {

		include 'connect.php';

		$cid = $_GET["cid"];
		$dep = $_GET["dep"];
		// $month = $_GET["m"];
		$projid = $_GET["projid"];
		// $irDid = $_GET["irDid"];
		$irID = $_GET["irID"];

		$str_sql_user = "SELECT * FROM user_tb AS u 
						INNER JOIN level_tb AS l ON u.user_levid = l.lev_id 
						INNER JOIN department_tb AS d ON u.user_depid = d.dep_id 
						WHERE user_id = '". $_SESSION["user_id"] ."'";
		$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
		$obj_row_user = mysqli_fetch_array($obj_rs_user);

		// echo $obj_row_user["lev_name"];

		$str_sql_dep = "SELECT * FROM department_tb WHERE dep_id = '". $dep ."'";
		$obj_rs_dep = mysqli_query($obj_con, $str_sql_dep);
		$obj_row_dep = mysqli_fetch_array($obj_rs_dep);

		$str_sql_proj = "SELECT * FROM project_tb WHERE proj_id = '". $projid ."'";
		$obj_rs_proj = mysqli_query($obj_con, $str_sql_proj);
		$obj_row_proj = mysqli_fetch_array($obj_rs_proj);

		$str_sql_comp = "SELECT * FROM company_tb WHERE comp_id = '". $cid ."'";
		$obj_rs_comp = mysqli_query($obj_con, $str_sql_comp);
		$obj_row_comp = mysqli_fetch_array($obj_rs_comp);

		$str_sql_ird = "SELECT * FROM invoice_rcpt_desc_tb WHERE invrcptD_irid = '". $irID ."'";
		$obj_rs_ird = mysqli_query($obj_con, $str_sql_ird);
		$obj_row_ird = mysqli_fetch_array($obj_rs_ird);
		$countird  = mysqli_num_rows($obj_rs_ird);

		if($countird > 0) {
			if($projid == '') {
				$str_sql = "SELECT * FROM invoice_rcpt_tb AS ir 
							INNER JOIN company_tb AS c ON ir.invrcpt_compid = c.comp_id 
							INNER JOIN customer_tb AS cust ON ir.invrcpt_custid = cust.cust_id 
							WHERE invrcpt_id = '". $irID ."'";
				$obj_rs = mysqli_query($obj_con, $str_sql);
				$obj_row = mysqli_fetch_array($obj_rs);
				
				$projname = '';
				$lesson = '';
			} else {
				$str_sql = "SELECT * FROM invoice_rcpt_tb AS ir 
							INNER JOIN company_tb AS c ON ir.invrcpt_compid = c.comp_id 
							INNER JOIN customer_tb AS cust ON ir.invrcpt_custid = cust.cust_id 
							INNER JOIN project_tb AS proj ON ir.invrcpt_projid = proj.proj_id 
							INNER JOIN invoice_rcpt_desc_tb AS ird ON ir.invrcpt_id = ird.invrcptD_irid 
							WHERE invrcpt_id = '". $irID ."'";
				$obj_rs = mysqli_query($obj_con, $str_sql);
				$obj_row = mysqli_fetch_array($obj_rs);
				$irDid = $obj_row["irDid"];
				$projname = $obj_row["proj_name"];
				$lesson = $obj_row["invrcptD_lesson"];
			}
		} else {
			if($projid == '') {
				$str_sql = "SELECT * FROM invoice_rcpt_tb AS ir 
							INNER JOIN company_tb AS c ON ir.invrcpt_compid = c.comp_id 
							INNER JOIN customer_tb AS cust ON ir.invrcpt_custid = cust.cust_id 
							WHERE invrcpt_id = '". $irID ."'";
				$obj_rs = mysqli_query($obj_con, $str_sql);
				$obj_row = mysqli_fetch_array($obj_rs);
				
				$projname = '';
				$lesson = '';
			} else {
				$str_sql = "SELECT * FROM invoice_rcpt_tb AS ir 
							INNER JOIN company_tb AS c ON ir.invrcpt_compid = c.comp_id 
							INNER JOIN customer_tb AS cust ON ir.invrcpt_custid = cust.cust_id 
							INNER JOIN project_tb AS proj ON ir.invrcpt_projid = proj.proj_id 
							INNER JOIN invoice_rcpt_desc_tb AS ird ON ir.invrcpt_id = ird.invrcptD_irid 
							WHERE invrcpt_id = '". $irID ."'";
				$obj_rs = mysqli_query($obj_con, $str_sql);
				$obj_row = mysqli_fetch_array($obj_rs);
				
				$projname = $obj_row["proj_name"];
				$lesson = $obj_row["invrcptD_lesson"];
			}
		}
		
		if($obj_row["invrcpt_book"] == '') {
			$invoiceNo = $obj_row["invrcpt_no"];
		} else {
			$invoiceNo = $obj_row["invrcpt_book"]."/".$obj_row["invrcpt_no"];
		}

?>
<!DOCTYPE html>
<html>
<head>
	
	<?php include 'head.php'; ?>

	<link rel="stylesheet" type="text/css" href="css/checkbox.css">

	<style type="text/css">
		div#show-listCust {
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
	</style>

</head>
<body>

	<?php include 'navbar.php'; ?>

	<section>
		<div class="container">

			<form method="POST" name="frmCancelInvoiceRe" id="frmCancelInvoiceRe" action="">
				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-plus-circle"></i>
							&nbsp;&nbsp;ยกเลิกใบแจ้งหนี้ ( รายรับ )
						</h3>
					</div>

					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="compid" id="compid" value="<?=$cid;?>">
						<input type="text" class="form-control" name="depid" id="depid" value="<?=$dep;?>">
						<input type="text" class="form-control" name="projid" id="projid" value="<?=$projid;?>">
						<input type="text" class="form-control" name="irbook" id="irbook" value="<?=$obj_row["invrcpt_book"];?>">
						<input type="text" class="form-control" name="irno" id="irno" value="<?=$obj_row["invrcpt_no"];?>">
						<input type="text" class="form-control" name="irID" id="irID" value="<?=$irID;?>">
						<input type="text" class="form-control" name="irDid" id="irDid" value="<?=$irDid;?>">
						<input type="text" class="form-control" name="stsid" id="stsid" value="<?=$obj_row["invrcpt_stsid"];?>">
						<input type="text" class="form-control" name="iruseridCreate" id="iruseridCreate" value="<?=$obj_row["invrcpt_userid_create"];?>">
						<input type="datetime" class="form-control" name="irCreateDate" id="irCreateDate" value="<?=$obj_row["invrcpt_createdate"];?>">
						<input type="text" class="form-control" name="iruseridEdit" id="iruseridEdit" value="<?=$obj_row_user["user_id"];?>">
						<input type="date" class="form-control" name="irEditDate" id="irEditDate" value="">
						<input type="text" class="form-control" name="iryear" id="iryear" value="<?=$obj_row["invrcpt_year"];?>">
						<input type="text" class="form-control" name="irmonth" id="irmonth" value="<?=$obj_row["invrcpt_month"];?>">
						<input type="text" class="form-control" name="irfile" id="irfile" value="<?=$obj_row["invrcpt_file"];?>">
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12 py-3">
						<h2>ใบแจ้งหนี้ฝ่าย&nbsp;&nbsp;<?=$obj_row_dep["dep_name"];?></h2>
					</div>

					<div class="col-md-3 pt-1 pb-3">
						<label for="invReno" class="mb-1">เลขที่ใบแจ้งหนี้</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-numbered"></i>
								</i>
							</div>
							<input type="text" class="form-control" name="invReno" id="invReno" autocomplete="off" placeholder="เว้นว่างไว้เพื่อสร้างอัตโนมัติ" value="<?=$invoiceNo;?>" tabindex="0" readonly>
						</div>
					</div>

					<div class="col-md-3 pt-1 pb-3">
						<label for="irdate" class="mb-1">วันที่ใบแจ้งหนี้</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-ui-calendar"></i>
								</i>
							</div>
							<input type="date" class="form-control" name="irdate" id="irdate" autocomplete="off" tabindex="1" value="<?=$obj_row["invrcpt_date"];?>" readonly>
						</div>
					</div>

					<div class="col-md-3 pt-1 pb-3">
						<label for="SelMonth" class="mb-1">เดือน</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-ui-calendar"></i>
								</i>
							</div>
							<input class="form-control" name="SelMonth" id="SelMonth" readonly></input>
						</div>
					</div>

					<div class="col-md-3 pt-1 pb-3">
						<label for="invRedepid" class="mb-1">ฝ่าย</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-company"></i>
								</i>
							</div>
							<input type="text" class="form-control" name="invRedepname" id="invRedepname" value="<?=$obj_row_dep["dep_name"];?>" readonly>
							<input type="text" class="form-control d-none" name="invRedepid" id="invRedepid" value="<?=$dep;?>">
						</div>
					</div>

					<div class="col-md-9 pt-1 pb-3" id="showDataProject">
						<label for="searchProject" class="mb-1">ชื่อโครงการ</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-building-alt"></i>
								</i>
							</div>
							<input type="text" class="form-control" name="searchProject" id="searchProject" placeholder="กรอกชื่อโครงการ" value="<?=$projname;?>" autocomplete="off" readonly>
							<input type="text" class="form-control d-none" name="invReprojid" id="invReprojid" value="<?=$obj_row["invrcpt_projid"];?>" readonly>
						</div>
						<div class="list-group" id="show-listProj"></div>
					</div>


					<div class="col-md-3 pt-1 pb-3 <?php if($projid == '') echo "d-none"; ?>">
						<label for="invRelesson" class="mb-1">งวดที่</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-listing-number"></i>
								</i>
							</div>
							<input type="text" class="form-control" name="invRelesson" id="invRelesson" placeholder="กรอกงวด" value="<?=$lesson;?>" autocomplete="off" readonly>
						</div>
					</div>

					<div class="col-md-12 pt-1 pb-3" id="showDataCust">
						<label for="searchCustomer" class="mb-1">ชื่อ-นามสกุล / ชื่อบริษัทผู้รับบริการ</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-building"></i>
								</i>
							</div>
							<input type="text" name="searchCustomer" id="searchCustomer" class="form-control" placeholder="กรอกบางส่วนของชื่อ-นามสกุล/ชื่อบริษัท" autocomplete="off" tabindex="3" value="<?=$obj_row["cust_name"];?>" readonly>

							<input type="text" class="form-control d-none" id="custid" name="custid" value="<?=$obj_row["invrcpt_custid"];?>">

						</div>
						<div class="list-group" id="show-listCust"></div>
					</div>


					<!-- <?php
						if ($obj_row_comp["comp_name"] == "บริษัท ธรรมบุรี จำกัด") {
					?> -->

						<!-- <script type="text/javascript" src="js/calinvoiceRevenue_TBRI.js"></script>

						<div class="col-md-12 pt-1 pb-3">
							<div class="row">
								<div class="col-md-1"></div>
								<div class="col-md-7 pr-1">
									<div class="row">
										<div class="col-md-12">
											<div class="text-center" style="background-color: #E9ECEF; padding: 12px 0; border: 1px solid #000;">
												<b>รายละเอียด</b>
											</div>
											<div class="row">
												<div class="col-md-9 pr-1">
													<input type="text" class="form-control my-1" name="invredesc1" id="invredesc1" autocomplete="off" tabindex="6" value="<?=$obj_row["invrcpt_description1"];?>" placeholder="กรอกรายละเอียด">
													<input type="text" class="form-control my-1" name="invredesc2" id="invredesc2" autocomplete="off" tabindex="8" value="<?=$obj_row["invrcpt_description2"];?>" placeholder="กรอกรายละเอียด">
													<input type="text" class="form-control my-1" name="invredesc3" id="invredesc3" autocomplete="off" tabindex="10" value="<?=$obj_row["invrcpt_description3"];?>" placeholder="กรอกรายละเอียด">
													<input type="text" class="form-control my-1" name="invredesc4" id="invredesc4" autocomplete="off" tabindex="12" value="<?=$obj_row["invrcpt_description4"];?>" placeholder="กรอกรายละเอียด">
													<input type="text" class="form-control my-1" name="invredesc5" id="invredesc5" autocomplete="off" tabindex="14" value="<?=$obj_row["invrcpt_description5"];?>" placeholder="กรอกรายละเอียด">
													<div class="row px-0">
														<div class="col-md-9 pr-1">
															<input type="text" class="form-control" name="invredesc6" id="invredesc6" autocomplete="off" tabindex="16" value="<?=$obj_row["invrcpt_description6"];?>" placeholder="กรอกรายละเอียด">
														</div>
														<div class="col-md-3 pl-1">
															<input type="text" class="form-control text-right" name="invresubdesc6" id="invresubdesc6" autocomplete="off" tabindex="17" value="<?=number_format($obj_row["invrcpt_sub_description6"],4);?>" placeholder="กรอกจำนวนหน่วยคูณ">
															<input type="text" class="form-control text-right d-none" name="invresubdescHidden6" id="invresubdescHidden6" value="<?=$obj_row["invrcpt_sub_description6"];?>">
														</div>
													</div>
													
													<input type="text" class="form-control my-1" name="invredesc7" id="invredesc7" autocomplete="off" tabindex="19" value="<?=$obj_row["invrcpt_description7"];?>" placeholder="กรอกรายละเอียด">
													<input type="text" class="form-control my-1" name="invredesc8" id="invredesc8" autocomplete="off" tabindex="21" value="<?=$obj_row["invrcpt_description8"];?>" placeholder="กรอกรายละเอียด">
												</div>
												<div class="col-md-3 pl-1">
													<input type="text" class="form-control my-1 text-center" name="invresubdesc1" id="invresubdesc1" tabindex="7" autocomplete="off" value="<?=$obj_row["invrcpt_sub_description1"];?>" placeholder="กรอกเฉพาะตัวอักษร">
													<input type="text" class="form-control my-1 text-center" name="invresubdesc2" id="invresubdesc2" tabindex="9" autocomplete="off" value="<?=$obj_row["invrcpt_sub_description2"];?>" placeholder="กรอกเฉพาะตัวอักษร">
													<input type="text" class="form-control my-1 text-right" name="invresubdesc3" id="invresubdesc3" tabindex="11" autocomplete="off" value="<?=number_format($obj_row["invrcpt_sub_description3"],4);?>" placeholder="กรอกเฉพาะตัวเลข">
													<input type="text" class="form-control my-1 d-none" readonly name="invresubdescHidden3" id="invresubdescHidden3" autocomplete="off" value="<?=$obj_row["invrcpt_sub_description3"];?>">
													<input type="text" class="form-control my-1 text-right" name="invresubdesc4" id="invresubdesc4" tabindex="13" autocomplete="off" value="<?=number_format($obj_row["invrcpt_sub_description4"],4);?>" placeholder="กรอกเฉพาะตัวเลข">
													<input type="text" class="form-control my-1 d-none" readonly name="invresubdescHidden4" id="invresubdescHidden4" autocomplete="off" value="<?=$obj_row["invrcpt_sub_description4"];?>">
													<input type="text" class="form-control my-1 text-right" name="invresubdesc5" id="invresubdesc5" tabindex="15" autocomplete="off" value="<?=number_format($obj_row["invrcpt_sub_description5"],4);?>" placeholder="กรอกเฉพาะตัวเลข">
													<input type="text" class="form-control my-1 d-none" readonly name="invresubdescHidden5" id="invresubdescHidden5" autocomplete="off" value="<?=$obj_row["invrcpt_sub_description5"];?>">
													<input type="text" class="form-control my-1 text-right" name="invresubdesc7" id="invresubdesc7" tabindex="18" autocomplete="off" value="<?=number_format($obj_row["invrcpt_sub_description7"],4);?>" placeholder="กรอกเฉพาะตัวเลข">
													<input type="text" class="form-control my-1 d-none" readonly name="invresubdescHidden7" id="invresubdescHidden7" autocomplete="off" value="<?=$obj_row["invrcpt_sub_description7"];?>">
													<input type="text" class="form-control my-1 text-right" name="invresubdesc8" id="invresubdesc8" tabindex="20" autocomplete="off" value="<?=number_format($obj_row["invrcpt_sub_description8"],4);?>" placeholder="กรอกเฉพาะตัวเลข">
													<input type="text" class="form-control my-1 d-none" readonly name="invresubdescHidden8" id="invresubdescHidden8" autocomplete="off" value="<?=$obj_row["invrcpt_sub_description8"];?>">
													<input type="text" class="form-control my-1 text-right" name="invresubdesc9" id="invresubdesc9" tabindex="22" autocomplete="off" value="<?=number_format($obj_row["invrcpt_sub_description9"],4);?>" placeholder="กรอกเฉพาะตัวเลข">
													<input type="text" class="form-control my-1 d-none" readonly name="invresubdescHidden9" id="invresubdescHidden9" autocomplete="off" value="<?=$obj_row["invrcpt_sub_description9"];?>">
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-3 pl-1">
									<div class="row">
										<div class="col-md-12">
											<div class="text-center" style="background-color: #E9ECEF; padding: 12px 0; border: 1px solid #000;">
												<b>จำนวนเงิน</b>
											</div>
											<input type="text" class="form-control text-right my-1" name="amount1" id="amount1" autocomplete="off" tabindex="23" value="<?=number_format($obj_row["invrcpt_amount1"],2);?>">
											<input type="text" class="form-control text-right my-1 d-none" name="amountHidden1" id="amountHidden1" value="<?=$obj_row["invrcpt_amount1"];?>">

											<input type="text" class="form-control text-right my-1" name="amount2" id="amount2" autocomplete="off" tabindex="24" value="<?=number_format($obj_row["invrcpt_amount2"],2);?>">
											<input type="text" class="form-control text-right my-1 d-none" name="amountHidden2" id="amountHidden2" value="<?=$obj_row["invrcpt_amount2"];?>">

											<input type="text" class="form-control text-right my-1" name="amount3" id="amount3" autocomplete="off" tabindex="25" value="<?=number_format($obj_row["invrcpt_amount3"],2);?>">
											<input type="text" class="form-control text-right my-1 d-none" name="amountHidden3" id="amountHidden3" value="<?=$obj_row["invrcpt_amount3"];?>">

											<input type="text" class="form-control text-right my-1" name="amount4" id="amount4" autocomplete="off" tabindex="26" value="<?=number_format($obj_row["invrcpt_amount4"],2);?>">
											<input type="text" class="form-control text-right my-1 d-none" name="amountHidden4" id="amountHidden4" value="<?=$obj_row["invrcpt_amount4"];?>">

											<input type="text" class="form-control text-right my-1" name="amount5" id="amount5" autocomplete="off" tabindex="27" value="<?=number_format($obj_row["invrcpt_amount5"],2);?>">
											<input type="text" class="form-control text-right my-1 d-none" name="amountHidden5" id="amountHidden5" value="<?=$obj_row["invrcpt_amount5"];?>">

											<input type="text" class="form-control text-right my-1" name="amount6" id="amount6" autocomplete="off" tabindex="28" value="<?=number_format($obj_row["invrcpt_amount6"],2);?>">
											<input type="text" class="form-control text-right my-1 d-none" name="amountHidden6" id="amountHidden6" value="<?=$obj_row["invrcpt_amount6"];?>">

											<input type="text" class="form-control text-right my-1" name="amount7" id="amount7" autocomplete="off" tabindex="29" value="<?=number_format($obj_row["invrcpt_amount7"],2);?>">
											<input type="text" class="form-control text-right my-1 d-none" name="amountHidden7" id="amountHidden7" value="<?=$obj_row["invrcpt_amount7"];?>">

											<input type="text" class="form-control text-right my-1" name="amount8" id="amount8" autocomplete="off" tabindex="30" value="<?=number_format($obj_row["invrcpt_amount8"],2);?>">
											<input type="text" class="form-control text-right my-1 d-none" name="amountHidden8" id="amountHidden8" value="<?=$obj_row["invrcpt_amount8"];?>">
										</div>
									</div>
								</div>
								<div class="col-md-1"></div>
							</div>

							<div class="row">
								<div class="col-md-1"></div>
								<div class="col-md-7 pr-1">
									<div class="row">
										<div class="col-md-8">
											<input type="text" class="form-control my-1 d-none" name="invredesc9" id="invredesc9" autocomplete="off" tabindex="31" value="">
											<input type="text" class="form-control my-1 d-none" name="invredesc10" id="invredesc10" autocomplete="off" tabindex="32" value="">
											<input type="text" class="form-control my-1 d-none" name="invredesc11" id="invredesc11" autocomplete="off" tabindex="33" value="">
										</div>
										<div class="col-md-4">
											<div class="row">
												<label class="col-md-12 col-form-label my-1 text-right">จำนวนเงิน Sub Total</label>

												<div class="col-md-12">
													<div class="row">
														<label class="col-md-6 col-form-label px-0 text-right">ภาษีมูลค่าเพิ่ม</label>
														<div class="col-md-6">
															<input type="text" class="form-control my-1 text-right" name="vatpercent" id="showVatPercent" autocomplete="off" tabindex="35" value="<?=number_format($obj_row["invrcpt_vatpercent"],2);?>">
															<input type="text" class="form-control my-1 text-right d-none" name="vatpercentHidden" id="calVatPercent" value="<?=$obj_row["invrcpt_vatpercent"];?>" readonly>
														</div>
													</div>
												</div>

												<label class="col-md-12 col-form-label text-right">จำนวนเงินรวม Grand Total</label>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-3 pl-1">
									<div class="row">
										<div class="col-md-12">
											<input type="text" class="form-control my-1 text-right" name="subtotal" id="showSubtotal" autocomplete="off" tabindex="34" value="<?=number_format($obj_row["invrcpt_subtotal"],2);?>" readonly>
											<input type="text" class="form-control my-1 text-right d-none" name="subtotalHidden" id="calSubtotal" value="<?=$obj_row["invrcpt_subtotal"];?>" readonly>
										
											<input type="text" class="form-control my-1 text-right" name="vat" id="showVat" autocomplete="off" tabindex="36" value="<?=number_format($obj_row["invrcpt_vat"],2);?>" readonly>
											<input type="text" class="form-control my-1 text-right d-none" name="vatHidden" id="calVat" value="<?=$obj_row["invrcpt_vat"];?>" readonly>

											<input type="text" class="form-control text-right" name="grandtotal" id="showGrandtotal" autocomplete="off" tabindex="38" value="<?=number_format($obj_row["invrcpt_grandtotal"],2);?>" readonly>
											<input type="text" class="form-control text-right d-none" name="grandtotalHidden" id="calGrandtotal" value="<?=$obj_row["invrcpt_grandtotal"];?>" readonly>

											<input type="text" class="form-control text-right d-none" name="balanceHidden" id="balanceHidden" value="<?=$obj_row["invrcpt_balancetotal"];?>" readonly>
										</div>
									</div>
								</div>
								<div class="col-md-1">
									<div class="row">
										<div class="col-md-12" style="padding-left: 0px;">
											<label class="col-md-12 col-form-label px-0 mt-1 text-center">+ / -</label>

											<input type="text" class="form-control my-1 text-right" name="diffvat" id="showDiffVat" autocomplete="off" tabindex="37" value="<?=number_format($obj_row["invrcpt_differencevat"],2);?>">
											<input type="text" class="form-control my-1 text-right d-none" name="DiffVatHidden" id="calDiffVat" autocomplete="off" value="<?=$obj_row["invrcpt_differencevat"];?>" readonly>

											<input type="text" class="form-control my-1 text-right" name="diffgrand" id="showDiffGrand" autocomplete="off" tabindex="39" value="<?=number_format($obj_row["invrcpt_differencegrandtotal"],2);?>">
											<input type="text" class="form-control my-1 text-right d-none" name="DiffGrandHidden" id="calDiffGrand" autocomplete="off" value="<?=$obj_row["invrcpt_differencegrandtotal"];?>" readonly>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-12 pt-1 pb-3">
							<div class="row">
								<div class="col-md-1"></div>
								<div class="col-md-7 pr-1">
									<h4>
										ได้รับใบแจ้งหนี้เรียบร้อยแล้ว<br>
										โปรดชำระเงินภายในวันที่ <input type="date" class="form-control" style="display: inline-block; width: 50%" name="invrcptduedate" id="invrcptduedate" tabindex="40" value="<?=$obj_row["invrcpt_duedate"];?>">
									</h4>
								</div>
								<div class="col-md-3 pl-1"></div>
								<div class="col-md-1"></div>
							</div>
						</div>

					<?php } else { ?> -->

						<!-- <script type="text/javascript" src="js/calinvoiceRevenue.js"></script> -->
						
						<div class="col-md-12 pt-1 pb-3">
							<div class="row">
								<div class="col-md-1"></div>
								<div class="col-md-7">
									<div class="row">
										<div class="col-md-12">
											<div class="text-center" style="background-color: #E9ECEF; padding: 12px 0; border: 1px solid #000;">
												<b>รายละเอียด</b>
											</div>
											<input type="text" class="form-control my-1" name="invredesc1" id="invredesc1" autocomplete="off" tabindex="4" value="<?=$obj_row["invrcpt_description1"];?>" readonly>
											<input type="text" class="form-control my-1" name="invredesc2" id="invredesc2" autocomplete="off" tabindex="5" value="<?=$obj_row["invrcpt_description2"];?>" readonly>
											<input type="text" class="form-control my-1" name="invredesc3" id="invredesc3" autocomplete="off" tabindex="6" value="<?=$obj_row["invrcpt_description3"];?>" readonly>
											<input type="text" class="form-control my-1" name="invredesc4" id="invredesc4" autocomplete="off" tabindex="7" value="<?=$obj_row["invrcpt_description4"];?>" readonly>
											<input type="text" class="form-control my-1" name="invredesc5" id="invredesc5" autocomplete="off" tabindex="8" value="<?=$obj_row["invrcpt_description5"];?>" readonly>

											<?php if($cid == 'C014' || $cid == 'C015') { ?>
												<input type="text" class="form-control my-1" name="invredesc6" id="invredesc6" autocomplete="off" tabindex="9" value="<?=$obj_row["invrcpt_description6"];?>" readonly>
												<input type="text" class="form-control my-1" name="invredesc7" id="invredesc7" autocomplete="off" tabindex="10" value="<?=$obj_row["invrcpt_description7"];?>" readonly>
												<input type="text" class="form-control my-1" name="invredesc8" id="invredesc8" autocomplete="off" tabindex="11" value="<?=$obj_row["invrcpt_description8"];?>" readonly> 
											<?php } else { ?>
												<input type="text" class="form-control my-1" name="invredesc6" id="invredesc6" autocomplete="off" tabindex="9" value="" readonly>
												<input type="text" class="form-control my-1" name="invredesc7" id="invredesc7" autocomplete="off" tabindex="10" value="" readonly>
												<input type="text" class="form-control my-1" name="invredesc8" id="invredesc8" autocomplete="off" tabindex="11" value="" readonly>							
											<?php }?>

										</div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="row">
										<div class="col-md-12">
											<div class="text-center" style="background-color: #E9ECEF; padding: 12px 0; border: 1px solid #000;">
												<b>จำนวนเงิน</b>
											</div>
											<input type="text" class="form-control text-right my-1" name="amount1" id="amount1" autocomplete="off" tabindex="15" value="<?=number_format($obj_row["invrcpt_amount1"],2);?>" readonly>
											<input type="text" class="form-control text-right my-1 d-none" name="amountHidden1" id="amountHidden1" value="<?=$obj_row["invrcpt_amount1"];?>">
											<input type="text" class="form-control text-right my-1" name="amount2" id="amount2" autocomplete="off" tabindex="16" value="<?=number_format($obj_row["invrcpt_amount2"],2);?>" readonly>
											<input type="text" class="form-control text-right my-1 d-none" name="amountHidden2" id="amountHidden2" value="<?=$obj_row["invrcpt_amount2"];?>">
											<input type="text" class="form-control text-right my-1" name="amount3" id="amount3" autocomplete="off" tabindex="17" value="<?=number_format($obj_row["invrcpt_amount3"],2);?>" readonly>
											<input type="text" class="form-control text-right my-1 d-none" name="amountHidden3" id="amountHidden3" value="<?=$obj_row["invrcpt_amount3"];?>">
											<input type="text" class="form-control text-right my-1" name="amount4" id="amount4" autocomplete="off" tabindex="18" value="<?=number_format($obj_row["invrcpt_amount4"],2);?>" readonly>
											<input type="text" class="form-control text-right my-1 d-none" name="amountHidden4" id="amountHidden4" value="<?=$obj_row["invrcpt_amount4"];?>">
											<input type="text" class="form-control text-right my-1" name="amount5" id="amount5" autocomplete="off" tabindex="19" value="<?=number_format($obj_row["invrcpt_amount5"],2);?>" readonly>
											<input type="text" class="form-control text-right my-1 d-none" name="amountHidden5" id="amountHidden5" value="<?=$obj_row["invrcpt_amount5"];?>">

											<?php if($cid == 'C014' || $cid == 'C015') { ?>	
												<input type="text" class="form-control text-right my-1" name="amount6" id="amount6" autocomplete="off" tabindex="20" value="<?=number_format($obj_row["invrcpt_amount6"],2);?>" readonly>
												<input type="text" class="form-control text-right my-1 d-none" name="amountHidden6" id="amountHidden6" value="<?=$obj_row["invrcpt_amount6"];?>">
												<input type="text" class="form-control text-right my-1" name="amount7" id="amount7" autocomplete="off" tabindex="21" value="<?=number_format($obj_row["invrcpt_amount7"],2);?>" readonly>
												<input type="text" class="form-control text-right my-1 d-none" name="amountHidden7" id="amountHidden7" value="<?=$obj_row["invrcpt_amount7"];?>">
												<input type="text" class="form-control text-right my-1" name="amount8" id="amount8" autocomplete="off" tabindex="22" value="<?=number_format($obj_row["invrcpt_amount8"],2);?>" readonly>
												<input type="text" class="form-control text-right my-1 d-none" name="amountHidden8" id="amountHidden8" value="<?=$obj_row["invrcpt_amount8"];?>">
											<?php } else { ?>
												<input type="text" class="form-control text-right my-1" name="amount6" autocomplete="off" tabindex="20" readonly>
												<input type="text" class="form-control text-right my-1 d-none" name="amountHidden6" id="amountHidden6" value="0.00">
												<input type="text" class="form-control text-right my-1" name="amount7" autocomplete="off" tabindex="21" readonly>
												<input type="text" class="form-control text-right my-1 d-none" name="amountHidden7" id="amountHidden7" value="0.00">
												<input type="text" class="form-control text-right my-1" name="amount8" autocomplete="off" tabindex="22" readonly>
												<input type="text" class="form-control text-right my-1 d-none" name="amountHidden8" id="amountHidden8" value="0.00">
											<?php }?>
			
										</div>
									</div>
								</div>
								<div class="col-md-1"></div>
							</div>

							<div class="row">
								<div class="col-md-1"></div>
								<div class="col-md-7">
									<div class="row">
										<div class="col-md-8">
											<input type="text" class="form-control my-1" name="invredesc9" id="invredesc9" autocomplete="off" tabindex="12" value="<?=$obj_row["invrcpt_description9"];?>" readonly>
											<input type="text" class="form-control my-1" name="invredesc10" id="invredesc10" autocomplete="off" tabindex="13" value="<?=$obj_row["invrcpt_description10"];?>" readonly>
											<input type="text" class="form-control my-1" name="invredesc11" id="invredesc11" autocomplete="off" tabindex="14" value="<?=$obj_row["invrcpt_description11"];?>" readonly>
										</div>
										<div class="col-md-4">
											<div class="row">
												<label class="col-md-12 col-form-label px-0 my-1 text-right">จำนวนเงิน Sub Total</label>

												<div class="col-md-12">
													<div class="row">
														<label class="col-md-6 col-form-label px-0 text-right">ภาษีมูลค่าเพิ่ม</label>
														<div class="col-md-6">
															<input type="text" class="form-control my-1 text-right" name="vatpercent" id="showVatPercent" autocomplete="off" tabindex="24" value="<?=number_format($obj_row["invrcpt_vatpercent"],2);?>" readonly>
															<input type="text" class="form-control my-1 text-right d-none" name="vatpercentHidden" id="calVatPercent" value="<?=$obj_row["invrcpt_vatpercent"];?>" readonly>
														</div>
													</div>
												</div>

												<label class="col-md-12 col-form-label px-0 text-right">จำนวนเงินรวม Grand Total</label>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="row">
										<div class="col-md-12">
											<input type="text" class="form-control my-1 text-right" name="subtotal" id="showSubtotal" autocomplete="off" tabindex="23" value="<?=number_format($obj_row["invrcpt_subtotal"],2);?>" readonly>
											<input type="text" class="form-control my-1 text-right d-none" name="subtotalHidden" id="calSubtotal" value="<?=$obj_row["invrcpt_subtotal"];?>" readonly>
											<input type="text" class="form-control my-1 text-right" name="vat" id="showVat" autocomplete="off" tabindex="25" value="<?=number_format($obj_row["invrcpt_vat"],2);?>" readonly>
											<input type="text" class="form-control my-1 text-right d-none" name="vatHidden" id="calVat" value="<?=$obj_row["invrcpt_vat"];?>" readonly>
											<input type="text" class="form-control text-right" name="grandtotal" id="showGrandtotal" autocomplete="off" tabindex="27" value="<?=number_format($obj_row["invrcpt_grandtotal"],2);?>" readonly>
											<input type="text" class="form-control text-right d-none" name="grandtotalHidden" id="calGrandtotal" value="<?=$obj_row["invrcpt_grandtotal"];?>" readonly>
											<input type="text" class="form-control text-right d-none" name="balanceHidden" id="balanceHidden" value="<?=$obj_row["invrcpt_balancetotal"];?>" readonly>
										</div>
									</div>
								</div>
								<div class="col-md-1">
									<div class="row">
										<div class="col-md-12" style="padding-left: 0px;">
											<label class="col-md-12 col-form-label px-0 mt-1 text-center">+ / -</label>

											<input type="text" class="form-control my-1 text-right" name="diffvat" id="showDiffVat" autocomplete="off" tabindex="26" value="<?=number_format($obj_row["invrcpt_differencevat"],2);?>" readonly>
											<input type="text" class="form-control my-1 text-right d-none" name="DiffVatHidden" id="calDiffVat" autocomplete="off" value="<?=$obj_row["invrcpt_differencevat"];?>" readonly>

											<input type="text" class="form-control my-1 text-right" name="diffgrand" id="showDiffGrand" autocomplete="off" tabindex="28" value="<?=number_format($obj_row["invrcpt_differencegrandtotal"],2);?>" readonly>
											<input type="text" class="form-control my-1 text-right d-none" name="DiffGrandHidden" id="calDiffGrand" autocomplete="off" value="<?=$obj_row["invrcpt_differencegrandtotal"];?>" readonly>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-12 pt-1 pb-3 d-none">
							<input type="text" class="form-control" name="invresubdesc1">
							<input type="text" class="form-control" name="invresubdesc2">
							<input type="text" class="form-control" name="invresubdescHidden3">
							<input type="text" class="form-control" name="invresubdescHidden4">
							<input type="text" class="form-control" name="invresubdescHidden5">
							<input type="text" class="form-control" name="invresubdescHidden6">
							<input type="text" class="form-control" name="invresubdescHidden7">
							<input type="text" class="form-control" name="invresubdescHidden8">
							<input type="text" class="form-control" name="invresubdescHidden9">
							<input type="date" class="form-control" name="invrcptduedate" value="<?=$obj_row["invrcpt_duedate"];?>">
						</div>

					<!-- <?php } ?> -->
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF;">
					<div class="col-md-12 pb-4 text-center">
						<input type="button" class="btn btn-success px-5 py-2 mx-1" name="btnInsert" id="btnInsert" value="บันทึก">
					</div>
				</div>
			</form>
			
		</div>
	</section>

	<script type="text/javascript">
		$(document).ready(function() {

			$("#btnInsert").click(function() {
				var formData = new FormData(this.form);
                $.ajax({
                    type: 'POST',
                    url: 'r_invoice_rcpt_cancel.php', 
                    // data: $("#frmCancelInvoiceRe").serialize(),
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData:false,
                    success: function(result) {
                        if(result.status == 1) {
                            swal({
                            	title: "ยกเลิกข้อมูลสำเร็จ",
								text: "เลขที่ใบแจ้งหนี้รายรับ " + result.invoiceRcpt,
                            	type: "success",
                            	closeOnClickOutside: false
                            },function() {
                                window.location.href = "invoice_rcpt.php?cid="+result.compid+"&dep="+result.depid;
                            });
                        } else {
                            alert(result.message);
                        }
                    }
                });
			});

		});

	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>