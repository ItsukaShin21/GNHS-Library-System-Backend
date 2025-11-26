<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SystemLog;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request) {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $tokenResult = $user->createToken('Personal Access Token');

            return response()->json([
                'status' => 'success',
                'token' => $tokenResult->plainTextToken,
                'username' => $user->username,
                'user_type' => $user->user_type,
                'id' => $user->id,
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Incorrect Password',
            'username' => $request->username,
        ]);
    }

    public function logout(Request $request) {
        $user = Auth::user();
    
        if ($user) {
            $user->tokens()->delete();
    
            return response()->json([
                'status' => 'success',
                'message' => 'Logged out successfully',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'No user is currently authenticated',
            ], 401);
        }
    }

    public function userRegister(Request $request) {
        $user = User::create([
            "username" => $request->username,
            "password" => Hash::make($request->password),
            "user_type" => "Student",
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Request created successfully',
        ]);

    }

    public function user_record_login(Request $request) {
        $loginRecord = SystemLog::create([
            "username" => $request->username,
            "login_at" => now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User create successfully',
            'username' => $request->username,
            'log_id' => $loginRecord->id,
        ]);
    }

    public function user_record_logout(Request $request) {
        $log_id = SystemLog::find($request->id);

        $log_id->update([
            'logout_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User has been updated',
        ]);
    }

    public function fetchLoginRecords() {
        $LoginLogs = SystemLog::get();

        return response()->json([
            "loginLogs" => $LoginLogs
        ]);
    }

    public function user_info($user_id) {
        $user = User::where('user_id', $user_id)->first();

        $userInfo = [
            'user_id' => $user->user_id,
            'username' => $user->username,
            'user_type' => $user->user_type,
            'department' => $user->department,
            'email' => $user->email,
        ];

        return response()->json([
            'status' => 'success',
            'user_info' => $userInfo,
        ]);
    }

    public function user_update(Request $request) {
        $userInfo = User::find($request->user_id);

        $userInfo->update([
            'username' => $request->username,
            'department' => $request->department,
            'user_type' => $request->user_type,
            'email' => $request->email,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User has been updated',
            'user_info' => $userInfo->fresh()
        ]);
    }

    public function user_delete(Request $request) {
        $user = User::find($request->user_id);

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Request deleted successfully',
        ]);
    }
}
