<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $userExist = User::where('email', $request->email)->first();
        if ($userExist != null) {
            return response()->json(['status' => 409, "message" => "User Already Exists"], 409);
        } else {
            $user = User::create(
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                    'gender' => $request->gender,
                    'password' => Hash::make($request->password)
                ]
            );

            if ($user) {
                return response()->json(['status' => 201, "message" => "Registration Successful", "user" => $user], 201);
            } else {
                return response()->json(['status' => 500, "message" => "Registration failed", "error" => $user], 500);
            }
        }
    }

     public function login (Request $request) {
        $user = User::where('email', $request->email)->first();
        if ($user == null) {
            return response()->json(['status' => 401, "message" => "Invalid Credentials"], 401);
        } else {
            $isValidPassword = Hash::check($request->password, $user->password);

            if ($isValidPassword) {
                $token = $user->createToken('authToken')->accessToken;
                return response()->json(['status' => 200, "message" => "Login Successful", "user" => $user, "token" => $token], 200);
            } else {
                return response()->json(['status' => 401, "message" => "Login failed", "error" => $user], 401);
            }
        }
     }
}
