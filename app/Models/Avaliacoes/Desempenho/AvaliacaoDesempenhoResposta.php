<?php

namespace App\Models\Avaliacoes\Desempenho;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AvaliacaoDesempenhoResposta extends Model
{
    protected $table = 'avaliacao_desempenho_respostas';

    protected $fillable = [
        'avaliacao_id',
        'avaliador_vinculo_id',
        'pergunta_id',
        'resposta_numerica',
        'resposta_boolean',
        'resposta_opcao',
        'resposta_texto',
        'comentario',
        'respondida_em',
    ];

    protected function casts(): array
    {
        return [
            'resposta_numerica' => 'decimal:2',
            'resposta_boolean' => 'boolean',
            'respondida_em' => 'datetime',
        ];
    }

    public function avaliacao(): BelongsTo
    {
        return $this->belongsTo(AvaliacaoDesempenhoAvaliacao::class, 'avaliacao_id');
    }

    public function avaliadorVinculo(): BelongsTo
    {
        return $this->belongsTo(AvaliacaoDesempenhoAvaliador::class, 'avaliador_vinculo_id');
    }

    public function pergunta(): BelongsTo
    {
        return $this->belongsTo(AvaliacaoDesempenhoPergunta::class, 'pergunta_id');
    }
}
