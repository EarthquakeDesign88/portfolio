<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            $user = User::where('user_name', $request->username)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['error' => 'Invalid username or password'], 401);
            }

            $user->last_login_at = now();
            $user->save();

            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'user' => [
                    'user_id' => $user->user_id,
                    'user_name' => $user->user_name,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'role_name' => $user->role->role_name,
                    'last_login_at' => $user->last_login_at->format('d-m-Y H:i:s')
                ],
                'token' => $token
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }
}
