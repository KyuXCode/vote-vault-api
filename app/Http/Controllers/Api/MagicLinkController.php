<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\MagicLinkMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class MagicLinkController extends Controller
{
    public function sendMagicLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        // Generate a signed URL that expires in 15 minutes
        $signedUrl = URL::temporarySignedRoute(
            'magic-login-verify',  // Named route for verification
            now()->addMinutes(15),
            ['email' => $user->email] // Embed user email
        );

        // Frontend Redirect URL
        $frontendMagicLink = "http://localhost:3000/magic-login?" . parse_url($signedUrl, PHP_URL_QUERY);

        // Send magic link email
        Mail::to($user->email)->send(new MagicLinkMail($frontendMagicLink));

        return response()->json([
            'message' => 'Magic link sent successfully.',
        ]);
    }
    public function magicLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'signature' => 'required',
            'expires' => 'required|numeric',
        ]);

//        // Manually recreate the signed URL
//        $signedUrl = URL::temporarySignedRoute(
//            'magic-login-verify',
//            Carbon::createFromTimestamp($request->expires), // Use the same expiration timestamp
//            ['email' => $request->email]
//        );
//
//        // Extract expected signature
//        $expectedSignature = parse_url($signedUrl, PHP_URL_QUERY);
//        parse_str($expectedSignature, $queryParams);
//
//        if ($queryParams['signature'] !== $request->signature) {
//            return response()->json(['message' => 'Invalid or expired magic link.'], 400);
//        }

        // Find the user
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        // Generate authentication token (Sanctum)
        $authToken = $user->createToken('API Access')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $authToken,
        ]);
    }
}
