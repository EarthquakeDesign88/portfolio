<?php
session_start();
if (!$_SESSION["user_name"]) {  //check session
    Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
} else {
    include 'connect.php';

    $irID = $_GET["irID"];
    
    // $download = isset($_GET['download']) ? $_GET['download'] : 0;
             
    function DateThai($strDate) {
        $strYear = date("Y",strtotime($strDate))+543;
        $strMonth= date("n",strtotime($strDate));
        $strDay= date("j",strtotime($strDate));
        $strHour= date("H",strtotime($strDate));
        $strMinute= date("i",strtotime($strDate));
        $strSeconds= date("s",strtotime($strDate));
        $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
        $strMonthThai=$strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear";
    }
    
    $str_sql = "SELECT * FROM invoice_rcpt_tb AS i 
    INNER JOIN company_tb AS c ON i.invrcpt_compid = c.comp_id 
    INNER JOIN customer_tb AS cust ON i.invrcpt_custid = cust.cust_id 
    INNER JOIN department_tb AS d ON i.invrcpt_depid = d.dep_id 
    WHERE invrcpt_id = '$irID'";
    
    $obj_query = mysqli_query($obj_con, $str_sql);
    $obj = mysqli_fetch_assoc($obj_query);

    $filename = "ใบวางบิล";

    $expprt_pdf = false;

    if(isset($_GET['export'])){
        if($_GET['export']== 'excel'){
            header("Content-Type: application/xls");
            header("Content-Disposition: attachment; filename=$filename.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            // $download = 0;
        }else if($_GET['export']== 'pdf'){
            $expprt_pdf = true;
        }
    }
        
    if($expprt_pdf){
       require_once __DIR__ . '/vendor/autoload.php';
        
        $defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
                    
		$mpdf = new \Mpdf\Mpdf([
			'useActiveForms' => true,
			'mode' => 'utf-8',
			'format' => [148,210],
			// 'format' => 'A5',
			'margin_top' => 6,
			'margin_bottom' => 5,
			'margin_left' => 10,
			'margin_right' => 10,
			'margin_header' => 5,     // 9 margin header
			'margin_footer' => 5,     // 9 margin footer
			'tempDir' => __DIR__ . '/tmp/',
			// 'default_font_size' => 14,
			'fontdata' => $fontData + [
				'sarabun' => [
					'R' => 'THSarabunNew.ttf',
					'I' => 'THSarabunNewItalic.ttf',
					'B' =>  'THSarabunNewBold.ttf',
					'BI' => "THSarabunNewBoldItalic.ttf",
				]
			],
		]);
		$datefooter = date('d');
		$monthfooter = date('m');
		$yearfooter = date('Y') + 543;

        ob_start();  
    }
    ?>

    <!DOCTYPE html>
    <html>
    	<head>
    		<meta charset="utf-8">
    		<title></title>
    		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    	
        	<?php if($expprt_pdf){ ?>
                <style>
                    body {
                        font-family: 'Sarabun', sans-serif;
                    }

                    table{
                        font-size: 20px;
                        line-height: 1.5;
                    }

                    .table-a {
                        text-align: center;
                        width: 100%;
                    }
                    .table-a th , .table-a td {
                        padding: 1px 8px;
                    }

                    .table-a tr:nth-child(2),.table-a tr:nth-child(1){
                        border-bottom: 1px solid #eee;
                    }

                    .table-a th {
                        font-size: 12pt;  
                        text-align: center;
                        border: 1px solid #000;
                    } 
                    .t-head tr th{
                        text-align: right;
                        font-size: 30px;
                        padding-right: 40px;
                    }
                    .t-head tr td{
                        text-align: center;
                        font-weight: bold;
                    }
                </style>    
           <?php } ?>
    	</head>
    	<body>	
            <?php for($i = 0; $i < 2; $i++) { ?>
    		<table class="t-head" style="font-size:25px" width="100%">
    			<thead>
                    <tr>
                        <td>ใบรับวางบิล</td>
                    </tr>
                    <tr>
                        <th><?= $i == 0 ? "ต้นฉบับ" : "สำเนา"?></th>
                    </tr>                    
    			</thead>
    			<tbody>
                </tbody>
            </table>
            <table>
    			<tbody>
                    <tr>
                        <td colspan="2"><?= $obj['cust_name'] ?></td>
                    </tr>     
                    <tr>
                        <td><?= $obj['cust_address'] ?></td>
                        <td width="40%"></td>
                    </tr> 
                    <tr>
                        <td colspan="2">ได้รับวางบิล จาก <?= $obj['comp_name'] ?></td>
                    </tr> 
                    <tr>
                        <td colspan="2">ดังรายละเอียดต่อไปนี้</td>
                    </tr>             
    			</tbody>
            </table>
            <table border="1" class="table-a" align="center" style="margin-top: 20px;">
    			<thead>
                    <tr>
                        <th width="10%">NO.</th>
                        <th width="30%">Bill No.</th>
                        <th width="30%">Date</th>
                        <th width="30%">Amount</th>
                    </tr>                  
    			</thead>
    			<tbody>
                    <tr>
                        <td>1</td>
                        <td><?= $obj['invrcpt_book'] == "" ? $obj['invrcpt_no'] : $obj['invrcpt_book'] . '/' . $obj['invrcpt_no'] ?></td>
                        <td><?= DateThai($obj['invrcpt_date']) ?></td>
                        <td style="text-align:right"><?= number_format($obj['invrcpt_grandtotal'],2) ?></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td> 
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>Total</td>
                        <td style="text-align:right"><?= number_format($obj['invrcpt_grandtotal'],2) ?></td>
                    </tr>
                </tbody>
            </table>
            <table>
    			<tbody>
                    <tr>
                        <td>ค่าบริการดังกล่าวรวมภาษีมูลค่าเพิ่มแล้ว</td>
                    </tr>              
    			</tbody>
            </table>
            <table style="margin-top: 40px;">
    			<tbody>
                    <tr>
                        <td>ลงชื่อ..........................................ผู้รับวางบิล นัดรับเช็ควันที่..................................................</td>
                    </tr> 
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;วันที่............/............/............</td>
                    </tr>                    
    			</tbody>
            </table>
            <table style="margin-top: 30px;">
    			<tbody>
                    <tr>
                        <td>ลงชื่อ..........................................ผู้วางบิล</td>
                    </tr>      
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;วันที่............/............/............</td>
                    </tr>       
    			</tbody>
            </table>
            <table style="margin-top: 10px;">
    			<tbody>
                    <tr>
                        <td>(ต้นฉบับ นำกลับบริษัท ฯ,สำเนาให้ลูกค้า)</td>
                    </tr>            
    			</tbody>
            </table>
            <?php } ?>
    	</body>
    </html>
    
    <?php
    if($expprt_pdf){
       $filename = $filename.".pdf"; 
        $html = ob_get_clean();ob_end_clean(); 
        $mpdf->WriteHTML($html);
        $mpdf->Output($filename,"I");
    }
    ?>
 <?php } ?>