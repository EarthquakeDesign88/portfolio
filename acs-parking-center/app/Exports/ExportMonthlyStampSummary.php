<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportMonthlyStampSummary implements FromArray, WithStyles
{
    protected $reports;
    protected $totals;

    public function __construct($reports, $totals)
    {
        $this->reports = $reports;
        $this->totals = $totals;
    }

    public function array(): array
    {
        $data = [];

        $headers = [
            'รหัสตราประทับ',
            'รหัสบัตรจอดรถ',
            'วันที่จอดรถเข้า',
            'วันที่จอดรถออก',
            'เวลาเข้า',
            'เวลาออก',
            'ป้ายทะเบียน',
            'จำนวนตราประทับ',
            'เวลาจอดรถ',
            'ชม. คงเหลือตราประทับ',
            'ชม. จอดเกินตราประทับ',
            'สถานะบัตรจอดรถ'
        ];

        $data[] = $headers;
        $totalStampQty = 0;
        $totalParkingSeconds = 0;
        $totalRemainingTimeSeconds = 0;
        $totalExceededTimeSeconds = 0;

        foreach ($this->reports as $stampCode => $reports) {
            foreach ($reports as $report) {
                $data[] = [
                    'รหัสตราประทับ' => $report->stamp_code,
                    'รหัสบัตรจอดรถ' => $report->parking_pass_code,
                    'วันที่จอดรถเข้า' => $report->carin_date,
                    'วันที่จอดรถออก' => $report->carout_date,
                    'เวลาเข้า' => $report->carin_time,
                    'เวลาออก' => $report->carout_time,
                    'ป้ายทะเบียน' => $report->license_plate,
                    'จำนวนตราประทับ' => $report->stamp_qty,
                    'เวลาจอดรถ' => $report->total_parking_time,
                    'ชม. คงเหลือตราประทับ' => ($report->total_remaining_time < '00:00:00') ? '00:00:00' : $report->total_remaining_time,
                    'ชม. จอดเกินตราประทับ' => ($report->total_exceeded_time == '00:00:00') ? '00:00:00' : substr($report->total_exceeded_time, 1),
                    'สถานะบัตรจอดรถ' => $report->added_manually == '1' ? 'Manual' : 'ปกติ'
                ];

                $report->total_remaining_time = ($report->total_remaining_time < '00:00:00') ? '00:00:00' : $report->total_remaining_time;
                $report->total_exceeded_time = ($report->total_exceeded_time == '00:00:00') ? '00:00:00' : substr($report->total_exceeded_time, 1);

                $totalStampQty += $report->stamp_qty;
                
                $totalParkingSeconds += $this->convertTimeToSeconds($report->total_parking_time);
                $totalRemainingTimeSeconds += $this->convertTimeToSeconds($report->total_remaining_time);
                $totalExceededTimeSeconds += $this->convertTimeToSeconds($report->total_exceeded_time);
            }

 
            $totalParkingTime = $this->convertSecondsToTime($totalParkingSeconds);
            $totalRemainingTime = $this->convertSecondsToTime($totalRemainingTimeSeconds);
            $totalExceededTime = $this->convertSecondsToTime($totalExceededTimeSeconds);

            $data[] = [
                'รหัสตราประทับ' => '',
                'รหัสบัตรจอดรถ' => '',
                'วันที่จอดรถเข้า' => '',
                'วันที่จอดรถออก' => '',
                'เวลาเข้า' => '',
                'เวลาออก' => '',
                'ป้ายทะเบียน' => 'รวมทั้งสิ้น',
                'จำนวนตราประทับ' => $totalStampQty,
                'เวลาจอดรถ' => $totalParkingTime,
                'ชม. คงเหลือตราประทับ' => $totalRemainingTime,
                'ชม. จอดเกินตราประทับ' => $totalExceededTime,
            ];
        }

        return $data;
    }

    private function convertTimeToSeconds($time)
    {
        if (!$time || $time == '00:00:00') {
            return 0;
        }

        list($hours, $minutes, $seconds) = explode(':', $time);

        return ($hours * 3600) + ($minutes * 60) + $seconds;
    }

    private function convertSecondsToTime($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }

    public function styles(Worksheet $sheet)
    {
        // Calculate the last row index by adding all entries from reports
        $totalReportEntries = array_reduce($this->reports, function ($carry, $reports) {
            return $carry + count($reports);
        }, 0);

        // Total number of rows = header + all report entries + total row for each stamp code
        $lastRow = 1 + $totalReportEntries + count($this->reports);

        return [
            // Bold headers
            1 => ['font' => ['bold' => true]],

            // Bold totals row (last row)
            $lastRow => ['font' => ['bold' => true]],
        ];
    }
}
