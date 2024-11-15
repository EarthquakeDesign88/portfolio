<?php

namespace App\Http\Controllers;

use App\Models\FormCreate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\AppMailable;
use Illuminate\Support\Facades\Mail;

class FormCreateController extends Controller
{
    public function create(Request $request)
    {
        $portfolioFilename = null;
        try {
            $validatedData = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'mobile' => 'required|string|size:10',
                'salary' => 'required|numeric|min:0',
                'cv' => 'required|file|mimes:png,jpg,jpeg|max:2048',
            ], [
                'first_name.required' => 'กรุณากรอกชื่อ',
                'last_name.required' => 'กรุณากรอกนามสกุล',
                'email.required' => 'กรุณากรอกอีเมล',
                'mobile.required' => 'กรุณากรอกเบอร์โทรศัพท์',
                'salary.required' => 'กรุณากรอกเงินเดือน',
                'salary.numeric' => 'กรุณากรอกเป็นตัวเลข',
                'cv.required' => 'กรุณาแนบไฟล์ cv',
                'cv.file' => 'กรุณาอัปโหลดไฟล์ที่ถูกต้อง',
                'cv.mimes' => 'ไฟล์ต้องเป็นรูปภาพเท่านั้น (png, jpg, jpeg)',
                'cv.max' => 'ขนาดไฟล์ต้องไม่เกิน 2MB',
            ]);
            
            if ($request->hasFile('cv') && $request->file('cv')->isValid() && $request->file('cv')) {
                $cvFilename = time() . '_cv.' . $request->file('cv')->getClientOriginalExtension();
                $request->file('cv')->storeAs('uploads/cvs', $cvFilename);
            } else {
                return response()->json(['message' => 'ไฟล์ CV อัพโหลดไม่สำเร็จ',], 400);
            }

            if ($request->hasFile('portfolio') && $request->file('portfolio')->isValid() && $request->file('portfolio')) {
                $portfolioFilename = time() . '_portfolio.' . $request->file('portfolio')->getClientOriginalExtension();
                $request->file('portfolio')->storeAs('uploads/portfolios', $portfolioFilename);
            }

            $data = FormCreate::create([
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'mobile' => $validatedData['mobile'],
                'salary' => (float)$validatedData['salary'],
                'cv' => $cvFilename,
                'portfolio' => $portfolioFilename,
                'mail_status' => '0'
            ]);

            Mail::to('arunchaiseri.dev@gmail.com')->send(new AppMailable($data));

            return response()->json(["message" => "ส่งแบบฟอร์มสำเร็จ", "data" => $data], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'กรุณาส่งข้อมูลให้ถูกต้อง', 'err'=> $e->validator->errors()], 422);
            //
        }
    }

    public function jobs()
    {
        $results = DB::table('positions as p')
            ->join('departments as d', 'p.position_department_id', '=', 'd.department_id')
            ->join('job_qualifications as jq', 'p.position_job_qualification_id', '=', 'jq.job_qualification_id')
            ->join('work_modes as wm', 'p.position_mode_id', '=', 'wm.mode_id')
            ->select('p.position_id', 'p.position_desc_th', 'p.responsibilities_th')
            ->get();

        return response()->json(['data' => $results], 200);
    }

    public function job($id)
    {
        $results = DB::table('positions as p')
            ->join('departments as d', 'p.position_department_id', '=', 'd.department_id')
            ->join('job_qualifications as jq', 'p.position_job_qualification_id', '=', 'jq.job_qualification_id')
            ->join('work_modes as wm', 'p.position_mode_id', '=', 'wm.mode_id')
            ->select('p.*', 'd.*', 'jq.*', 'wm.*')
            ->where('p.position_id', '=', $id)
            ->get();

        return response()->json(['data' => $results], 200);
    }
}
