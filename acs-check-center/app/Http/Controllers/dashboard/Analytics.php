<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkShift;
use App\Models\Zone;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class Analytics extends Controller
{
  public function index()
  {
    $date = Carbon::now()->toDateString();

    $jobSchedules = DB::table('job_schedules as js')
      ->whereDate('js.job_schedule_date', $date)
      ->get();

    $zones = Zone::all();
    $workshift = WorkShift::all();
    return view('content.dashboard.dashboards-analytics', compact("jobSchedules", "zones", "workshift"));
  }
}
