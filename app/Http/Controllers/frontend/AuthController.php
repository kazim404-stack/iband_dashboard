<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'access_token' => $token
        ], 201);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'access_token' => $token
        ]);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }

    public function profile(Request $request)
    {
        $request->validate([
            'profile_image' => 'image|mimes:png,jpg,webp|max:2048'
        ]);
        $token = $request->user()->createToken('api_token')->plainTextToken;
        if ($request->hasFile('profile_image')) {
            if (is_file(public_path($request->user()->profile_image))) {
                unlink(public_path($request->user()->profile_image));
            }
            $image = $request->file('profile_image');
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = "backend/assets/images/users/" . $imageName;
            $image->move(public_path("backend/assets/images/users/"), $imageName);

            $request->user()->update([
                'profile_image' => $imagePath,
            ]);
            return response()->json([
                'message' => 'profifle image updated successfully',
                'access_token' => $token,
            ]);
        } else {
            $request->user()->update([
                'country' => $request->country,
                'city' => $request->city,
                'address' => $request->address,
                'zip_code' => $request->zipe_code,
                'phone_number' => $request->phone_number,
                'profile_completed' => 1,
            ]);
            return response()->json([
                'message' => 'Profile has been updated successfully',
                'access_token' => $token,
            ]);
        }
    }
}
