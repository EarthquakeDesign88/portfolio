<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RepairController extends Controller
{
    public function repairs(Request $request)
    {
        $_query = DB::table('job_schedules as js')
        ->join('user_authorities as ua', 'js.job_authority_id', '=', 'ua.user_authority_id')
        ->join('work_shifts as ws', 'js.job_schedule_shift_id', '=', 'ws.work_shift_id')
        ->join('job_statuses as jst', 'js.job_schedule_status_id', '=', 'jst.job_status_id')
        ->join('users as u', 'ua.user_id', '=', 'u.user_id')
        ->join('roles as r', 'u.role_id', '=', 'r.role_id')
        ->join('locations as l', 'ua.user_location_id', '=', 'l.location_id')
        ->join('zones as z', 'l.location_zone_id', '=', 'z.zone_id')
        ->join('issue_topics as i', 'i.issue_id', '=', 'js.job_schedule_issue_id')
        ->where('js.job_schedule_status_id', '=', '2');

        $jobs = $_query->paginate(10);

        return view('content.repair.repairs', compact("jobs"));
    }
}
