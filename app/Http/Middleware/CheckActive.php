<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckActive
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && ! $user->is_active) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Your account is disabled by admin.',
                ], 403);
            }

            Auth::guard('web')->logout();

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Your account is disabled by admin.',
                ]);
        }

        return $next($request);
    }
}
