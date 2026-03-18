<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFirstAccessCompleted
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

        if (empty($user->senha)) {
            return redirect()
                ->route('auth.acesso')
                ->with('warning', 'Você precisa concluir o primeiro acesso antes de entrar no sistema.');
        }

        return $next($request);
    }
}
