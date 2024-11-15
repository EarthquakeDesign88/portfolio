<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobStatus;
use App\Models\Location;
use App\Models\User;
use App\Models\UserAuthority;
use App\Models\WorkShift;
use App\Models\JobSchedule;
use App\Models\Zone;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use App\Events\MessageSent;
use GuzzleHttp\Client;

class JobController extends Controller
{
    public function job_status_create(Request $request)
    {

        if (empty($request->input('job_desc'))) {
            return response()->json(['status' => 'error', 'message' => 'กรุณากรอกรายละเอียดสถานะ']);
        }

        try {
            $count = DB::table('job_statuses as js')
                ->where('js.job_status_description', '=', $request->input('job_desc'))->count();

            if ($count > 0) {
                return response()->json(['status' => 'error', 'message' => 'ข้อมูลรายละเอียดสถานะซ้ำ']);
            }

            $job = new JobStatus;
            $job->job_status_description = $request->input('job_desc');
            $job->save();

            return response()->json(['status' => 'success', 'message' => 'สร้างสถานะงานสำเร็จ']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด']);
        }
    }

    public function job_status_get(Request $request)
    {
        $id = $request->route('id');

        if ($id !== null && !empty($id)) {
            $job = JobStatus::where('job_status_id', $id)->first();
            return view('content.job.jobstatus_edit', compact('job'));
        } else {
            return redirect()->back();
        }
    }

    public function job_status_update(Request $request, $id)
    {

        if (empty($request->input('job_desc'))) {
            return response()->json(['status' => 'error', 'message' => 'กรุณากรอกรายละเอียดสถานะ']);
        }

        try {
            $count = DB::table('job_statuses as js')
                ->where('js.job_status_description', '=', $request->input('job_desc'))->count();

            if ($count > 0) {
                return response()->json(['status' => 'error', 'message' => 'ข้อมูลรายละเอียดสถานะซ้ำ']);
            }

            $job = JobStatus::find($id);
            $job->job_status_description = $request->input('job_desc');
            $job->save();

            return response()->json(['status' => 'success', 'message' => 'อัพเดทสร้างสถานะงานสำเร็จ']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด']);
        }
    }

    public function job_status()
    {
        $่job_status = JobStatus::paginate(10);
        return view('content.job.jobstatus', compact('่job_status'));
    }

    public function workshift()
    {
        $workshift = WorkShift::whereNull('deleted_at')->paginate(10);
        return view('content.job.workshift', compact('workshift'));
    }

    public function workshift_create(Request $request)
    {

        if (empty($request->input('workshift_desc'))) {
            return response()->json(['status' => 'error', 'message' => 'กรุณากรอกรายละเอียดตารางงาน']);
        }

        if (empty($request->input('shift_time_slot'))) {
            return response()->json(['status' => 'error', 'message' => 'กรุณากรอกระยะเวลาเริ่มต้น']);
        }

        if (empty($request->input('_shift_time_slot'))) {
            return response()->json(['status' => 'error', 'message' => 'กรุณากรอกระยะเวลาสิ้นสุด']);
        }

        try {

            $count = DB::table('work_shifts as ws')
                ->where('ws.work_shift_description', '=', $request->input('workshift_desc'))
                ->where('ws.shift_time_slot', '=', (string)$request->input('shift_time_slot') . '-' . (string)$request->input('_shift_time_slot'))
                ->count();

            if ($count > 0) {
                return response()->json(['status' => 'error', 'message' => 'ข้อมูลรายละเอียดงานซ้ำ']);
            }

            $workshift = new WorkShift();
            $workshift->work_shift_description = $request->input('workshift_desc');
            $workshift->shift_time_slot = (string)$request->input('shift_time_slot') . '-' . (string)$request->input('_shift_time_slot');
            $workshift->save();
            return response()->json(['status' => 'success', 'message' => 'สร้างกะการงานสำเร็จ']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด']);
        }
    }

    public function workshift_del(Request $request)
    {
        try {
            $id = $request->input("id");
            $workshift = WorkShift::findOrFail($id);

            if ($workshift) {

                DB::beginTransaction();

                try {

                    DB::table('work_shifts')
                        ->where('work_shift_id', $workshift->work_shift_id)
                        ->update([
                            'work_shift_status' => '0',
                            'deleted_at' => now(),
                        ]);

                    DB::commit();

                    return response()->json(['status' => 'success', 'message' => 'ลบข้อมูลสำเร็จ']);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด']);
                }
            } else {

                return response()->json(['status' => 'error', 'message' => 'ไม่พบข้อมูล']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด']);
        }
    }

    public function workshift_status(Request $request)
    {
        if (empty($request->input('id'))) {
            return response()->json(['status' => 'error', 'message' => 'ไม่พบข้อมูลสถานะที่ใช้']);
        }

        try {
            $id = explode("_", $request->input('id'))[1];
            $workshift = WorkShift::findOrFail($id);
            $status = $request->input('status') == '0' ? '1' : '0';

            if ($workshift) {

                DB::beginTransaction();

                try {

                    DB::table('work_shifts')
                        ->where('work_shift_id', $workshift->work_shift_id)
                        ->update([
                            'work_shift_status' => $status,
                        ]);

                    DB::commit();

                    return response()->json(['status' => 'success', 'message' => 'เปิดใช้งานสำเร็จ', 'workshift-status' => $status]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด']);
                }
            } else {

                return response()->json(['status' => 'error', 'message' => 'ไม่พบข้อมูล']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด']);
        }
    }

    public function workshift_get(Request $request, $id)
    {
        $workshift = WorkShift::find($id);
        $timeRange = $workshift->shift_time_slot;
        $correctedTime = str_replace('.', ':', $timeRange);
        list($startTime, $endTime) = explode('-', $correctedTime);

        return view('content.job.workshift_edit', compact('workshift', 'startTime', 'endTime'));
    }

    public function workshift_get_json(Request $request)
    {
        $workshift = WorkShift::all();
        return response()->json($workshift);
    }


    public function workshift_update(Request $request, $id)
    {

        if (empty($request->input('workshift_desc'))) {
            return response()->json(['status' => 'error', 'message' => 'กรุณากรอกรายละเอียดตารางงาน']);
        }

        if (empty($request->input('shift_time_slot'))) {
            return response()->json(['status' => 'error', 'message' => 'กรุณากรอกระยะเวลาเริ่มต้น']);
        }

        if (empty($request->input('_shift_time_slot'))) {
            return response()->json(['status' => 'error', 'message' => 'กรุณากรอกระยะเวลาสิ้นสุด']);
        }

        try {

            $count = DB::table('work_shifts as ws')
                ->where('ws.work_shift_description', '=', $request->input('workshift_desc'))
                ->where('ws.shift_time_slot', '=', (string)$request->input('shift_time_slot') . '-' . (string)$request->input('_shift_time_slot'))
                ->count();

            if ($count > 0) {
                return response()->json(['status' => 'error', 'message' => 'ข้อมูลรายละเอียดงานซ้ำ']);
            }
            
            $workshift = WorkShift::find($id);
            $workshift->work_shift_description = $request->input('workshift_desc');
            $workshift->shift_time_slot = (string)$request->input('shift_time_slot') . '-' . (string)$request->input('_shift_time_slot');
            $workshift->save();

            return response()->json(['status' => 'success', 'message' => 'อัพเดทกะการงานสำเร็จ']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด']);
        }
    }

    public function job_schedules(Request $request)
    {

        $date = Carbon::now()->toDateString();

        $_query = DB::table('job_schedules as js')
            ->join('user_authorities as ua', 'js.job_authority_id', '=', 'ua.user_authority_id')
            ->join('work_shifts as ws', 'js.job_schedule_shift_id', '=', 'ws.work_shift_id')
            ->join('job_statuses as jst', 'js.job_schedule_status_id', '=', 'jst.job_status_id')
            ->join('users as u', 'ua.user_id', '=', 'u.user_id')
            ->join('roles as r', 'u.role_id', '=', 'r.role_id')
            ->join('locations as l', 'ua.user_location_id', '=', 'l.location_id')
            ->join('zones as z', 'l.location_zone_id', '=', 'z.zone_id')
            ->orderBy('l.location_id', 'ASC');

        if ($request->filled('work_shift')) {
            $_query->where('work_shift_id', $request->input('work_shift'));
        }

        if ($request->filled('job_status')) {
            $_query->where('job_status_id', $request->input('job_status'));
        }

        if ($request->filled('zone')) {
            $_query->where('zone_id', $request->input('zone'));
        }

        if ($request->filled('date')) {
            $_query->whereDate('job_schedule_date', $request->input('date'));
            $date = $request->input('date');
        } else {
            $_query->whereDate('js.job_schedule_date', $date);
        }

        $_zones = DB::table('zones');

        if ($request->filled('zone')) {
            $_zones->where('zone_id', $request->input('zone'));
        }

        $_workshift = DB::table('work_shifts');

        if ($request->filled('work_shift')) {
            $_workshift->where('work_shift_id', $request->input('work_shift'));
        }

        $_jobSchedules = $_query->get();
        $jobSchedules = $_query->paginate(10);
        $_zones = $_zones->get();
        $_workshift = $_workshift->get();

        $zones = Zone::all();
        $workshift = WorkShift::all();
        $jobStatus = JobStatus::all();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('content.job.jobschedules_table', compact('jobSchedules'))->render(),
                'pagination' => [
                    'total' => $jobSchedules->total(),
                    'current_page' => $jobSchedules->currentPage(),
                    'last_page' => $jobSchedules->lastPage(),
                ],
            ]);
        }

        return view('content.job.jobschedules', compact("workshift", "_workshift", "jobSchedules", "jobStatus", "zones", "_zones", "_jobSchedules", "date"));
    }

    public function job_schedules_image(Request $request, $id)
    {

        $jobSchedules = DB::table('job_schedules as js')
            ->join('user_authorities as ua', 'js.job_authority_id', '=', 'ua.user_authority_id')
            ->join('work_shifts as ws', 'js.job_schedule_shift_id', '=', 'ws.work_shift_id')
            ->join('job_statuses as jst', 'js.job_schedule_status_id', '=', 'jst.job_status_id')
            ->join('users as u', 'ua.user_id', '=', 'u.user_id')
            ->join('roles as r', 'u.role_id', '=', 'r.role_id')
            ->join('locations as l', 'ua.user_location_id', '=', 'l.location_id')
            ->join('zones as z', 'l.location_zone_id', '=', 'z.zone_id')
            ->select('js.*', 'ua.*', 'ws.*', 'jst.*', 'u.*', 'r.*', 'l.*', 'z.*')
            ->where('js.job_schedule_id', '=', $id)
            ->orderBy('jst.job_status_id', 'DESC')
            ->first();

        $jobImage = DB::table('job_schedules as js')
            ->join('job_schedule_images as jsi', 'js.job_schedule_id', '=', 'jsi.job_schedule_id')
            ->where('jsi.job_schedule_id', '=', $id)
            ->get();

        return view('content.job.jobschedules_images', compact("jobSchedules", "jobImage"));
    }

    public function authority(Request $request)
    {
        $authorities = DB::table('user_authorities as ua')
            ->join('users as u', 'ua.user_id', '=', 'u.user_id')
            ->join('locations as l', 'ua.user_location_id', '=', 'l.location_id')
            ->join('zones as z', 'l.location_zone_id', '=', 'z.zone_id')
            ->select(
                'ua.user_id',
                DB::raw('MAX(ua.user_authority_id) as user_authority_id'),
                DB::raw('MAX(u.first_name) as first_name'),
                DB::raw('MAX(u.last_name) as last_name'),
                DB::raw('MAX(u.user_name) as user_name'),
                DB::raw('MAX(l.location_description) as location_description'),
                DB::raw('MAX(z.zone_description) as zone_description')
            )
            ->whereNull('ua.deleted_at')
            ->where('ua.user_authority_status', '1')
            ->groupBy('ua.user_id')
            ->orderBy('user_authority_id', 'ASC')
            ->paginate(10);

        $accounts = User::all();

        if ($request->input("id")) {
            $firstId = $request->input("id");
        } else {
            $firstId = $accounts->isNotEmpty() ? $accounts->first()->user_id : null;
        }

        $_query = DB::table('user_authorities as ua')
            ->join('users as u', 'ua.user_id', '=', 'u.user_id')
            ->join('roles as r', 'u.role_id', '=', 'r.role_id')
            ->join('locations as l', 'ua.user_location_id', '=', 'l.location_id')
            ->join('zones as z', 'l.location_zone_id', '=', 'z.zone_id')
            ->whereNull('ua.deleted_at')->where('ua.user_authority_status', '1');

        if (!empty($_query)) {
            $authority_query = $_query->get();
        } else {
            $authority_query = null;
        }

        $locations = DB::table('locations as l')
            ->join('zones as z', 'l.location_zone_id', '=', 'z.zone_id')
            ->where('l.location_status', '1')->whereNull('l.deleted_at')
            ->where('z.zone_status', '1')->whereNull('z.deleted_at');

        $locations = $locations->get();

        $zones = DB::table('zones as z')
            ->join('locations as l', 'z.zone_id', '=', 'l.location_zone_id')
            ->where('l.location_status', '1')->whereNull('l.deleted_at')
            ->where('z.zone_status', '1')->whereNull('z.deleted_at')
            ->select('z.*')
            ->groupBy('z.zone_id')
            ->get();

        if ($request->ajax()) {
            return response()->json([
                'accounts' => $accounts,
                'locations' => $locations,
                'authority_query' => $authority_query,
                'zones' => $zones
            ]);
        }

        return view('content.job.authority', compact('accounts', 'locations', 'authorities', 'authority_query', 'firstId', 'zones'));
    }

    public function authority_get(Request $request, $id)
    {
        $authority = DB::table('user_authorities')
            ->join('users', 'user_authorities.user_id', '=', 'users.user_id')
            ->join('locations', 'user_authorities.user_location_id', '=', 'locations.location_id')
            ->join('zones', 'locations.location_zone_id', '=', 'zones.zone_id')
            ->where('user_authorities.user_authority_id', '=', $id)
            ->orderBy('user_authority_id', 'ASC')->first();

        $accounts = User::all();

        $locations = DB::table('locations')
            ->join('zones', 'locations.location_zone_id', '=', 'zones.zone_id')
            ->select('locations.*', 'zones.zone_description')
            ->get();

        return view('content.job.authority_edit', compact('accounts', 'locations', 'authority'));
    }

    public function authority_user(Request $request, $id)
    {
        $date = Carbon::now()->toDateString();

        $_jobSchedules = DB::table('job_schedules as js')
            ->join('user_authorities as ua', 'js.job_authority_id', '=', 'ua.user_authority_id')
            ->join('work_shifts as ws', 'js.job_schedule_shift_id', '=', 'ws.work_shift_id')
            ->join('job_statuses as jst', 'js.job_schedule_status_id', '=', 'jst.job_status_id')
            ->join('users as u', 'ua.user_id', '=', 'u.user_id')
            ->join('roles as r', 'u.role_id', '=', 'r.role_id')
            ->join('locations as l', 'ua.user_location_id', '=', 'l.location_id')
            ->join('zones as z', 'l.location_zone_id', '=', 'z.zone_id')
            ->where('u.user_id', $id);

        if ($request->filled('work_shift')) {
            $_jobSchedules->where('work_shift_id', $request->input('work_shift'));
        }

        if ($request->filled('job_status')) {
            $_jobSchedules->where('job_status_id', $request->input('job_status'));
        }

        if ($request->filled('date')) {
            $_jobSchedules->whereDate('job_schedule_date', $request->input('date'));
            $date = $request->input('date');
        } else {
            $_jobSchedules->whereDate('js.job_schedule_date', $date);
        }

        $_zones = DB::table('zones');

        if ($request->filled('zone')) {
            $_zones->where('zone_id', $request->input('zone'));
        }

        $_workshift = DB::table('work_shifts');

        if ($request->filled('work_shift')) {
            $_workshift->where('work_shift_id', $request->input('work_shift'));
        }

        $_jobSchedules = $_jobSchedules->get();
        $_zones = $_zones->get();
        $_workshift = $_workshift->get();

        return view('content.job.authority_user', compact("_workshift", "_zones", "_jobSchedules", "date"));
    }

    public function authority_create(Request $request)
    {

        if (empty($request->input('user_id'))) {
            return response()->json(['status' => 'error', 'message' => 'กรุณาเลือกยูสเซอร์']);
        }

        if (empty($request->input('id'))) {
            return response()->json(['status' => 'error', 'message' => 'กรุณาเลือกสถานที่']);
        }

        $data = $request->input('id');
        $user_id = $request->input('user_id');

        foreach ($data as $key => $val) {

            // $user_check = DB::table('user_authorities')
            // ->where('user_id', $user_id)
            // ->where('user_location_id', $key)
            // ->first();

            // if($user_check && $user_check->user_authority_status == '1' && $data[$key] === "false"){
            //     DB::table('user_authorities')
            //     ->where('user_id', $user_id)
            //     ->where('user_location_id', $key)
            //     ->update(['user_authority_status' => '0']);

            // }elseif($user_check && $user_check->user_authority_status == '0' && $data[$key] === "true"){
            //     DB::table('user_authorities')
            //     ->where('user_id', $user_id)
            //     ->where('user_location_id', $key)
            //     ->update(['user_authority_status' => '1']);
            // }else{
            //     if (!$user_check) {
            //         DB::table('user_authorities')->insert([
            //             'user_id' => $user_id,
            //             'user_location_id' => $key,
            //             'user_authority_status' => '1',
            //             'created_at' => now(),
            //             'updated_at' => now(),
            //         ]);
            //     }
            // }

            if ($data[$key] === "false") {
                DB::table('user_authorities')
                    ->where('user_id', $user_id)
                    ->where('user_location_id', $key)
                    ->update(['user_authority_status' => '0']);
            }

            if ($data[$key] === "true") {

                $user_check = DB::table('user_authorities')
                    ->where('user_id', $user_id)
                    ->where('user_location_id', $key)
                    ->where('user_authority_status', '1')
                    ->first();

                if (!$user_check) {
                    DB::table('user_authorities')->insert([
                        'user_id' => $user_id,
                        'user_location_id' => $key,
                        'user_authority_status' => '1',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        return response()->json(['status' => 'success', 'message' => 'กำหนดสิทธิผู้ตรวจสำเร็จ']);
    }
}
