<!DOCTYPE html>
<html>
<head>
    <title>Monthly Stamp Summary</title>

</head>
<body>
    <h1>Monthly Stamp Summary</h1>
    <table border="1">
        <tbody>
            @foreach($allReports as $stampCode => $reports)
                @foreach($reports as $report)
                @if($loop->first)
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
                @endif
                    <tr>
                        <td>{{ $report->parking_pass_code }}</td>
                        <td>{{ $report->carin_date }}</td>
                        <td>{{ $report->carout_date }}</td>
                        <td>{{ $report->carin_time }}</td>
                        <td>{{ $report->carout_time }}</td>
                        <td>{{ $report->stamp_condition }}</td>
                        <td>{{ $report->parking_pass_code }}</td>
                        <td>{{ $report->carin_date }}</td>
                        <td>{{ $report->carout_date }}</td>
                        <td>{{ $report->total_parking_time }}</td>
                        <td>{{ $report->total_remaining_time }}</td>
                        <td>{{ $report->total_exceeded_time }}</td>
                        <td>{{ $report->license_plate }}</td>
                        <td>{{ $report->stamp_code }}</td>
                        <td>{{ $report->stamp_qty }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
    <h2>Totals</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Stamp Code</th>
                <th>Total Remaining Hours</th>
                <th>Total Remaining Minutes</th>
                <th>Total Remaining Seconds</th>
                <th>Total Exceeded Hours</th>
                <th>Total Exceeded Minutes</th>
                <th>Total Exceeded Seconds</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allTotals as $stampCode => $totals)
                <tr>
                    <td>{{ $stampCode }}</td>
                    <td>{{ $totals['total_remaining_hours'] }}</td>
                    <td>{{ $totals['total_remaining_minutes'] }}</td>
                    <td>{{ $totals['total_remaining_seconds'] }}</td>
                    <td>{{ $totals['total_exceeded_hours'] }}</td>
                    <td>{{ $totals['total_exceeded_minutes'] }}</td>
                    <td>{{ $totals['total_exceeded_seconds'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
