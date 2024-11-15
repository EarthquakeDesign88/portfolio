<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MemberType;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Validation\Rule;

class MemberTypeController extends Controller
{
    public function index(Request $request) 
    { 
        if ($request->ajax()) {
            return DataTables::of(MemberType::query()->orderBy('id', 'DESC'))
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('member.member-type');
    }

    public function getMemberTypes()
    {
        $memberTypes = MemberType::orderBy('id', 'DESC')->get();
        $countMemberTypes = $memberTypes->count();

        return response()->json([
            'memberTypes' => $memberTypes,
            'countMemberTypes' => $countMemberTypes
        ]);
    }

    public function insertMemberType(Request $request) 
    {
        $request->validate(
            [
                'member_type' => 'required|unique:member_types,member_type'
            ],
            [
                'member_type.required' => 'กรุณากรอกประเภทสมาชิก',
                'member_type.unique' => 'ประเภทสมาชิกนี้มีอยู่ในระบบแล้ว'
            ]
        );

        $data = $request->all();

        try {
            MemberType::create($data);
    
            $response = [
                'status' => 'success',
                'message' => 'ประเภทสมาชิกถูกบันทึกเรียบร้อยแล้ว'
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

    public function getMemberTypeById($id) 
    {
        $memberType = MemberType::find($id);

        if (!$memberType) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบประเภทสมาชิกนี้ในระบบ'
            ];   
            return response()->json($response, 404);
        }

        try{
            $response = [
                'status' => 'success',
                'message' => 'ดึงประเภทสมาชิกสำเร็จ',
                'memberType' => $memberType
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


    public function updateMemberType(Request $request, $id) 
    {
        $memberType = MemberType::find($id);

        if (!$memberType) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบประเภทสมาชิกนี้ในระบบ'
            ];   
            return response()->json($response, 404);
        }

        $request->validate(
            [
                'member_type' => 'required|unique:member_types,member_type'
            ],
            [
                'member_type.required' => 'กรุณากรอกประเภทสมาชิก',
                'member_type.unique' => 'ประเภทสมาชิกนี้มีอยู่ในระบบแล้ว'
            ]
        );

        $data = $request->all();
    
        try {
            $memberType->update($data);
    
            $response = [
                'status' => 'success',
                'message' => 'ประเภทสมาชิกนี้ถูกอัพเดตเรียบร้อยแล้ว',
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

    public function deleteMemberType($id) 
    {
        $memberType = MemberType::find($id);

        if (!$memberType) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบประเภทสมาชิกนี้ในระบบ'
            ];   
            return response()->json($response, 404);
        }


        try {
            $memberType->delete();
            
            $response = [
                'status' => 'success',
                'message' => 'ประเภทสมาชิกถูกลบเรียบร้อยแล้ว',
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
