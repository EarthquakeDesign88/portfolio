<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanySetup;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Validation\Rule;

class CompanySetupController extends Controller
{
    public function index(Request $request) 
    {
        if ($request->ajax()) {
            return DataTables::of(DB::table('company_setup')
            ->join('companies', 'company_setup.company_id', '=', 'companies.id')
            ->join('stamps', 'company_setup.stamp_id', '=', 'stamps.id')
            ->join('floors', 'company_setup.floor_id', '=', 'floors.id')
            ->join('places', 'company_setup.place_id', '=', 'places.id')
            ->select('company_setup.id', 'companies.company_name', 'companies.company_phone', 'companies.company_address',
                'stamps.stamp_code', 'stamps.stamp_condition', 
                'floors.floor_number',
                'places.place_name',
                'company_setup.total_quota', 'company_setup.remaining_quota')
            ->orderBy('company_setup.id', 'desc')
            ->get())
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
        }
    
        return view('company.company-setup');
    }
    
    public function getCompanySetup()
    {
        $companySetups = DB::table('company_setup')
        ->join('companies', 'company_setup.company_id', '=', 'companies.id')
        ->join('stamps', 'company_setup.stamp_id', '=', 'stamps.id')
        ->join('floors', 'company_setup.floor_id', '=', 'floors.id')
        ->join('places', 'company_setup.place_id', '=', 'places.id')
        ->select('company_setup.id', 'companies.company_name', 'companies.company_phone', 'companies.company_address',
            'stamps.stamp_code', 'stamps.stamp_condition', 
            'floors.floor_number',
            'places.place_name',
            'company_setup.total_quota', 'company_setup.remaining_quota')
        ->orderBy('company_setup.id', 'desc')
        ->get(); 
    
        $countCompanySetups = $companySetups->count();
    
        return response()->json([
            'companySetups' => $companySetups,
            'countCompanySetups' => $countCompanySetups
        ]);
    }

    public function insertCompanySetup(Request $request) 
    {
        $request->validate(
            [
                'company_id' => 'required',
                'stamp_id' => 'required',
                'floor_id' => 'required',
                'place_id' => 'required',
                'total_quota' => 'required',
            ],
            [
                'company_id.required' => 'กรุณาเลือกบริษัท',
                'stamp_id.required' => 'กรุณาเลือกรหัสตราประทับ',
                'floor_id.required' => 'กรุณาเลือกชั้น',
                'place_id.required' => 'กรุณาเลือกสถานที่',
                'total_quota.required' => 'กรุณากรอกโควต้าบริษัท',
            ]
        );

        $data = $request->all();
        $data['remaining_quota'] = $data['total_quota'];

        try {
            CompanySetup::create($data);
    
            $response = [
                'status' => 'success',
                'message' => 'ตั้งค่าบริษัทเรียบร้อยแล้ว'
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

    public function getCompanySetupById($id) 
    {
        try {
            $companySetup = CompanySetup::query()
                ->join('companies', 'company_setup.company_id', '=', 'companies.id')
                ->join('stamps', 'company_setup.stamp_id', '=', 'stamps.id')
                ->join('floors', 'company_setup.floor_id', '=', 'floors.id')
                ->join('places', 'company_setup.place_id', '=', 'places.id')
                ->select('company_setup.*', 'companies.company_name', 'companies.company_phone', 'companies.company_address',
                    'stamps.stamp_code', 'stamps.stamp_condition', 
                    'floors.floor_number',
                    'places.place_name',
                    'company_setup.total_quota', 'company_setup.remaining_quota')
                ->orderBy('company_setup.id', 'desc')
                ->findOrFail($id);
    
            $response = [
                'status' => 'success',
                'message' => 'ดึงข้อมูลตั้งค่าบริษัทสำเร็จ',
                'companySetup' => $companySetup
            ];
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบข้อมูลตั้งค่าบริษัทนี้ในระบบ',
                'error' => $e->getMessage()
            ];
            return response()->json($response, 404);
        }
    
        return response()->json($response);
    }


    public function updateCompanySetup(Request $request, $id) 
    {
        $companySetup = CompanySetup::find($id);

        if (!$companySetup) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบข้อมูลตั้งค่าบริษัทนี้ในระบบ'
            ];   
            return response()->json($response, 404);
        }

        $request->validate(
            [
                'company_id' => 'required',
                'stamp_id' => 'required',
                'floor_id' => 'required',
                'place_id' => 'required',
                'total_quota' => 'required',
            ],
            [
                'company_id.required' => 'กรุณาเลือกบริษัท',
                'stamp_id.required' => 'กรุณาเลือกรหัสตราประทับ',
                'floor_id.required' => 'กรุณาเลือกชั้น',
                'place_id.required' => 'กรุณาเลือกสถานที่',
                'total_quota.required' => 'กรุณากรอกโควต้าบริษัท',
            ]
        );

        $data = $request->all();
    
        try {
            $companySetup->update($data);
    
            $response = [
                'status' => 'success',
                'message' => 'อัพเดทข้อมูลตั้งค่าบริษัทใหม่เรียบร้อยแล้ว',
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

    public function deleteCompanySetup($id) 
    {
        $companySetup = CompanySetup::find($id);

        if (!$companySetup) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบบริษัทนี้ในระบบ'
            ];   
            return response()->json($response, 404);
        }


        try {
            $companySetup->delete();
            
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

    public function reQuotaCompany($id) 
    {
        $companySetup = CompanySetup::find($id);

        if (!$companySetup) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบบริษัทนี้ในระบบ'
            ];   
            return response()->json($response, 404);
        }


        try {
            $companySetup->update([
                'remaining_quota' => $companySetup->total_quota
            ]);
            
            $response = [
                'status' => 'success',
                'message' => 'อัพเดทโควต้าตราประทับเรียบร้อยแล้ว',
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
