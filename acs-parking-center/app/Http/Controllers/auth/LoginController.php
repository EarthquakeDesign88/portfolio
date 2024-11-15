<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function loginPerform(Request $request)
    {
        $credentials = $request->only('username', 'password');
 
        if (Auth::attempt($credentials)) {
            return redirect()->route('dashboard')->with('success', 'เข้าสู่ระบบสำเร็จแล้ว');
        }
     
        return back()->with('error', 'ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง');
    }

    public function login(Request $request) {
        $data = $request->all();

        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ],
        [
            'username.required' => 'กรุณากรอกยูสเซอร์',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'failed_4001', 'errors' => $validator->errors()], 422);
        }

        $check_record = User::where('name', $data['username'])
                            ->where('password', $data['password'])
                            ->first();
        
        if(!$check_record){
            $data['msg'] = "ไม่พบชื่อสมาชิก";
            return response()->json(['status' => 'failed_4002', 'errors' => $data], 404);
        }

        return Response()->json(['status' => 'success', 'data' => $check_record], 200);
    }

}
