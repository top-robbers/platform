<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Simple static Bearer token middleware for internal API consumers
 * (Discord bot, launcher, etc.) that don't have/need a user session.
 *
 * Token is set via API_TOKEN in .env.
 */
class ApiTokenMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        $expected = config('app.api_token');

        if (empty($expected)) {
            abort(500, 'API_TOKEN is not configured.');
        }

        if (!hash_equals($expected, (string) $token)) {
            return response()->json(['message' => 'Unauthorized.'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}