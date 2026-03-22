<?php

namespace App\Policies\Concerns;

use Illuminate\Support\Facades\DB;

trait ChecksScreenPermissions
{
    protected function resolveScreenPermissions(mixed $user, string $screenSlug): array
    {
        $permissaoId = $user->permissao_id ?? null;

        if (empty($permissaoId)) {
            return [
                'has_access' => false,
                'pode_ler' => false,
                'pode_gravar' => false,
                'pode_editar' => false,
                'pode_excluir' => false,
            ];
        }

        $registro = DB::table('vinculo_permissao_x_tela')
            ->join('gestao_tela', 'gestao_tela.id', '=', 'vinculo_permissao_x_tela.tela_id')
            ->where('vinculo_permissao_x_tela.permissao_id', $permissaoId)
            ->where('gestao_tela.slug', $screenSlug)
            ->select(
                DB::raw('true as has_access'),
                DB::raw('COALESCE(vinculo_permissao_x_tela.pode_ler, false) as pode_ler'),
                DB::raw('COALESCE(vinculo_permissao_x_tela.pode_gravar, false) as pode_gravar'),
                DB::raw('COALESCE(vinculo_permissao_x_tela.pode_editar, false) as pode_editar'),
                DB::raw('COALESCE(vinculo_permissao_x_tela.pode_excluir, false) as pode_excluir')
            )
            ->first();

        return [
            'has_access' => (bool) ($registro->has_access ?? false),
            'pode_ler' => (bool) ($registro->pode_ler ?? false),
            'pode_gravar' => (bool) ($registro->pode_gravar ?? false),
            'pode_editar' => (bool) ($registro->pode_editar ?? false),
            'pode_excluir' => (bool) ($registro->pode_excluir ?? false),
        ];
    }

    protected function hasScreenAccess(mixed $user, string $screenSlug): bool
    {
        return $this->resolveScreenPermissions($user, $screenSlug)['has_access'];
    }
}
