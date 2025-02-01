<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function registerForUser(Request $request)
    {
        $registerUserData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:15', // Add validation for phone
            'username' => 'required|string|max:255|unique:users', // Ensure unique username
            'supervisor_name' => 'nullable|string|max:255', // Optional supervisor name
            'supervisor_email' => 'nullable|string|email|max:255', // Optional supervisor email
            'organization' => 'required|in:County,State,VSTOP', // Validate organization
        ]);
        $user = User::create([
            'name' => $registerUserData['name'],
            'email' => $registerUserData['email'],
            'password' => bcrypt($registerUserData['password']),
            'phone' => $registerUserData['phone'] ?? null,
            'username' => $registerUserData['username'],
            'supervisor_name' => $registerUserData['supervisor_name'] ?? null,
            'supervisor_email' => $registerUserData['supervisor_email'] ?? null,
            'organization' => $registerUserData['organization'],
            'status' => 'Pending',
        ]);
        return response()->json([
            'message' => 'User Created ',
        ]);
    }

    public function login(Request $request)
    {
        $loginUserData = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|min:8',
        ]);

        $user = User::where('email', $loginUserData['email'])->first();

        if (!$user || !Hash::check($loginUserData['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid Credentials',
            ], 401);
        }

        $existingToken = $user->tokens()->where('expires_at', '>', now())->first();

        if ($existingToken) {
            return response()->json([
                'message' => 'You already have an active token.',
            ], 400);
        }

         $user->tokens->each(function ($token) {
             $token->delete();
         });

        $expirationTime = Carbon::now()->addMinutes(15);

        $token = $user->createToken($user->name.'-AuthToken', ['*'], $expirationTime)->plainTextToken;

        return response()->json([
            'access_token' => $token,
        ]);
    }



    public function logout(){
        auth()->user()->tokens()->delete();

        return response()->json([
            "message"=>"logged out"
        ]);
    }

    public function generateRandomLink($user_id)
    {
        return URL::temporarySignedRoute(
            'login', now()->addMinutes(30), ['user' => $user_id]
        );
    }
}
