<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Response\BaseResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);

            // Coba temukan admin dengan username
            $admin = Admin::where('username', $request->username)->first();

            if (!$admin || !Hash::check($request->password, $admin->password)) {
                return BaseResponse::error('Username atau password salah', 401);
            }

            // Buat token untuk autentikasi
            $token = $admin->createToken('auth_token')->plainTextToken;

            return BaseResponse::success('Login berhasil', [
                'token' => $token,
                'admin' => [
                    'id' => $admin->id,
                    'name' => $admin->name,
                    'username' => $admin->username,
                    'phone' => $admin->phone,
                    'email' => $admin->email,
                ],
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return BaseResponse::error($th->getMessage(), 500);
        }
    }
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return BaseResponse::actionSuccess('Logout berhasil.');
        } catch (Exception $e) {
            return BaseResponse::error($th->getMessage(), 500);
        }
    }
    public function notLogin() 
    {
        return BaseResponse::error("You must be loggin first",401);
    }
}
