<?php

	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {

		if(isset($_POST["id"])) {

			$output = '';
			include 'connect.php';

			$str_sql = "SELECT * FROM project_tb WHERE proj_id = '".$_POST["id"]."'";  
			$obj_rs = mysqli_query($obj_con, $str_sql);
			$obj_row = mysqli_fetch_array($obj_rs);
			
			$str_sql2 = "SELECT COUNT(invrcptD_status) as count_p, SUM(invrcptD_subtotal) as total_p FROM `invoice_rcpt_desc_tb` WHERE invrcptD_projid = '".$_POST["id"]."' AND invrcptD_status = 1";
			$obj_rs2 = mysqli_query($obj_con, $str_sql2);
			$obj_row2 = mysqli_fetch_array($obj_rs2);			

			$output .= '<div class="container py-4 px-4">
							<div class="row py-2">
								<div class="col-md-12 col-lg-8 px-1"></div>
								<div class="col-md-12 col-lg-2 px-1">
									<b>รหัสโครงการ :</b>
								</div>
								<div class="col-md-12 col-lg-2" style="border-bottom: 1px dashed #333; text-align: center;">
									' . $obj_row['proj_id'] . '
								</div>
							</div>

							<div class="row py-2">
								<div class="col-md-12 col-lg-2 px-1">
									<b>ชื่อโครงการ :</b>
								</div>
								<div class="col-md-12 col-lg-10" style="border-bottom: 1px dashed #333;">
									' . $obj_row['proj_name'] . '
								</div>
							</div>

							<div class="row py-2">
								<div class="col-md-12 col-lg-2 px-1">
									<b>ที่อยู่โครงการ :</b>
								</div>
								<div class="col-md-12 col-lg-10" style="border-bottom: 1px dashed #333;">
									' . $obj_row['proj_address'] . '
								</div>
							</div>

							<div class="row py-2">
								<div class="col-md-7">
									<div class="row">
										<div class="col-md-12 col-lg-4 px-1">
											<b>มูลค่าโครงการ :</b>
										</div>
										<div class="col-md-12 col-lg-6 text-right" style="border-bottom: 1px dashed #333;">
											' . number_format($obj_row['proj_amount'],2) . '
										</div>
										<div class="col-md-12 col-lg-2 px-1">
											<b>บาท</b>
										</div>
									</div>
								</div>
								<div class="col-md-5">
									<div class="row">
										<div class="col-md-12 col-lg-4 px-1">
											<b>จำนวนงวด :</b>
										</div>
										<div class="col-md-12 col-lg-6 text-center" style="border-bottom: 1px dashed #333;">
											' . $obj_row['proj_lesson'] . '
										</div>
										<div class="col-md-12 col-lg-2 px-1">
											<b>งวด</b>
										</div>
									</div>
								</div>
							</div>
							<div class="row py-2">
								<div class="col-md-7">
									<div class="row">
										<div class="col-md-12 col-lg-4 px-1">
											<b>จ่ายแล้ว :</b>
										</div>
										<div class="col-md-12 col-lg-6 text-right" style="border-bottom: 1px dashed #333;">
											' . number_format($obj_row2['total_p'],2) . '
										</div>
										<div class="col-md-12 col-lg-2 px-1">
											<b>บาท</b>
										</div>
									</div>
								</div>
								<div class="col-md-5">
									<div class="row">
										<div class="col-md-12 col-lg-4 px-1">
											<b>จำนวนที่จ่าย :</b>
										</div>
										<div class="col-md-12 col-lg-6 text-center" style="border-bottom: 1px dashed #333;">
											' . $obj_row2['count_p'] . '
										</div>
										<div class="col-md-12 col-lg-2 px-1">
											<b>งวด</b>
										</div>
									</div>
								</div>
							</div>';

			$output .= '</div>';

			echo $output;

		}

	}

?>