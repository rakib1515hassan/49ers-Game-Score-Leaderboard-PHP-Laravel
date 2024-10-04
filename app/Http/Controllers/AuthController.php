<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    // Register 
    public function register(Request $request)
    {
        Log::info("Registering Request");

        // Validate request data
        $request->validate([
            'f_name' => 'required|string|max:255',
            'l_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);



        // Create a new user
        $user = User::create([
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Optionally, you can return a response here
        return response()->json(['message' => 'User registered successfully'], 201);
    }



    // Login
    public function login(Request $request)
    {
        Log::info("Login Request");

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        return $this->respondWithToken($token);
    }



    // Logout
    public function logout(Request $request)
    {
        Log::info("Logout Request");

        try {
            // Invalidate the token
            JWTAuth::invalidate(JWTAuth::getToken());
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to logout, please try again.'], 500);
        }

        return response()->json(['message' => 'Successfully logged out.']);
    }




    protected function respondWithToken($token)
    {
        // Optionally, you can also include user information
        $user = Auth::user();
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60, 
            'user' => $user,
        ]);
    }


}
