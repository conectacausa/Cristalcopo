<?php

namespace App\Services\Configuracao;

use App\Models\GestaoPais;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PaisService
{
    public function listar(?string $descricao = null, int $perPage = 25): LengthAwarePaginator
    {
        return GestaoPais::query()
            ->when(filled($descricao), function ($query) use ($descricao) {
                $query->where('nome', 'ilike', '%' . trim((string) $descricao) . '%');
            })
            ->orderBy('nome')
            ->paginate($perPage);
    }

    public function criar(array $dados): GestaoPais
    {
        return GestaoPais::query()->create([
            'nome' => trim($dados['nome']),
            'iso2' => strtoupper(trim($dados['iso2'])),
            'iso3' => strtoupper(trim($dados['iso3'])),
        ]);
    }

    public function atualizar(GestaoPais $pais, array $dados): GestaoPais
    {
        $pais->update([
            'nome' => trim($dados['nome']),
            'iso2' => strtoupper(trim($dados['iso2'])),
            'iso3' => strtoupper(trim($dados['iso3'])),
        ]);

        return $pais->refresh();
    }

    public function excluir(GestaoPais $pais): void
    {
        $pais->delete();
    }
}
