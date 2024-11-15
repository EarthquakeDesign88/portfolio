<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'user_name' => 'required',
            'password' => 'required',
        ],
        [
            'user_name.required' => "กรุณากรอกบัญชีผู้ใช้",
            'password.required' => "กรุณากรอกรหัสผ่าน",
        ]);

      
        if (Auth::attempt(['user_name' => $request->user_name, 'password' => $request->password])) {
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Logged successfully'
                ]
            );
        }
        else {
            return response()->json([
                'status' => 'error',
                'message' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง'
            ], 401); 
        }

    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('login');
    }

    public function users()
    {
        return view('auth.users');
    }

    public function getUsers() {
        $users = User::orderBy('first_name', 'ASC')->get();
    
        return response()->json([
            'users' => $users,
            'countUsers' => $users->count()
        ]);
    }


    public function insertUser(Request $request) 
    {
        $request->validate(
            [
                'user_name' => 'required|unique:users,user_name',
                'password' => [
                    'required',
                    'min:8',
                    'regex:/[a-z]/',
                    'regex:/[A-Z]/',
                    'regex:/[0-9]/', 
                    'regex:/[@$!%*#?&]/', 
                ],
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|unique:users,email|email',
            ],
            [
                'user_name.required' => 'กรุณากรอกชื่อบัญชีผู้ใช้',
                'user_name.unique' => 'ชื่อบัญชีผู้ใช้นี้มีอยู่ในระบบแล้ว',
                'password.required' => 'กรุณากรอกรหัสผ่าน',
                'password.min' => 'รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร',
                'password.regex' => 'รหัสผ่านต้องประกอบด้วยตัวอักษรตัวเล็ก, ตัวใหญ่, ตัวเลข และอักขระพิเศษ',
                'first_name.required' => 'กรุณากรอกชื่อจริง',
                'last_name.required' => 'กรุณากรอกนามสกุล',
                'email.required' => 'กรุณากรอกอีเมล',
                'email.unique' => 'อีเมลนี้มีอยู่ในระบบแล้ว',
                'email.email' => 'กรุณากรอกรูปแบบอีเมลที่ถูกต้อง',
            ]
        );

        $data = $request->all();
        $data['password'] = bcrypt($data['password']); 

        try {
            User::create($data);
    
            $response = [
                'status' => 'success',
                'message' => 'บัญชีผู้ใช้นี้ถูกบันทึกเรียบร้อยแล้ว'
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

    public function getUserById($id) 
    {
        $user = User::find($id);

        if (!$user) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบบัญชีผู้ใช้นี้ในระบบ'
            ];   
            return response()->json($response, 404);
        }

        try{
            $response = [
                'status' => 'success',
                'message' => 'ดึงบัญชีผู้ใช้สำเร็จ',
                'user' => $user
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


    public function updateUser(Request $request, $id) 
    {
        $user = User::find($id);

        if (!$user) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบบัญชีผู้ใช้ในระบบ'
            ];   
            return response()->json($response, 404);
        }

        $request->validate(
            [
                'user_name' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email',
            ],
            [
                'user_name.required' => 'กรุณากรอกชื่อบัญชีผู้ใช้',
                'first_name.required' => 'กรุณากรอกชื่อจริง',
                'last_name.required' => 'กรุณากรอกนามสกุล',
                'email.required' => 'กรุณากรอกอีเมล',
                'email.email' => 'กรุณากรอกรูปแบบอีเมลที่ถูกต้อง',
            ]
        );

        $data = $request->all();
    
        try {
            $user->update($data);
    
            $response = [
                'status' => 'success',
                'message' => 'บัญชีผู้ใช้นี้ถูกอัพเดตเรียบร้อยแล้ว',
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

    public function deleteUser($id) 
    {
        $user = User::find($id);

        if (!$user) {
            $response = [
                'status' => 'error',
                'message' => 'ไม่พบบัญชีผู้ใช้นี้ในระบบ'
            ];   
            return response()->json($response, 404);
        }


        try {
            $user->delete();
            
            $response = [
                'status' => 'success',
                'message' => 'บัญชีผู้ใช้นี้ถูกลบเรียบร้อยแล้ว',
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
