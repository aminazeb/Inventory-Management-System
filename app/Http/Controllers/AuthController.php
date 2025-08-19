<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Events\UserCreated;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Saloon\Requests\VerifyTextbeltOTP;
use App\Saloon\Connectors\TextbeltConnector;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'phone' => 'nullable|string|max:100',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
        ]);

        UserCreated::dispatch($user);

        return response()->json(new UserResource($user), 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json(new UserResource($user), 201);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out']);
    }

    public function verifyPhone(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'otp' => 'required|string',
        ]);

        $user = User::findOrFail($request->input('user_id'));
        if (empty($user->phone)) {
            return response()->json(['message' => 'No phone on record'], 422);
        }

        $apiKey = (string) config('services.textbelt.key', '');
        if ($apiKey === '') {
            return response()->json(['message' => 'Service not configured'], 500);
        }

        $connector = new TextbeltConnector();
        $requestVerify = new VerifyTextbeltOTP(
            $request->string('otp'),
            (string) $user->getKey(),
            $apiKey
        );

        $response = $connector->send($requestVerify);
        $data = $response->json();

        if (!empty($data['success']) && !empty($data['isValidOtp'])) {
            $user->forceFill(['phone_verified_at' => now()])->save();
            return response()->json(['message' => 'Phone verified']);
        }

        return response()->json([
            'message' => $data['error'] ?? 'Invalid OTP',
        ], 422);
    }

    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals(
            (string) $hash,
            sha1($user->getEmailForVerification())
        )) {
            return response()->json(['message' => 'Invalid verification link'], 400);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified']);
        }

        $user->markEmailAsVerified();

        return response()->json(['message' => 'Email verified successfully']);
    }

    public function resendEmailVerification(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified']);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification link sent']);
    }

    public function profile(Request $request)
    {
        return $request->user();
    }
}
