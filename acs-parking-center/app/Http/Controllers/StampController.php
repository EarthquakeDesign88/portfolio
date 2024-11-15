<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stamp;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Validation\Rule;
use Endroid\QrCode\QrCode;

class StampController extends Controller
{
    public function index(Request $request) 
    { 
        if ($request->ajax()) {
            return DataTables::of(Stamp::query()->orderBy('id', 'DESC'))
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('stamp.stamp-list');
    }

    public function getStamps()
    {
        $stamps = Stamp::orderBy('id', 'DESC')->get();
        $countStamps = $stamps->count();

        return response()->json([
            'stamps' => $stamps,
            'countStamps' => $countStamps
        ]);
    }

    
    public function insertStamp(Request $request) 
    {
        $request->validate(
            [
                'stamp_code' => 'required|unique:stamps,stamp_code',
                'stamp_condition' => 'required'
            ],
            [
                'stamp_code.required' => 'กรุณากรอกรหัสตราประทับ',
                'stamp_code.unique' => 'รหัสตราประทับนี้มีอยู่ในระบบแล้ว',
                'stamp_condition.required' => 'กรุณากรอกเงื่อนไขรหัสตราประทับ'
            ]
        );

        $data = $request->all();

        try {
            Stamp::create($data);
    
            $response = [
                'status' => 'success',
                'message' => 'ตราประทับนี้ถูกบันทึกเรียบร้อยแล้ว'
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

    public function getStampById($id) 
    {
        $stamp = Stamp::find($id);

        if (!$stamp) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบตราประทับนี้ในระบบ'
            ];   
            return response()->json($response, 404);
        }

        try{
            $response = [
                'status' => 'success',
                'message' => 'ดึงตราประทับสำเร็จ',
                'stamp' => $stamp
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


    public function updateStamp(Request $request, $id) 
    {
        $stamp = Stamp::find($id);

        if (!$stamp) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบตราประทับนี้ในระบบ'
            ];   
            return response()->json($response, 404);
        }

        $request->validate(
            [
                'stamp_code' => 'required|' . Rule::unique('stamps', 'stamp_code')->ignore($stamp->id),
                'stamp_condition' => 'required'
            ],
            [
                'stamp_code.required' => 'กรุณากรอกรหัสตราประทับ',
                'stamp_code.unique' => 'รหัสตราประทับนี้มีอยู่ในระบบแล้ว',
                'stamp_condition.required' => 'รหัสตราประทับนี้มีอยู่ในระบบแล้ว'
            ]
        );

        $data = $request->all();
    
        try {
            $stamp->update($data);
    
            $response = [
                'status' => 'success',
                'message' => 'รหัสตราประทับนี้ถูกอัพเดตเรียบร้อยแล้ว',
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

    public function deleteStamp($id) 
    {
        $stamp = Stamp::find($id);

        if (!$stamp) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบตราประทับนี้ในระบบ'
            ];   
            return response()->json($response, 404);
        }


        try {
            $stamp->delete();
            
            $response = [
                'status' => 'success',
                'message' => 'ตราประทับนี้ถูกลบเรียบร้อยแล้ว',
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
