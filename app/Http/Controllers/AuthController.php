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
    * @unauthenticated
    *
    * @bodyParam name string required Nombre del usuario. Example: Juan Perez
    * @bodyParam email string required Email del usuario. Example: juan@example.com
    * @bodyParam password string required Contraseña del usuario. Example: 123456
    */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
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
    * @unauthenticated
    */

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Credenciales incorrectas'
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

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json(['message' => 'Sesión cerrada']);
    }
}
