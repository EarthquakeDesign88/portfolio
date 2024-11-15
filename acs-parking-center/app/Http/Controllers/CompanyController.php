<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    public function index(Request $request) 
    { 
        if ($request->ajax()) {
            return DataTables::of(Company::query()->orderBy('id', 'DESC'))
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('company.company-list');
    }

    public function getCompanies()
    {
        $companies = Company::orderBy('id', 'DESC')->get();
        $countCompanies = $companies->count();

        return response()->json([
            'companies' => $companies,
            'countCompanies' => $countCompanies
        ]);
    }

    public function insertCompany(Request $request) 
    {
        $request->validate(
            [
                'company_name' => 'required|unique:companies,company_name',
                'company_phone' => 'required',
                'company_address' => 'required'
            ],
            [
                'company_name.required' => 'กรุณากรอกชื่อบริษัท',
                'company_name.unique' => 'ชื่อบริษัทนี้มีอยู่ในระบบแล้ว',
                'company_phone.required' => 'กรุณากรอกเบอร์โทรศัพท์',
                'company_address.required' => 'กรุณากรอกที่อยู่บริษัท',
            ]
        );

        $data = $request->all();
        $data['company_status_id'] = '1';

        try {
            Company::create($data);
    
            $response = [
                'status' => 'success',
                'message' => 'บริษัทถูกบันทึกเรียบร้อยแล้ว'
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

    public function getCompanyById($id) 
    {
        $company = Company::find($id);

        if (!$company) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบบริษัทนี้ในระบบ'
            ];   
            return response()->json($response, 404);
        }

        try{
            $response = [
                'status' => 'success',
                'message' => 'ดึงบริษัทสำเร็จ',
                'company' => $company
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


    public function updateCompany(Request $request, $id) 
    {
        $company = Company::find($id);

        if (!$company) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบบริษัทนี้ในระบบ'
            ];   
            return response()->json($response, 404);
        }

        $request->validate(
            [
                'company_name' => 'required|' . Rule::unique('companies', 'company_name')->ignore($company->id),
                'company_phone' => 'required',
                'company_address' => 'required'
            ],
            [
                'company_name.required' => 'กรุณากรอกชื่อบริษัท',
                'company_name.unique' => 'ชื่อบริษัทนี้มีอยู่ในระบบแล้ว',
                'company_phone.required' => 'กรุณากรอกเบอร์โทรศัพท์',
                'company_address.required' => 'กรุณากรอกที่อยู่บริษัท',
            ]
        );

        $data = $request->all();
    
        try {
            $company->update($data);
    
            $response = [
                'status' => 'success',
                'message' => 'บริษัทนี้ถูกอัพเดตเรียบร้อยแล้ว',
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

    public function deleteCompany($id) 
    {
        $company = Company::find($id);

        if (!$company) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบบริษัทนี้ในระบบ'
            ];   
            return response()->json($response, 404);
        }


        try {
            $company->delete();
            
            $response = [
                'status' => 'success',
                'message' => 'บริษัทถูกลบเรียบร้อยแล้ว',
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
