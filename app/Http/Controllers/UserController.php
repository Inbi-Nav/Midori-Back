<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index() {
        return response()->json(User::all());
    }

    public function show($id) {
        $user = User::find($id);
        if ($user) {
            return response()->json($user);
        } else  {
            return response()->json(['message' => 'usuario no encontrado'], 404);
        }
        return response()->json($user);
    }

    public function update(Request $request, $id) {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
        $user->update($request->only([
            'name',
            'email',
            'role'
        ]));

        return response()->json($user);
    }

    public function me(Request $request) {
    return response()->json($request->user());
    }
    
    public function updateMe(Request $request) {
        $user = $request->user();

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only(['name', 'email']));

        return response()->json([
            'message' => 'Perfil actualizado correctamente',
            'user' => $user
        ]);
    }

    public function changePassword(Request $request)
    {
        $user = $request->user();
    
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);
    
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'La contraseña actual no es correcta'
            ], 400);
        }
    
        $user->password = Hash::make($request->new_password);
        $user->save();
    
        return response()->json([
            'message' => 'Contraseña actualizada correctamente'
        ]);
    }

    public function destroy($id) {
        $user = User::find($id);

        if (!$user) {
        return response()->json(['message' => 'usuario no encontrado'], 404);
        } 

        $user->delete();
        return response()->json(['message' => 'usuario eliminado']);
    }

    public function requestProvider(Request $request){
        $user = $request->user();

        if ($user->role !== 'client') {
            return response()->json([
                'message' => 'Solo los clientes pueden solicitar ser proveedor'
            ], 403);
        }

        $user->provider_request = true;
        $user->save();

        return response()->json([
            'message' => 'Solicitud enviada. Pendiente de aprobación por el admin'
        ]);
    }

    public function providerRequests() {
        $users = User::where('provider_request', true)->get();
        return response()->json($users);
    }

    public function approveProvider($id) {
        $user = User::find($id);

        if (!$user->provider_request) {
            return response()->json(['message' => 'El usuario no ha solicitado ser proveedor'], 400);
        }

        $user->role = 'provider';
        $user->provider_request = false;
        $user->save();

        return response()->json(['message' => 'Usuario aprobado como proveedor', 'user' => $user]);
    }

    public function declineProvider($id) {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        if (!$user->provider_request) {
            return response()->json(['message' => 'El usuario no tiene una solicitud pendiente'], 400);
        }

        $user->provider_request = false;
        $user->save();

        return response()->json(['message' => 'Solicitud de proveedor rechazada correctamente']);
    }
}