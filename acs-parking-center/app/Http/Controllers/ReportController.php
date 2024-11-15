<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanySetup;
use App\Models\ParkingRecord;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Yajra\DataTables\DataTables;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\View;
use PDF;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use NumberToWords\NumberToWords;
use Maatwebsite\Excel\Facades\Excel; 
use App\Exports\ExportMonthlyStampSummary;



class ReportController extends Controller
{
    public function extractYearMonth($selectedMonthYear) {
        list($year, $month) = explode('-', $selectedMonthYear);
    
        $monthNamesThai = [
            '01' => 'มกราคม',
            '02' => 'กุมภาพันธ์',
            '03' => 'มีนาคม',
            '04' => 'เมษายน',
            '05' => 'พฤษภาคม',
            '06' => 'มิถุนายน',
            '07' => 'กรกฎาคม',
            '08' => 'สิงหาคม',
            '09' => 'กันยายน',
            '10' => 'ตุลาคม',
            '11' => 'พฤศจิกายน',
            '12' => 'ธันวาคม',
        ];
    
        $monthThai = $monthNamesThai[$month];
        $shortMonthThai = mb_substr($monthThai, -2);
    
        $yearThai = $year + 543;
    
        return [
            'year' => $year,
            'month' => $month,
            'monthThai' => $monthThai, 
            'shortMonthThai' => $shortMonthThai,
            'yearThai' => $yearThai
        ];
    }

    public function monthlyStampSummaryList(Request $request) 
    { 
        if ($request->ajax()) {
            $query = CompanySetup::query()
                ->join('companies', 'company_setup.company_id', '=', 'companies.id')
                ->join('stamps', 'company_setup.stamp_id', '=', 'stamps.id')
                ->join('floors', 'company_setup.floor_id', '=', 'floors.id')
                ->join('places', 'company_setup.place_id', '=', 'places.id')
                ->select(
                    'companies.company_name as company_name', 
                    'stamps.id as id', 
                    'stamps.stamp_code as stamp_code', 
                    'stamps.stamp_condition as stamp_condition', 
                    'floors.floor_number as floor_number', 
                    'places.place_name as place_name'
                )
                ->orderBy('stamps.stamp_code', 'asc');
    
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('companies.company_name', 'like', "%{$search}%")
                      ->orWhere('stamps.stamp_code', 'like', "%{$search}%")
                      ->orWhere('stamps.stamp_condition', 'like', "%{$search}%")
                      ->orWhere('floors.floor_number', 'like', "%{$search}%")
                      ->orWhere('places.place_name', 'like', "%{$search}%");
                });
            }
    
            return DataTables::of($query)
                ->addIndexColumn()
                ->make(true);
        }
    
        return view('report.monthly-stamp-sum');
    }
    


    public function getMonthlyStampSummaryReport() 
    { 
        $reports = CompanySetup::query()
        ->join('companies', 'company_setup.company_id', '=', 'companies.id')
        ->join('stamps', 'company_setup.stamp_id', '=', 'stamps.id')
        ->join('floors', 'company_setup.floor_id', '=', 'floors.id')
        ->join('places', 'company_setup.place_id', '=', 'places.id')
        ->leftjoin('parking_records', 'parking_records.stamp_id', '=', 'stamps.id')
        ->select(
            'companies.company_name', 
            'stamps.id', 
            'stamps.stamp_code', 
            'stamps.stamp_condition',
            'floors.floor_number', 
            'places.place_name'
        )
        ->groupBy('companies.company_name', 'stamps.id', 'stamps.stamp_code', 'stamps.stamp_condition', 'floors.floor_number', 'places.place_name')
        ->orderBy('stamps.stamp_code', 'asc')
        ->distinct()
        ->get();
                
    
        return response()->json([
            'reports' => $reports
        ]);
    }

    public function exportMonthlyStampSummaryPDF(Request $request)
    {
        ini_set('memory_limit', '512M');
        $stampCodes = $request->input('stampCodes');
        $selectedMonthYear = $request->input('selectedMonthYear');
        $exportType = $request->input('exportType');


        if($exportType == 'pdf')
        {
            $extractedDate = $this->extractYearMonth($selectedMonthYear);
            $year = $extractedDate['year'];
            $month = $extractedDate['month'];
            $monthThai = $extractedDate['monthThai'];
            $shortMonthThai = $extractedDate['shortMonthThai'];
            $yearThai = $extractedDate['yearThai'];
    
    
            if ($shortMonthThai == 'ยน') {
                $daysBetweenMonth = '1-30';
            } 
            elseif ($shortMonthThai == 'คม') {
                $daysBetweenMonth = '1-31';
            } 
            elseif ($shortMonthThai == 'ธ์') {
                $isLeapYear = (($year % 4 == 0) && ($year % 100 != 0)) || ($year % 400 == 0);
                if ($isLeapYear && $year == 2024) {
                    $daysBetweenMonth = '1-29'; 
                } else {
                    $daysBetweenMonth = '1-28'; 
                }
            }
     
    
            $pdfTitle = "ประจำวันที่ $daysBetweenMonth เดือน$monthThai $yearThai";
    
        
            $allReports = [];
            $allTotals = [];
            $pages = 0;
            $itemsPerPage = 0;
            $headerPerPage[] = 0;
    
    
            foreach($stampCodes as $stampCode) {
                $reports = DB::select("
                SELECT 
                pr.id,
                c.company_name,
                f.floor_number,
                p.place_name,
                cs.total_quota, 
                s.stamp_condition,
                pr.parking_pass_code,
                pr.carin_datetime,
                pr.carout_datetime,
                DATE_FORMAT(pr.carin_datetime, '%d/%m/%Y') AS carin_date,
                DATE_FORMAT(pr.carout_datetime, '%d/%m/%Y') AS carout_date,
                DATE_FORMAT(pr.carin_datetime, '%H:%i:%s') AS carin_time,
                DATE_FORMAT(pr.carout_datetime, '%H:%i:%s') AS carout_time,
                    CONCAT(
            LPAD(TIMESTAMPDIFF(HOUR, pr.carin_datetime, pr.carout_datetime), 2, '0'), ':',
            LPAD(TIMESTAMPDIFF(MINUTE, pr.carin_datetime, pr.carout_datetime) % 60, 2, '0'), ':',
            LPAD((TIMESTAMPDIFF(SECOND, pr.carin_datetime, pr.carout_datetime) % 60), 2, '0')
        ) AS total_parking_time,
                TIMESTAMPDIFF(HOUR, pr.carin_datetime, pr.carout_datetime) AS total_parking_hours,
                TIMESTAMPDIFF(MINUTE, pr.carin_datetime, pr.carout_datetime) % 60 AS total_parking_minutes,
                (TIMESTAMPDIFF(SECOND, pr.carin_datetime, pr.carout_datetime) % 60) AS total_parking_seconds,
                TIME_FORMAT(
                    SEC_TO_TIME(
                        TIME_TO_SEC(
                            CONCAT(
                                LPAD((pr.stamp_qty * 2), 2, '0'), ':00:00'
                            )
                        ) - 
                        TIME_TO_SEC(
                            CONCAT(
                                LPAD(TIMESTAMPDIFF(HOUR, pr.carin_datetime, pr.carout_datetime), 2, '0'), ':',
                                LPAD(TIMESTAMPDIFF(MINUTE, pr.carin_datetime, pr.carout_datetime) % 60, 2, '0'), ':',
                                LPAD((TIMESTAMPDIFF(SECOND, pr.carin_datetime, pr.carout_datetime) % 60), 2, '0') 
                            )
                        )
                    ),
                    '%H:%i:%s'
                ) AS total_remaining_time,
                TIME_FORMAT(
                    SEC_TO_TIME(
                        TIME_TO_SEC(
                            CONCAT(
                                LPAD((pr.stamp_qty * 2), 2, '0'), ':00:00'
                            )
                        ) - 
                        TIME_TO_SEC(
                            CONCAT(
                                LPAD(TIMESTAMPDIFF(HOUR, pr.carin_datetime, pr.carout_datetime), 2, '0'), ':',
                                LPAD(TIMESTAMPDIFF(MINUTE, pr.carin_datetime, pr.carout_datetime) % 60, 2, '0'), ':',
                                LPAD((TIMESTAMPDIFF(SECOND, pr.carin_datetime, pr.carout_datetime) % 60), 2, '0') 
                            )
                        )
                    ),
                    '%H'
                ) AS total_remaining_hours,
                    TIME_FORMAT(
                    SEC_TO_TIME(
                        TIME_TO_SEC(
                            CONCAT(
                                LPAD((pr.stamp_qty * 2), 2, '0'), ':00:00'
                            )
                        ) - 
                        TIME_TO_SEC(
                            CONCAT(
                                LPAD(TIMESTAMPDIFF(HOUR, pr.carin_datetime, pr.carout_datetime), 2, '0'), ':',
                                LPAD(TIMESTAMPDIFF(MINUTE, pr.carin_datetime, pr.carout_datetime) % 60, 2, '0'), ':',
                                LPAD((TIMESTAMPDIFF(SECOND, pr.carin_datetime, pr.carout_datetime) % 60), 2, '0') 
                            )
                        )
                    ),
                    '%i'
                ) AS total_remaining_minutes,
                    TIME_FORMAT(
                    SEC_TO_TIME(
                        TIME_TO_SEC(
                            CONCAT(
                                LPAD((pr.stamp_qty * 2), 2, '0'), ':00:00'
                            )
                        ) - 
                        TIME_TO_SEC(
                            CONCAT(
                                LPAD(TIMESTAMPDIFF(HOUR, pr.carin_datetime, pr.carout_datetime), 2, '0'), ':',
                                LPAD(TIMESTAMPDIFF(MINUTE, pr.carin_datetime, pr.carout_datetime) % 60, 2, '0'), ':',
                                LPAD((TIMESTAMPDIFF(SECOND, pr.carin_datetime, pr.carout_datetime) % 60), 2, '0') 
                            )
                        )
                    ),
                    '%s'
                ) AS total_remaining_seconds,
            CASE WHEN TIME_TO_SEC(SEC_TO_TIME(
                        TIME_TO_SEC(
                            CONCAT(
                                LPAD((pr.stamp_qty * 2), 2, '0'), ':00:00'
                            )
                        ) - 
                        TIME_TO_SEC(
                            CONCAT(
                                LPAD(TIMESTAMPDIFF(HOUR, pr.carin_datetime, pr.carout_datetime), 2, '0'), ':',
                                LPAD(TIMESTAMPDIFF(MINUTE, pr.carin_datetime, pr.carout_datetime) % 60, 2, '0'), ':',
                                LPAD((TIMESTAMPDIFF(SECOND, pr.carin_datetime, pr.carout_datetime) % 60), 2, '0') 
                            )
                        )
                    )) < 0 THEN 
                TIME_FORMAT(
                    SEC_TO_TIME(
                        TIME_TO_SEC(
                            CONCAT(
                                LPAD((pr.stamp_qty * 2), 2, '0'), ':00:00'
                            )
                        ) - 
                        TIME_TO_SEC(
                            CONCAT(
                                LPAD(TIMESTAMPDIFF(HOUR, pr.carin_datetime, pr.carout_datetime), 2, '0'), ':',
                                LPAD(TIMESTAMPDIFF(MINUTE, pr.carin_datetime, pr.carout_datetime) % 60, 2, '0'), ':',
                                LPAD((TIMESTAMPDIFF(SECOND, pr.carin_datetime, pr.carout_datetime) % 60), 2, '0') 
                            )
                        )
                    ),
                    '%H:%i:%s'
                )
            ELSE '00:00:00' END AS total_exceeded_time,
                pr.license_plate,
                pr.license_plate_path,
                s.stamp_code,
                pr.stamp_qty
            FROM 
                parking_records AS pr
            LEFT JOIN 
                acs_parking.payment_transactions AS pt ON pr.id = pt.parking_record_id
            LEFT JOIN 
                payment_methods AS pm ON pt.payment_method_id = pm.id 
            LEFT JOIN 
                company_setup AS cs ON pr.stamp_id = cs.stamp_id
            LEFT JOIN 
                floors AS f ON cs.floor_id = f.id
            LEFT JOIN 
                places AS p ON cs.place_id = p.id
            LEFT JOIN 
                companies AS c ON cs.company_id = c.id
            LEFT JOIN 
                stamps as s ON cs.stamp_id = s.id
                WHERE 
                pr.stamp_id IN ($stampCode) AND DATE_FORMAT(pr.carin_datetime, '%Y-%m') = '$selectedMonthYear'
            ORDER BY pr.carin_datetime ASC");
    
    
                if (!isset($allTotals[$stampCode])) {
                    $allTotals[$stampCode] = [
                        'totalStampQty' => 0,
                        'totalParkingHours' => 0,
                        'totalParkingMinutes' => 0,
                        'totalParkingSeconds' => 0,
                        'totalRemainingHours' => 0,
                        'totalRemainingMinutes' => 0,
                        'totalRemainingSeconds' => 0,
                        'totalExceededHours' => 0,
                        'totalExceededMinutes' => 0,
                        'totalExceededSeconds' => 0,
                        'total' => 0,
                    ];
                }
    
    
                if(!empty($reports)) {
                    $totalQuota = $reports[0]->total_quota;
                    $allTotals[$stampCode]['totalQuota'] = $reports[0]->total_quota;
                    //เพิ่มชั่วคราว
                    $_h = 0; $_m = 0; $_s = 0;
                    //------------------------
                    
                    foreach($reports as $report) {
                        $report->total_remaining_time = ($report->total_remaining_time < '00:00:00') ? '00:00:00' : $report->total_remaining_time;
                        $report->total_exceeded_time = ($report->total_exceeded_time == '00:00:00') ? '00:00:00' : substr($report->total_exceeded_time, 1);
                        
                        //เพิ่มชั่วคราว
                        $sum = explode(":", $report->total_remaining_time);
                        $_h += $sum[0];
                        $_m += $sum[1];
                        $_s += $sum[2];
                        //--------------------
                   
                        $allTotals[$stampCode]['totalStampQty'] += $report->stamp_qty;

                        $allTotals[$stampCode]['totalParkingHours'] += $report->total_parking_hours;
                        $allTotals[$stampCode]['totalParkingMinutes'] += $report->total_parking_minutes;
                        $allTotals[$stampCode]['totalParkingSeconds'] += $report->total_parking_seconds;

                        $allTotals[$stampCode]['totalRemainingHours'] += $report->total_remaining_hours;
                        $allTotals[$stampCode]['totalRemainingMinutes'] += $report->total_remaining_minutes;
                        $allTotals[$stampCode]['totalRemainingSeconds'] += $report->total_remaining_seconds;
                        
                        $extractTimeExceeded = explode(":", $report->total_exceeded_time);
                        $hoursExceeded = (int)$extractTimeExceeded[0];
                        $minutesExceeded = (int)$extractTimeExceeded[1];
                        $secondsExceeded = (int)$extractTimeExceeded[2];
    
                        $allTotals[$stampCode]['totalExceededHours'] += $hoursExceeded;
                        $allTotals[$stampCode]['totalExceededMinutes'] += $minutesExceeded;
                        $allTotals[$stampCode]['totalExceededSeconds'] += $secondsExceeded;
    
                        $allTotals[$stampCode]['totalRemainingMinutes'] += floor($allTotals[$stampCode]['totalRemainingSeconds']/ 60);
                        $allTotals[$stampCode]['totalRemainingSeconds'] %= 60;
                        $allTotals[$stampCode]['totalRemainingHours'] += floor($allTotals[$stampCode]['totalRemainingMinutes'] / 60);
                        $allTotals[$stampCode]['totalRemainingMinutes'] %= 60;
                    
    
                        $allTotals[$stampCode]['totalExceededMinutes'] += floor($allTotals[$stampCode]['totalExceededSeconds']  / 60);
                        $allTotals[$stampCode]['totalExceededSeconds'] %= 60;
                        $allTotals[$stampCode]['totalExceededHours'] += floor($allTotals[$stampCode]['totalExceededMinutes']  / 60);
                        $allTotals[$stampCode]['totalExceededMinutes'] %= 60;
                        $allTotals[$stampCode]['totalExceededTime'] =  $allTotals[$stampCode]['totalExceededHours']  . ':' .  $allTotals[$stampCode]['totalExceededMinutes']  . ':' . $allTotals[$stampCode]['totalExceededSeconds'];
                        
        
                        $allReports[$stampCode][] = $report;
                    }

                    //เพิ่มชั่วคราว
                    $_total = ((int)$_h * 3600) + ((int)$_m * 60) + ((int)$_s);
                    $allTotals[$stampCode]['_hour'] = floor($_total / 3600);
                    $allTotals[$stampCode]['total'] = $this->secondsToHMS($_total);
                    //-----------------------------------------------

                    $allTotals[$stampCode]['totalParkingHours'] = max(0, (int)$allTotals[$stampCode]['totalParkingHours']);
                    $allTotals[$stampCode]['totalParkingMinutes'] = max(0, (int)$allTotals[$stampCode]['totalParkingMinutes']);
                    $allTotals[$stampCode]['totalParkingSeconds'] = max(0, (int)$allTotals[$stampCode]['totalParkingSeconds']);

             
                    $totalSeconds = ($allTotals[$stampCode]['totalParkingHours'] * 3600) +
                                    ($allTotals[$stampCode]['totalParkingMinutes'] * 60) +
                                    $allTotals[$stampCode]['totalParkingSeconds'];

          
                    $allTotals[$stampCode]['totalParkingHours'] = floor($totalSeconds / 3600);
                    $totalSeconds %= 3600;
                    $allTotals[$stampCode]['totalParkingMinutes'] = floor($totalSeconds / 60);
                    $allTotals[$stampCode]['totalParkingSeconds'] = $totalSeconds % 60;

                
                    $allTotals[$stampCode]['totalParkingTime'] = sprintf('%02d:%02d:%02d', $allTotals[$stampCode]['totalParkingHours'], $allTotals[$stampCode]['totalParkingMinutes'], $allTotals[$stampCode]['totalParkingSeconds']);

     
                    $allTotals[$stampCode]['totalRemainingHours'] = max(0, (int)$allTotals[$stampCode]['totalRemainingHours']);
                    $allTotals[$stampCode]['totalRemainingMinutes'] = max(0, (int)$allTotals[$stampCode]['totalRemainingMinutes']);
                    $allTotals[$stampCode]['totalRemainingSeconds'] = max(0, (int)$allTotals[$stampCode]['totalRemainingSeconds']);
                    
                    $allTotals[$stampCode]['totalRemainingTime'] = sprintf('%02d:%02d:%02d', $allTotals[$stampCode]['totalRemainingHours'], $allTotals[$stampCode]['totalRemainingMinutes'], $allTotals[$stampCode]['totalRemainingSeconds']);
                    
                    $allTotals[$stampCode]['stampCondition'] = 2;
                    $allTotals[$stampCode]['stampCharge'] = 15;
                    $allTotals[$stampCode]['vatPercent'] = 7;
                    
                    $allTotals[$stampCode]['stampHours'] = $allTotals[$stampCode]['totalStampQty'] * $allTotals[$stampCode]['stampCondition'];
                    $allTotals[$stampCode]['stampRemainingHours'] = $allTotals[$stampCode]['stampHours'] - $allTotals[$stampCode]['totalRemainingHours'];

                    $allTotals[$stampCode]['hoursSpentExceeded'] = ($allTotals[$stampCode]['stampHours'] - $allTotals[$stampCode]['totalRemainingHours']) - $allTotals[$stampCode]['totalQuota'];
                    $allTotals[$stampCode]['totalParkingFee'] = $allTotals[$stampCode]['hoursSpentExceeded'] * $allTotals[$stampCode]['stampCharge'];   

                    //เพิ่มชั่วคราว
                    $allTotals[$stampCode]['_hoursSpentExceeded'] = ($allTotals[$stampCode]['stampHours'] - $allTotals[$stampCode]['_hour']) - $allTotals[$stampCode]['totalQuota'];
                    $allTotals[$stampCode]['_totalParkingFee'] = $allTotals[$stampCode]['_hoursSpentExceeded'] * $allTotals[$stampCode]['stampCharge']; 
                    //-----------------------

                    $itemsPerPage = 17; 
                    $pages = (int)ceil(count($reports) / $itemsPerPage);
    
    
                    for ($i = 0; $i < $pages; $i++) {
                        $headerPerPage[] = ($i * $itemsPerPage) + 17;
                    }
                    
                }
                else {
                    $response = [
                        'status' => 'error',
                        'message' => 'ไม่พบข้อมูลในระบบ'
                    ];
                    return response()->json($response); 
                }
            }
    
            $pdf = App::make('dompdf.wrapper');
            
            $pdf = PDF::loadView('exports.pdf.monthly-stamp-summary-pdf', 
            compact('allReports', 'allTotals', 'pages', 'itemsPerPage', 'headerPerPage', 'pdfTitle'));
       
    
            $pdfDirectory = public_path('pdfs/monthly_summary');
            $pdfFilename = 'รายงานสรุปการใช้ตราประทับ_เดือน' . $monthThai . '_' . $yearThai . '.pdf'; 
            $pdfPath = $pdfDirectory . '/' . $pdfFilename;
    
        
            if (!file_exists($pdfDirectory)) {
                mkdir($pdfDirectory, 0755, true);
            }
    
    
            $pdf->save($pdfPath);
    
            if (file_exists($pdfPath)) {
                $pdfUrl = asset('pdfs/monthly_summary/' . $pdfFilename);
                $response = [
                    'status' => 'success',
                    'message' => 'ดาวน์โหลดรายงาน โปรดรอสักครู่...',
                    'pdf_url' => $pdfUrl,
                    'pdf_filename' => $pdfFilename
                ];
                return response()->json($response);
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'พบข้อผิดพลาด โปรดลองใหม่อีกครั้ง'
                ];
                return response()->json($response);
            }
        }
    } 

    //เพิ่มชั่วคราว
    public function secondsToHMS($s) {
        $hours = floor($s / 3600) ;
        $minutes = floor(($s % 3600) / 60);
        $seconds = (($s % 3600) % 60);

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }

    public function exportMonthlyStampSummaryExcel(Request $request)
    {
        ini_set('memory_limit', '512M');
        $stampCodes = $request->input('stampCodes');
        $selectedMonthYear = $request->input('selectedMonthYear');

        $allReports = [];
        $allTotals = [];
        
        foreach ($stampCodes as $stampCode) {
            $reports = DB::select("
            SELECT 
            pr.id,
            c.company_name,
            f.floor_number,
            p.place_name,
            cs.total_quota, 
            s.stamp_condition,
            pr.parking_pass_code,
            pr.carin_datetime,
            pr.carout_datetime,
            DATE_FORMAT(pr.carin_datetime, '%d/%m/%Y') AS carin_date,
            DATE_FORMAT(pr.carout_datetime, '%d/%m/%Y') AS carout_date,
            DATE_FORMAT(pr.carin_datetime, '%H:%i:%s') AS carin_time,
            DATE_FORMAT(pr.carout_datetime, '%H:%i:%s') AS carout_time,
                CONCAT(
        LPAD(TIMESTAMPDIFF(HOUR, pr.carin_datetime, pr.carout_datetime), 2, '0'), ':',
        LPAD(TIMESTAMPDIFF(MINUTE, pr.carin_datetime, pr.carout_datetime) % 60, 2, '0'), ':',
        LPAD((TIMESTAMPDIFF(SECOND, pr.carin_datetime, pr.carout_datetime) % 60), 2, '0')
    ) AS total_parking_time,
            TIMESTAMPDIFF(HOUR, pr.carin_datetime, pr.carout_datetime) AS total_parking_hours,
            TIMESTAMPDIFF(MINUTE, pr.carin_datetime, pr.carout_datetime) % 60 AS total_parking_minutes,
            (TIMESTAMPDIFF(SECOND, pr.carin_datetime, pr.carout_datetime) % 60) AS total_parking_seconds,
            TIME_FORMAT(
                SEC_TO_TIME(
                    TIME_TO_SEC(
                        CONCAT(
                            LPAD((pr.stamp_qty * 2), 2, '0'), ':00:00'
                        )
                    ) - 
                    TIME_TO_SEC(
                        CONCAT(
                            LPAD(TIMESTAMPDIFF(HOUR, pr.carin_datetime, pr.carout_datetime), 2, '0'), ':',
                            LPAD(TIMESTAMPDIFF(MINUTE, pr.carin_datetime, pr.carout_datetime) % 60, 2, '0'), ':',
                            LPAD((TIMESTAMPDIFF(SECOND, pr.carin_datetime, pr.carout_datetime) % 60), 2, '0') 
                        )
                    )
                ),
                '%H:%i:%s'
            ) AS total_remaining_time,
            TIME_FORMAT(
                SEC_TO_TIME(
                    TIME_TO_SEC(
                        CONCAT(
                            LPAD((pr.stamp_qty * 2), 2, '0'), ':00:00'
                        )
                    ) - 
                    TIME_TO_SEC(
                        CONCAT(
                            LPAD(TIMESTAMPDIFF(HOUR, pr.carin_datetime, pr.carout_datetime), 2, '0'), ':',
                            LPAD(TIMESTAMPDIFF(MINUTE, pr.carin_datetime, pr.carout_datetime) % 60, 2, '0'), ':',
                            LPAD((TIMESTAMPDIFF(SECOND, pr.carin_datetime, pr.carout_datetime) % 60), 2, '0') 
                        )
                    )
                ),
                '%H'
            ) AS total_remaining_hours,
                TIME_FORMAT(
                SEC_TO_TIME(
                    TIME_TO_SEC(
                        CONCAT(
                            LPAD((pr.stamp_qty * 2), 2, '0'), ':00:00'
                        )
                    ) - 
                    TIME_TO_SEC(
                        CONCAT(
                            LPAD(TIMESTAMPDIFF(HOUR, pr.carin_datetime, pr.carout_datetime), 2, '0'), ':',
                            LPAD(TIMESTAMPDIFF(MINUTE, pr.carin_datetime, pr.carout_datetime) % 60, 2, '0'), ':',
                            LPAD((TIMESTAMPDIFF(SECOND, pr.carin_datetime, pr.carout_datetime) % 60), 2, '0') 
                        )
                    )
                ),
                '%i'
            ) AS total_remaining_minutes,
                TIME_FORMAT(
                SEC_TO_TIME(
                    TIME_TO_SEC(
                        CONCAT(
                            LPAD((pr.stamp_qty * 2), 2, '0'), ':00:00'
                        )
                    ) - 
                    TIME_TO_SEC(
                        CONCAT(
                            LPAD(TIMESTAMPDIFF(HOUR, pr.carin_datetime, pr.carout_datetime), 2, '0'), ':',
                            LPAD(TIMESTAMPDIFF(MINUTE, pr.carin_datetime, pr.carout_datetime) % 60, 2, '0'), ':',
                            LPAD((TIMESTAMPDIFF(SECOND, pr.carin_datetime, pr.carout_datetime) % 60), 2, '0') 
                        )
                    )
                ),
                '%s'
            ) AS total_remaining_seconds,
        CASE WHEN TIME_TO_SEC(SEC_TO_TIME(
                    TIME_TO_SEC(
                        CONCAT(
                            LPAD((pr.stamp_qty * 2), 2, '0'), ':00:00'
                        )
                    ) - 
                    TIME_TO_SEC(
                        CONCAT(
                            LPAD(TIMESTAMPDIFF(HOUR, pr.carin_datetime, pr.carout_datetime), 2, '0'), ':',
                            LPAD(TIMESTAMPDIFF(MINUTE, pr.carin_datetime, pr.carout_datetime) % 60, 2, '0'), ':',
                            LPAD((TIMESTAMPDIFF(SECOND, pr.carin_datetime, pr.carout_datetime) % 60), 2, '0') 
                        )
                    )
                )) < 0 THEN 
            TIME_FORMAT(
                SEC_TO_TIME(
                    TIME_TO_SEC(
                        CONCAT(
                            LPAD((pr.stamp_qty * 2), 2, '0'), ':00:00'
                        )
                    ) - 
                    TIME_TO_SEC(
                        CONCAT(
                            LPAD(TIMESTAMPDIFF(HOUR, pr.carin_datetime, pr.carout_datetime), 2, '0'), ':',
                            LPAD(TIMESTAMPDIFF(MINUTE, pr.carin_datetime, pr.carout_datetime) % 60, 2, '0'), ':',
                            LPAD((TIMESTAMPDIFF(SECOND, pr.carin_datetime, pr.carout_datetime) % 60), 2, '0') 
                        )
                    )
                ),
                '%H:%i:%s'
            )
        ELSE '00:00:00' END AS total_exceeded_time,
            pr.license_plate,
            pr.license_plate_path,
            s.stamp_code,
            pr.stamp_qty,
            pr.added_manually
        FROM 
            parking_records AS pr
        LEFT JOIN 
            acs_parking.payment_transactions AS pt ON pr.id = pt.parking_record_id
        LEFT JOIN 
            payment_methods AS pm ON pt.payment_method_id = pm.id 
        LEFT JOIN 
            company_setup AS cs ON pr.stamp_id = cs.stamp_id
        LEFT JOIN 
            floors AS f ON cs.floor_id = f.id
        LEFT JOIN 
            places AS p ON cs.place_id = p.id
        LEFT JOIN 
            companies AS c ON cs.company_id = c.id
        LEFT JOIN 
            stamps as s ON cs.stamp_id = s.id
            WHERE 
            pr.stamp_id IN ($stampCode) AND DATE_FORMAT(pr.carin_datetime, '%Y-%m') = '$selectedMonthYear'
        ORDER BY pr.carin_datetime ASC");
        
            
            $totals = [
                'total_remaining_hours' => collect($reports)->sum('total_remaining_hours'),
                'total_remaining_minutes' => collect($reports)->sum('total_remaining_minutes'),
                'total_remaining_seconds' => collect($reports)->sum('total_remaining_seconds'),
                'total_exceeded_hours' => collect($reports)->sum('total_exceeded_hours'),
                'total_exceeded_minutes' => collect($reports)->sum('total_exceeded_minutes'),
                'total_exceeded_seconds' => collect($reports)->sum('total_exceeded_seconds')
            ];

            $allReports[$stampCode] = $reports;
            $allTotals[$stampCode] = $totals;
        }

       
        $excelFilename = 'รายงานสรุปการใช้ตราประทับ.xlsx';

        // Use storage path instead of public path for temporary storage
        Excel::store(new ExportMonthlyStampSummary($allReports, $allTotals), 'monthly_summary/' . $excelFilename, 'local');
    
        // Move the Excel file to the public directory
        $tempPath = storage_path('app/monthly_summary/' . $excelFilename);
        $publicPath = public_path('excels/monthly_summary/' . $excelFilename);
    
        if (!file_exists(dirname($publicPath))) {
            mkdir(dirname($publicPath), 0755, true);
        }
    
        rename($tempPath, $publicPath);
    
        $response = [
            'status' => 'success',
            'message' => 'ดาวน์โหลดรายงาน โปรดรอสักครู่...',
            'excel_url' => asset('excels/monthly_summary/' . $excelFilename),
            'excel_filename' => $excelFilename
        ];
    
        return response()->json($response);
    }
    
    public function dailyParkingFeeList(Request $request) 
    { 
        if ($request->ajax()) {
            return DataTables::of(
                ParkingRecord::query()
                    ->leftJoin('stamps', 'parking_records.stamp_id', '=', 'stamps.id') 
                    ->leftJoin('payment_transactions', 'parking_records.id', '=', 'payment_transactions.parking_record_id') 
                    ->leftJoin('payment_methods', 'payment_transactions.payment_method_id', '=', 'payment_methods.id') 
                    ->select(
                        'parking_records.*', 
                        'stamps.id as stamp_id',
                        'stamps.stamp_code', 
                        'payment_transactions.*', 
                        'payment_methods.payment_method'
                    )
                    ->orderBy('parking_records.id', 'desc')
            )->addIndexColumn()
              ->make(true);
        }

        return view('report.daily-parking-fee');
    }


    public function getDailyParkingFeeReport(Request $request) 
    { 
        $selectedDate = $request->input('selectedDate') != '' ? $request->input('selectedDate') : date('Y-m-d');
        $selectedPaymentMethod = $request->input('selectedPaymentMethod');

        $reports = ParkingRecord::query()
        ->leftJoin('stamps', 'parking_records.stamp_id', '=', 'stamps.id') 
        ->leftJoin('payment_transactions', 'parking_records.id', '=', 'payment_transactions.parking_record_id') 
        ->leftJoin('payment_methods', 'payment_transactions.payment_method_id', '=', 'payment_methods.id') 
        ->select(
            'parking_records.*', 
            'stamps.id', 
            'stamps.stamp_code', 
            'payment_transactions.*', 
            'payment_methods.payment_method',
            DB::raw("DATE_FORMAT(parking_records.carin_datetime, '%H:%i:%s') AS carin_time"),
            DB::raw("DATE_FORMAT(parking_records.carout_datetime, '%H:%i:%s') AS carout_time"),
            DB::raw("CONCAT(
                LPAD(TIMESTAMPDIFF(HOUR, parking_records.carin_datetime, parking_records.carout_datetime), 2, '0'), ':',
                LPAD(TIMESTAMPDIFF(MINUTE, parking_records.carin_datetime, parking_records.carout_datetime) % 60, 2, '0'), ':',
                LPAD((TIMESTAMPDIFF(SECOND, parking_records.carin_datetime, parking_records.carout_datetime) % 60), 2, '0')
            ) AS total_parking_time"),
            DB::raw("TIMESTAMPDIFF(HOUR, parking_records.carin_datetime, parking_records.carout_datetime) AS total_parking_hours"),
            DB::raw("TIMESTAMPDIFF(MINUTE, parking_records.carin_datetime, parking_records.carout_datetime) % 60 AS total_parking_minutes"),
            DB::raw("(TIMESTAMPDIFF(SECOND, parking_records.carin_datetime, parking_records.carout_datetime) % 60) AS total_parking_seconds"),
            DB::raw("TIME_FORMAT(
                SEC_TO_TIME(
                    TIME_TO_SEC(
                        CONCAT(
                            LPAD((parking_records.stamp_qty * 2), 2, '0'), ':00:00'
                        )
                    ) - 
                    TIME_TO_SEC(
                        CONCAT(
                            LPAD(TIMESTAMPDIFF(HOUR, parking_records.carin_datetime, parking_records.carout_datetime), 2, '0'), ':',
                            LPAD(TIMESTAMPDIFF(MINUTE, parking_records.carin_datetime, parking_records.carout_datetime) % 60, 2, '0'), ':',
                            LPAD((TIMESTAMPDIFF(SECOND, parking_records.carin_datetime, parking_records.carout_datetime) % 60), 2, '0') 
                        )
                    )
                ),
                '%H:%i:%s'
            ) AS total_remaining_time"))
        ->orderBy('parking_records.id', 'desc')
        ->whereDate(DB::raw('DATE(payment_transactions.paid_datetime)'), '=', $selectedDate)
        ->where('parking_pass_type', '=', '0')
        ->where(function ($query) use ($selectedPaymentMethod) {
            if ($selectedPaymentMethod == '0' || $selectedPaymentMethod == '') {
                $query->where('payment_method_id', '!=', '0');
            } else {
                $query->where('payment_method_id', '=', $selectedPaymentMethod);
            }
        })
        ->get();

        $totalFee = $reports->sum('fee');
        
        return response()->json([
            'reports' => $reports,
            'totalFee' => $totalFee
        ]);
    }

    public function exportDailyParkingFee(Request $request) 
    {
        $selectedDate = $request->input('selectedDate') != '' ? $request->input('selectedDate') : date('Y-m-d');
        $carbonDate = Carbon::parse($selectedDate);
        $carbonDateThai = $carbonDate->addYears(543);
        $dateThai = $carbonDateThai->locale('th')->isoFormat('D MMMM GGGG');
        $exportType = $request->input('exportType');
        $selectedPaymentMethod = $request->input('selectedPaymentMethod');

        $pages = 0;
        $itemsPerPage = 0;
        $headerPerPage[] = 0;

        $reports = ParkingRecord::query()
        ->leftJoin('stamps', 'parking_records.stamp_id', '=', 'stamps.id') 
        ->leftJoin('payment_transactions', 'parking_records.id', '=', 'payment_transactions.parking_record_id') 
        ->leftJoin('payment_methods', 'payment_transactions.payment_method_id', '=', 'payment_methods.id') 
        ->select(
            'parking_records.*', 
            'stamps.id', 
            'stamps.stamp_code', 
            'payment_transactions.*', 
            'payment_methods.payment_method',
            DB::raw("DATE_FORMAT(parking_records.carin_datetime, '%H:%i:%s') AS carin_time"),
            DB::raw("DATE_FORMAT(parking_records.carout_datetime, '%H:%i:%s') AS carout_time"),
            DB::raw("CONCAT(
                LPAD(TIMESTAMPDIFF(HOUR, parking_records.carin_datetime, parking_records.carout_datetime), 2, '0'), ':',
                LPAD(TIMESTAMPDIFF(MINUTE, parking_records.carin_datetime, parking_records.carout_datetime) % 60, 2, '0'), ':',
                LPAD((TIMESTAMPDIFF(SECOND, parking_records.carin_datetime, parking_records.carout_datetime) % 60), 2, '0')
            ) AS total_parking_time"),
            DB::raw("TIMESTAMPDIFF(HOUR, parking_records.carin_datetime, parking_records.carout_datetime) AS total_parking_hours"),
            DB::raw("TIMESTAMPDIFF(MINUTE, parking_records.carin_datetime, parking_records.carout_datetime) % 60 AS total_parking_minutes"),
            DB::raw("(TIMESTAMPDIFF(SECOND, parking_records.carin_datetime, parking_records.carout_datetime) % 60) AS total_parking_seconds"),
            DB::raw("TIME_FORMAT(
                SEC_TO_TIME(
                    TIME_TO_SEC(
                        CONCAT(
                            LPAD((parking_records.stamp_qty * 2), 2, '0'), ':00:00'
                        )
                    ) - 
                    TIME_TO_SEC(
                        CONCAT(
                            LPAD(TIMESTAMPDIFF(HOUR, parking_records.carin_datetime, parking_records.carout_datetime), 2, '0'), ':',
                            LPAD(TIMESTAMPDIFF(MINUTE, parking_records.carin_datetime, parking_records.carout_datetime) % 60, 2, '0'), ':',
                            LPAD((TIMESTAMPDIFF(SECOND, parking_records.carin_datetime, parking_records.carout_datetime) % 60), 2, '0') 
                        )
                    )
                ),
                '%H:%i:%s'
            ) AS total_remaining_time"))
        ->orderBy('parking_records.id', 'desc')
        ->whereDate(DB::raw('DATE(payment_transactions.paid_datetime)'), '=', $selectedDate)
        ->where('parking_pass_type', '=', '0')
        ->where(function ($query) use ($selectedPaymentMethod) {
            if ($selectedPaymentMethod == '0' || $selectedPaymentMethod == '') {
                $query->where('payment_method_id', '!=', '0');
            } else {
                $query->where('payment_method_id', '=', $selectedPaymentMethod);
            }
        })
        ->get();

        $totalFee = $reports->sum('fee');

        $itemsPerPage = 18; 
        $pages = (int)ceil(count($reports) / $itemsPerPage);


        for ($i = 0; $i < $pages; $i++) {
            $headerPerPage[] = ($i * $itemsPerPage) + 18;
        }


        $pdf = App::make('dompdf.wrapper');
        $pdf = PDF::loadView('exports.pdf.daily-parking-fee-pdf', 
        compact('reports', 'totalFee', 'dateThai', 'pages', 'itemsPerPage', 'headerPerPage'));
   

        $pdfDirectory = public_path('pdfs/daily');
        $pdfFilename = 'รายงานสรุปค่าที่จอดรถรายวัน' . '_' . $dateThai . '.pdf'; 
        $pdfPath = $pdfDirectory . '/' . $pdfFilename;

    
        if (!file_exists($pdfDirectory)) {
            mkdir($pdfDirectory, 0755, true);
        }


        $pdf->save($pdfPath);

        if (file_exists($pdfPath)) {
            $pdfUrl = asset('pdfs/daily/' . $pdfFilename);
            $response = [
                'status' => 'success',
                'message' => 'ดาวน์โหลดรายงาน โปรดรอสักครู่...',
                'pdf_url' => $pdfUrl,
                'pdf_filename' => $pdfFilename
            ];
            return response()->json($response);
        } else {
            $response = [
                'status' => 'error',
                'message' => 'พบข้อผิดพลาด โปรดลองใหม่อีกครั้ง'
            ];
            return response()->json($response);
        }


    
    }


    public function test() {
        $reports = DB::select("
        SELECT 
        pr.id,
        c.company_name,
        f.floor_number,
        p.place_name,
        cs.total_quota, 
        pr.parking_pass_code,
        pr.carin_datetime,
        pr.carout_datetime,
        DATE_FORMAT(pr.carin_datetime, '%d/%m/%Y') AS carin_date,
        DATE_FORMAT(pr.carout_datetime, '%d/%m/%Y') AS carout_date,
        DATE_FORMAT(pr.carin_datetime, '%H:%i:%s') AS carin_time,
        DATE_FORMAT(pr.carout_datetime, '%H:%i:%s') AS carout_time,
            CONCAT(
    LPAD(TIMESTAMPDIFF(HOUR, pr.carin_datetime, pr.carout_datetime), 2, '0'), ':',
    LPAD(TIMESTAMPDIFF(MINUTE, pr.carin_datetime, pr.carout_datetime) % 60, 2, '0'), ':',
    LPAD((TIMESTAMPDIFF(SECOND, pr.carin_datetime, pr.carout_datetime) % 60), 2, '0')
) AS total_parking_time,
        TIMESTAMPDIFF(HOUR, pr.carin_datetime, pr.carout_datetime) AS total_parking_hours,
        TIMESTAMPDIFF(MINUTE, pr.carin_datetime, pr.carout_datetime) % 60 AS total_parking_minutes,
        (TIMESTAMPDIFF(SECOND, pr.carin_datetime, pr.carout_datetime) % 60) AS total_parking_seconds,
        TIME_FORMAT(
            SEC_TO_TIME(
                TIME_TO_SEC(
                    CONCAT(
                        LPAD((pr.stamp_qty * 2), 2, '0'), ':00:00'
                    )
                ) - 
                TIME_TO_SEC(
                    CONCAT(
                        LPAD(TIMESTAMPDIFF(HOUR, pr.carin_datetime, pr.carout_datetime), 2, '0'), ':',
                        LPAD(TIMESTAMPDIFF(MINUTE, pr.carin_datetime, pr.carout_datetime) % 60, 2, '0'), ':',
                        LPAD((TIMESTAMPDIFF(SECOND, pr.carin_datetime, pr.carout_datetime) % 60) - 
                        CASE WHEN TIMESTAMPDIFF(SECOND, pr.carin_datetime, pr.carout_datetime) % 60 > 0 
                        THEN 1 ELSE 0 END, 2, '0')
                    )
                )
            ),
            '%H:%i:%s'
        ) AS total_remaining_time,
        TIME_FORMAT(
            SEC_TO_TIME(
                TIME_TO_SEC(
                    CONCAT(
                        LPAD((pr.stamp_qty * 2), 2, '0'), ':00:00'
                    )
                ) - 
                TIME_TO_SEC(
                    CONCAT(
                        LPAD(TIMESTAMPDIFF(HOUR, pr.carin_datetime, pr.carout_datetime), 2, '0'), ':',
                        LPAD(TIMESTAMPDIFF(MINUTE, pr.carin_datetime, pr.carout_datetime) % 60, 2, '0'), ':',
                        LPAD((TIMESTAMPDIFF(SECOND, pr.carin_datetime, pr.carout_datetime) % 60) - 
                        CASE WHEN TIMESTAMPDIFF(SECOND, pr.carin_datetime, pr.carout_datetime) % 60 > 0 
                        THEN 1 ELSE 0 END, 2, '0')
                    )
                )
            ),
            '%H'
        ) AS total_remaining_hours,
            TIME_FORMAT(
            SEC_TO_TIME(
                TIME_TO_SEC(
                    CONCAT(
                        LPAD((pr.stamp_qty * 2), 2, '0'), ':00:00'
                    )
                ) - 
                TIME_TO_SEC(
                    CONCAT(
                        LPAD(TIMESTAMPDIFF(HOUR, pr.carin_datetime, pr.carout_datetime), 2, '0'), ':',
                        LPAD(TIMESTAMPDIFF(MINUTE, pr.carin_datetime, pr.carout_datetime) % 60, 2, '0'), ':',
                        LPAD((TIMESTAMPDIFF(SECOND, pr.carin_datetime, pr.carout_datetime) % 60) - 
                        CASE WHEN TIMESTAMPDIFF(SECOND, pr.carin_datetime, pr.carout_datetime) % 60 > 0 
                        THEN 1 ELSE 0 END, 2, '0')
                    )
                )
            ),
            '%i'
        ) AS total_remaining_minutes,
            TIME_FORMAT(
            SEC_TO_TIME(
                TIME_TO_SEC(
                    CONCAT(
                        LPAD((pr.stamp_qty * 2), 2, '0'), ':00:00'
                    )
                ) - 
                TIME_TO_SEC(
                    CONCAT(
                        LPAD(TIMESTAMPDIFF(HOUR, pr.carin_datetime, pr.carout_datetime), 2, '0'), ':',
                        LPAD(TIMESTAMPDIFF(MINUTE, pr.carin_datetime, pr.carout_datetime) % 60, 2, '0'), ':',
                        LPAD((TIMESTAMPDIFF(SECOND, pr.carin_datetime, pr.carout_datetime) % 60) - 
                        CASE WHEN TIMESTAMPDIFF(SECOND, pr.carin_datetime, pr.carout_datetime) % 60 > 0 
                        THEN 1 ELSE 0 END, 2, '0')
                    )
                )
            ),
            '%s'
        ) AS total_remaining_seconds,
    CASE WHEN TIME_TO_SEC(SEC_TO_TIME(
                TIME_TO_SEC(
                    CONCAT(
                        LPAD((pr.stamp_qty * 2), 2, '0'), ':00:00'
                    )
                ) - 
                TIME_TO_SEC(
                    CONCAT(
                        LPAD(TIMESTAMPDIFF(HOUR, pr.carin_datetime, pr.carout_datetime), 2, '0'), ':',
                        LPAD(TIMESTAMPDIFF(MINUTE, pr.carin_datetime, pr.carout_datetime) % 60, 2, '0'), ':',
                        LPAD((TIMESTAMPDIFF(SECOND, pr.carin_datetime, pr.carout_datetime) % 60) - 
                        CASE WHEN TIMESTAMPDIFF(SECOND, pr.carin_datetime, pr.carout_datetime) % 60 > 0 
                        THEN 1 ELSE 0 END, 2, '0')
                    )
                )
            )) < 0 THEN 
        TIME_FORMAT(
            SEC_TO_TIME(
                TIME_TO_SEC(
                    CONCAT(
                        LPAD((pr.stamp_qty * 2), 2, '0'), ':00:00'
                    )
                ) - 
                TIME_TO_SEC(
                    CONCAT(
                        LPAD(TIMESTAMPDIFF(HOUR, pr.carin_datetime, pr.carout_datetime), 2, '0'), ':',
                        LPAD(TIMESTAMPDIFF(MINUTE, pr.carin_datetime, pr.carout_datetime) % 60, 2, '0'), ':',
                        LPAD((TIMESTAMPDIFF(SECOND, pr.carin_datetime, pr.carout_datetime) % 60) - 
                        CASE WHEN TIMESTAMPDIFF(SECOND, pr.carin_datetime, pr.carout_datetime) % 60 > 0 
                        THEN 1 ELSE 0 END, 2, '0')
                    )
                )
            ),
            '%H:%i:%s'
        )
    ELSE '00:00:00' END AS total_exceeded_time,
        pr.license_plate,
        s.stamp_code,
        pr.stamp_qty,
        (TIMESTAMPDIFF(HOUR, pr.carin_datetime, pr.carout_datetime) * 20) AS parking_fee
    FROM 
        parking_records AS pr
    LEFT JOIN 
        acs_parking.payment_transactions AS pt ON pr.id = pt.parking_record_id
    LEFT JOIN 
        payment_methods AS pm ON pt.payment_method_id = pm.id 
    LEFT JOIN 
        company_setup AS cs ON pr.stamp_id = cs.stamp_id
    LEFT JOIN 
        floors AS f ON cs.floor_id = f.id
    LEFT JOIN 
        places AS p ON cs.place_id = p.id
    LEFT JOIN 
        companies AS c ON cs.company_id = c.id
    LEFT JOIN 
        stamps as s ON cs.stamp_id = s.id
    WHERE 
        pr.stamp_id = '10'
    ORDER BY pr.carin_datetime ASC
    
        ");

        $totalStampQty = 0;

        $totalRemainingHours = 0;
        $totalRemainingMinutes = 0;
        $totalRemainingSeconds = 0;

        $totalExceededHours = 0;
        $totalExceededMinutes = 0;
        $totalExceededSeconds = 0;


        foreach($reports as $report) {
            $report->total_remaining_time = ($report->total_remaining_time < '00:00:00') ? '00:00:00' : $report->total_remaining_time;
            $report->total_exceeded_time = ($report->total_exceeded_time == '00:00:00') ? '00:00:00' : substr($report->total_exceeded_time, 1);
        
            $totalStampQty += $report->stamp_qty;

        
            $totalRemainingHours += $report->total_remaining_hours;
            $totalRemainingMinutes += $report->total_remaining_minutes;
            $totalRemainingSeconds += $report->total_remaining_seconds;


            $extractTimeExceeded = explode(":", $report->total_exceeded_time);
            $hoursExceeded = (int)$extractTimeExceeded[0]; 
            $minutesExceeded = (int)$extractTimeExceeded[1];
            $secondsExceeded = (int)$extractTimeExceeded[2];

            $totalExceededHours += $hoursExceeded;
            $totalExceededMinutes += $minutesExceeded;
            $totalExceededSeconds += $secondsExceeded;
        }

       
        $totalRemainingMinutes += floor($totalRemainingSeconds / 60);
        $totalRemainingSeconds %= 60;

   
        $totalRemainingHours += floor($totalRemainingMinutes / 60);
        $totalRemainingMinutes %= 60;

        $totalRemainingTime = $totalRemainingHours . ':' . $totalRemainingMinutes . ':' . $totalRemainingSeconds;


        $totalExceededMinutes += floor($totalExceededSeconds / 60);
        $totalExceededSeconds %= 60;

        $totalExceededHours += floor($totalExceededMinutes / 60);
        $totalExceededMinutes %= 60;

        $totalExceededTime = $totalExceededHours . ':' . $totalExceededMinutes . ':' . $totalExceededSeconds;

        $companyName = !empty($reports) ? $reports[0]->company_name : '';
        $floorNumber = !empty($reports) ? $reports[0]->floor_number : '';
        $placeName = !empty($reports) ? $reports[0]->place_name : '';
        $stampCode = !empty($reports) ? $reports[0]->stamp_code : '';
        $totalQuota = !empty($reports) ? $reports[0]->total_quota : '';


        $stampCondition = 2;
        $stampHours = $totalStampQty * $stampCondition;
        $stampRemainingHours = number_format($stampHours - $totalRemainingHours, 0);
        $stampCharge = 15;

        $totalParkingFee = number_format($stampRemainingHours * $stampCharge, 0);

        $quotaExceeded =  $totalQuota - $stampRemainingHours;
        $checkQuotaExceeded = $quotaExceeded < 0 ? 1 : 0;

        $itemsPerPage = 10; 
        $pages = (int)ceil(count($reports) / $itemsPerPage);

        $pdf = App::make('dompdf.wrapper');
        $pdf = PDF::loadView('exports.pdf.test', 
        compact('pages', 'reports', 'companyName', 'floorNumber', 'placeName', 'stampCode', 
        'totalQuota', 'totalStampQty', 'totalRemainingHours', 'totalRemainingTime', 
        'totalExceededTime', 'stampCondition', 'stampHours', 'stampRemainingHours', 
        'stampCharge', 'totalParkingFee', 'quotaExceeded', 'checkQuotaExceeded'));
            
        return $pdf->stream();

    }
}