<?php

namespace App\Http\Controllers\Avaliacoes\Desempenho\Concerns;

use App\Models\Avaliacoes\Desempenho\AvaliacaoDesempenhoPerguntaOpcao;
use App\Models\Avaliacoes\Desempenho\AvaliacaoDesempenhoPublicoAlvo;
use App\Models\Avaliacoes\Desempenho\AvaliacaoDesempenhoRegraAplicacao;

trait InteractsWithAvaliacaoDesempenho
{
    protected function syncRegras(string $escopoTipo, int $escopoId, array $regras): void
    {
        AvaliacaoDesempenhoRegraAplicacao::query()
            ->where('escopo_tipo', $escopoTipo)
            ->where('escopo_id', $escopoId)
            ->delete();

        foreach ($regras as $regra) {
            AvaliacaoDesempenhoRegraAplicacao::create([
                'escopo_tipo' => $escopoTipo,
                'escopo_id' => $escopoId,
                'regra_tipo' => $regra['regra_tipo'],
                'referencia_id' => $regra['referencia_id'],
            ]);
        }
    }

    protected function syncPublicoAlvo(int $cicloId, array $itens): void
    {
        AvaliacaoDesempenhoPublicoAlvo::query()->where('ciclo_id', $cicloId)->delete();

        foreach ($itens as $item) {
            AvaliacaoDesempenhoPublicoAlvo::create([
                'ciclo_id' => $cicloId,
                'tipo' => $item['tipo'],
                'referencia_id' => $item['referencia_id'],
            ]);
        }
    }

    protected function syncOpcoesPergunta(int $perguntaId, array $opcoes): void
    {
        AvaliacaoDesempenhoPerguntaOpcao::query()->where('pergunta_id', $perguntaId)->delete();

        foreach ($opcoes as $opcao) {
            AvaliacaoDesempenhoPerguntaOpcao::create([
                'pergunta_id' => $perguntaId,
                'texto' => $opcao['texto'],
                'valor' => $opcao['valor'],
                'ordem' => $opcao['ordem'],
                'ativa' => $opcao['ativa'],
            ]);
        }
    }
}
