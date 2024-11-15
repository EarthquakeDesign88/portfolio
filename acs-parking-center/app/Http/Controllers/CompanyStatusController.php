<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyStatus;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Validation\Rule;

class CompanyStatusController extends Controller
{
    public function index(Request $request) 
    { 
        if ($request->ajax()) {
            return DataTables::of(CompanyStatus::query()->orderBy('id', 'DESC'))
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('company.company-status');
    }

    public function getCompanyStatus()
    {
        $companyStatus = CompanyStatus::orderBy('id', 'DESC')->get();
        $countCompanyStatus = $companyStatus->count();

        return response()->json([
            'companyStatus' => $companyStatus,
            'countCompanyStatus' => $countCompanyStatus
        ]);
    }

    
    public function insertCompanyStatus(Request $request) 
    {
        $request->validate(
            [
                'status' => 'required|unique:company_status,status'
            ],
            [
                'status.required' => 'กรุณากรอกสถานะบริษัท',
                'status.unique' => 'สถานะบริษัทนี้มีอยู่ในระบบแล้ว'
            ]
        );

        $data = $request->all();

        try {
            CompanyStatus::create($data);
    
            $response = [
                'status' => 'success',
                'message' => 'สถานะบริษัทถูกบันทึกเรียบร้อยแล้ว'
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

    public function getCompanyStatusById($id) 
    {
        $companyStatus = CompanyStatus::find($id);

        if (!$companyStatus) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบสถานะบริษัทนี้ในระบบ'
            ];   
            return response()->json($response, 404);
        }

        try{
            $response = [
                'status' => 'success',
                'message' => 'ดึงสถานะบริษัทสำเร็จ',
                'companyStatus' => $companyStatus
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


    public function updateCompanyStatus(Request $request, $id) 
    {
        $companyStatus = CompanyStatus::find($id);

        if (!$companyStatus) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบสถานะบริษัทนี้ในระบบ'
            ];   
            return response()->json($response, 404);
        }

        $request->validate(
            [
                'status' => 'required|unique:company_status,status'
            ],
            [
                'status.required' => 'กรุณากรอกสถานะบริษัท',
                'status.unique' => 'สถานะบริษัทนี้มีอยู่ในระบบแล้ว'
            ]
        );

        $data = $request->all();
    
        try {
            $companyStatus->update($data);
    
            $response = [
                'status' => 'success',
                'message' => 'ชื่อสถานะบริษัทนี้ถูกอัพเดตเรียบร้อยแล้ว',
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

    public function deleteCompanyStatus($id) 
    {
        $companyStatus = CompanyStatus::find($id);

        if (!$companyStatus) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบสถานะบริษัทนี้ในระบบ'
            ];   
            return response()->json($response, 404);
        }


        try {
            $companyStatus->delete();
            
            $response = [
                'status' => 'success',
                'message' => 'สถานะบริษัทถูกลบเรียบร้อยแล้ว',
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
