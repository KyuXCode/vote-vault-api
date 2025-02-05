<?php

namespace App\Http\Controllers\Api;

use App\Helpers\OrgType;
use App\Helpers\RoleType;
use App\Http\Controllers\Controller;
use App\Mail\MagicLinkMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function registerForUser(Request $request)
    {
        $registerUserData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:15'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'supervisor_name' => ['required', 'string', 'max:255'],
            'supervisor_email' => ['required', 'string', 'email', 'max:255'],
            'organization' => ['required', new Enum(OrgType::class)],
            'role' => ['required', new Enum(RoleType::class)],
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
            'role' => $registerUserData['role'],
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

        if ($user->status == 'Pending') {
            return response()->json([
                'message' => 'Contact manager or VSTOP to approve your account',
            ], 401);
        } elseif ($user->status == 'Disabled') {
            return response()->json([
                'message' => 'This account has been disabled',
            ], 401);
        } elseif (!$user || !Hash::check($loginUserData['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid Credentials',
            ], 401);
        }

        $existingToken = $user->tokens()->where('expires_at', '>', now())->first();

        if ($existingToken) {
            // Sanctum does NOT store the plaintext token, so we must issue a new one
            $user->tokens()->delete();
        }

        $user->tokens->each(function ($token) {
            $token->delete();
        });

        $expirationTime = Carbon::now()->addMinutes(15);

        $token = $user->createToken($user->name . '-AuthToken', ['*'], $expirationTime)->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'expires_at' => Carbon::parse($expirationTime)->toDateTimeString(),
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            "message" => "logged out"
        ], 200);
    }

    public function sendMagicLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        $token = $user->createToken($user->name . '-magic-link', ['*'])->plainTextToken;

        $encryptedToken = Crypt::encryptString($token);

        // Generate the magic link (signed URL) containing the encrypted token
        $magicLink = URL::temporarySignedRoute(
            'magic-login',  // Named route
            now()->addMinutes(30),  // Link expires in 30 minutes
            ['token' => $encryptedToken]  // Passing the encrypted token as a query parameter
        );

        // Send the magic link to the user's email
        Mail::to($user->email)->send(new MagicLinkMail($magicLink));

        return response()->json([
            'message' => 'Magic link has been sent to your email.',
            'magic_link' => $magicLink,
        ]);
    }

    public function magicLogin(Request $request, $encryptedToken)
    {
        if (!$request->hasValidSignature()) {
            return response()->json([
                'message' => 'Invalid or expired magic link.',
            ], 400);
        }

        try {
            $token = Crypt::decryptString($encryptedToken);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Token decryption failed.'], 500);
        }

        $user = User::whereHas('tokens', function ($query) use ($token) {
            $query->where('token', $token);
        })->first();

        if (!$user) {
            return response()->json(['message' => 'User not found or token mismatch.'], 404);
        }

        if ($user->token_expires_at && Carbon::parse($user->token_expires_at)->isPast()) {
            return response()->json(['message' => 'The magic link has expired.'], 400);
        }

        $apiToken = $user->createToken('API Access')->plainTextToken;

        return response()->json([
            'message' => 'Token validated successfully!',
            'token' => $apiToken,  // Return the stored token for API authentication
        ]);
    }
}
