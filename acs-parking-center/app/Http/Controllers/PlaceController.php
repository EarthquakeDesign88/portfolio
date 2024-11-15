<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Place;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Validation\Rule;

class PlaceController extends Controller
{
    public function index(Request $request) 
    { 
        if ($request->ajax()) {
            return DataTables::of(Place::query()->orderBy('id', 'DESC'))
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('place.place-list');
    }

    public function getPlaces()
    {
        $places = Place::orderBy('id', 'DESC')->get();
        $countPlaces = $places->count();

        return response()->json([
            'places' => $places,
            'countPlaces' => $countPlaces
        ]);
    }

    
    public function insertPlace(Request $request) 
    {
        $request->validate(
            [
                'place_name' => 'required|unique:places,place_name'
            ],
            [
                'place_name.required' => 'กรุณากรอกชื่อสถานที่',
                'place_name.unique' => 'ชื่อสถานที่นี้มีอยู่ในระบบแล้ว'
            ]
        );

        $data = $request->all();

        try {
            Place::create($data);
    
            $response = [
                'status' => 'success',
                'message' => 'สถานที่ถูกบันทึกเรียบร้อยแล้ว'
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

    public function getPlaceById($id) 
    {
        $place = Place::find($id);

        if (!$place) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบชื่อสถานที่นี้ในระบบ'
            ];   
            return response()->json($response, 404);
        }

        try{
            $response = [
                'status' => 'success',
                'message' => 'ดึงชื่อสถานที่สำเร็จ',
                'place' => $place
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


    public function updatePlace(Request $request, $id) 
    {
        $place = Place::find($id);

        if (!$place) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบชื่อสถานที่นี้ในระบบ'
            ];   
            return response()->json($response, 404);
        }

        $request->validate(
            [
                'place_name' => 'required|unique:places,place_name'
            ],
            [
                'place_name.required' => 'กรุณากรอกชื่อสถานที่',
                'place_name.unique' => 'ชื่อสถานที่นี้มีอยู่ในระบบแล้ว',
            ]
        );

        $data = $request->all();
    
        try {
            $place->update($data);
    
            $response = [
                'status' => 'success',
                'message' => 'ชื่อสถานที่นี้ถูกอัพเดตเรียบร้อยแล้ว',
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

    public function deletePlace($id) 
    {
        $place = Place::find($id);

        if (!$place) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบบริการนี้ในระบบ'
            ];   
            return response()->json($response, 404);
        }


        try {
            $place->delete();
            
            $response = [
                'status' => 'success',
                'message' => 'ชื่อสถานที่ถูกลบเรียบร้อยแล้ว',
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
