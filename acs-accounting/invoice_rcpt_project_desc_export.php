<?php
include 'connect.php';

function DateThai($strDate)
{

    $strYear = date("Y", strtotime($strDate)) + 543;
    $strMonth = date("n", strtotime($strDate));
    $strDay = date("j", strtotime($strDate));
    $strHour = date("H", strtotime($strDate));
    $strMinute = date("i", strtotime($strDate));
    $strSeconds = date("s", strtotime($strDate));
    $strMonthCut = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
    $strMonthThai = $strMonthCut[$strMonth];
    return "$strDay $strMonthThai $strYear";
}



function DateMonthThai($strDate, $mode)
{

    if ($mode === "head") {
        $strYear = date("Y", strtotime($strDate)) + 543;
    } else {
        $strYear = substr(date("Y", strtotime($strDate)) + 543, -2);
    }

    $strMonth = date("n", strtotime($strDate));
    $strDay = date("j", strtotime($strDate));
    $strHour = date("H", strtotime($strDate));
    $strMinute = date("i", strtotime($strDate));
    $strSeconds = date("s", strtotime($strDate));
    if ($mode === "head") {
        $strMonthCut = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
    } else {

        $strMonthCut = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
    }

    $strMonthThai = $strMonthCut[$strMonth];

    return array($strDay, $strMonthThai, $strYear);
}



$cid = isset($_GET['cid']) ? $_GET['cid'] : '';
$dep = isset($_GET['dep']) ? $_GET['dep'] : '';
$projid = isset($_GET['projid']) ? $_GET['projid'] : '';

$download = isset($_GET['download']) ? $_GET['download'] : 0;

// $str_sql = "SELECT *, ird.invrcptD_irid, 
//                 SUM(invrcptD_subtotal) AS _sum, 
//                 GROUP_CONCAT(invrcptD_lesson) AS lesson 
//             FROM invoice_rcpt_desc_tb AS ird 
//             INNER JOIN company_tb AS cp ON ird.invrcptD_compid = cp.comp_id
// 			INNER JOIN project_tb AS p ON ird.invrcptD_projid = p.proj_id 
// 			INNER JOIN customer_tb AS c ON ird.invrcptD_custid = c.cust_id 
// 			LEFT JOIN invoice_rcpt_tb AS i ON ird.invrcptD_irid = i.invrcpt_id
// 			LEFT JOIN receipt_tb AS r ON i.invrcpt_reid = r.re_id
// 			WHERE proj_compid = '" . $cid . "' AND proj_depid = '" . $dep . "' AND invrcptD_projid = '" . $projid . "'
//             GROUP BY ird.invrcptD_irid
//             HAVING ird.invrcptD_irid IS NOT NULL";

$str_sql = "SELECT *
            FROM invoice_rcpt_desc_tb AS ird 
            INNER JOIN project_tb AS p ON ird.invrcptD_projid = p.proj_id
            INNER JOIN company_tb AS cp ON ird.invrcptD_compid = cp.comp_id
            INNER JOIN customer_tb AS c ON ird.invrcptD_custid = c.cust_id
            LEFT JOIN invoice_rcpt_tb AS irt ON ird.invrcptD_irid = irt.invrcpt_id
            LEFT JOIN receipt_tb AS r ON irt.invrcpt_reid = r.re_id
            WHERE invrcptD_compid = '" . $cid . "' AND invrcptD_depid = '" . $dep . "' AND invrcptD_projid = '" . $projid . "'";
// $str_sql2 = "SELECT COUNT(invrcptD_status) as count_p, SUM(invrcptD_subtotal) as total_p FROM `invoice_rcpt_desc_tb` WHERE invrcptD_projid = '".$_POST["id"]."' AND invrcptD_status = 1";
// $obj_rs2 = mysqli_query($obj_con, $str_sql2);
// $obj_row2 = mysqli_fetch_array($obj_rs2);

$obj_query = mysqli_query($obj_con, $str_sql);
$obj_row = mysqli_fetch_assoc($obj_query);

$filename = "สรุปรายการ" . $obj_row['invrcptD_projid'];

$date_head = DateMonthThai($obj_row['tax_date'], "head");
$expprt_pdf = false;

if (isset($_GET['export'])) {

    if ($_GET['export'] == 'excel') {
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=$filename.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $download = 0;
    } else if ($_GET['export'] == 'pdf') {

        $expprt_pdf = true;
        $filename = "สรุปรายการ" . $obj_row['tax_id'] . ".pdf";
    }
}


if ($expprt_pdf) {

    require_once __DIR__ . '/vendor/autoload.php';

    $defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
    $fontDirs = $defaultConfig['fontDir'];
    $defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
    $fontData = $defaultFontConfig['fontdata'];

    $mpdf = new \Mpdf\Mpdf([

        'useActiveForms' => true,
        'mode' => 'utf-8',
        'format' => 'A4',
        // 'format' => 'A5',
        // 'margin_top' => 46,
        'margin_top' => 5,
        'margin_bottom' => 5,
        'margin_left' => 5,
        'margin_right' => 2,
        // 'margin_header' => 5,    
        // 'margin_footer' => 5,     
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

    // $header = '

    //     <table cellspacing="0" cellpadding="0" border="0" width="100%" class="tb_head">

    //         <tr>

    //             <td width="33%"></td>

    //             <td width="33%" style="text-align: center; font-size: 18pt;">

    //                 <b>รายงานภาษีซื้อ</b>

    //             </td>

    //             <td width="33%" style="text-align: right; font-size: 12pt;"><strong>หน้า {PAGENO}/{nb}</strong></td>

    //         </tr>

    //     </table>

    //     <table cellspacing="0" cellpadding="0" border="0" width="100%" class="tb_head">

    //         <tr>

    //             <td style="text-align: center; font-size: 16pt;">

    //                 <b>เดือน '. $date_head[1] . ' ' . $date_head[2] .'</b>

    //             </td>

    //         </tr>

    //     </table>

    //     <table cellspacing="0" cellpadding="0" border="0" width="100%" class="tb_head">

    //         <tr>

    //             <td width="20%" style="font-size: 12pt; padding: 3px 0px">

    //                 <b>ชื่อผู้ประกอบการ</b>

    //             </td>

    //             <td width="45%" style="font-size: 12pt; padding: 3px 0px">

    //                 <b>'. $obj_row['comp_name'] .'</b>

    //             </td>

    //             <td style="font-size: 12pt;">

    //                 <b>เลขประจำตัวผู้เสียภาษีอากร '. $obj_row['comp_taxno'] .'</b>

    //             </td>

    //         </tr>

    //         <tr>

    //             <td width="20%" style="font-size: 12pt; padding: 3px 0px">

    //                 <b>ชื่อสถานประกอบการ</b>

    //             </td>

    //             <td width="45%" style="font-size: 12pt; padding: 3px 0px">

    //                 <b>'. $obj_row['comp_name'] .'</b>

    //             </td>

    //             <td style="font-size: 12pt; padding: 3px 0px">

    //                 <b>สำนักงานใหญ่</b>

    //             </td>

    //         </tr>

    //     </table>



    //     <table cellspacing="0" cellpadding="0" border="0" width="100%">

    //         <tr>

    //             <td style="height: 10px"></td>

    //         </tr>

    //     </table>

    //     ';

    ob_start();
}

?>

<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <?php if ($expprt_pdf) { ?>
        <style>
            body {
                font-family: 'Sarabun', sans-serif;
            }

            table {
                padding: 1px 0px;
                overflow: wrap;
                font-size: 11pt;
                border-collapse: collapse;

            }

            table.txtbody th {
                text-align: center;
            }

            table.txtbody td {
                text-align: center;
                vertical-align: top;
            }

            table.txtbody td,
            table.txtbody th {
                border: 1px solid #000;
                padding: 3px 2px;

            }

            .tb_head td {
                font-weight: bold;
            }

            .txtbody tfoot td {
                font-weight: bold;
            }
        </style>
    <?php } ?>

</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <?php if ($expprt_pdf) { ?>
                    <h3 class="text-center"><b>สรุปรายการ</b></h3>
                    <h3><b><?= $obj_row['comp_name'] ?></b></h3>
                    <h3><b><?= $obj_row['proj_name'] ?></b></h3>
                <?php } ?>

                <table border="1" class="table txtbody">
                    <thead>
                        <tr class="info">
                            <!-- <th <?= ($expprt_pdf) ? 'width="10%"' : ""; ?>>ลำดับ</th>
								<th <?= ($expprt_pdf) ? 'width="70%"' : ""; ?>>รายการ</th>
								<th <?= ($expprt_pdf) ? 'width="20%"' : ""; ?>>จำนวนเงิน (ไม่รวมภาษี)</th> -->
                            <th width="5%" style="text-align: center;">ลำดับ</th>
                            <th width="53%" style="text-align: center;">รายการ</th>
                            <!--<th width="10%" style="text-align: center;">ใบแจ้งหนี้</th>-->
                            <!--   <th width="10%" style="text-align: center;">ใบเสร็จรับเงิน</th>-->
                            <th width="12%" style="text-align: center;">สถานะจ่ายเงิน</th>
                            <th width="10%" style="text-align: center;">จำนวนเงิน (ไม่รวมภาษี)</th>
                            <th width="10%" style="text-align: center;">แจ้งหนี้แล้ว (ไม่รวมภาษี)</th>
                            <th width="10%" style="text-align: center;">ชำระแล้ว (ไม่รวมภาษี)</th>
                            <th width="10%" style="text-align: center;">คงเหลือ (ไม่รวมภาษี)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1;
                        foreach ($obj_query as $obj_row) { ?>
                            <?php $sum = $obj_row['invrcpt_subtotal'] - $obj_row['re_subtotal'];
                            $result += $obj_row['invrcptD_subtotal'];
                            $pay += $obj_row['re_subtotal'];
                            $invoice = $obj_row['invrcptD_irid'] != "" ? $obj_row['invrcpt_subtotal'] : 0;
                            $invoice_sum += $invoice;
                            $status = $obj_row['invrcptD_irid'] != "" ? '' : ' <b style="color: red">(ยังไม่ออกใบแจ้งหนี้)<br></b>';
                            $desc = $obj_row['invrcpt_book'] != "" ? $obj_row['invrcpt_book'] . '/' . $obj_row['invrcpt_no'] : $obj_row['invrcpt_no'];
                            ?>
                            <tr>
                                <td style="text-align: center;"><?= $i++ ?></td>
                                <td style="text-align: left;"><?= $obj_row['cust_name'] . " งวดที่ " . $obj_row['invrcptD_lessonID'] .  $status ?></td>
                                <!-- <td style="text-align: center;"><?= $obj_row['invrcptD_irid'] != "" ? '/' : 'X' ?></td>-->
                                <!--<td style="text-align: center;"><?= $obj_row['invrcpt_reid'] != "" ? '/' : 'X' ?></td>-->
                                <td style="text-align: center;"><?= $obj_row['invrcptD_irid'] != "" ? 'ออกใบแจ้งหนี้แล้ว<br>' . $desc : 'รอแจ้งหนี้' ?></td>
                                <td style="text-align: right;"><?= number_format($obj_row['invrcptD_subtotal'], 2) ?></td>
                                <td style="text-align: right;"><?= number_format($obj_row['invrcpt_subtotal'], 2) ?></td>
                                <td style="text-align: right;"><?= number_format($obj_row['re_subtotal'], 2) ?></td>
                                <td style="text-align: right;color: <?= $sum > 0 ? 'red' : '' ?>"><?= number_format($sum, 2) ?></td>
                            </tr>
                        <?php
                        } ?>
                    </tbody>
                    <tfoot>
                        <?php $balance = $invoice_sum - $pay; ?>
                        <tr>
                            <td colspan="3" style="text-align: right;">
                                <b>รวมเงินทั้งหมด</b>
                            </td>
                            <td style="text-align: right;">
                                <b><?= number_format($result, 2) ?></b>
                            </td>
                            <td style="text-align: right;">
                                <b><?= number_format($invoice_sum, 2) ?></b>
                            </td>
                            <td style="text-align: right;">
                                <b><?= number_format($pay, 2) ?></b>
                            </td>
                            <td style="text-align: right;color: <?= $balance > 0 ? 'red' : '' ?>">
                                <b><?= number_format($balance, 2) ?></b>
                            </td>
                        <tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</body>

</html>

<?php

if ($expprt_pdf) {
    $html = ob_get_clean();
    ob_end_clean();
    // $mpdf->SetHeader($header);
    $mpdf->WriteHTML($html);

    if ($download) {
        $mpdf->Output($filename, "D");
    } else {
        $mpdf->Output($filename, "I");
    }
}
?>