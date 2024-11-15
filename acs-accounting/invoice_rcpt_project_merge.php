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
		$countChk = $_GET["countChk"];

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

		$amount1 = 0;
		$amount2 = 0;
		$amount3 = 0;
		$amount4 = 0;
		$amount5 = 0;
		$amount6 = 0;
		$amount7 = 0;
		$amount8 = 0;
		$subtotal = 0;
		$vat = 0;
		$grandtotal = 0;

		for ($i = 1; $i <= $countChk; $i++) {

			$irDid = "irDid" . $i;
			$irDid = $_GET["$irDid"];

			$str_sql = "SELECT * FROM invoice_rcpt_desc_tb AS ird 
						INNER JOIN customer_tb AS cust ON ird.invrcptD_custid = cust.cust_id 
						WHERE invrcptD_id = '". $irDid ."'";
			$obj_rs = mysqli_query($obj_con, $str_sql);
			$obj_row = mysqli_fetch_array($obj_rs);

			$desc1 = $obj_row["invrcptD_description1"];
			$desc2 = $obj_row["invrcptD_description2"];
			$desc3 = $obj_row["invrcptD_description3"];
			$desc4 = $obj_row["invrcptD_description4"];
			$desc5 = $obj_row["invrcptD_description5"];
			$desc6 = $obj_row["invrcptD_description6"];
			$desc7 = $obj_row["invrcptD_description7"];
			$desc8 = $obj_row["invrcptD_description8"];
			$desc9 = $obj_row["invrcptD_description9"];
			$desc10 = $obj_row["invrcptD_description10"];
			$desc11 = $obj_row["invrcptD_description11"];


			$amount1 += $obj_row["invrcptD_amount1"];
			$amount2 += $obj_row["invrcptD_amount2"];
			$amount3 += $obj_row["invrcptD_amount3"];
			$amount4 += $obj_row["invrcptD_amount4"];
			$amount5 += $obj_row["invrcptD_amount5"];
			$amount6 += $obj_row["invrcptD_amount6"];
			$amount7 += $obj_row["invrcptD_amount7"];
			$amount8 += $obj_row["invrcptD_amount8"];

			$subtotal += $obj_row["invrcptD_subtotal"];
			$vat += $obj_row["invrcptD_vat"];
			$grandtotal += $obj_row["invrcptD_grandtotal"];

			$amount = $amount1 + $amount2 + $amount3 + $amount4 + $amount5 + $amount6 + $amount7 + $amount8;

		}

?>
<!DOCTYPE html>
<html>
<head>
	
	<?php include 'head.php'; ?>

	<link rel="stylesheet" type="text/css" href="css/checkbox.css">
	<script type="text/javascript" src="js/calinvoiceRevenue.js"></script>

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

        .heading {
            font-size: 1.4rem;
            line-height: 18px;
            letter-spacing: 0.5px;
            padding: 10px 10px;
            border-bottom: #fcb108 solid 1px;
            position: relative;
            margin-left: 20px;
        }


	</style>

</head>
<body>

	<?php include 'navbar.php'; ?>

	<section>
		<div class="container">

			<form method="POST" name="frmAddInvoiceRe" id="frmAddInvoiceRe" action="">
				<div class="row py-4 px-1" style="background-color: #65f8ea">
					<div class="col-md-12">
						<h3 class="mb-0">
                            <i class="icofont-paper"></i>
							&nbsp;&nbsp;รวมงวดงาน
						</h3>
					</div>

					<div class="col-md-12 d-none">
						<<input type="text" class="form-control" name="compid" id="compid" value="<?=$cid;?>">
						<input type="text" class="form-control" name="depid" id="depid" value="<?=$dep;?>">
						<input type="text" class="form-control" name="projid" id="projid" value="<?=$projid;?>">
						<input type="text" class="form-control" name="irDid" id="irDid" value="<?=$irDid;?>">
						<input type="text" class="form-control" name="stsid" id="stsid" value="STS001">
						<input type="text" class="form-control" name="iruseridCreate" id="iruseridCreate" value="<?=$obj_row_user["user_id"];?>">
						<input type="date" class="form-control" name="irCreateDate" id="irCreateDate" value="">
						<input type="text" class="form-control" name="iruseridEdit" id="iruseridEdit" value="">
						<input type="date" class="form-control" name="irEditDate" id="irEditDate" value="">
						<input type="text" class="form-control" name="countChk" id="countChk" value="<?=$countChk;?>">
					</div>

					<div class="col-md-12 d-none">
						<?php
							for ($i = 1; $i <= $countChk; $i++) {

								$irDid = "irDid" . $i;
								$irDid = $_GET["$irDid"];

								$str_sql_ird = "SELECT * FROM invoice_rcpt_desc_tb AS ird 
											INNER JOIN customer_tb AS cust ON ird.invrcptD_custid = cust.cust_id 
											WHERE invrcptD_id = '". $irDid ."'";
								$obj_rs_ird = mysqli_query($obj_con, $str_sql_ird);
								$id = '';
								while($obj_row_ird = mysqli_fetch_array($obj_rs_ird)) {
									$id = $obj_row_ird["invrcptD_id"];
								}
						?>
								<input type="text" class="form-control" name="irDid<?=$i;?>" id="irDid<?=$i;?>" value="<?=$id;?>">
						<?php
							}
						?>
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
                    <div class="col-md-12 py-3">
						<h2 style="color: #fcb108">Preview รายละเอียด</h2>
                        <?php
							for ($i = 1; $i <= $countChk; $i++) {

								$irDid = "irDid" . $i;
								$irDid = $_GET["$irDid"];
                                $num = $i;

								$str_sql_ird = "SELECT * FROM invoice_rcpt_desc_tb AS ird 
											INNER JOIN customer_tb AS cust ON ird.invrcptD_custid = cust.cust_id 
											WHERE invrcptD_id = '". $irDid ."' ";
								$obj_rs_ird = mysqli_query($obj_con, $str_sql_ird);
								$id = '';
								while($obj_row_ird = mysqli_fetch_array($obj_rs_ird)) {
									$id = $obj_row_ird["invrcptD_id"];
                                    $desc1 = $obj_row_ird["invrcptD_description1"];
                                    $desc2 = $obj_row_ird["invrcptD_description2"];
                                    $desc3 = $obj_row_ird["invrcptD_description3"];
                                    $desc4 = $obj_row_ird["invrcptD_description4"];
                                    $desc5 = $obj_row_ird["invrcptD_description5"];

                                    $remark1 = $obj_row_ird["invrcptD_description9"];
                                    $remark2 = $obj_row_ird["invrcptD_description10"];
                                    $remark3 = $obj_row_ird["invrcptD_description11"];

                                    $subtotal = $obj_row_ird["invrcptD_subtotal"];

                                    $invrcptDLesson = $obj_row_ird["invrcptD_lesson"];

								}
						?>
                            <div class="card mt-2" style="background: #f4f4f4;">
                                <div class="card-body">
                                    <h4 class="heading">งวดที่ <?=$invrcptDLesson?> <?=$id?>
                                    <h5>รายละเอียด</h5>
                                    <ul>
                                        <?php if($desc1 != '') {?>
                                            <li><?=$desc1?></li>
                                        <?php }?>
                                        <?php if($desc2 != '') {?>
                                            <li><?=$desc2?></li>
                                        <?php }?>
                                        <?php if($desc3 != '') {?>
                                            <li><?=$desc3?></li>
                                        <?php }?>
                                        <?php if($desc4 != '') {?>
                                            <li><?=$desc4?></li>
                                        <?php }?>
                                        <?php if($desc5 != '') {?>
                                            <li><?=$desc5?></li>
                                        <?php }?>                                      
                                     
                                    </ul>

                                    <h5>หมายเหตุ
                                        <span style="color: #a4a4a4; margin-left: 10px">
                                            <?php if($remark1 == '' && $remark2 == '' && $remark3 == '') {?>
                                                -
                                            <?php }?>
                                        </span>
                                    </h5>
                                    <ul>
                                        <?php if($remark1 != '') {?>
                                            <li><?=$remark1?></li>
                                        <?php }?>
                                        <?php if($remark2 != '') {?>
                                            <li><?=$remark2?></li>
                                        <?php }?>
                                        <?php if($remark3 != '') {?>
                                            <li><?=$remark3?></li>
                                        <?php }?>
                                     
                                    </ul>

                                    <?php if($subtotal !='') {?>
                                        <h5>จำนวนเงิน <span style="color: #df0b0b"><?=number_format($subtotal, 2)?></span> บาท</h5>
                                    <?php }?>
                              
                                                        
                                </div>
                            </div>


						<?php
							}
						?>
                    
					</div>

					<div class="col-md-12 py-3">
						<h2>ใบแจ้งหนี้ฝ่าย&nbsp;&nbsp;<?=$obj_row_dep["dep_name"];?></h2>
					</div>
        

					<div class="col-md-12 pt-1 pb-3">
						<div class="row">
							<div class="col-md-3 mb-0">
								<div class="checkbox">
									<input type="checkbox" name="PartProj" id="havePart">
									<label for="havePart"><span>กรอกเลขที่ใบแจ้งหนี้ย้อนหลัง</span></label>
								</div>
							</div>
							<div class="col-md-3 mb-0 d-none">
								<input type="text" class="form-control" name="BackVal" id="BackVal">
							</div>
						</div>
						<script type="text/javascript">
							$(document).ready(function() {
							    $("#havePart").change(function() {
							        if (!this.checked) {
							            // $("#BackVal").attr('disabled', 'disabled');
							            $("#invReno").attr('readonly', 'true');
							            $("#BackVal").val('1');
							        }
							        else {
							            // $("#BackVal").removeAttr('disabled');
							            $("#invReno").removeAttr('readonly');
							             $("#BackVal").val('0');
							        }
							    });
							    $("#havePart").trigger("change")
							});
						</script>
					</div>

					<div class="col-md-3 pt-1 pb-3">
						<label for="invReno" class="mb-1">เลขที่ใบแจ้งหนี้</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-numbered"></i>
								</i>
							</div>
							<input type="text" class="form-control" name="invReno" id="invReno" autocomplete="off" placeholder="เว้นว่างไว้เพื่อสร้างอัตโนมัติ" readonly>
						</div>
						<input type="text" class="form-control d-none" name="irno" id="irno">
					</div>

					<div class="col-md-3 pt-1 pb-3">
						<label for="invRedate" class="mb-1">วันที่ใบแจ้งหนี้</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-ui-calendar"></i>
								</i>
							</div>
							<input type="date" class="form-control" name="invRedate" id="invRedate" autocomplete="off" tabindex="1">
						</div>
						<div class="input-group d-none">
							<input type="text" class="form-control" name="SelinvrcptDate" id="SelinvrcptDate">
							<input type="text" class="form-control" name="SelinvrcptYear" id="SelinvrcptYear">
							<input type="text" class="form-control" name="SelYear" id="SelYear">
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
							<select class="custom-select form-control" name="SelMonth" id="SelMonth" tabindex="2">
								<option value="">กรุณาเลือกเดือน...</option>
								<option value="01">มกราคม</option>
								<option value="02">กุมภาพันธ์</option>
								<option value="03">มีนาคม</option>
								<option value="04">เมษายน</option>
								<option value="05">พฤษภาคม</option>
								<option value="06">มิถุนายน</option>
								<option value="07">กรกฎาคม</option>
								<option value="08">สิงหาคม</option>
								<option value="09">กันยายน</option>
								<option value="10">ตุลาคม</option>
								<option value="11">พฤศจิกายน</option>
								<option value="12">ธันวาคม</option>
							</select>
						</div>
						<input type="text" class="form-control d-none" name="SelinvrcptMonth" id="SelinvrcptMonth">
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

					
					<div class="col-md-9 pt-1 pb-3 <?php if($projid == '') echo "d-none"; ?>">
						<label for="invReprojname" class="mb-1">ชื่อโครงการ</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-building-alt"></i>
								</i>
							</div>
							<input type="text" class="form-control" name="invReprojname" id="invReprojname" value="<?=$obj_row_proj["proj_name"];?>" readonly>
							<input type="text" class="form-control d-none" name="invReprojid" id="invReprojid" value="<?=$obj_row_proj["proj_id"];?>" readonly>
						</div>
					</div>

					<?php if($countChk > 1) { ?>
					<?php } else { ?>
					<div class="col-md-3 pt-1 pb-3 <?php if($projid == '') echo "d-none"; ?>">
						<label for="invRelesson" class="mb-1">งวดที่</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-listing-number"></i>
								</i>
							</div>
							<input type="text" class="form-control" name="invRelesson" id="invRelesson" value="<?=$obj_row["invrcptD_lesson"];?>" readonly>
						</div>
					</div>
					<?php } ?>

					<div class="col-md-9 pt-1 pb-3" id="showDataCust">
						<label for="searchCustomer" class="mb-1">ชื่อ-นามสกุล / ชื่อบริษัทผู้รับบริการ</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-building"></i>
								</i>
							</div>
							<input type="text" name="searchCustomer" id="searchCustomer" class="form-control" placeholder="กรอกบางส่วนของชื่อ-นามสกุล/ชื่อบริษัท" autocomplete="off" tabindex="3" value="<?=$obj_row["cust_name"];?>">

							<input type="text" class="form-control d-none" id="custid" name="custid" value="<?=$obj_row["invrcptD_custid"];?>">

							<div class="input-group-append">
								<button type="button" class="btn btn-info" onclick="
								document.getElementById('searchCustomer').value = ''; 
								document.getElementById('custid').value = '';
								document.getElementById('searchCustomer').focus();
								document.getElementById('show-listCust').style.display = 'none';" title="Clear">
									<i class="icofont-close-circled"></i>
									<span class="descbtn">&nbsp;&nbsp;Clear</span>
								</button>
								<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#AddCustomer" data-backdrop="static" data-keyboard="false" title="เพิ่มบริษัท">
									<i class="icofont-plus-circle"></i>
									<span class="descbtn">&nbsp;&nbsp;เพิ่มบริษัท</span>
								</button>
							</div>
						</div>
						<div class="list-group" id="show-listCust"></div>
					</div>
					<div class="col-md-3 pt-1 pb-3">
						<label for="" class="mb-1">งวดเดือน</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-ui-calendar"></i>
								</i>
							</div>
							<input type="text" name="invrcpt_lesson" id="invrcpt_lesson" class="form-control" placeholder="กรอกงวดเดือน" autocomplete="off" tabindex="5" value="">
						</div>
					</div>

					<div class="col-md-12 pt-1 pb-3">
						<div class="row">
							<div class="col-md-1"></div>
							<div class="col-md-7">
								<div class="row">
									<div class="col-md-12">
										<div class="text-center" style="background-color: #E9ECEF; padding: 12px 0; border: 1px solid #000;">
											<b>รายละเอียด</b>
										</div>
										<input type="text" class="form-control my-1" name="invredesc1" id="invredesc1" autocomplete="off" tabindex="4" value="<?=$obj_row["invrcptD_description1"];?>">
										<input type="text" class="form-control my-1" name="invredesc2" id="invredesc2" autocomplete="off" tabindex="5" value="<?=$obj_row["invrcptD_description2"];?>">
										<input type="text" class="form-control my-1" name="invredesc3" id="invredesc3" autocomplete="off" tabindex="6" value="<?=$obj_row["invrcptD_description3"];?>">
										<input type="text" class="form-control my-1" name="invredesc4" id="invredesc4" autocomplete="off" tabindex="7" value="<?=$obj_row["invrcptD_description4"];?>">
										<input type="text" class="form-control my-1" name="invredesc5" id="invredesc5" autocomplete="off" tabindex="8" value="<?=$obj_row["invrcptD_description5"];?>">

										<?php if($cid == 'C014' || $cid == 'C015') { ?>
											<input type="text" class="form-control my-1" name="invredesc6" id="invredesc6" autocomplete="off" tabindex="9" value="<?=$obj_row["invrcptD_description6"];?>">
											<input type="text" class="form-control my-1" name="invredesc7" id="invredesc7" autocomplete="off" tabindex="10" value="<?=$obj_row["invrcptD_description7"];?>">
											<input type="text" class="form-control my-1" name="invredesc8" id="invredesc8" autocomplete="off" tabindex="11" value="<?=$obj_row["invrcptD_description8"];?>">
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
										<input type="text" class="form-control text-right my-1" name="amount1" id="amount1" autocomplete="off" tabindex="15" value="<?=number_format($amount,2);?>">
										<input type="text" class="form-control text-right my-1 d-none" name="amountHidden1" id="amountHidden1" value="<?=$amount;?>">
										<input type="text" class="form-control text-right my-1" name="amount2" id="amount2" autocomplete="off" tabindex="16" value="0.00">
										<input type="text" class="form-control text-right my-1 d-none" name="amountHidden2" id="amountHidden2" value="0.00">
										<input type="text" class="form-control text-right my-1" name="amount3" id="amount3" autocomplete="off" tabindex="17" value="0.00">
										<input type="text" class="form-control text-right my-1 d-none" name="amountHidden3" id="amountHidden3" value="0.00">
										<input type="text" class="form-control text-right my-1" name="amount4" id="amount4" autocomplete="off" tabindex="18" value="0.00">
										<input type="text" class="form-control text-right my-1 d-none" name="amountHidden4" id="amountHidden4" value="0.00">
										<input type="text" class="form-control text-right my-1" name="amount5" id="amount5" autocomplete="off" tabindex="19" value="0.00">
										<input type="text" class="form-control text-right my-1 d-none" name="amountHidden5" id="amountHidden5" value="0.00">

										<?php if($cid == 'C014' || $cid == 'C015') { ?>	
											<input type="text" class="form-control text-right my-1" name="amount6" id="amount6" autocomplete="off" tabindex="20" value="0.00">
											<input type="text" class="form-control text-right my-1 d-none" name="amountHidden6" id="amountHidden6" value="0.00">
											<input type="text" class="form-control text-right my-1" name="amount7" id="amount7" autocomplete="off" tabindex="21" value="0.00">
											<input type="text" class="form-control text-right my-1 d-none" name="amountHidden7" id="amountHidden7" value="0.00">
											<input type="text" class="form-control text-right my-1" name="amount8" id="amount8" autocomplete="off" tabindex="22" value="0.00">
											<input type="text" class="form-control text-right my-1 d-none" name="amountHidden8" id="amountHidden8" value="0.00">
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
										<input type="text" class="form-control my-1" name="invredesc9" id="invredesc9" autocomplete="off" tabindex="12" value="">
										<input type="text" class="form-control my-1>" name="invredesc10" id="invredesc10" autocomplete="off" tabindex="13" value="">
										<input type="text" class="form-control my-1" name="invredesc11" id="invredesc11" autocomplete="off" tabindex="14" value="">
									</div>
									<div class="col-md-4">
										<div class="row">
											<label class="col-md-12 col-form-label px-0 my-1 text-right">จำนวนเงิน Sub Total</label>

											<div class="col-md-12">
												<div class="row">
													<label class="col-md-6 col-form-label px-0 text-right">ภาษีมูลค่าเพิ่ม</label>
													<div class="col-md-6">
														<input type="text" class="form-control my-1 text-right" name="vatpercent" id="showVatPercent" autocomplete="off" tabindex="24" value="<?=number_format($obj_row["invrcptD_vatpercent"],2);?>">
														<input type="text" class="form-control my-1 text-right d-none" name="vatpercentHidden" id="calVatPercent" value="<?=$obj_row["invrcptD_vatpercent"];?>" readonly>
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
										<input type="text" class="form-control my-1 text-right" name="subtotal" id="showSubtotal" autocomplete="off" tabindex="23" value="<?=number_format($subtotal,2);?>" readonly>
										<input type="text" class="form-control my-1 text-right d-none" name="subtotalHidden" id="calSubtotal" value="<?=$subtotal;?>" readonly>
									
										<input type="text" class="form-control my-1 text-right" name="vat" id="showVat" autocomplete="off" tabindex="25" value="<?=number_format($vat,2);?>" readonly>
										<input type="text" class="form-control my-1 text-right d-none" name="vatHidden" id="calVat" value="<?=$vat;?>" readonly>

										<input type="text" class="form-control text-right" name="grandtotal" id="showGrandtotal" autocomplete="off" tabindex="27" value="<?=number_format($grandtotal,2);?>" readonly>
										<input type="text" class="form-control text-right d-none" name="grandtotalHidden" id="calGrandtotal" value="<?=$grandtotal;?>" readonly>

										<div class="form-group my-1">
											<div class="checkbox">
												<input type="checkbox" id="totalVat" onclick="checkInv()">
												<label for="totalVat"><span>รวมภาษีมูลค่าเพิ่มแล้ว</span></label>
												<input type="text" class="form-control d-none" id="totalChkVat" name="" value="0">
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-1">
								<div class="row">
									<div class="col-md-12" style="padding-left: 0px;">
										<label class="col-md-12 col-form-label px-0 mt-1 text-center">+ / -</label>

										<input type="text" class="form-control my-1 text-right" name="diffvat" id="showDiffVat" autocomplete="off" tabindex="26" value="0.00">
										<input type="text" class="form-control my-1 text-right d-none" name="DiffVatHidden" id="calDiffVat" autocomplete="off" value="0.00" readonly>

										<input type="text" class="form-control my-1 text-right" name="diffgrand" id="showDiffGrand" autocomplete="off" tabindex="28" value="0.00">
										<input type="text" class="form-control my-1 text-right d-none" name="DiffGrandHidden" id="calDiffGrand" autocomplete="off" value="0.00" readonly>
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
						<input type="date" class="form-control" name="invrcptduedate">
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF;">
					<div class="col-md-12 pb-4 text-center">
					    <button type="button" class="btn btn-success  btn-action btn-action-preview"><i class="icofont-save"></i> บันทึกข้อมูล</button>
					</div>
				</div>
			</form>
			
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
				<button type="button" class="btn btn-success  btn-action btn-action-save"><i class="icofont-save"></i> บันทึกข้อมูล</button>
			</div>
			</div>
		</div>
	</div>

	<?php include 'invoice_add_customer.php'; ?>

	<script type="text/javascript">
		$(document).ready(function() {

			$("#invRedate").on('change', function() {
				var selectedDate = $(this).val();
				// alert(selectedDate);
				document.getElementById("SelinvrcptDate").value = selectedDate.substring(5, 7);
				var selectedYear = selectedDate.substring(0, 4);
				var selYear = Number(selectedYear) + 543;
				document.getElementById("SelinvrcptYear").value = selYear;
				var yy = document.getElementById("SelinvrcptYear").value;
				document.getElementById("SelYear").value = yy.substring(2, 4);
			});

			$("#SelMonth").on('change', function() {
				var selectedMonth = $(this).val();
				// alert(selectedMonth);
				document.getElementById("SelinvrcptMonth").value = selectedMonth;
			});

			$("#invReno").on('change', function() {
				var invrcptNo = $(this).val();
				// alert(invrcptNo);
				document.getElementById("irno").value = invrcptNo.substring(0, 2);
			});



			//------ START ADD INVOICE ------//
			$(".btn-action").click(function() {
				var formData = new FormData(document.getElementById("frmAddInvoiceRe"));
				
				if ($('#BackVal').val() == 0) { //---------- ย้อนหลัง ----------//

					if($('#irno').val() == $('#SelYear').val()) {
						
						if($('#SelinvrcptDate').val() == $('#SelinvrcptMonth').val()) {

							if($('#invRedate').val() == '') {
								swal({
									title: "กรุณาเลือกวันที่",
									text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
									type: "warning",
									closeOnClickOutside: false
								}).then(function() {
									frmAddInvoiceRe.invRedate.focus();
								});
							} else if($('#SelMonth').val() == '') {
								swal({
									title: "กรุณาเลือกเดือนที่ออกใบแจ้งหนี้",
									text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
									type: "warning",
									closeOnClickOutside: false
								}).then(function() {
									frmAddInvoiceRe.SelMonth.focus();
								});
							} else if($('#custid').val() == '') {
								swal({
									title: "กรุณาเลือกบริษัทผู้รับบริการ",
									text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
									type: "warning",
									closeOnClickOutside: false
								}).then(function() {
									frmAddInvoiceRe.custid.focus();
								});
							} else {
								$.ajax({
									type: 'POST',
									url: 'r_invoice_rcpt_add.php', 
									// data: $("#frmAddInvoiceRe").serialize(),
									data: formData,
									dataType: 'json',
									contentType: false,
									cache: false,
									processData:false,
									success: function(result) {
										if(result.status == 1) {
											// swal({
											// 	title: "บันทึกข้อมูลสำเร็จ",
											// 	text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
											// 	type: "success",
											// 	closeOnClickOutside: false
											// },function() {
												window.location.href = result.url;
											// });
										} else if(result.status == 2) {
											swal({
												title: "ข้อมูลซ้ำ",
												text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
												type: "success",
												closeOnClickOutside: false
											// },function() {
											// 	window.location.href = result.url;
											});
										} else {
											alert(result.message);
										}
									}
								});
							}

						} else {
							swal({
								title: "เดือนไม่ตรงกับวันที่ใบแจ้งหนี้\nกรุณาเลือกเดือนอีกครั้ง",
								text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
								type: "warning",
								closeOnClickOutside: false
							}).then(function() {
								frmAddInvoiceRe.SelMonth.focus();
							});
						}

					} else {
						swal({
							title: "ปีไม่ตรงกับวันที่ใบแจ้งหนี้\nกรุณากรอกวันที่ใบแจ้งหนี้อีกครั้ง",
							text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
							type: "warning",
							closeOnClickOutside: false
						}).then(function() {
							frmAddInvoiceRe.invReno.focus();
						});
					}

				} else { //---------- ปัจจุบัน ----------//

					if($('#SelinvrcptDate').val() == $('#SelinvrcptMonth').val()) {
						if($('#invRedate').val() == '') {
							swal({
								title: "กรุณาเลือกวันที่",
								text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
								type: "warning",
								closeOnClickOutside: false
							}).then(function() {
								frmAddInvoiceRe.invRedate.focus();
							});
						} else if($('#SelMonth').val() == '') {
							swal({
								title: "กรุณาเลือกเดือนที่ออกใบแจ้งหนี้",
								text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
								type: "warning",
								closeOnClickOutside: false
							}).then(function() {
								frmAddInvoiceRe.SelMonth.focus();
							});
						} else if($('#custid').val() == '') {
							swal({
								title: "กรุณาเลือกบริษัทผู้รับบริการ",
								text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
								type: "warning",
								closeOnClickOutside: false
							}).then(function() {
								frmAddInvoiceRe.custid.focus();
							});
						} else {
							    if($(this).hasClass("btn-action-save")){
	                                $.ajax({
	                                    type: "POST",
	                                    url: "r_invoice_rcpt_add.php",
	                                    data: formData,
	                                    dataType: 'json',
	                                    contentType: false,
	                                    cache: false,
	                                    processData:false,
	                                    success: function(result) {
	                                        if(result.status == 1) {
	                                            swal({
	                                                title: "บันทึกข้อมูลสำเร็จ",
	                                                text: "เลขที่ใบแจ้งหนี้ " + result.message,
	                                                type: "success",
	                                                closeOnClickOutside: false
	                                            },function() {
	                                                window.open("export/invoice_rcpt_pdf.php?irID="+result.irID, '_blank');window.location.href ="invoice_rcpt_project_desc.php?cid="+result.compid+"&dep="+result.depid+"&projid=<?=$projid;?>";
	                                            });
	                                        } else {
	                                            alert(result.message);
	                                        }
	                                    }
	                                });
	                                
							    }else{
	                                var modalSave = $("#modalSaveForm");
	                                var div = $("#saveForm");
	                                
	                                div.html("<br><div align='center'>กรุณารอสักครู่...<br><br><i class='icofont-spinner icofont-spin' style='font-size:24px'></i></div>");
	                                modalSave.modal('show');
	                                
	                                $.ajax({
	                                    type: 'POST',
	                                    url: "invoice_rcpt_preview.php",
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
	                                         
	                                        setTimeout(function() {
	                                            $.ajax({
	                                                url: "invoice_rcpt_preview.php?tmp="+preview_path
	                                            }); 
	                                        }, 500);
	                        
	                                    }
	                                }); 
							    }
						}
					} else {
						swal({
							title: "เดือนไม่ตรงกับวันที่ใบแจ้งหนี้\nกรุณาเลือกเดือนอีกครั้ง",
							text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
							type: "warning",
							closeOnClickOutside: false
						}).then(function() {
							frmAddInvoiceRe.SelMonth.focus();
						});
					}

				}
			});
			//------ END ADD INVOICE ------//

		});

		function Comma(Num){
			Num += '';
			Num = Num.replace(/,/g, '');
			x = Num.split('.');
			x1 = x[0];
			x2 = x.length > 1 ? '.' + x[1] : '';
			var rgx = /(\d+)(\d{3})/;

			while (rgx.test(x1))
				x1 = x1.replace(rgx, '$1' + ',' + '$2');
			return x1 + x2;
		}

			document.getElementById("amount1").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("amountHidden1").value = this.value.replace(/,/g, "");
			}

			document.getElementById("amount2").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("amountHidden2").value = this.value.replace(/,/g, "");
			}

			document.getElementById("amount3").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("amountHidden3").value = this.value.replace(/,/g, "");
			}

			document.getElementById("amount4").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("amountHidden4").value = this.value.replace(/,/g, "");
			}

			document.getElementById("amount5").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("amountHidden5").value = this.value.replace(/,/g, "");
			}

			
			<?php if($cid == 'C014' || $cid == 'C015') { ?>	
				document.getElementById("amount6").onblur = function (){
					this.value = parseFloat(this.value.replace(/,/g, ""))
					.toFixed(2)
					.toString()
					.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

					document.getElementById("amountHidden6").value = this.value.replace(/,/g, "");
				}

				document.getElementById("amount7").onblur = function (){
					this.value = parseFloat(this.value.replace(/,/g, ""))
					.toFixed(2)
					.toString()
					.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

					document.getElementById("amountHidden7").value = this.value.replace(/,/g, "");
				}

				document.getElementById("amount8").onblur = function (){
					this.value = parseFloat(this.value.replace(/,/g, ""))
					.toFixed(2)
					.toString()
					.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

					document.getElementById("amountHidden8").value = this.value.replace(/,/g, "");
				}
			<?php }?>

			document.getElementById("showVatPercent").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calVatPercent").value = this.value.replace(/,/g, "");
			}

			document.getElementById("showDiffVat").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				// .toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calDiffVat").value = this.value.replace(/,/g, "");
			}

			document.getElementById("showGrandtotal").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calGrandtotal").value = this.value.replace(/,/g, "");
			}

			document.getElementById("showDiffGrand").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				// .toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calDiffGrand").value = this.value.replace(/,/g, "");
			}

	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>