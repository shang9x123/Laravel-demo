<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {

            $data = JWTAuth::parseToken($request); // Parse the token from the request
            JWTAuth::authenticate($request); // Authenticate the user using the token

        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Unauthorized access',
                'error' => $e->getMessage()
            ], 401);
        }

        return $next($request);
    }
}
