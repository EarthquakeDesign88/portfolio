<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;


class SystemTriggerController extends Controller
{
    public function jobschedulesTrigger()
    {
        return view('content.system-trigger.jobschedules_trigger');
    }

    public function JobschedulesTrigger_run(Request $request)
    {

        $request->validate([
            'job_schedule_date' => 'required|date',
        ],[
            'job_schedule_date.required' => 'กรุณาเลือกวันที่',
            'job_schedule_date.date' => 'ประเภทข้อมูลต้องเป็นวันที่เท่านั้น',
        ]);


        $jobScheduleDate = $request->input('job_schedule_date');
    

        $existingSchedules = DB::table('job_schedules')
            ->where('job_schedule_date', $jobScheduleDate)
            ->exists();
    
        if ($existingSchedules) {
            return response()->json([
                'status' => 'error',
                'message' => 'ไม่สามารถสร้างตารางงานได้ เนื่องจากมีตารางงานวันที่นี้แล้ว'
            ], 400);
        }
    
        $authorities = DB::table('user_authorities')->where('user_authority_status', '=', '1')->get();
        $workshifts = DB::table('work_shifts')->where('work_shift_status', '=', '1')->get();
    

        $data = [];
        foreach ($authorities as $author) {
            foreach ($workshifts as $work) {
                $data[] = [
                    'job_authority_id' => $author->user_authority_id,
                    'job_schedule_date' => $jobScheduleDate,
                    'job_schedule_shift_id' => $work->work_shift_id,
                    'job_schedule_status_id' => 3,  
                    'job_schedule_issue_id' => 0, 
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
    

        try {
            $result = DB::table('job_schedules')->insert($data);
        
            if ($result) {
                $thaiDate = Carbon::createFromFormat('Y-m-d', $jobScheduleDate)
                ->locale('th')
                ->translatedFormat('j F Y');

                $year = Carbon::createFromFormat('Y-m-d', $jobScheduleDate)->year + 543; 
                $thaiDate = str_replace(date('Y'), $year, $thaiDate); 

                return response()->json([
                    'status' => 'success',
                    'message' => 'ตารางงานวันที่ ' . $thaiDate . ' ถูกสร้างสำเร็จ'
                ]);
            } 
            else {
                return redirect()->back()->with(['run_error' => 'ไม่สามารถสร้างตารางงานได้ พบข้อผิดพลาดโปรดลองใหม่ภายหลัง']);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'พบข้อผิดพลาด: ' . $e->getMessage()
            ], 500); 
        }

    }
    
}
