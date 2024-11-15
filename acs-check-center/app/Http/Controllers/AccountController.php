<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function login_template()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string',
            'password' => 'required|string',
        ], [
            'user_name.required' => 'กรุณากรอกยูสเซอร์',
            'password.required' => 'กรุณากรอกพาสเวิร์ด',
        ]);

        $credentials = $request->only('user_name', 'password');

        if (Auth::attempt($credentials)) {
            session(['user_name' => Auth::user()->user_name]);
            return redirect()->route('dashboard-analytics');
        } else {
            return redirect()->route('login')->with('error', 'ชื่อบัญชี หรือ รหัสผ่าน ไม่ถูกต้อง โปรดลองใหม่อีกครั้ง');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function accounts()
    {
        $accounts = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.role_id')
            ->orderBy('user_id', 'ASC')->paginate(10);

        $roles = Role::all();
        return view('content.account.account', compact('accounts', 'roles'));
    }

    public function role_get(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        return view('content.account.role_edit', compact('role'));
    }

    public function role_update(Request $request, $id)
    {

        if (empty($request->input('role_name'))) {
            return response()->json(['status' => 'error', 'message' => 'กรุณากรอกชื่อสิทธิ์']);
        }

        try {
            $count = DB::table('roles as r')
                ->where('r.role_name', '=', $request->input('role_name'))->count();

            if ($count > 0) {
                return response()->json(['status' => 'error', 'message' => 'ข้อมูลชื่อสิทธิ์ที่ซ้ำ']);
            }

            $role = Role::findOrFail($id);
            $role->role_name = $request->input('role_name');
            $role->save();

            return response()->json(['status' => 'success', 'message' => 'อัพเดทบทบาทสำเร็จแล้ว']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด']);
        }
    }

    public function account_get(Request $request, $id)
    {
        $account = User::findOrFail($id);
        $roles = Role::all();
        return view('content.account.account_edit', compact('account', 'roles'));
    }

    public function account_update(Request $request, $id)
    {

        if (empty($request->input('firstName'))) {
            return response()->json(['status' => 'error', 'message' => 'กรุณากรอกชื่อ']);
        }

        if (empty($request->input('lastName'))) {
            return response()->json(['status' => 'error', 'message' => 'กรุณากรอกนามสกุล']);
        }

        if (empty($request->input('email'))) {
            return response()->json(['status' => 'error', 'message' => 'กรุณากรอกอีเมล']);
        }

        if (empty($request->input('username'))) {
            return response()->json(['status' => 'error', 'message' => 'กรุณากรอกชื่อยูสเซอร์']);
        }

        try {
            $count = DB::table('users as u')
                ->where('u.user_name', '=', $request->input('username'))->count();

            if ($count == 0) {
                return response()->json(['status' => 'error', 'message' => 'ไม่พบข้อมูลชื่อยูสเซอร์']);
            }

            $account = User::findOrFail($id);
            $account->user_name = $request->input('username');
            $account->email = $request->input('email');
            $account->first_name = $request->input('firstName');
            $account->last_name = $request->input('lastName');

            if (!empty($request->new_password)) {
                $account->password = Hash::make($request->new_password);
            }

            $account->role_id = $request->input('role');
            $account->save();

            return response()->json(['status' => 'success', 'message' => 'อัพเดทบัญชีสำเร็จแล้ว']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด']);
        }
    }

    public function account_create(Request $request)
    {

        if (empty($request->input('firstName'))) {
            return response()->json(['status' => 'error', 'message' => 'กรุณากรอกชื่อ']);
        }

        if (empty($request->input('lastName'))) {
            return response()->json(['status' => 'error', 'message' => 'กรุณากรอกนามสกุล']);
        }

        if (empty($request->input('email'))) {
            return response()->json(['status' => 'error', 'message' => 'กรุณากรอกอีเมล']);
        }

        if (empty($request->input('username'))) {
            return response()->json(['status' => 'error', 'message' => 'กรุณากรอกชื่อยูสเซอร์']);
        }

        if (empty($request->input('password'))) {
            return response()->json(['status' => 'error', 'message' => 'กรุณากรอกรหัสผ่าน']);
        }

        try {
            $count = DB::table('users as u')
                ->where('u.user_name', '=', $request->input('username'))->count();

            if ($count > 0) {
                return response()->json(['status' => 'error', 'message' => 'ข้อมูลชื่อยูสเซอร์ซ้ำ']);
            }

            $account = new User;
            $account->user_name = $request->input('username');
            $account->email = $request->input('email');
            $account->first_name = $request->input('firstName');
            $account->last_name = $request->input('lastName');
            $account->password = Hash::make($request->input('password'));
            $account->role_id = $request->input('role');
            $account->save();

            return response()->json(['status' => 'success', 'message' => 'สร้างบัญชีสำเร็จแล้ว']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด']);
        }
    }

    public function roles()
    {
        $roles = Role::paginate(10);
        return view('content.account.role', compact('roles'));
    }

    public function role_create(Request $request)
    {

        if (empty($request->input('role_name'))) {
            return response()->json(['status' => 'error', 'message' => 'กรุณากรอกชื่อสิทธิ์']);
        }

        try {
            $count = DB::table('roles as r')
                ->where('r.role_name', '=', $request->input('role_name'))->count();

            if ($count > 0) {
                return response()->json(['status' => 'error', 'message' => 'ข้อมูลชื่อสิทธิ์ที่ซ้ำ']);
            }

            $role = new Role;
            $role->role_name = $request->input('role_name');
            $role->save();

            return response()->json(['status' => 'success', 'message' => 'สร้างบทบาทสำเร็จแล้ว']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด']);
        }
    }
}
