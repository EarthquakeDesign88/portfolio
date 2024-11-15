<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Floor;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Validation\Rule;

class FloorController extends Controller
{
    public function index(Request $request) 
    { 
        if ($request->ajax()) {
            return DataTables::of(Floor::query()->orderBy('id', 'DESC'))
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('company.floor');
    }

    public function getFloors()
    {
        $floors = Floor::orderBy('id', 'DESC')->get();
        $countFloors = $floors->count();

        return response()->json([
            'floors' => $floors,
            'countFloors' => $countFloors
        ]);
    }

    public function insertFloor(Request $request) 
    {
        $request->validate(
            [
                'floor_number' => 'required|unique:floors,floor_number'
            ],
            [
                'floor_number.required' => 'กรุณากรอกชั้น',
                'floor_number.unique' => 'ชั้นนี้มีอยู่ในระบบแล้ว'
            ]
        );

        $data = $request->all();

        try {
            Floor::create($data);
    
            $response = [
                'status' => 'success',
                'message' => 'ชั้นถูกบันทึกเรียบร้อยแล้ว'
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

    public function getFloorById($id) 
    {
        $floor = Floor::find($id);

        if (!$floor) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบชั้นนี้ในระบบ'
            ];   
            return response()->json($response, 404);
        }

        try{
            $response = [
                'status' => 'success',
                'message' => 'ดึงชั้นสำเร็จ',
                'floor' => $floor
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


    public function updateFloor(Request $request, $id) 
    {
        $floor = Floor::find($id);

        if (!$floor) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบชั้นนี้ในระบบ'
            ];   
            return response()->json($response, 404);
        }

        $request->validate(
            [
                'floor_number' => 'required|unique:floors,floor_number'
            ],
            [
                'floor_number.required' => 'กรุณากรอกชั้น',
                'floor_number.unique' => 'ชั้นนี้มีอยู่ในระบบแล้ว'
            ]
        );

        $data = $request->all();
    
        try {
            $floor->update($data);
    
            $response = [
                'status' => 'success',
                'message' => 'ชั้นนี้ถูกอัพเดตเรียบร้อยแล้ว',
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

    public function deleteFloor($id) 
    {
        $floor = Floor::find($id);

        if (!$floor) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบชั้นนี้ในระบบ'
            ];   
            return response()->json($response, 404);
        }


        try {
            $floor->delete();
            
            $response = [
                'status' => 'success',
                'message' => 'ชั้นถูกลบเรียบร้อยแล้ว',
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
