<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\ParkingRecord;
use App\Models\PaymentTransaction;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

class CarController extends Controller
{
    public function carIn(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(ParkingRecord::whereNull('carout_datetime')->orderBy('carin_datetime', 'DESC'))
                ->addIndexColumn()
                ->addColumn('parking_pass_type', function ($row) {
                    if ($row->parking_pass_type == "1") {
                        $type = "สมาชิก";
                    } else {
                        $type = "บุคคลทั่วไป";
                    }
                    return $type;
                })
                ->addColumn('carin_datetime_date', function ($row) {
                    $_date_in = explode(" ", $row->carin_datetime);
                    return $_date_in[0];
                })
                ->addColumn('carin_datetime_hms', function ($row) {
                    $_date_in = explode(" ", $row->carin_datetime);
                    return $_date_in[1];
                })
                ->make(true);
        }

        return view('car.car-in');
    }

    public function carOut(Request $request)
    {

        if ($request->ajax()) {
            return DataTables::of(ParkingRecord::whereNotNull('carin_datetime')
                ->whereNotNull('carout_datetime')
                ->orderBy('carout_datetime', 'DESC')
                )
                ->addIndexColumn()
                ->addColumn('parking_pass_type', function ($row) {
                    if ($row->parking_pass_type == "1") {
                        $type = "สมาชิก";
                    } else {
                        $type = "บุคคลทั่วไป";
                    }
                    return $type;
                })
                ->addColumn('carin_datetime_date', function ($row) {
                    $_date_in = explode(" ", $row->carin_datetime);
                    return $_date_in[0];
                })
                ->addColumn('carin_datetime_hms', function ($row) {
                    $_date_in = explode(" ", $row->carin_datetime);
                    return $_date_in[1];
                })
                ->addColumn('carout_datetime_date', function ($row) {
                    $_date_out = explode(" ", $row->carout_datetime);
                    return $_date_out[0];
                })
                ->addColumn('carout_datetime_hms', function ($row) {
                    $_date_out = explode(" ", $row->carout_datetime);
                    return $_date_out[1];
                })
                ->make(true);
        }

        return view('car.car-out');
    }

    public function carDashboard(Request $request) {
        App::setLocale('th');
    
        $today = Carbon::today()->locale('th')->addYears(543)->isoFormat('LL');
        $yesterday = Carbon::yesterday();

        $carIn = ParkingRecord::whereDate('carin_datetime', Carbon::now())
        ->orderBy('carin_datetime','desc')
        ->get();    
        $countCarIn = $carIn->count();
    
        $carOut = ParkingRecord::whereDate('carout_datetime', Carbon::now())
        ->orderBy('carout_datetime','desc')
        ->get();
        $countCarOut = $carOut->count();

        $totalCars = ParkingRecord::whereDate('carin_datetime', Carbon::now())
        ->count();
    
        $sumFee = PaymentTransaction::whereDate('paid_datetime', Carbon::today())->sum('fee');
    

        $yesterdayCarIn = ParkingRecord::whereDate('carin_datetime', $yesterday)->count();
        $yesterdayCarOut = ParkingRecord::whereDate('carout_datetime', $yesterday)->count();
        $yesterdayTotalCars = ParkingRecord::whereDate('carin_datetime', $yesterday)->whereDate('carout_datetime', $yesterday)->count();
        $yesterdaySumFee = PaymentTransaction::whereDate('paid_datetime', $yesterday)->sum('fee');


        $carInPercentage = number_format($this->calculatePercentageChange($countCarIn, $yesterdayCarIn), 0);
        $carOutPercentage = number_format($this->calculatePercentageChange($countCarOut, $yesterdayCarOut), 0);
        $totalCarsPercentage = number_format($this->calculatePercentageChange($totalCars, $yesterdayTotalCars), 0);
        $sumFeePercentage = number_format($this->calculatePercentageChange($sumFee, $yesterdaySumFee), 0);

            
        return view('car-management', compact(
            'countCarIn', 'countCarOut', 'totalCars', 'today', 'sumFee', 
            'carIn', 'carOut', 'carInPercentage', 'carOutPercentage', 
            'totalCarsPercentage', 'sumFeePercentage'
        ));
    }

    private function calculatePercentageChange($today, $yesterday) {
        if($yesterday == 0) {
            return $today > 0 ? 100 : 0;
        }

        return ((($today - $yesterday) / $yesterday) * 100);
    }
}
