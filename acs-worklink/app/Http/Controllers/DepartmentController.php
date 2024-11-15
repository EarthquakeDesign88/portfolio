<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        return view('work.departments');
    }

    public function getDepartments()
    {
        $departments = Department::orderBy('department_id', 'DESC')->get();
        $countDepartments = $departments->count();

        return response()->json([
            'departments' => $departments,
            'countDepartments' => $countDepartments
        ]);
    }

    
    public function insertDepartment(Request $request) 
    {
        $request->validate(
            [
                'department_desc_th' => 'required|unique:departments,department_desc_th',
                'department_desc_en' => 'required|unique:departments,department_desc_en',
            ],
            [
                'department_desc_th.required' => 'กรุณากรอกชื่อแผนกภาษาไทย',
                'department_desc_th.unique' => 'ชื่อแผนกนี้มีอยู่ในระบบแล้ว',
                'department_desc_en.required' => 'กรุณากรอกชื่อแผนกภาษาอังกฤษ',
                'department_desc_en.unique' => 'ชื่อแผนกนี้มีอยู่ในระบบแล้ว',
            ]
        );

        $data = $request->all();

        try {
            Department::create($data);
    
            $response = [
                'status' => 'success',
                'message' => 'แผนกนี้ถูกบันทึกเรียบร้อยแล้ว'
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

    public function getDepartmentById($id) 
    {
        $department = Department::find($id);

        if (!$department) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบชื่อแผนกนี้ในระบบ'
            ];   
            return response()->json($response, 404);
        }

        try{
            $response = [
                'status' => 'success',
                'message' => 'ดึงแผนกสำเร็จ',
                'department' => $department
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


    public function updateDepartment(Request $request, $id) 
    {
        $department = Department::find($id);

        if (!$department) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบแผนกนี้ในระบบ'
            ];   
            return response()->json($response, 404);
        }

        $request->validate(
            [
                'department_desc_th' => 'required',
                'department_desc_en' => 'required',
            ],
            [
                'department_desc_th.required' => 'กรุณากรอกชื่อแผนกภาษาไทย',
                'department_desc_en.required' => 'กรุณากรอกชื่อแผนกภาษาอังกฤษ',
            ]
        );

        $data = $request->all();
    
        try {
            $department->update($data);
    
            $response = [
                'status' => 'success',
                'message' => 'แผนกนี้ถูกอัพเดตเรียบร้อยแล้ว',
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

    public function deleteDepartment($id) 
    {
        $department = Department::find($id);

        if (!$department) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบแผนกนี้ในระบบ'
            ];   
            return response()->json($response, 404);
        }


        try {
            $department->delete();
            
            $response = [
                'status' => 'success',
                'message' => 'แผนกนี้ถูกลบเรียบร้อยแล้ว',
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
