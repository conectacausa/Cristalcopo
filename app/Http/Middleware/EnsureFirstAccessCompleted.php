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

        // Se não houver usuário autenticado, deixa o fluxo seguir
        // para a middleware auth tratar.
        if (! $user) {
            return $next($request);
        }

        // Como foi definido no projeto, a senha pode ser nula
        // até o usuário concluir o primeiro acesso.
        if (empty($user->senha)) {
            auth()->logout();

            return redirect()
                ->route('auth.acesso.index')
                ->with('warning', 'Você precisa concluir o primeiro acesso antes de entrar no sistema.');
        }

        return $next($request);
    }
}
