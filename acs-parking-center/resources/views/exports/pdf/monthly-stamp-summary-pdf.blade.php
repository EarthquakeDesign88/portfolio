<!DOCTYPE html>
<html lang="th">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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

        @page {
            margin: 2cm 1.5cm;
        }

        body {
            font-family: 'THSarabunNew', Arial, sans-serif;
            font-size: 18px;
            /* margin-top: 4.5cm; */
        }


        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 3.5cm;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .border-none th,
        .border-none td {
            border: none !important;
        }

        th,
        td {
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

        thead {
            display: table-header-group;
        }
    </style>
</head>

<body>
    @foreach($allReports as $stampCode => $reports)
        @php
            $companyDisplayed = false;
            $stampCodeDisplayed = false;
            $parkingRecordDisplayed = false;
            $total = $allTotals[$stampCode];
        @endphp

        @foreach($reports as $index => $report)
            @if(!$companyDisplayed)
            <h2 class="text-center">สรุปยอดจำนวนตราประทับ</h2>
            <h3 class="text-center">{{ $pdfTitle }}</h3>

            <table class="border-none" style="margin-top: 20px;">
                <tr>
                    <th class="left-align">เรียน กรรมการผู้จัดการ</th>
                    <th class="right-align"></th>
                </tr>
            </table>


            <table class="border-none">
                <tr>
                    <th class="left-align" style="padding-left: 40px;">{{ $report->company_name }}</th>
                    <th class="right-align">{{ $report->floor_number }} {{ $report->place_name }}</th>
                </tr>
            </table>

            @if($total['totalQuota'] > 0)
                <table class="border-none" style="margin-top: 20px;">
                    <tr>
                        <td class="left-align" style="padding-left: 80px;">บริษัท ธรรมพิพัฒน์ จำกัด ได้จัดสิทธิ์ในตราประทับฟรี รหัสหมายเลข {{ $report->stamp_code }} ให้บริษัท ฯ ของท่าน จำนวน {{ $total['totalQuota'] }} ชั่วโมง / เดือน</td>
                    </tr>
                    <tr>
                        <td class="left-align"> บริษัทฯ ขอเรียนให้ท่านทราบดังนี้</td>
                    </tr>
                </table>

                @if($report->stamp_condition == 'ปล่อยผ่าน คิดเงินสิ้นเดือน')
                    <table class="border-none" style="margin: 20px 0px 0px 0px">
                        <tr>
                            <th colspan="3" class="left-align">ท่านได้ใช้ชั่วโมงในตราประทับทั้งสิ้น</th>
                            <th colspan="1">จำนวน</th>
                            <th colspan="1">{{ number_format($total['totalParkingHours'], 0) }}</th>
                            <th colspan="1">ชั่วโมง</th>
                            <th colspan="1">{{ number_format($total['totalParkingMinutes'], 0) }}</th>
                            <th colspan="1">นาที</th>
                        </tr>

                        <tr>
                            <th colspan="3" class="left-align">ใช้ชั่วโมงในตราประทับเกิน</th>
                            <th colspan="1">จำนวน </th>
                            <th colspan="1">{{ $total['totalQuota'] < $total['totalParkingHours'] ? number_format(abs($total['totalQuota'] - $total['totalParkingHours']),0) : '0' }}</th>
                            <th colspan="1">ชั่วโมง</th>
                            <th colspan="1">0</th>
                            <th colspan="1">นาที</th>
                        </tr>

                        <tr>
                            <th colspan="3" class="left-align">คิดเป็นเงิน ยอดคงเหลือที่ต้องชำระ</th>
                            <th colspan="1">จำนวน</th>
                            <th colspan="1">{{ $total['totalQuota'] < $total['totalParkingHours'] ? number_format(abs(($total['totalQuota'] - $total['totalParkingHours']) * $total['stampCharge']), 0) : '0' }}</th>
                            <th colspan="1">บาท</th>
                        </tr>
                    </table>
                @else 
                    <table class="border-none" style="margin: 20px 0px 0px 0px">
                        <tr>
                            <th colspan="3" class="left-align">ท่านได้ใช้ชั่วโมงในตราประทับทั้งสิ้น</th>
                            <th colspan="1">จำนวน</th>
                            <th colspan="1">{{ $total['stampRemainingHours'] > 0 ? number_format($total['stampRemainingHours']) : '0' }}</th>
                            <th colspan="1">ชั่วโมง</th>
                            <th colspan="1">0</th>
                            <th colspan="1">นาที</th>
                        </tr>
                        <tr>
                            <th colspan="3" class="left-align">ใช้ชั่วโมงในตราประทับเกิน</th>
                            <th colspan="1">จำนวน </th>
                            <th colspan="1">{{ ($total['totalQuota'] < $total['stampHours']) ? number_format($total['hoursSpentExceeded'],0) : '0' }}</th>
                            <th colspan="1">ชั่วโมง</th>
                            <th colspan="1">0</th>
                            <th colspan="1">นาที</th>
                        </tr>
                        <tr>
                            <th colspan="3" class="left-align">คิดเป็นเงิน ยอดคงเหลือที่ต้องชำระ</th>
                            <th colspan="1">จำนวน</th>
                            <th colspan="1">{{ ($total['totalQuota'] < $total['stampHours']) ? number_format($total['totalParkingFee'], 0) : '-' }}</th>
                            <th colspan="1">บาท</th>
                            <th colspan="1"></th>
                            <th colspan="1"></th>
                        </tr>
                    </table>
                @endif
            @else
            <table class="border-none" style="margin-top: 20px;">
                <tr>
                    <td class="left-align" style="padding-left: 80px;">รหัสตราประทับหมายเลข {{ $report->stamp_code }} บริษัทฯ ขอเรียนยอดใช้ตราประทับให้ท่านทราบดังนี้</td>
                </tr>
            </table>

            @if($report->stamp_condition == 'ปล่อยผ่าน คิดเงินสิ้นเดือน')
                <table class="border-none" style="margin: 20px 0px 0px 0px">
                    <tr>
                        <th colspan="3" class="left-align">ท่านได้ใช้ชั่วโมงในตราประทับทั้งสิ้น</th>
                        <th colspan="1">จำนวน</th>
                        <th colspan="1">{{ number_format($total['totalParkingHours'], 0) }}</th>
                        <th colspan="1">ชั่วโมง</th>
                        <th colspan="1">{{ number_format($total['totalParkingMinutes'], 0) }}</th>
                        <th colspan="1">นาที</th>
                    </tr>

                    <tr>
                        <th colspan="3" class="left-align">คิดเป็นเงิน ยอดคงเหลือที่ต้องชำระ</th>
                        <th colspan="1">จำนวน</th>
                        <th colspan="1">{{ number_format($total['totalParkingHours'] * $total['stampCharge'], 0) }}</th>
                        <th colspan="1">บาท</th>
                    </tr>
                </table>
            @else
                <table class="border-none" style="margin: 20px 0px 0px 0px">
                    <tr>
                        <th colspan="3" class="left-align">ท่านได้ใช้ชั่วโมงในตราประทับทั้งสิ้น</th>
                        <th colspan="1">จำนวน</th>
                        <th colspan="1">{{ $total['stampRemainingHours'] > 0 ? number_format($total['_hoursSpentExceeded'], 0) : '0' }}</th>
                        <th colspan="1">ชั่วโมง</th>
                        <th colspan="1">0</th>
                        <th colspan="1">นาที</th>
                    </tr>

                    <tr>
                        <th colspan="3" class="left-align">คิดเป็นเงิน ยอดคงเหลือที่ต้องชำระ</th>
                        <th colspan="1">จำนวน</th>
                        <th colspan="1">{{ $total['stampRemainingHours'] > 0 ? number_format($total['_totalParkingFee'], 0) : '0' }}</th>
                        <th colspan="1">บาท</th>
                    </tr>
                </table>
            @endif
        @endif


    <table class="border-none" style="margin: 30px 0px 0px 30px;">
        <tr>
            <td class="left-align" style="text-indent: -30px;"><u style="font-weight: bold;">หมายเหตุ</u> 1. หากท่านมีข้อสงสัย และติดต่อชำระเงินได้ที่ คุณตรึงตรา เพิงระนัย โทรศัพท์ 02-937-4385 และ 02-937-4858 ต่อ 134 </td>
        </tr>
        <tr>
            <td class="left-align" style="text-indent: -30px;">ชั้น 24 อาคาร เอ</td>
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
            <td class="left-align" style="text-indent: -30px;">สาขา ถนนพหลโยธิน 26 เลขที่บัญชี 395-1-01625-2 หรือ @ line : parking.tukchang เพื่อส่งหลักฐานการโอนเงิน</td>
        </tr>
    </table>
    <div class="page-break"></div>
    @php
        $companyDisplayed = true;
        $header = false;
        @endphp
    @endif

    @if($report->stamp_condition == 'ปล่อยผ่าน คิดเงินสิ้นเดือน')   
    <table border="1" style="font-size: 14px !important;" class="no-border-outside">
        @if(in_array($index, $headerPerPage))
        <thead>
            <tr class="border-none text-center" style="font-size: 20px !important;">
                <th colspan="9">รายการสรุปการใช้ตราประทับ</th>
            </tr>
            <tr class="border-none left-align" style="font-size: 17px !important;">
                <td colspan="9" style="text-align: left; padding-left: 150px;">รหัสตราประทับ : {{ $report->stamp_code }}</td>
            </tr>
            <tr class="border-none left-align" style="font-size: 17px !important;">
                <td colspan="9" style="text-align: left; padding-left: 150px;">หน่วยงาน/บริษัท : {{ $report->company_name }}</td>
            </tr>
            <tr>
                <th colspan="2">วัน/เดือนปี</th>
                <th colspan="2">เวลา</th>
                <th rowspan="2" colspan="2">ทะเบียนรถ</th>
                <th rowspan="2" colspan="3">จำนวนชั่วโมงในการจอดรถ</th>
            </tr>
            <tr>
                <th>เข้า</th>
                <th>ออก</th>
                <th>เข้า</th>
                <th>ออก</th>
            </tr>
        </thead>

        @endif


        <tbody>

            @if($loop->iteration % 20 == 0)
            <tr>
                @endif
                <td colspan="1">{{ $report->carin_date }}</td>
                <td colspan="1">{{ $report->carout_date }}</td>
                <td colspan="1">{{ $report->carin_time }}</td>
                <td colspan="1">{{ $report->carout_time }}</td>
                <td colspan="2">
                    @if($report->license_plate_path != '')
                    <img src="{{ public_path($report->license_plate_path) }}" alt="License Plate" height="20" width="80">
                    @else
                    -
                    @endif
                </td>
                <td colspan="3">{{ $report->total_parking_time }}</td>

                @if($loop->iteration % 20 == 0)
            </tr>
            @endif

            @if($index === count($reports) - 1)
            <tr>
                <th colspan="5" class="no-border-outside"></th>
                <th colspan="1" class="no-border-outside">รวมทั้งสิ้น</th>
                <th class="bordered" colspan="3">{{ $total['totalParkingHours'] }}:{{ $total['totalParkingMinutes'] }}:{{ $total['totalParkingSeconds'] }}</th>
            </tr>
            @endif
        </tbody>
    </table>


    @else
        <table border="1" style="font-size: 14px !important;" class="no-border-outside">
            @if(in_array($index, $headerPerPage))
            <thead>
                <tr class="border-none text-center" style="font-size: 20px !important;">
                    <th colspan="9">รายการสรุปการใช้ตราประทับ</th>
                </tr>
                <tr class="border-none left-align" style="font-size: 17px !important;">
                    <td colspan="9" style="text-align: left; padding-left: 150px;">รหัสตราประทับ : {{ $report->stamp_code }}</td>
                </tr>
                <tr class="border-none left-align" style="font-size: 17px !important;">
                    <td colspan="9" style="text-align: left; padding-left: 150px;">หน่วยงาน/บริษัท : {{ $report->company_name }}</td>
                </tr>
                <tr>
                    <th colspan="2">วัน/เดือนปี</th>
                    <th colspan="2">เวลา</th>
                    <th rowspan="2">ทะเบียนรถ</th>
                    <th rowspan="2">จำนวน ชม. <br>ในการจอดรถ</th>
                    <th rowspan="2">จำนวน <br>ตราประทับ</th>
                    <th rowspan="2">ชม.คงเหลือ <br>ตราประทับ</th>
                    <th rowspan="2">ชม.จอดเกิน <br>ตราประทับ</th>
                </tr>
                <tr>
                    <th>เข้า</th>
                    <th>ออก</th>
                    <th>เข้า</th>
                    <th>ออก</th>
                </tr>
            </thead>

            @endif

            <tbody>
                @if($loop->iteration % 20 == 0)
                <tr>
                    @endif
                    <td colspan="1">{{ $report->carin_date }}</td>
                    <td colspan="1">{{ $report->carout_date }}</td>
                    <td colspan="1">{{ $report->carin_time }}</td>
                    <td colspan="1">{{ $report->carout_time }}</td>
                    <td colspan="1">
                        @if($report->license_plate_path != '')
                        <img src="{{ public_path($report->license_plate_path) }}" alt="License Plate" height="20" width="60">
                        @else
                        -
                        @endif
                    </td>
                    <td colspan="1">{{ $report->total_parking_time }}</td>
                    <td colspan="1">{{ $report->stamp_qty }}</td>
                    <td colspan="1">{{ $report->total_remaining_time }}</td>
                    <td colspan="1">{{ $report->total_exceeded_time }}</td>

                    @if($loop->iteration % 20 == 0)
                </tr>
                @endif

                @if($index === count($reports) - 1)
                <tr>
                    <th colspan="5" class="no-border-outside"></th>
                    <th colspan="1" class="no-border-outside">รวมทั้งสิ้น</th>
                    <th class="bordered" colspan="1">{{ $total['totalStampQty'] }}</th>
                    <th class="bordered" colspan="1">{{ $total['total'] }}</th>

                    @if($total['totalExceededTime'] != '0:0:0')
                        <th class="bordered" colspan="1">{{ $total['totalExceededHours'] }}:{{ $total['totalExceededMinutes'] }}:{{ $total['totalExceededSeconds'] }}</th>
                    @else
                        <th style="border: none;" colspan="1"></th>
                    @endif
                </tr>
                @endif
            </tbody>
        </table>
    @endif

    @endforeach


    <div class="page-break"></div>

    @if($report->stamp_condition == 'ปล่อยผ่าน คิดเงินสิ้นเดือน')   
        @if($total['totalQuota'] < $total['totalParkingHours'])
            <div class="container">
                <h3 class="left-align">คำนวณสรุปการใช้ตราประทับ</h3>
                <table class="border-none" style="margin: 30px 0px 0px 0px">
                    @if($total['totalQuota'] > 0)
                        <tr>
                            <td colspan="2" class="left-align"></td>
                            <td colspan="1" class="left-align">{{ number_format($total['totalParkingHours'], 0) }} - {{ number_format($total['totalQuota'], 0) }}</td>
                            <td colspan="4" class="left-align">จำนวนชั่วโมงในการจอดรถ ลบ ชั่วโมงจอดฟรี </td>
                        </tr>

                        <tr>
                            <td colspan="2" class="left-align"></td>
                            <td colspan="1" class="left-align">{{ number_format(abs($total['totalQuota'] - $total['totalParkingHours']), 0) }} x {{ number_format($total['stampCharge'], 0) }} บาท</td>
                            <td colspan="4" class="left-align">จำนวนชั่วโมงจอดเกิน x จำนวนเงินค่าชั่วโมงจอดรถที่บริษัทกำหนด</td>
                        </tr>

                        <tr>
                            <td colspan="2" class="left-align"></td>
                            <td colspan="1" class="left-align">{{ number_format(abs(($total['totalQuota'] - $total['totalParkingHours']) * $total['stampCharge']), 0) }} บาท</td>
                            <td colspan="4" class="left-align">จำนวนเงินต้องชำระ ไม่รวม vat {{ number_format($total['vatPercent'], 0) }}%</td>
                        </tr>
                    @else 
                        <tr>
                            <td colspan="2" class="left-align"></td>
                            <td colspan="1" class="left-align">{{ number_format($total['totalParkingHours'], 0) }} x {{ number_format($total['stampCharge'], 0) }} บาท</td>
                            <td colspan="4" class="left-align">จำนวนชั่วโมงในการจอดรถ x จำนวนเงินค่าชั่วโมงจอดรถที่บริษัทกำหนด</td>
                        </tr>

                        <tr>
                            <td colspan="2" class="left-align"></td>
                            <td colspan="1" class="left-align">{{ number_format(abs($total['totalParkingHours'] * $total['stampCharge']), 0) }} บาท</td>
                            <td colspan="4" class="left-align">จำนวนเงินต้องชำระ ไม่รวม vat {{ number_format($total['vatPercent'], 0) }}%</td>
                        </tr>
                    @endif

                </table>
            </div>

        <div class="page-break"></div>
        @endif
    @else 
        @if($total['totalQuota'] < $total['stampHours'])
        <div class="container">
            <h3 class="left-align">คำนวณสรุปการใช้ตราประทับ</h3>
            <table class="border-none" style="margin: 30px 0px 0px 0px">
                <tr>
                    <td colspan="2" class="left-align">จำนวนตราประทับที่ใช้</td>
                    <td colspan="1" class="left-align">{{ number_format($total['totalStampQty'], 0) }} x {{ number_format($total['stampCondition'], 0) }} ชม.</td>
                    <td colspan="4" class="left-align">จำนวนตราประทับที่ใช้ x จำนวนขั่วโมงจอดรถ {{ number_format($total['stampCondition'], 0) }} ชั่วโมง</td>
                </tr>
                <tr>
                    <td colspan="2" class="left-align"></td>
                    <td colspan="1" class="left-align">{{ number_format($total['stampHours'], 0) }} - {{ number_format($total['_hour'], 0) }}</td>
                    <td colspan="4" class="left-align">ผลที่ได้นำมาลบ ชั่วโมงคงเหลือใช้ตราประทับ</td>
                </tr>

                @if($total['totalQuota'] > 0)
                    <tr>
                        <td colspan="2" class="left-align"></td>
                        <td colspan="1" class="left-align">{{ number_format($total['stampRemainingHours'], 0) }} - {{ number_format($total['totalQuota'], 0) }}</td>
                        <td colspan="4" class="left-align">ผลที่ได้นำมาลบ ชั่วโมงจอดฟรี</td>
                    </tr>
                @endif
                <tr>
                    <td colspan="2" class="left-align"></td>
                    <td colspan="1" class="left-align">{{ number_format($total['_hoursSpentExceeded'], 0) }} x {{ number_format($total['stampCharge'], 0) }} บาท</td>
                    <td colspan="4" class="left-align">จำนวนชั่วโมงจอดเกิน x จำนวนเงินค่าชั่วโมงจอดรถที่บริษัทกำหนด</td>
                </tr>
                <tr>
                    <td colspan="2" class="left-align"></td>
                    <td colspan="1" class="left-align">{{ number_format($total['_totalParkingFee'], 0) }} บาท</td>
                    <td colspan="4" class="left-align">จำนวนเงินต้องชำระ ไม่รวม vat {{ number_format($total['vatPercent'], 0) }}%</td>
                </tr>

            </table>
        </div>

        <div class="page-break"></div>
        @endif
    @endif

    @endforeach


</body>

</html>