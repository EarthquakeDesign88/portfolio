<!DOCTYPE html>
<html lang="th">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>รายงานสรุปการใช้ตราประทับ</title>
    <style type="text/css">
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: normal;
            src: url("{{ public_path('fonts/THSarabunNew.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: bold;
            src: url("{{ public_path('fonts/THSarabunNew Bold.ttf') }}") format('truetype');
        }

        
        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: normal;
            src: url("{{ public_path('fonts/THSarabunNew Italic.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: bold;
            src: url("{{ public_path('fonts/THSarabunNew BoldItalic.ttf') }}") format('truetype');
        }

        /* @page {
            margin: 1cm 1.5cm 1cm 1.5cm;
        } */

        body {
            font-family: 'THSarabunNew', Arial, sans-serif;
            font-size: 20px;
        }

        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 5.5cm;
            background-color: red;
            
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }

        .border-none th,
        .border-none td {
            border: none !important;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .left-align {
            text-align: left;
        }
        .right-align {
            text-align: right;
        }
        .page-break {
            page-break-after: always;
        }
        .no-border {
            border: none;
        }
        .bordered {
            border: 1px solid black;
        }

        .no-border-outside {
            border-top: none;
            border-bottom: none;
            border-left: none;
            border-right: none;
        }
    </style>
</head>
<body>

    <div class="container" style="margin-left: 30; margin-right: 30px;">
    <h2 class="text-center">สรุปยอดจำนวนตราประทับ</h2>
    <h3 class="text-center">ประจำวันที่ 1 - 31 มีนาคม 2567</h3>


    <table class="border-none" style="margin-top: 30px;">
        <tr>
            <th class="left-align">เรียน กรรมการผู้จัดการ</th>
            <th class="right-align"></th>
        </tr>
        <tr>
            <th class="left-align" style="padding-left: 40px;">{{ $companyName }}</th>
            <th class="right-align">{{ $floorNumber }} {{ $placeName }}</th>
        </tr>
    </table>

    
    @if($totalQuota > 0)
        <table class="border-none" style="margin-top: 30px;">
            <tr>
                <td class="left-align" style="padding-left: 80px;">บริษัท ธรรมพิพัฒน์ จำกัด ได้จัดสิทธิ์ในตราประทับฟรี รหัสหมายเลข {{ $stampCode }} ให้บริษัท ฯ</td>
            </tr>
            <tr>
                <td class="left-align">ของท่าน จำนวน {{ $totalQuota }} ชั่วโมง / เดือน บริษัทฯ ขอเรียนให้ท่านทราบดังนี้</td>
            </tr>
        </table>

        <table class="border-none" style="margin: 30px 0px 0px 0px">
            <tr>
                <th class="left-align">ท่านได้ใช้ชั่วโมงในตราประทับทั้งสิ้น</th>
                <th>จำนวน</th>
                <th>{{ $stampRemainingHours > 0 ? $stampRemainingHours : '0' }}</th>
                <th>ชั่วโมง</th>
                <th>0</th>
                <th>นาที</th>
            </tr>
            <tr>
                <th class="left-align">ใช้ชั่วโมงในตราประทับเกิน</th>
                <th>จำนวน</th>
                <th>{{ $checkQuotaExceeded == 1 ? abs($quotaExceeded) : '0' }}</th>
                <th>ชั่วโมง</th>
                <th>0</th>
                <th>นาที</th>
            </tr>
            <tr>
                <th class="left-align">เป็นจำนวนตราประทับ</th>
                <th>จำนวน</th>
                <th>{{ $checkQuotaExceeded == 1 ? abs($quotaExceeded / 2) : '-' }}</th>
                <th>ดวง</th>
            </tr>
            <tr>
                <th class="left-align">คิดเป็นเงิน ยอดคงเหลือที่ต้องชำระ</th>
                <th>จำนวน</th>
                <th>-</th>
                <th>บาท</th>
            </tr>
        </table>

    @else
        <table class="border-none" style="margin-top: 30px;">
            <tr>
                <td class="left-align" style="padding-left: 80px;">รหัสตราประทับหมายเลข {{ $stampCode }} บริษัทฯ ขอเรียนยอดใช้ตราประทับให้ท่านทราบดังนี้</td>
            </tr>
        </table>

        
        <table class="border-none" style="margin: 30px 0px 0px 0px">
            <tr>
                <th class="left-align">ท่านได้ใช้ชั่วโมงในตราประทับทั้งสิ้น</th>
                <th>จำนวน</th>
                <th>{{ $stampRemainingHours > 0 ? $stampRemainingHours : '0' }}</th>
                <th>ชั่วโมง</th>
            </tr>

            <tr>
                <th class="left-align">คิดเป็นเงิน ยอดคงเหลือที่ต้องชำระ</th>
                <th>จำนวน</th>
                <th>{{ $totalParkingFee > 0 ? $totalParkingFee : '0' }}</th>
                <th>บาท</th>
            </tr>
        </table>

    @endif

    <table class="border-none" style="margin: 30px 0px 0px 30px;">
        <tr>
            <td class="left-align" style="text-indent: -30px;"><u style="font-weight: bold;">หมายเหตุ</u> 1. หากท่านมีข้อสงสัย และติดต่อชำระเงินได้ที่ คุณตรึงตรา เพิงระนัย โทรศัพท์ 02-937-4385 และ</td>
        </tr>
        <tr>
            <td class="left-align" style="text-indent: -30px;">02-937-4858 ต่อ 134 ชั้น 24 อาคาร เอ</td>
        </tr>
        <tr>
            <td class="left-align" style="padding-left: 30px;">2. ได้แนบเอกสารรายงานการใช้ตราประทับเต็มชุดมาพร้อมใบสรุปนี้แล้ว ในกรณีที่ท่านขอสำเนา</td>
        </tr>
        <tr>
            <td class="left-align" style="text-indent: -30px;">รายงานการใช้ตราประทับเพิ่ม ฝ่ายบริหารที่จอดรถ ขอสงวนสิทธิ์ในการคิดค่าบริการ แผ่นละ 50 บาท (ไม่รวม vat)</td>
        </tr>
        <tr>
            <td class="left-align" style="text-indent: -30px;">เริ่มตั้งแต่วันที่ 21 กันยายน 62 เป็นต้นไป</td>
        </tr>
        <tr>
            <td class="left-align" style="padding-left: 30px;">3. ลูกค้าสามารถโอนเงินเข้าบัญชีบริษัท ธรรมพิพัฒน์ จำกัด ธนาคารกรุงศรีอยุธยา จำกัด (มหาชน)</td>
        </tr>
        <tr>
            <td class="left-align" style="text-indent: -30px;">สาขา ถนนพหลโยธิน 26 เลขที่บัญชี 395-1-01625-2 หรือ @ line : pariking.tukchang เพื่อส่งหลักฐานการโอนเงิน</td>
        </tr>
    </table>
    </div>

    <div class="page-break"></div>
  
    

       <!-- <header>
            <h2 class="text-center">รายการสรุปการใช้ตราประทับ</h2>
            <p style="margin-left: 150px;">รหัสตราประทับ : {{ $stampCode }}</p>
            <p style="margin-left: 150px;">หน่วยงาน/บริษัท : {{ $companyName }}</p>
        </header> -->



    <table border="1" style="font-size: 16px !important;"  class="no-border-outside">
        <thead>
            <tr>
                <th colspan="2">วัน/เดือนปี</th>
                <th colspan="2">เวลา</th>
                <th rowspan="2">ทะเบียนรถ</th>
                <th rowspan="2">จำนวน ชม. <br>ในการจอดรถ</th>
                <th rowspan="2">จำนวน <br>ตราประทับ</th>
                <th rowspan="2">ชม.คงเหลือ <br>ตราประทับ</th>
                <th rowspan="2">ชม.จอดเกิน <br>ตราประทับ</th>
                <th rowspan="2">ค่าที่จอดรถ</th>
            </tr>
            <tr>
                <th>เข้า</th>
                <th>ออก</th>
                <th>เข้า</th>
                <th>ออก</th>
            </tr>
        </thead>
    <tbody>

        @foreach($reports as $report)
        <tr>
            <td>{{ $report->carin_date }}</td>
            <td>{{ $report->carout_date }}</td>
            <td>{{ $report->carin_time }}</td>
            <td>{{ $report->carout_time }}</td>
            <td>{{ $report->license_plate }}</td>
            <td>{{ $report->total_parking_time }}</td>
            <td>{{ $report->stamp_qty }}</td>
            <td>{{ $report->total_remaining_time }}</td>
            <td>{{ $report->total_exceeded_time }}</td>
            <td>฿{{ number_format($report->parking_fee, 2) }}</td>
        </tr>

        @endforeach
        <tr>
            <th colspan="5" class="no-border"></th>
            <th colspan="1" class="no-border">รวมทั้งสิ้น</th>
            <th class="bordered">{{ $totalStampQty }}</th>
            <th class="bordered">{{ $totalRemainingTime }}</th>

            @if($totalExceededTime != '0:0:0')
            <th class="bordered">{{ $totalExceededTime }}</th>
            @else
            <th class="no-border"></th>
            @endif
            <th colspan="1" class="no-border"></th>
        </tr>

    </tbody>
</table>


@if($checkQuotaExceeded == 1)
<div class="page-break"></div>
    <div class="container">
        <h3 class="left-align">คำนวณสรุปการใช้ตราประทับ</h3>
        <table class="border-none" style="margin: 30px 0px 0px 0px">
            <tr>
                <td class="left-align">จำนวนตราประทับที่ใช้</td>
                <td class="left-align">{{ $totalStampQty }} x {{ $stampCondition }} ชม.</td>
                <td class="left-align">จำนวนตราประทับที่ใช้ x จำนวนขั่วโมงจอดรถ {{ $stampCondition }} ชั่วโมง</td>
            </tr>
            <tr>
                <td colspan="1" class="left-align"></td>
                <td class="left-align">{{ $stampHours }} - {{ $totalRemainingHours }}</td>
                <td class="left-align">ผลที่ได้นำมาลบ ชั่วโมงคงเหลือใช้ตราประทับ</td>
            </tr>
            <tr>
                <td colspan="1" class="left-align"></td>
                <td class="left-align">{{ $stampRemainingHours }} x 15 บาท</td>
                <td class="left-align">จำนวนชั่วโมงจอดเกิน x จำนวนเงินค่าใช้ตราประทับที่บริษัทกำหนด</td>
            </tr>
            <tr>
                <td colspan="1" class="left-align"></td>
                <td class="left-align">{{ $totalParkingFee }} บาท</td>
                <td class="left-align">จำนวนเงินต้องชำระ ไม่รวม vat 7%</td>
            </tr>
        </table>
    </div>
@endif

</body>
</html>