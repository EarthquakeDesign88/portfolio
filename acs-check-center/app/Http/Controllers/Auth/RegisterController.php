<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // $request->validate([
        //     'username' => 'required|unique:users',
        //     'email' => 'required|email|unique:users',
        //     'password' => 'required|min:6|max:20|regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*?&]{6,}$/',
        //     'stamp_code' => 'required',
        //     'stamp_condition' => 'required',
        //     'company_name' => 'required',
        // ],
        // [
        //     'username.required' => 'กรุณากรอกชื่อบัญชีผู้ใช้งาน',
        //     'username.unique' => 'ชื่อบัญชีผู้ใช้งานนี้มีอยู่ในระบบแล้ว',
        //     'password.required' => 'กรุณากรอกรหัสผ่าน',
        //     'password.min' => 'รหัสผ่านต้องมีอย่างน้อย 6 ตัว',
        //     'password.max' => 'รหัสผ่านต้องมีไม่เกิน 20 ตัว',
        //     'password.regex' => ' รหัสผ่านต้องมีอย่างน้อย 6 ตัว, สามารถประกอบไปด้วยตัวอักษรภาษาอังกฤษ, เลข, หรือสัญลักษณ์ @$!%*?&',
        //     'stamp_code.required' => 'กรุณาเลือกรหัสตราประทับ',
        //     'stamp_condition.required' => 'กรุณาเลือกเงื่อนไขตราประทับ',
        //     'company_name.required' => 'กรุณาเลือกบริษัท',
        // ]
        // );

       
        try {
            // Check if the username already exists
            $existingUser = User::where('user_name', $request->user_name)->first();
            if ($existingUser) {
                return response()->json(['message' => 'ชื่อผู้ใช้งานนี้มีอยู่แล้ว'], 400);
            }

            $user = User::create([
                'user_name' => $request->user_name,
                'email' => $request->email,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
            ]);
    
            return response()->json(['message' => 'ลงทะเบียนผู้ใช้งานสำเร็จ', 'user' => $user], 201);
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'An error occurred while processing the request.',
                'error' => $e->getMessage()
            ];
    
            return response()->json($response, 500);   
        }
    }
}
