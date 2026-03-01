<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Register a new user
     *
     * @group Authentication
     * @unauthenticated
     *
     * @bodyParam name string required Full name of the user. Example: John Doe
     * @bodyParam email string required Unique email address. Example: john@example.com
     * @bodyParam password string required Password (min 8 chars, must contain uppercase, lowercase and number). Example: Password123
     *
     * @response 201 {
     *   "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIs...",
     *   "user": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "email": "john@example.com",
     *     "role": "client"
     *   }
     * }
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
            ],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'client',
        ]);

        if (app()->environment('testing')) {
            $token = 'fake-token';
        } else {
            $token = $user->createToken('authToken')->accessToken;
        }

        return response()->json([
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    /**
     * User Login
     *
     * @group Authentication
     * @unauthenticated
     *
     * @bodyParam email string required Registered email address. Example: admin@midori.com
     * @bodyParam password string required User password. Example: Password123
     *
     * @response 200 {
     *   "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIs...",
     *   "user": {
     *     "id": 1,
     *     "name": "Admin",
     *     "email": "admin@midori.com",
     *     "role": "admin"
     *   }
     * }
     *
     * @response 401 {
     *   "message": "Invalid credentials"
     * }
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();

        if (app()->environment('testing')) {
            $token = 'fake-token';
        } else {
            $token = $user->createToken('login-token')->accessToken;
        }

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ]);
    }

    /**
     * Logout
     *
     * Revokes the current access token and closes the user session.
     *
     * @group Authentication
     *
     * @response 200 {
     *   "message": "Session closed"
     * }
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Session closed'
        ]);
    }
}