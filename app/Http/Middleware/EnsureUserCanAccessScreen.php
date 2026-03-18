<?php

namespace App\Http\Middleware;

use App\Models\GestaoTela;
use App\Models\VinculoPermissaoXTela;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserCanAccessScreen
{
    public function handle(Request $request, Closure $next, string $screenSlug): Response
    {
        $user = auth()->user();

        if (! $user) {
            return redirect()->route('auth.login');
        }

        if (empty($user->permissao_id)) {
            abort(403, 'Usuário sem grupo de permissão vinculado.');
        }

        $tela = GestaoTela::query()
            ->where('slug', $screenSlug)
            ->first();

        if (! $tela) {
            abort(403, 'Tela não cadastrada.');
        }

        $hasAccess = VinculoPermissaoXTela::query()
            ->where('permissao_id', $user->permissao_id)
            ->where('tela_id', $tela->id)
            ->exists();

        if (! $hasAccess) {
            abort(403, 'Você não tem permissão para acessar esta tela.');
        }

        return $next($request);
    }
}
