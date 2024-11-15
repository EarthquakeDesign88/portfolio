<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash; 
class RegisterController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    
    public function registerPerform(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'username' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|max:30',
        ], [
            'name.required' => 'กรุณากรอกชื่อ',
            'name.string' => 'กรุณากรอกชื่อให้ถูกต้อง',
            'username.required' => 'กรุณากรอกชื่อบัญชีผู้ใช้',
            'username.string' => 'กรุณากรอกชื่อบัญชีผู้ใช้ให้ถูกต้อง',
            'email.required' => 'กรุณากรอกอีเมล',
            'email.email' => 'กรุณากรอกรูปแบบอีเมลให้ถูกต้อง',
            'email.unique' => 'อีเมลนี้มีผู้ใช้งานแล้ว',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
            'password.min' => 'รหัสผ่านต้องมีอย่างน้อย 8 ตัว',
            'password.max' => 'รหัสผ่านต้องไม่เกิน 30 ตัว',
        ]);

        try {
            $user = new User();

            $user->name = $request->name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
    
            $user->save();
            $response = [
                'status' => 'success',
                'message' => 'สร้างบัญชีเสร็จเรียบร้อยแล้ว'
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
