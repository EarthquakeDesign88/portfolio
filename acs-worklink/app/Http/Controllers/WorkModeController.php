<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkMode;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Validation\Rule;

class WorkModeController extends Controller
{
    public function index(Request $request)
    {
        return view('work.work_modes');
    }

    public function getWorkmodes()
    {
        $workModes = WorkMode::orderBy('mode_id', 'DESC')->get();
        $countWorkModes = $workModes->count();

        return response()->json([
            'workModes' => $workModes,
            'countWorkModes' => $countWorkModes
        ]);
    }

    
    public function insertWorkMode(Request $request) 
    {
        $request->validate(
            [
                'mode_desc_th' => 'required|unique:work_modes,mode_desc_th',
                'mode_desc_en' => 'required|unique:work_modes,mode_desc_en',
            ],
            [
                'mode_desc_th.required' => 'กรุณากรอกรูปแบบงานภาษาไทย',
                'mode_desc_th.unique' => 'รูปแบบงานนี้มีอยู่ในระบบแล้ว',
                'mode_desc_en.required' => 'กรุณากรอกรูปแบบงานภาษาอังกฤษ',
                'mode_desc_en.unique' => 'รูปแบบงานนี้มีอยู่ในระบบแล้ว',
            ]
        );

        $data = $request->all();

        try {
            Workmode::create($data);
    
            $response = [
                'status' => 'success',
                'message' => 'รูปแบบงานนี้ถูกบันทึกเรียบร้อยแล้ว'
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

    public function getWorkModeById($id) 
    {
        $workMode = WorkMode::find($id);

        if (!$workMode) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบรูปแบบงานนี้ในระบบ'
            ];   
            return response()->json($response, 404);
        }

        try{
            $response = [
                'status' => 'success',
                'message' => 'ดึงรูปแบบงานสำเร็จ',
                'workMode' => $workMode
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


    public function updateWorkMode(Request $request, $id) 
    {
        $workMode = WorkMode::find($id);

        if (!$workMode) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบรูปแบบงานนี้ในระบบ'
            ];   
            return response()->json($response, 404);
        }

        $request->validate(
            [
                'mode_desc_th' => 'required',
                'mode_desc_en' => 'required',
            ],
            [
                'mode_desc_th.required' => 'กรุณากรอกรูปแบบงานภาษาไทย',
                'mode_desc_en.required' => 'กรุณากรอกรูปแบบงานภาษาอังกฤษ',
            ]
        );

        $data = $request->all();
    
        try {
            $workMode->update($data);
    
            $response = [
                'status' => 'success',
                'message' => 'รูปแบบงานนี้ถูกอัพเดตเรียบร้อยแล้ว',
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

    public function deleteWorkMode($id) 
    {
        $workMode = WorkMode::find($id);

        if (!$workMode) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบรูปแบบงานนี้ในระบบ'
            ];   
            return response()->json($response, 404);
        }


        try {
            $workMode->delete();
            
            $response = [
                'status' => 'success',
                'message' => 'รูปแบบงานนี้ถูกลบเรียบร้อยแล้ว',
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
