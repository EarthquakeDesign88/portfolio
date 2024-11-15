<!DOCTYPE html>
<html lang="th">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>รายงานสรุปค่าที่จอดรถ รายวัน</title>
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


@foreach($reports as $index => $report)
    <table border="1" style="font-size: 14px !important;" class="no-border-outside">
        @if(in_array($index, $headerPerPage))
            <thead>
                <tr class="border-none text-center" style="font-size: 26px !important;">
                    <th colspan="8">รายงานสรุปค่าที่จอดรถ รายวัน</th>
                </tr>
                <tr class="border-none text-center" style="font-size: 22px !important;">
                    <td colspan="8">ประจำวันที่ : {{ $dateThai }}</td>
                </tr>
                <tr>
                    <th>รหัสบัตรจอดรถ</th>
                    <th>ทะเบียนรถ</th>
                    <th>เวลาเข้า</th>
                    <th>เวลาออก</th>
                    <th>เวลาที่จอดรถ</th>
                    <th>จำนวนตราประทับ</th>
                    <th>วิธีการชำระเงิน</th>
                    <th>ค่าที่จอดรถ</th>
                </tr>
            </thead>
        @endif

          
        <tbody>
            @if($loop->iteration % 20 == 0)
                <tr>
            @endif
                <td>{{ $report->parking_pass_code }}</td>
                <!-- <td><img src="{{ public_path($report->license_plate_path) }}" alt="License Plate" alt="License Plate" height="20" width="80"></td> -->
                <td>{{ $report->license_plate }}</td>
                <td>{{ $report->carin_time }}</td>
                <td>{{ $report->carout_time }}</td>
                <td>{{ $report->total_parking_time }}</td>
                <td>{{ $report->stamp_qty != null ? $report->stamp_qty : 0 }}</td>
                <td>{{ $report->payment_method }}</td>
                <td>{{ $report->fee }}</td>
            
            @if($loop->iteration % 20 == 0)
                </tr>
            @endif

            @if($index === count($reports) - 1)
                <tr>
                    <th colspan="6" class="no-border-outside"></th>
                    <th colspan="1" class="no-border-outside">รวมทั้งสิ้น</th>
                    <th class="bordered" colspan="1">{{ number_format($totalFee, 0) }}</th>
                </tr>
            @endif
        </tbody>
    </table>
@endforeach









</body>

</html>