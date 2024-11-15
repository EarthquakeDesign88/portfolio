<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Position;
use App\Models\Department;
use App\Models\JobQualification;
use App\Models\WorkMode;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PositionController extends Controller
{
    public function index()
    {
        $departments = Department::orderBy('department_desc_th', 'ASC')->get();
        $jobQualifications = JobQualification::orderBy('company_th', 'ASC')->get();
        $workModes = WorkMode::orderBy('mode_desc_th', 'ASC')->get();


        return view('work.positions', compact('departments', 'jobQualifications', 'workModes'));
    }

    
    public function getPositions() {
        $positions = DB::table('positions')
        ->leftjoin('departments', 'positions.position_department_id', '=', 'departments.department_id')
        ->leftjoin('job_qualifications', 'positions.position_job_qualification_id', '=', 'job_qualifications.job_qualification_id')
        ->leftjoin('work_modes', 'positions.position_mode_id', '=', 'work_modes.mode_id')
        ->select('positions.*', 'departments.department_desc_th', 'departments.department_desc_en', 'job_qualifications.company_th',
        'job_qualifications.company_en', 'mode_desc_th', 'mode_desc_en')
        ->orderBy('position_id', 'DESC')->get();
    
        return response()->json([
            'positions' => $positions,
            'countPositions' => $positions->count()
        ]);
    }


    public function insertPosition(Request $request) 
    {
        $request->validate(
            [
                'position_desc_th' => 'required|unique:positions,position_desc_th',
                'position_desc_en' => 'required|unique:positions,position_desc_en',
                'position_status' => 'required',
                'position_department_id' => 'required',
                'position_job_qualification_id' => 'required',
                'position_mode_id' => 'required',
                'responsibilities_th' => 'required',
                'responsibilities_en' => 'required',
                'knowledge_skills_th' => 'required',
                'knowledge_skills_en' => 'required',
                'require_feature_th' => 'required',
                'require_feature_en' => 'required',
                'salary' => 'required',
                'vacancies' => 'required|integer',
            ],
            [
                'position_desc_th.required' => 'กรุณากรอกตำแหน่งภาษาไทย',
                'position_desc_th.unique' => 'ตำแหน่งนี้มีอยู่ในระบบแล้ว',
                'position_desc_en.required' => 'กรุณากรอกตำแหน่งภาษาอังกฤษ',
                'position_desc_en.unique' => 'ตำแหน่งนี้มีอยู่ในระบบแล้ว',
                'position_status.required' => 'กรุณาเลือกสถานะการรับสมัคร',
                'position_department_id.required' => 'กรุณาเลือกแผนก',
                'position_job_qualification_id.required' => 'กรุณาเลือกคุณสมบัติงาน',
                'position_mode_id.required' => 'กรุณาเลือกรูปแบบงาน',
                'responsibilities_th.required' => 'กรุณากรอกหน้าที่ความรับผิดชอบภาษาไทย',
                'responsibilities_en.required' => 'กรุณากรอกหน้าที่ความรับผิดชอบภาษาอังกฤษ',
                'knowledge_skills_th.required' => 'กรุณากรอกความรู้ความสามารถภาษาไทย',
                'knowledge_skills_en.required' => 'กรุณากรอกความรู้ความสามารถภาษาอังกฤษ',
                'require_feature_th.required' => 'กรุณากรอกคุณสมบัติที่ต้องการภาษาไทย',
                'require_feature_en.required' => 'กรุณากรอกคุณสมบัติที่ต้องการภาษาอังกฤษ',
                'salary.required' => 'กรุณากรอกเงินเดือน',
                'vacancies.required' => 'กรุณากรอกจำนวนตำแหน่งงานว่าง',
                'vacancies.integer' => 'กรุณากรอกจำนวนตำแหน่งงานว่างเป็นตัวเลขจำนวนเต็ม',
            ]
        );

        $data = $request->all();

        try {
            Position::create($data);
    
            $response = [
                'status' => 'success',
                'message' => 'ตำแหน่งถูกบันทึกเรียบร้อยแล้ว'
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

    public function getPositionById($id) 
    {
        $position = Position::find($id);

        if (!$position) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบตำแหน่งนี้ในระบบ'
            ];   
            return response()->json($response, 404);
        }

        try{
            $response = [
                'status' => 'success',
                'message' => 'ดึงตำแหน่งสำเร็จ',
                'position' => $position
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


    public function updatePosition(Request $request, $id) 
    {
        $position = Position::find($id);

        if (!$position) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบตำแหน่งนี้ในระบบ'
            ];   
            return response()->json($response, 404);
        }

        $request->validate(
            [
                'position_desc_th' => 'required',
                'position_desc_en' => 'required',
                'position_status' => 'required',
                'position_department_id' => 'required',
                'position_job_qualification_id' => 'required',
                'position_mode_id' => 'required',
                'responsibilities_th' => 'required',
                'responsibilities_en' => 'required',
                'knowledge_skills_th' => 'required',
                'knowledge_skills_en' => 'required',
                'require_feature_th' => 'required',
                'require_feature_en' => 'required',
                'salary' => 'required',
                'vacancies' => 'required|integer',
            ],
            [
                'position_desc_th.required' => 'กรุณากรอกตำแหน่งภาษาไทย',
                'position_desc_th.unique' => 'ตำแหน่งนี้มีอยู่ในระบบแล้ว',
                'position_desc_en.required' => 'กรุณากรอกตำแหน่งภาษาอังกฤษ',
                'position_desc_en.unique' => 'ตำแหน่งนี้มีอยู่ในระบบแล้ว',
                'position_status.required' => 'กรุณาเลือกสถานะการรับสมัคร',
                'position_department_id.required' => 'กรุณาเลือกแผนก',
                'position_job_qualification_id.required' => 'กรุณาเลือกคุณสมบัติงาน',
                'position_mode_id.required' => 'กรุณาเลือกรูปแบบงาน',
                'responsibilities_th.required' => 'กรุณากรอกหน้าที่ความรับผิดชอบภาษาไทย',
                'responsibilities_en.required' => 'กรุณากรอกหน้าที่ความรับผิดชอบภาษาอังกฤษ',
                'knowledge_skills_th.required' => 'กรุณากรอกความรู้ความสามารถภาษาไทย',
                'knowledge_skills_en.required' => 'กรุณากรอกความรู้ความสามารถภาษาอังกฤษ',
                'require_feature_th.required' => 'กรุณากรอกคุณสมบัติที่ต้องการภาษาไทย',
                'require_feature_en.required' => 'กรุณากรอกคุณสมบัติที่ต้องการภาษาอังกฤษ',
                'salary.required' => 'กรุณากรอกเงินเดือน',
                'vacancies.required' => 'กรุณากรอกจำนวนตำแหน่งงานว่าง',
                'vacancies.integer' => 'กรุณากรอกจำนวนตำแหน่งงานว่างเป็นตัวเลขจำนวนเต็ม',
            ]
        );

        $data = $request->all();

    
        try {
            $position->update($data);
    
            $response = [
                'status' => 'success',
                'message' => 'ตำแหน่งนี้ถูกอัพเดตเรียบร้อยแล้ว',
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

    public function deletePosition($id) 
    {
        $position = Position::find($id);

        if (!$position) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบตำแหน่งนี้ในระบบ'
            ];   
            return response()->json($response, 404);
        }


        try {
            $position->delete();
            
            $response = [
                'status' => 'success',
                'message' => 'ตำแหน่งนี้ถูกลบเรียบร้อยแล้ว',
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
