<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{

    public function login(Request $req)
    {
        $credentials = $req->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                $response['message'] = 'Invalid Email or Password';
                return response()->json(['error' => $response], 400);
            }
        } catch (JWTException $e) {
            $response['message'] = 'Server Error!, Could Not Generate Token';
            return response()->json(['error' => $response], 500);
        }
        $response['message'] = 'Token Created Successfully';
        $response['token'] = $token;
        return response()->json(['success' => $response]);
    }

    public function register(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|string|min:3',
            'email' => 'required|email',
            'password' => 'required|string|min:5|confirmed'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $input = $req->all();
        $input['password'] = bcrypt($input['password']);
        // User::create($input);
        $response['token'] = JWTAuth::fromUser(User::create($input));
        $response['message'] = 'Account Registered Successfully';
        return response()->json(['success' => $response], 200);
    }

}
