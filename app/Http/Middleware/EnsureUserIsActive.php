<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (! $user) {
            return $next($request);
        }

        if ((int) $user->ativo !== 1) {
            auth()->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('auth.login');
        }

        return $next($request);
    }
}
