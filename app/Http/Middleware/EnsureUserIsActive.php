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

        // Se não houver usuário autenticado, deixa a middleware auth tratar.
        if (! $user) {
            return $next($request);
        }

        /*
         |---------------------------------------------------------------
         | Ajuste esta regra conforme o padrão real da sua tabela.
         | Exemplos possíveis:
         | - $user->ativo === true
         | - $user->situacao === 'ATIVO'
         | - $user->status === 'ATIVO'
         |---------------------------------------------------------------
         */
        if ((int) $user->ativo !== 1) {
            auth()->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('auth.login')
                ->with('error', 'Seu usuário está inativo e não pode acessar o sistema.');
        }

        return $next($request);
    }
}
