<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::all());
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return new UserResource($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update($request->only(['name', 'email', 'role']));

        return new UserResource($user);
    }

    public function me(Request $request)
    {
        return new UserResource($request->user());
    }

    public function updateMe(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only(['name', 'email']));

        return new UserResource($user);
    }

    public function changePassword(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password updated successfully']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted']);
    }

    public function requestProvider(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'client') {
            return response()->json(['message' => 'Only clients can request to become providers'], 403);
        }

        $user->provider_request = true;
        $user->save();

        return response()->json(['message' => 'Request submitted successfully']);
    }

    public function providerRequests()
    {
        return UserResource::collection(
            User::where('provider_request', true)->get()
        );
    }

    public function approveProvider($id)
    {
        $user = User::findOrFail($id);

        if (!$user->provider_request) {
            return response()->json(['message' => 'User has not requested provider role'], 400);
        }

        $user->role = 'provider';
        $user->provider_request = false;
        $user->save();

        return new UserResource($user);
    }

    public function declineProvider($id)
    {
        $user = User::findOrFail($id);

        if (!$user->provider_request) {
            return response()->json(['message' => 'No pending request'], 400);
        }

        $user->provider_request = false;
        $user->save();

        return response()->json(['message' => 'Request declined']);
    }
}