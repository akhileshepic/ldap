<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class VerifyApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->hasHeader('Authorization')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Extract the token from the 'Authorization' header.
        $token = $request->header('Authorization');

        // Remove the 'Bearer ' prefix from the token (if present).
        $token = str_replace('Bearer ', '', $token);

        // Use the 'api' guard to verify the token.
        if (!Auth::guard('api')->checkUsingId($token)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
