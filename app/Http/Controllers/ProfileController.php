<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Get user profile information
     */
    public function show(User $user)
    {
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'bio' => $user->bio,
                'avatar' => $user->avatar ? Storage::url($user->avatar) : null,
                'cover_photo' => $user->cover_photo ? Storage::url($user->cover_photo) : null,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at
            ]
        ]);
    }

    /**
     * Update user profile information
     */
    public function updateProfile(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'bio' => 'sometimes|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $user->update($request->only(['name', 'email', 'bio']));

            return response()->json([
                'message' => 'Profile updated successfully',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'bio' => $user->bio,
                    'avatar' => $user->avatar ? Storage::url($user->avatar) : null,
                    'cover_photo' => $user->cover_photo ? Storage::url($user->cover_photo) : null,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Profile update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user's avatar only
     */
    public function updateAvatar(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // Delete old avatar if it was a local file (optional)
            if ($user->avatar && !Str::startsWith($user->avatar, 'http')) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Update with Cloudinary URL directly
            $user->update(['avatar' => $request->avatar]);

            return response()->json([
                'message' => 'Avatar updated successfully',
                'avatar_url' => $request->avatar
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Avatar update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user's cover photo only
     */
    public function updateCoverPhoto(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'cover_photo' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // Optionally delete old local file if it's not a URL
            if ($user->cover_photo && !Str::startsWith($user->cover_photo, 'http')) {
                Storage::disk('public')->delete($user->cover_photo);
            }

            // Save Cloudinary URL directly
            $user->update(['cover_photo' => $request->cover_photo]);

            return response()->json([
                'message' => 'Cover photo updated successfully',
                'cover_photo_url' => $request->cover_photo
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Cover photo update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Update user's bio only
     */
    public function updateBio(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'bio' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $user->update(['bio' => $request->bio]);

            return response()->json([
                'message' => 'Bio updated successfully',
                'bio' => $user->bio
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Bio update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}