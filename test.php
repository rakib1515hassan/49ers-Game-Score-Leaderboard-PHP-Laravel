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
    public function __construct()
    {
        # By default we are using here auth:api middleware
        $this->middleware('auth:api', ['except' => ['login']]);
    }


    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        Log::info("Registering user");

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



    /**
     * Login a user and return a token.
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }


    /**
     * Get the authenticated User.
     */
    public function me()
    {
        # Here we just get information about current user
        return response()->json(auth()->user());
    }



    /**
     * Refresh a token.
     */
    public function refresh()
    {
        # When access token will be expired, we are going to generate a new one wit this function 
        # and return it here in response
        return $this->respondWithToken(auth()->refresh());
    }




    /**
     * Logout the user by invalidating the token.
     */
    public function logout()
    {
        try {
            Auth::logout();
            return response()->json(['message' => 'User logged out successfully']);
        } catch (JWTException $exception) {
            return response()->json(['error' => 'Could not log out the user'], 500);
        }
    }





    /**
     * Get the token array structure.
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
        ]);
    }




    /**
     * Get the guard to be used during authentication.
     */
    public function guard()
    {
        return Auth::guard();
    }
}
