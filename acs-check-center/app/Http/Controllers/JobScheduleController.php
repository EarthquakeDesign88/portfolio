<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Models\JobSchedule;
use App\Models\WorkShift;
use App\Models\Zone;
use App\Models\IssueTopic;
use App\Models\Repair;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use App\Events\MessageSent;

class JobScheduleController extends Controller
{
    public function fetchWorkShifts(Request $request)
    {
        $workShifts = WorkShift::select('work_shift_id', 'work_shift_description', 'shift_time_slot')
        ->orderBy('shift_time_slot', 'ASC')->get();

        return response()->json($workShifts);
    }

    public function fetchZones(Request $request)
    {
        $zones = Zone::select('zone_id', 'zone_description')->orderBy('zone_description', 'ASC')->get();

        return response()->json($zones);
    }

    public function fetchIssueTopics(Request $request)
    {
        $issues = IssueTopic::select('issue_id', 'issue_description')->orderBy('issue_description', 'ASC')->get();

        return response()->json($issues);
    }

    public function fetchZonesByUser(Request $request)
    {
        $data = $request->json()->all();

        $jobScheduleDate = $data['job_schedule_date'] ?? null;
        $userId = $data['user_id'] ?? null;
        $jobScheduleShiftId = $data['job_schedule_shift_id'] ?? null;

        $zones = DB::table('job_schedules as j')
        ->join('user_authorities as ua', 'j.job_authority_id', '=', 'ua.user_authority_id')
        ->join('locations as l', 'ua.user_location_id', '=', 'l.location_id')
        ->join('zones as z', 'l.location_zone_id', '=', 'z.zone_id')
        ->join('users as u', 'ua.user_id', '=', 'u.user_id')
        ->select('z.zone_id', 'z.zone_description')
        ->where('j.job_schedule_date', $jobScheduleDate)
        ->where('ua.user_id', $userId)
        ->where('j.job_schedule_shift_id', $jobScheduleShiftId)
        ->groupBy('z.zone_id', 'z.zone_description')
        ->get();

       
        return response()->json($zones);

    }

    public function fetchWorkShiftsByUser(Request $request)
    {
        $data = $request->json()->all();

        $userId = $data['user_id'] ?? null;
        $jobScheduleDate = $data['job_schedule_date'] ?? null;


        if (!$userId || !$jobScheduleDate) {
            return response()->json(['error' => 'Missing parameters'], 400);
        }

        $jobSchedules = JobSchedule::select(
                'job_schedules.job_schedule_shift_id',
                'work_shifts.work_shift_description',
                'work_shifts.shift_time_slot',
                DB::raw('MAX(job_schedules.job_schedule_date) as job_schedule_date'),
                'user_authorities.user_id'
            )
            ->join('work_shifts', 'job_schedules.job_schedule_shift_id', '=', 'work_shifts.work_shift_id')
            ->join('user_authorities', 'job_schedules.job_authority_id', '=', 'user_authorities.user_authority_id')
            ->where('user_authorities.user_id', $userId)
            ->whereDate('job_schedules.job_schedule_date', $jobScheduleDate) 
            ->groupBy('job_schedules.job_schedule_shift_id', 'work_shifts.work_shift_description', 'user_authorities.user_id')
            ->get();

        return response()->json($jobSchedules);
    }

    public function fetchJobSchedules(Request $request)
    {
        $data = $request->json()->all();

        $userId = $data['user_id'] ?? null;
        $jobScheduleDate = $data['job_schedule_date'] ?? null;
        $jobScheduleShiftId = $data['job_schedule_shift_id'] ?? null;
        $jobScheduleZoneId = $data['zone_id'] ?? null;


        if (!$userId || !$jobScheduleDate || !$jobScheduleShiftId) {
            return response()->json(['error' => 'Missing parameters'], 400);
        }

        $query = JobSchedule::select(
            'job_schedules.job_schedule_id',
            'job_schedules.job_schedule_date',
            'job_schedules.job_schedule_status_id',
            'job_schedules.job_schedule_shift_id',
            'job_statuses.job_status_description',
            'work_shifts.work_shift_description',
            'work_shifts.shift_time_slot',
            'locations.location_id',
            'locations.location_description',
            'zones.zone_id',
            'zones.zone_description',
            'job_schedules.job_authority_id',
            'users.user_id'
        )
        ->leftJoin('job_statuses', 'job_schedules.job_schedule_status_id', '=', 'job_statuses.job_status_id')
        ->leftJoin('work_shifts', 'job_schedules.job_schedule_shift_id', '=', 'work_shifts.work_shift_id')
        ->leftJoin('user_authorities', 'job_schedules.job_authority_id', '=', 'user_authorities.user_authority_id')
        ->leftJoin('locations', 'user_authorities.user_location_id', '=', 'locations.location_id')
        ->leftJoin('zones', 'locations.location_zone_id', '=', 'zones.zone_id')
        ->leftJoin('users', 'user_authorities.user_id', '=', 'users.user_id')
        ->where('users.user_id', $userId)
        ->whereDate('job_schedules.job_schedule_date', $jobScheduleDate)
        ->where('job_schedules.job_schedule_shift_id', $jobScheduleShiftId);

        if($jobScheduleZoneId && $jobScheduleZoneId != 0) {
            $query->where('zones.zone_id', $jobScheduleZoneId);
        }

        $jobSchedules = $query->get();

        
        if ($jobSchedules->isEmpty()) {
            return response()->json(['message' => 'Not found job schedule'], 404);
        }
    
        return response()->json($jobSchedules, 200);
    }

    public function fetchCheckedPointsCount(Request $request)
    {
        $data = $request->json()->all();
    
        $userId = $data['user_id'] ?? null;
        $jobScheduleDate = $data['job_schedule_date'] ?? null;
        $jobScheduleShiftId = $data['job_schedule_shift_id'] ?? null;
        $jobScheduleZoneId = $data['zone_id'] ?? null;
    
        if (!$userId || !$jobScheduleDate || !$jobScheduleShiftId) {
            return response()->json(['error' => 'Missing parameters'], 400);
        }
    
            $query = JobSchedule::select(
                'job_schedules.job_schedule_id',
                'job_schedules.job_schedule_date',
                'job_statuses.job_status_description',
                'work_shifts.work_shift_description',
                'work_shifts.shift_time_slot',
                'locations.location_description',
                'zones.zone_id',
                'zones.zone_description',
                'users.user_id'
            )
            ->leftJoin('job_statuses', 'job_schedules.job_schedule_status_id', '=', 'job_statuses.job_status_id')
            ->leftJoin('work_shifts', 'job_schedules.job_schedule_shift_id', '=', 'work_shifts.work_shift_id')
            ->leftJoin('user_authorities', 'job_schedules.job_authority_id', '=', 'user_authorities.user_authority_id')
            ->leftJoin('locations', 'user_authorities.user_location_id', '=', 'locations.location_id')
            ->leftJoin('zones', 'locations.location_zone_id', '=', 'zones.zone_id')
            ->leftJoin('users', 'user_authorities.user_id', '=', 'users.user_id')
            ->where('users.user_id', $userId)
            ->whereDate('job_schedules.job_schedule_date', $jobScheduleDate)
            ->where('job_schedules.job_schedule_shift_id', $jobScheduleShiftId)
            ->where('job_statuses.job_status_description', '!=', 'ยังไม่ได้ตรวจสอบ');

            if($jobScheduleZoneId && $jobScheduleZoneId != 0) {
                $query->where('zones.zone_id', $jobScheduleZoneId);
            }

            $checkedPointsCount = $query->count();
    
        return response()->json(['checked_points_count' => $checkedPointsCount], 200);
    }
    

    public function saveInspectionResult(Request $request)
    {
        $user_id = $request->input('user_id') ?? null;
        $jobScheduleDate = $request->input('job_schedule_date') ?? null;
        $jobScheduleShiftId = $request->input('job_schedule_shift_id') ?? null;
        $jobScheduleStatusId = $request->input('job_schedule_status_id') ?? null;
        $locationQR = $request->input('location_qr') ?? null;
        $inspectionCompletedAt = $request->input('inspection_completed_at') ?? null;
        $images = $request->file('images_path') ?? null;
        $issueTopicId = $request->input('issue_topic_id') ?? null;

        if (!$user_id || !$jobScheduleDate || !$jobScheduleShiftId || !$jobScheduleStatusId || !$locationQR || !$inspectionCompletedAt) {
            return response()->json([
                'success' => false, 
                'message' => 'ข้อมูลส่งมาไม่ครบ โปรดลองใหม่ภายหลัง'
            ], 400);
        }

        
        if($jobScheduleStatusId == 2 && !$issueTopicId) {
            return response()->json([
                'success' => false, 
                'message' => 'กรุณาเลือกหัวข้อปัญหา'
            ], 400);  
        }

        try {
            $jobSchedule = DB::table('job_schedules as js')
                ->leftJoin('user_authorities as ua', 'js.job_authority_id', '=', 'ua.user_authority_id')
                ->leftJoin('locations as lc', 'ua.user_location_id', '=', 'lc.location_id')
                ->leftJoin('users as us', 'ua.user_id', '=', 'us.user_id')
                ->select('js.job_schedule_id')
                ->where('us.user_id', $user_id)
                ->where('js.job_schedule_date', $jobScheduleDate)
                ->where('js.job_schedule_shift_id', $jobScheduleShiftId)
                ->where('lc.location_qr', $locationQR)
                ->first();

            if (!$jobSchedule) {
                return response()->json([
                    'success' => false, 
                    'message' => 'ไม่พบรหัสจุดตรวจ ในตารางงานนี้'
                ], 404);
            }

            $jobScheduleId = $jobSchedule->job_schedule_id;


            DB::table('job_schedules')
                ->where('job_schedule_id', $jobScheduleId)
                ->update([
                    'job_schedule_status_id' => $jobScheduleStatusId,
                    'inspection_completed_at' => now(),
                    'job_schedule_issue_id' => $issueTopicId,
                    'updated_at' => now(),
                ]);


                if ($jobScheduleStatusId == 2 && $images) {

                    $repairExists = DB::table('repairs')->where('job_id', $jobScheduleId)->exists();

                    if (!$repairExists) {
                        DB::table('repairs')->insert([ 
                            'job_id' => $jobScheduleId,
                            'repair_status' => '2',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    $existingImagesCount = DB::table('job_schedule_images')
                        ->where('job_schedule_id', $jobScheduleId)
                        ->count();
        
                    $remainingSlots = 3 - $existingImagesCount;
        
                    if ($remainingSlots > 0) {
                        foreach ($images as $image) {
                            if ($remainingSlots <= 0) break;
        
                            if ($image && $image->isValid()) {
                                $imagePath = $image->store('job_schedule_images', 'public');
        
                                DB::table('job_schedule_images')->insert([
                                    'job_schedule_id' => $jobScheduleId,
                                    'image_path' => $imagePath,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);
        
                                $remainingSlots--;
                            } else {
                                return response()->json([
                                    'success' => false, 
                                    'message' => 'ไฟล์รูปภาพไม่ถูกต้องหรือไม่สามารถอ่านได้'
                                ], 400);
                            }
                        }
                    } else {
                        return response()->json([
                            'success' => false, 
                            'message' => 'อัพโหลดรูปภาพครบ 3 รูปแล้ว ไม่สามารถอัพโหลดได้อีก'
                        ], 400);
                    }
                }

                $this->sendMessage($jobScheduleId, $jobScheduleStatusId);

                return response()->json([
                    'success' => true, 
                    'message' => 'ผลการตรวจสอบถูกบันทึกเรียบร้อยแล้ว'
                ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'status' => 'Server error',
                'message' => 'Server error: ' . $e->getMessage()], 
            500);
        }
    }

    public function sendMessage($jobScheduleId, $jobScheduleStatusId)
    {
        $data = ['job_schedule_id' => $jobScheduleId, 'job_schedule_status' => $jobScheduleStatusId];
        event(new MessageSent($data));

        return response()->json(['data' => $data]);
    }

    
    public function countCompletedSchedules(Request $request)
    {
        $userId = $request->query('user_id');
        $jobScheduleDate = $request->query('job_schedule_date');
    
        if (!$userId || !$jobScheduleDate) {
            return response()->json(['error' => 'Missing parameters'], 400);
        }
    
        $countCompletedJob = DB::table('acs_check.job_schedules as js')
        ->leftJoin('acs_check.work_shifts as ws', 'js.job_schedule_shift_id', '=', 'ws.work_shift_id')
        ->leftJoin('acs_check.user_authorities as ua', 'js.job_authority_id', '=', 'ua.user_authority_id')
        ->select(
            'js.job_schedule_date',
            'js.job_schedule_shift_id',
            'ws.work_shift_description',
            'ws.shift_time_slot',
            DB::raw('COUNT(js.job_schedule_id) AS total_job_schedules'),
            DB::raw('SUM(CASE WHEN js.job_schedule_status_id = 2 OR js.job_schedule_status_id = 1 THEN 1 ELSE 0 END) AS completed_job_schedules')
        )
        ->where('ua.user_id', $userId)
        ->whereDate('js.job_schedule_date', $jobScheduleDate)
        ->groupBy(
            'js.job_schedule_date',
            'js.job_schedule_shift_id',
            'ws.work_shift_description',
            'ws.shift_time_slot'
        )
        ->get();

        return response()->json($countCompletedJob);
    }
    
    public function fetchLocationDetails(Request $request)
    {
        $data = $request->json()->all();

        $jobAuthorityId = $data['job_authority_id'] ?? null;
        $jobScheduleDate = $data['job_schedule_date'] ?? null;
        $jobScheduleShiftId = $data['job_schedule_shift_id'] ?? null;

        if (!$jobAuthorityId || !$jobScheduleDate || !$jobScheduleShiftId) {
            return response()->json(['error' => 'Missing parameters'], 400);
        }

        $locationDetails = JobSchedule::select(
            DB::raw('DISTINCT job_schedules.job_schedule_id'),
            'locations.location_id',
            'locations.location_description',
            'locations.location_qr',
            'zones.zone_description',
            'job_statuses.job_status_id',
            'job_statuses.job_status_description',
            'job_schedules.inspection_completed_at',
            'issue_topics.issue_description',
        )
        ->leftJoin('job_statuses', 'job_schedules.job_schedule_status_id', '=', 'job_statuses.job_status_id')
        ->leftJoin('work_shifts', 'job_schedules.job_schedule_shift_id', '=', 'work_shifts.work_shift_id')
        ->leftJoin('user_authorities', 'job_schedules.job_authority_id', '=', 'user_authorities.user_authority_id')
        ->leftJoin('locations', 'user_authorities.user_location_id', '=', 'locations.location_id')
        ->leftJoin('zones', 'locations.location_zone_id', '=', 'zones.zone_id')
        ->leftJoin('users', 'user_authorities.user_id', '=', 'users.user_id')
        ->leftJoin('issue_topics', 'issue_topics.issue_id', '=', 'job_schedules.job_schedule_issue_id')
        ->where('job_schedules.job_authority_id', $jobAuthorityId)
        ->whereDate('job_schedules.job_schedule_date', $jobScheduleDate)
        ->where('job_schedules.job_schedule_shift_id', $jobScheduleShiftId)
        ->get();

        return response()->json($locationDetails);
    }

    public function fetchImagesJobschedules(Request $request)
    {
        $data = $request->json()->all();

        $jobScheduleId = $data['job_schedule_id'] ?? null;


        if (!$jobScheduleId) {
            return response()->json(['error' => 'Missing parameters'], 400);
        }

        $locationDetails = JobSchedule::select(
            'job_schedules.job_schedule_id',
            'job_schedule_images.image_path',
        )
        ->leftJoin('job_schedule_images', 'job_schedules.job_schedule_id', '=', 'job_schedule_images.job_schedule_id')
        ->where('job_schedule_images.job_schedule_id', $jobScheduleId)
        ->get();

        return response()->json($locationDetails);
    }

    public function fetchJobSchedulesHistory(Request $request)
    {
        $data = $request->json()->all();
    
        $userId = $data['user_id'] ?? null;
        $jobScheduleDate = $data['job_schedule_date'] ?? null;
        $jobScheduleShiftId = $data['job_schedule_shift_id'] ?? null;
        $jobScheduleStatusId = $data['job_schedule_status_id'] ?? null;
        $zoneId = $data['zone_id'] ?? null;
    
        if (!$userId) {
            return response()->json(['error' => 'User ID is required'], 400);
        }
    
        $query = JobSchedule::select(
            'job_schedules.job_schedule_id',
            'job_schedules.job_schedule_date',
            'job_schedules.job_schedule_status_id',
            'job_schedules.job_schedule_shift_id',
            'job_statuses.job_status_description',
            'job_schedules.inspection_completed_at',
            'work_shifts.work_shift_description',
            'work_shifts.shift_time_slot',
            'locations.location_id',
            'locations.location_description',
            'zones.zone_id',
            'zones.zone_description',
            'job_schedules.job_authority_id',
            'users.user_id',
            'issue_topics.issue_description'
        )
        ->leftJoin('job_statuses', 'job_schedules.job_schedule_status_id', '=', 'job_statuses.job_status_id')
        ->leftJoin('work_shifts', 'job_schedules.job_schedule_shift_id', '=', 'work_shifts.work_shift_id')
        ->leftJoin('user_authorities', 'job_schedules.job_authority_id', '=', 'user_authorities.user_authority_id')
        ->leftJoin('locations', 'user_authorities.user_location_id', '=', 'locations.location_id')
        ->leftJoin('zones', 'locations.location_zone_id', '=', 'zones.zone_id')
        ->leftJoin('users', 'user_authorities.user_id', '=', 'users.user_id')
        ->leftJoin('issue_topics', 'issue_topics.issue_id', '=', 'job_schedules.job_schedule_issue_id')
        ->where('users.user_id', $userId)
        ->orderBy('locations.location_id', 'ASC');
    
        if ($jobScheduleDate) {
            $query->whereDate('job_schedules.job_schedule_date', $jobScheduleDate);
        }
    
        if ($jobScheduleShiftId) {
            $query->where('job_schedules.job_schedule_shift_id', $jobScheduleShiftId);
        }
    
        if ($jobScheduleStatusId) {
            $query->where('job_schedules.job_schedule_status_id', $jobScheduleStatusId);
        }

        if ($zoneId) {
            $query->where('zones.zone_id', $zoneId);
        }
    
        $jobSchedules = $query->get();
        
    
        if ($jobSchedules->isEmpty()) {
            return response()->json(['message' => 'Not found job schedule'], 404);
        }
    
        return response()->json($jobSchedules, 200);
        
    }


    public function bulkInsertLocations() 
    {
        $data = [];
        $zoneDesc = "2B";
        $length = 50;
        $zoneId = 5;
 

        for ($i = 1; $i <= $length; $i++) {
            $qr = $zoneDesc . "_" . $i . "_QR";

            $data[] = [
                'location_description' => $i,
                'location_qr' => $qr,
                'location_zone_id' => $zoneId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $qrCode = QrCode::size(150)->generate($qr);
            // $fileName = 'qr_' . $qr . '.png';
            $fileName = 'qr_' . $qr . '.svg';
            Storage::put('public/qr_codes/' . $fileName, $qrCode);
        }

        // Insert data into the table
        $result = DB::table('locations')->insert($data);

        if($result) {
            echo "success";
        }
        else {
            echo "error";
        }
    }


    public function bulkInsertAuthorities() 
    {
        $data = [];
        $userId = 2;
        $length = 50;


        for ($i = 1; $i <= $length; $i++) {
            $data[] = [
                'user_id' => $userId,
                'user_location_id' => $i,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert data into the table
        $result = DB::table('user_authorities')->insert($data);

        if($result) {
            echo "success";
        }
        else {
            echo "error";
        }
    }


    public function bulkInsertJob() 
    {
        $data = [];
        $length = 250;
        $jobDate = '2024-09-17';
        $totalShifts = 12;
        $statusId = 3;

        for($shiftId = 1; $shiftId <= $totalShifts; $shiftId++) {
            for ($i = 1; $i <= $length; $i++) {
                $data[] = [
                    'job_authority_id' => $i,
                    'job_schedule_date' => $jobDate,
                    'job_schedule_shift_id' => $shiftId,
                    'job_schedule_status_id' => $statusId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        

        // Insert data into the table
        $result = DB::table('job_schedules')->insert($data);

        if($result) {
            echo "success";
        }
        else {
            echo "error";
        }
    }



    
}
   
