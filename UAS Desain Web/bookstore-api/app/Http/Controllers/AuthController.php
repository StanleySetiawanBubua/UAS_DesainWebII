<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'status' => 'error',
                'message' => $errors->first(),
                'data' => null
            ], 302);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $status = "error";
        $message = "";
        $data = null;
        $code = 401;
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $user->generateToken();
                $status = 'success';
                $message = 'Login Sukses';
                $data = $user->toArray();
                $code = 200;
            } else {
                $message = "Login gagal, password salah";
            }
        } else {
            $message = "Login gagal, email salah";
        }
    
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $message = $errors;
            $status = "error";
            $data = null;
            $code = 400;
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'roles' => json_encode(['CUSTOMER']),
            ]);

            if ($user) {
                $user->generateToken();
                $status = "success";
                $message = "register successfully";
                $data = $user->toArray();
                $code = 200;
            } else {
                $message = 'register failed';
                $status = "error";
                $data = null;
                $code = 400;
            }
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);

    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $user->api_token = null;
            $user->save();
        }
        Auth::guard('web')->logout();
    
        return response()->json([
            'status' => 'success',
            'message' => 'Logout Berhasil',
            'data' => null
        ], 200);
    }
}



//$user_id = Auth::user()->id;
//$user = User::where('id',$user_id )->first();