<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $account = $request->user();

        if (! $account) {
            return $next($request);
        }

        if ($account->active === true) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            $account->currentAccessToken()?->delete();

            return response()->json([
                'message' => 'Account disabled.',
            ], 403);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->withErrors([
                'email' => 'This account is disabled.',
            ]);
    }
}