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

function convertAmountToThaiWords($amount) {
    // Define words for numbers in Thai
    $numberWords = [
        '', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า'
    ];
    $positionWords = [
        '', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน'
    ];

    // Separate integer and decimal parts
    list($integerPart, $decimalPart) = explode('.', number_format($amount, 2, '.', ''));

    // Function to convert a single part (integer or decimal) to words
    $convertPartToWords = function($number) use ($numberWords, $positionWords) {
        $length = strlen($number);
        $words = '';

        for ($i = 0; $i < $length; $i++) {
            $digit = (int)$number[$i];
            $position = $length - $i - 1;

            if ($digit != 0) {
                if ($position == 1 && $digit == 1) {
                    $words .= 'สิบ';
                } elseif ($position == 1 && $digit == 2) {
                    $words .= 'ยี่สิบ';
                } elseif ($position == 0 && $digit == 1 && $length > 1) {
                    $words .= 'เอ็ด';
                } else {
                    $words .= $numberWords[$digit] . $positionWords[$position];
                }
            }
        }

        return $words;
    };

    // Convert integer part to Thai words
    $integerWords = $convertPartToWords($integerPart) . 'บาท';

    // Convert decimal part to Thai words, if any
    $decimalWords = '';
    if ((int)$decimalPart > 0) {
        $decimalWords = $convertPartToWords($decimalPart) . 'สตางค์';
    } else {
        $decimalWords = 'ถ้วน';
    }

    return $integerWords . $decimalWords;
}

$action = $_POST['action'] ?? '';
$pCashId = $_POST['pCashId'] ?? '';

if($action == 'fetchDetails' || $action == 'generatePDF') {
        $sql_pcash = "SELECT
        pc.*,
        GROUP_CONCAT(pcl.pcl_id SEPARATOR ', ') AS pcl_id,
        GROUP_CONCAT(pcl.pcl_tax_no SEPARATOR ', ') AS pcl_tax_no,
        GROUP_CONCAT(pcl.pcl_tax_date SEPARATOR ', ') AS pcl_tax_date,
        GROUP_CONCAT(pcl.pcl_tax_refund SEPARATOR ', ') AS pcl_tax_refund,
        GROUP_CONCAT(pcl.pcl_description SEPARATOR ', ') AS pcl_descriptions,
        GROUP_CONCAT(pcl.pcl_fee SEPARATOR ', ') AS pcl_fees,
        GROUP_CONCAT(pcl.pcl_vat SEPARATOR ', ') AS pcl_vats,
        GROUP_CONCAT(pcl.pcl_tax SEPARATOR ', ') AS pcl_taxs,
        GROUP_CONCAT(pcl.pcl_total SEPARATOR ', ') AS pcl_totals,
        GROUP_CONCAT(pcl.pcl_total_diff SEPARATOR ', ') AS pcl_total_diffs,
        GROUP_CONCAT(pcl.pcl_net_amount SEPARATOR ', ') AS pcl_net_amounts,
        c.comp_name,
        d.dep_name,
        py.paya_name
    FROM pettycash_tb AS pc
    INNER JOIN pettycash_list_tb AS pcl ON pc.pcash_id = pcl.pcl_pcash_id 
    LEFT JOIN company_tb AS c ON pc.pcash_comp_id = c.comp_id 
    LEFT JOIN department_tb AS d ON pc.pcash_dep_id = d.dep_id 
    LEFT JOIN payable_tb AS py ON pc.pcash_paya_id = py.paya_id 
    WHERE pc.pcash_id = '$pCashId'
    AND pc.soft_delete = '0'
    GROUP BY pc.pcash_id;";

    $result = mysqli_query($obj_con, $sql_pcash);
    $data = mysqli_fetch_assoc($result);

    $pCashNo = $data['pcash_no'];
    $pCashDepartment = $data['dep_name'];
    $dateArray = DateMonthThai($data['pcash_date'], "head"); 
    $pCashDate = $dateArray[0] . ' ' . $dateArray[1] . ' ' . $dateArray[2];
    $pCashPayaName = $data['paya_name'];
    $pCashType = $data['pcash_type'] == '2' ? 'ทดลองจ่าย' : 'ใบจ่ายเงินสดย่อย';

    if($pCashType == "ทดลองจ่าย") {
        $specificCode = "Adv-";
        $topicDesc = "Advance Payment";
        $title = "ใบทดลองจ่าย";
    } else {
        $specificCode = "Cash-";
        $topicDesc = "Petty Cash Payment";
        $title = "ใบจ่ายเงินสดย่อย";
    }   

    $descriptions = !empty($data['pcl_descriptions']) ? explode(", ", $data['pcl_descriptions']) : [];
    $fees = !empty($data['pcl_fees']) ? array_map(function($value) { return number_format((float)$value, 2); }, explode(", ", $data['pcl_fees'])) : [];
    $vats = !empty($data['pcl_vats']) ? array_map(function($value) { return number_format((float)$value, 2); }, explode(", ", $data['pcl_vats'])) : [];
    $taxs = !empty($data['pcl_taxs']) ? array_map(function($value) { return number_format((float)$value, 2); }, explode(", ", $data['pcl_taxs'])) : [];
    $totals = !empty($data['pcl_totals']) ? array_map(function($value) { return number_format((float)$value, 2); }, explode(", ", $data['pcl_totals'])) : [];
    $diffs = !empty($data['pcl_total_diffs']) ? array_map(function($value) { return number_format((float)$value, 2); }, explode(", ", $data['pcl_total_diffs'])) : [];
    $netAmounts = !empty($data['pcl_net_amounts']) ? array_map(function($value) { return number_format((float)$value, 2); }, explode(", ", $data['pcl_net_amounts'])) : [];


    $pCashFee = !empty($data['pcash_fee']) ? number_format($data['pcash_fee'], 2) : '0.00';
    $pCashVat = !empty($data['pcash_vat']) ? number_format($data['pcash_vat'], 2) : '0.00';
    $pCashTax = !empty($data['pcash_tax']) ? number_format($data['pcash_tax'], 2) : '0.00';
    $pCashTotal = !empty($data['pcash_total']) ? number_format($data['pcash_total'], 2) : '0.00';
    $pCashTotalDiff = !empty($data['pcash_total_diff']) ? number_format($data['pcash_total_diff'], 2) : '0.00';
    $pCashNetAmount = !empty($data['pcash_net_amount']) ? number_format($data['pcash_net_amount'], 2) : '0.00';


    $pCashNetAmountTH = !empty($data['pcash_net_amount']) ? convertAmountToThaiWords($data['pcash_net_amount']) : 'ศูนย์จุดศูนย์ศูนย์';

}
else if($action == 'preview' || $action == 'previewEdit') {
    $pCashType = $_POST['pettyCashType'] ?? 'ไม่พบข้อมูล';
    $pCashPayaName = $_POST['payableName'] ?? 'ไม่พบข้อมูล';


    if($_POST['departmentName']) {
        if($pCashType == "2") {
            $specificCode = "Adv-";
            $topicDesc = "Advance Payment";
            $title = "ใบทดลองจ่าย";
        } else {
            $specificCode = "Cash-";
            $topicDesc = "Petty Cash Payment";
            $title = "ใบจ่ายเงินสดย่อย";
        }   

        $pCashDepartment = $_POST['departmentName'];
        $length = strlen($pCashDepartment);
        $code = str_repeat("x", $length);

        if($action == 'previewEdit') {
            $pCashNo = $_POST['pettyCashNo'];
        }
        else {
            $pCashNo = $specificCode . $code . "-xxxxxxx";
        }

    }
    else {
        $pCashDepartment = "ไม่พบข้อมูล";
        $pCashNo = "ไม่พบข้อมูล";
    }

    
    if($_POST['pettyCashDate']) {
        $dateArray = DateMonthThai($_POST['pettyCashDate'], "head"); 
        $pCashDate = $dateArray[0] . ' ' . $dateArray[1] . ' ' . $dateArray[2];
    }
    else {
        $pCashDate = "ไม่พบข้อมูล";
    }
    
    
    $descriptions = $_POST['descriptions'] ?? [];
    $fees = array_map(function($value) { 
        return is_numeric($value) ? number_format((float)$value, 2) : '';
    }, $_POST['fees']);

    $vats = array_map(function($value) { 
        return is_numeric($value) ? number_format((float)$value, 2) : '';
    }, $_POST['vats']);

    $taxs = array_map(function($value) { 
        return is_numeric($value) ? number_format((float)$value, 2) : '';
    }, $_POST['taxs']);

    $totals = array_map(function($value) { 
        return is_numeric($value) ? number_format((float)$value, 2) : '';
    }, $_POST['totals']);

    $diffs = array_map(function($value) { 
        return is_numeric($value) ? number_format((float)$value, 2) : '';
    }, $_POST['totalDiffs']);

    $netAmounts = array_map(function($value) { 
        return is_numeric($value) ? number_format((float)$value, 2) : '';
    }, $_POST['netAmounts']);


    $pCashFee = number_format((float)$_POST['pettyCashFee'], 2);
    $pCashVat = number_format((float)$_POST['pettyCashVat'], 2);
    $pCashTax= number_format((float)$_POST['pettyCashTax'], 2);
    $pCashTotal = number_format((float)$_POST['pettyCashTotal'], 2);
    $pCashTotalDiff = number_format((float)$_POST['pettyCashTotalDiff'], 2);
    $pCashNetAmount = number_format((float)$_POST['pettyCashNetAmount'], 2);

    if (!empty(array_filter($descriptions)) && is_numeric($_POST['pettyCashNetAmount']) && $_POST['pettyCashNetAmount'] != '') {
        $pCashNetAmountTH = convertAmountToThaiWords($_POST['pettyCashNetAmount']);
    } else {
        $pCashNetAmountTH = 'ศูนย์จุดศูนย์ศูนย์';
    }
}


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
<title><?= $title ?> <?= $pCashNo ?> </title>
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
        font-size: 16px;
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

    table tr, table td {
        height: 40px; 
        vertical-align: middle;
    }

</style>
</head>


<body>
<table>
        <tr>
            <td class="no-border header-left" width="50%">
                <div>ใบจ่ายเงินสดย่อย</div>
                <div> <?= $topicDesc ?></div>
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
                    $diffValue = '';
                    if (!empty($descriptions[$i])) {
                        $diffValue = !empty($diffs[$i]) ? $diffs[$i] : '0.00';
                    }

                    echo '<tr>';
                    echo '<td class="item-center">' . (isset($descriptions[$i]) && $descriptions[$i] != '' ? ($i + 1) : '') . '</td>';
                    echo '<td class="item-left">' . ($descriptions[$i] ?? '') . '</td>';
                    echo '<td class="item-right">' . ($fees[$i] ?? '') . '</td>';
                    echo '<td class="item-right">' . ($vats[$i] ?? '') . '</td>';
                    echo '<td class="item-right">' . ($taxs[$i] ?? '') . '</td>'; 
                    echo '<td class="item-right">' . ($totals[$i] ?? '') . '</td>';
                    echo '<td class="item-right">' . $diffValue . '</td>';
                    echo '<td class="item-right">' . ($netAmounts[$i] ?? '') . '</td>';
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
                <td class="item-right"><?= $pCashTotalDiff ?></td>
                <td class="item-right"><?= $pCashNetAmount ?></td>
            </tr>
            <tr>
                <td colspan="8"><span class="fw-bold">ตัวอักษร : <?= $pCashNetAmountTH ?></span></td>
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
                <div>............................................................</div>
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
    $mpdf->Output();

    exit; 

?>


