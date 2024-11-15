<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobQualification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class JobQualificationController extends Controller
{
    public function index()
    {
        return view('work.job_qualifications');
    }

    public function getJobQualifications() {
        $jobQualifications = JobQualification::orderBy('Job_qualification_id', 'DESC')->get();
    
        return response()->json([
            'jobQualifications' => $jobQualifications,
            'countJobQualifications' => $jobQualifications->count()
        ]);
    }

    public function insertJobQualification(Request $request) 
    {
        $request->validate(
            [
                'company_th' => 'required|unique:job_qualifications,company_th',
                'company_en' => 'required|unique:job_qualifications,company_en',
                'work_place_th' => 'required',
                'work_place_en' => 'required',
                'working_day_th' => 'required',
                'working_day_en' => 'required',
                'day_off_th' => 'required',
                'day_off_en' => 'required',
                'working_time_start' => 'required',
                'working_time_end' => 'required',
                'benefits_th' => 'required',
                'benefits_en' => 'required',
            ],
            [
                'company_th.required' => 'กรุณากรอกชื่อบริษัทภาษาไทย',
                'company_th.unique' => 'ชื่อบริษัทนี้มีอยู่ในระบบแล้ว',
                'company_en.required' => 'กรุณากรอกชื่อบริษัทภาษาอังกฤษ',
                'company_en.unique' => 'ชื่อบริษัทนี้มีอยู่ในระบบแล้ว',
                'work_place_th.required' => 'กรุณากรอกที่อยู่บริษัทภาษาไทย',
                'work_place_en.required' => 'กรุณากรอกที่อยู่บริษัทภาษาอังกฤษ',
                'working_day_th.required' => 'กรุณากรอกวันทำงานภาษาไทย',
                'working_day_en.required' => 'กรุณากรอกวันทำงานภาษาอังกฤษ',
                'day_off_th.required' => 'กรุณากรอกวันหยุดภาษาไทย',
                'day_off_en.required' => 'กรุณากรอกวันหยุดภาษาอังกฤษ',
                'working_time_start.required' => 'กรุณาเลือกเวลาเริ่มงาน',
                'working_time_end.required' => 'กรุณาเลือกเวลาสิ้นสุดการทำงาน',
                'benefits_th.required' => 'กรุณากรอกสวัสดิการภาษาไทย',
                'benefits_en.required' => 'กรุณากรอกสวัสดิการภาษาอังกฤษ',
            ]
        );

        $data = $request->all();

        $data['working_time'] = $request->working_time_start . ' - ' . $request->working_time_end;
        unset($data['working_time_start'], $data['working_time_end']);

        try {
            JobQualification::create($data);
    
            $response = [
                'status' => 'success',
                'message' => 'คุณสมบัติถูกบันทึกเรียบร้อยแล้ว'
            ];    
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'พบข้อผิดพลาด โปรดลองใหม่อีกครั้ง',
                'error' => $e->getMessage()
            ];       
        }

        return Response()->json($response);

    }

    public function getJobQualificationById($id) 
    {
        $jobQualification = JobQualification::find($id);

        if (!$jobQualification) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบคุณสมบัตินี้ในระบบ'
            ];   
            return response()->json($response, 404);
        }

        try{
            $response = [
                'status' => 'success',
                'message' => 'ดึงคุณสมบัติสำเร็จ',
                'jobQualification' => $jobQualification
            ];    
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'พบข้อผิดพลาด โปรดลองใหม่อีกครั้ง',
                'error' => $e->getMessage()
            ];     
        }

        return Response()->json($response);            

    }


    public function updateJobQualification(Request $request, $id) 
    {
        $jobQualification = JobQualification::find($id);

        if (!$jobQualification) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบคุณสมบัตินี้ในระบบ'
            ];   
            return response()->json($response, 404);
        }

        $request->validate(
            [
                'company_th' => 'required',
                'company_en' => 'required',
                'work_place_th' => 'required',
                'work_place_en' => 'required',
                'working_day_th' => 'required',
                'working_day_en' => 'required',
                'day_off_th' => 'required',
                'day_off_en' => 'required',
                'working_time_start' => 'required',
                'working_time_end' => 'required',
                'benefits_th' => 'required',
                'benefits_en' => 'required',
            ],
            [
                'company_th.required' => 'กรุณากรอกชื่อบริษัทภาษาไทย',
                'company_en.required' => 'กรุณากรอกชื่อบริษัทภาษาอังกฤษ',
                'work_place_th.required' => 'กรุณากรอกที่อยู่บริษัทภาษาไทย',
                'work_place_en.required' => 'กรุณากรอกที่อยู่บริษัทภาษาอังกฤษ',
                'working_day_th.required' => 'กรุณากรอกวันทำงานภาษาไทย',
                'working_day_en.required' => 'กรุณากรอกวันทำงานภาษาอังกฤษ',
                'day_off_th.required' => 'กรุณากรอกวันหยุดภาษาไทย',
                'day_off_en.required' => 'กรุณากรอกวันหยุดภาษาอังกฤษ',
                'working_time_start.required' => 'กรุณาเลือกเวลาเริ่มงาน',
                'working_time_end.required' => 'กรุณาเลือกเวลาสิ้นสุดการทำงาน',
                'benefits_th.required' => 'กรุณากรอกสวัสดิการภาษาไทย',
                'benefits_en.required' => 'กรุณากรอกสวัสดิการภาษาอังกฤษ',
            ]
        );

        $data = $request->all();

        $data['working_time'] = $request->working_time_start . ' - ' . $request->working_time_end;
        unset($data['working_time_start'], $data['working_time_end']);
    
        try {
            $jobQualification->update($data);
    
            $response = [
                'status' => 'success',
                'message' => 'คุณสมบัตินี้ถูกอัพเดตเรียบร้อยแล้ว',
            ];    
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'พบข้อผิดพลาด',
                'error' => $e->getMessage()
            ];    
        }
    
        return response()->json($response);
    }

    public function deleteJobQualification($id) 
    {
        $jobQualification = JobQualification::find($id);

        if (!$jobQualification) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบคุณสมบัตินี้ในระบบ'
            ];   
            return response()->json($response, 404);
        }


        try {
            $jobQualification->delete();
            
            $response = [
                'status' => 'success',
                'message' => 'คุณสมบัตินี้ถูกลบเรียบร้อยแล้ว',
            ];    
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'พบข้อผิดพลาด โปรดลองใหม่อีกครั้ง',
                'error' => $e->getMessage()
            ];    
        }

        return Response()->json($response);
       
    }
    
}
