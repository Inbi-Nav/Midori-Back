<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Requests\ChangePasswordRequest;
/**
 * @group Users
 */
class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::all());
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());

        return new UserResource($user);
    }

    public function updateMe(ProfileUpdateRequest $request)
    {
        $user = $request->user();

        $user->update($request->validated());

        return new UserResource($user);
    }

    public function me(Request $request) {
        
        return new UserResource($request->user());
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect'
            ], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'Password updated successfully'
        ]);
    }

   public function destroy(User $user, Request $request) {
        $authUser = $request->user();

        if ($authUser->role !== 'admin') {
            return response()->json([
                'message' => 'Not authorized'
            ], 403);
        }

        if ($user->id === $authUser->id) {
            return response()->json([
                'message' => 'You cannot delete your own account'
            ], 403);
        }

        if ($user->role === 'admin') {
            return response()->json([
                'message' => 'Admin users cannot be deleted'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }

    public function requestProvider(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'client') {
            return response()->json([
                'message' => 'Only clients can request to become providers'
            ], 403);
        }

        $user->provider_request = true;
        $user->save();

        return response()->json([
            'message' => 'Request submitted successfully'
        ]);
    }

    public function providerRequests()
    {
        return UserResource::collection(
            User::where('provider_request', true)->get()
        );
    }

    public function approveProvider(User $user)
    {
        if (!$user->provider_request) {
            return response()->json([
                'message' => 'User has not requested provider role'
            ], 400);
        }

        $user->update([
            'role' => 'provider',
            'provider_request' => false
        ]);

        return new UserResource($user);
    }

    public function declineProvider(User $user)
    {
        if (!$user->provider_request) {
            return response()->json([
                'message' => 'No pending request'
            ], 400);
        }

        $user->update([
            'provider_request' => false
        ]);

        return response()->json([
            'message' => 'Request declined successfully'
        ]);
    }
}