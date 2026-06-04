<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        // Get token from Authorization header
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Find the token
        $personalToken = PersonalAccessToken::where('token', $token)->first();

        if (!$personalToken) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        // Check if token is expired
        if ($personalToken->expires_at && $personalToken->expires_at->isPast()) {
            return response()->json(['error' => 'Token expired'], 401);
        }

        // Update last used
        $personalToken->update(['last_used_at' => now()]);

        // Set authenticated user
        auth()->login($personalToken->user);

        return $next($request);
    }
}