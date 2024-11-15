<?php
// Require composer autoload
require_once __DIR__ . '/vendor/autoload.php';
include './connect.php';

function MonthThaiShort($strDate) {
    $strYear = substr(date("Y", strtotime($strDate)) + 543, -2);
    $strMonth = date("n", strtotime($strDate));
    $strDay = date("j", strtotime($strDate));
    $strMonthCut = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
    return "$strDay " . $strMonthCut[$strMonth] . " $strYear";
}

function DateMonthThai($strDate, $mode) {
    if ($mode === "head") {
        $strYear = date("Y", strtotime($strDate)) + 543;
    } else {
        $strYear = substr(date("Y", strtotime($strDate)) + 543, -2);
    }
    $strMonth = date("n", strtotime($strDate));
    $strDay = date("j", strtotime($strDate));
    $strMonthCut = ($mode === "head") ? array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม") : array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
    
    return array($strDay, $strMonthCut[$strMonth], $strYear);
}

$pCashId = $_POST['pCashId'] ?? '';

$sql_pcash = "SELECT
    pc.*,
    MAX(pcl.pcl_tax_no) AS pcl_tax_no,
    MAX(pcl.pcl_tax_date) AS pcl_tax_date,
    GROUP_CONCAT(pcl.pcl_description SEPARATOR ', ') AS pcl_descriptions,
    GROUP_CONCAT(pcl.pcl_fee SEPARATOR ', ') AS pcl_fees,
    GROUP_CONCAT(pcl.pcl_vat SEPARATOR ', ') AS pcl_vats,
    GROUP_CONCAT(pcl.pcl_tax SEPARATOR ', ') AS pcl_taxs,
    GROUP_CONCAT(pcl.pcl_total SEPARATOR ', ') AS pcl_totals,
    GROUP_CONCAT(pcl.pcl_diff SEPARATOR ', ') AS pcl_diffs,
    GROUP_CONCAT(pcl.pcl_net_amount SEPARATOR ', ') AS pcl_net_amounts,
    c.comp_name,
    d.dep_name,
    py.paya_name
FROM pettycash_tb AS pc
INNER JOIN pettycash_list_tb AS pcl ON pc.pcash_id = pcl.pcl_pcash_id 
LEFT JOIN company_tb AS c ON pc.pcash_comp_id = c.comp_id 
LEFT JOIN department_tb AS d ON pc.pcash_dep_id = d.dep_id 
LEFT JOIN payable_tb AS py ON pc.pcash_paya_id = py.paya_id 
WHERE pc.pcash_id = 95
AND pc.soft_delete = '0'
GROUP BY pc.pcash_id;";

$result = mysqli_query($obj_con, $sql_pcash);
$data = mysqli_fetch_assoc($result);


// Data preparation for fields
$pCashNo = $data['pcash_no'];
$pCashDepartment = $data['dep_name'];
$dateArray = DateMonthThai($data['pcash_date'], "head"); 
$pCashDate = $dateArray[0] . ' ' . $dateArray[1] . ' ' . $dateArray[2];
$pCashPayaName = $data['paya_name'];

// Exploding GROUP_CONCAT fields into arrays
$descriptions = explode(", ", $data['pcl_descriptions']);
$fees = explode(", ", $data['pcl_fees']);
$vats = explode(", ", $data['pcl_vats']) ;
$taxs = explode(", ", $data['pcl_taxs']);
$totals = explode(", ", $data['pcl_totals']);
$diffs = explode(", ", $data['pcl_diffs']);
$netAmounts = explode(", ", $data['pcl_net_amounts']);


$pCashFee = number_format($data['pcash_fee'], 2);
$pCashVat = number_format($data['pcash_vat'], 2);
$pCashTax= number_format($data['pcash_tax'], 2);
$pCashTotal = number_format($data['pcash_total'], 2);
$pCashDiff = number_format($data['pcash_diff'], 2);
$pCashNetAmount = number_format($data['pcash_net_amount'], 2);

ob_start(); 

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];
$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => 'A5-L',
    'margin_top' => 7,
    'margin_bottom' => 0,
    'margin_left' => 4,
    'margin_right' => 4,
    'margin_header' => 2,
    'margin_footer' => 10,
    'tempDir' => __DIR__ . '/tmp/',
    'fontdata' => $fontData + [
        'sarabun' => [
            'R' => 'THSarabunNew.ttf',
            'I' => 'THSarabunNewItalic.ttf',
            'B' => 'THSarabunNewBold.ttf',
            'BI' => 'THSarabunNewBoldItalic.ttf',
        ]
    ],
]);

?>

<!DOCTYPE html>
<html>
<title>ใบจ่ายเงินสดย่อย <?= $pCashId ?> </title>
้<head>
<style>
    body {
        font-family: "sarabun", sans-serif;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }
    
    td, th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }

    .header-right {
        text-align: right;
        font-size: 22px;
        font-weight: bold;
    }

    .header-left {
        text-align: left;
        font-size: 28px;
        font-weight: bold;
    }

    .header-center {
        text-align: center;
        font-size: 22px;
        font-weight: bold;
    }

    .item-right {
        text-align: right;
    }

    .item-left {
        text-align: left;
    }
    
    .item-center {
        text-align: center;
    }

    .no-border {
        border: none; 
    }

    .border-bottom-dotted {
        border-bottom: 1px dotted #333;
        display: block;
        width: 100%; 
        padding-bottom: 5px;
        box-sizing: border-box; 
    }

    .fw-bold {
        font-weight: bold;
    }

    .fs-18 {
        font-size: 18px;
    }

    .fs-20 {
        font-size: 20px;
    }

    .signature-line {
        text-align: center;
    }


    .dot-border {
        border-bottom: 2px dashed #333;
        width: 100%;
        padding-bottom: 10px;
    }

    /* .box {
        padding: 1rem;
        vertical-align: top;
        border-top: 1px solid #dee2e6;
    } */

</style>
</head>


<body>
<table>
        <tr>
            <td class="no-border header-left" width="50%">
                <div>ใบจ่ายเงินสดย่อย</div>
                <div>Petty Cash Payment</div>
            </td>
            <td class="no-border header-right" width="28%">
                <div>เลขที่ / No.</div>
                <div>ฝ่าย / Dept</div>
                <div>วันที่ / Date</div>
            </td>
            <td class="no-border header-center" width="22%">
                <div><?= $pCashNo ?></div>
                <div><?= $pCashDepartment ?></div>
                <div><?= $pCashDate ?></div>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td class="no-border fw-bold fs-20" width="10%">ชื่อผู้รับ :</td>
            <td class="no-border fw-bold fs-20" width="90%">
                <div><?= $pCashPayaName ?></div>
            </td>
        </tr>
    </table>

    <table class="fs-18">
        <thead>
            <tr>
                <th class="item-center" width="5%">ที่</th>
                <th class="item-center" width="37%">รายการ</th>
                <th class="item-center" width="10%">ค่าบริการ</th>
                <th class="item-center" width="8%">VAT</th>
                <th class="item-center" width="8%">TAX</th>
                <th class="item-center" width="10%">ยอดชำระ</th>
                <th class="item-center" width="7%">+/-</th>
                <th class="item-center" width="15%">ยอดชำระสุทธิ</th>
            </tr>
        </thead>
        <tbody>
            <?php
                // $numItems = count($descriptions);
                for ($i = 0; $i < 5; $i++) {
                    echo '<tr>';
                    echo '<td class="box item-center">' . ($i + 1) . '</td>'; 
                    echo '<td class="box item-left">' . ($descriptions[$i] ?? '') . '</td>';
                    echo '<td class="box item-right">' . $fees[$i] . '</td>';
                    echo '<td class="box item-right">' . $vats[$i] . '</td>';
                    echo '<td class="box item-right">' . $taxs[$i] . '</td>'; 
                    echo '<td class="box item-right">' . $totals[$i] . '</td>';
                    echo '<td class="box item-right">' . $diffs[$i] . '</td>';
                    echo '<td class="box item-right">' . $netAmounts[$i] . '</td>';
                    echo '</tr>';
                }
      
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="item-right"><b>รวมเป็นเงิน</b></td>
                <td class="item-right"><?= $pCashFee ?></td>
                <td class="item-right"><?= $pCashVat ?></td>
                <td class="item-right"><?= $pCashTax ?></td>
                <td class="item-right"><?= $pCashTotal ?></td>
                <td class="item-right"><?= $pCashDiff ?></td>
                <td class="item-right"><?= $pCashNetAmount ?></td>
            </tr>
            <tr>
                <td colspan="8"><span class="fw-bold">ตัวอักษร : </span></td>
            </tr>
        </tfoot>
    </table>


    <table class="no-border" style="margin-top: 10px;">
        <tr class="no-border">
            <td class="no-border signature-line" width="2%"></td>
            <td class="no-border fs-18 signature-line" width="30%">
                <div>ผู้อนุมัติ ............................................</div>
                <div>.........................................................</div>
            </td>
            <td class="no-border fs-18 signature-line" width="30%">
                <div>ผู้จ่ายเงิน .............................................</div>
                <div>...........................................................</div>
            </td>
            <td class="no-border fs-18 signature-line" width="30%">
                <div>ผู้รับเงิน ............................................</div>
                <div>..........................................................</div>
            </td>
        </tr>
    </table>


</body>
</html>

<?php



	mysqli_close($obj_con);
    ini_set('memory_limit', '512M');

    // $mpdf->SetFooter($footer);
    $html = ob_get_contents();
    ob_end_clean();
    
    $mpdf->WriteHTML($html);
    $mpdf->Output('ใบจ่ายเงินสดย่อย.pdf', 'I');
    exit; 

?>


