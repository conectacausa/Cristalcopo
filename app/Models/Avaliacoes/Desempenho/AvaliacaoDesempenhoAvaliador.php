<?php

namespace App\Models\Avaliacoes\Desempenho;

use App\Models\Colaborador;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AvaliacaoDesempenhoAvaliador extends Model
{
    protected $table = 'avaliacao_desempenho_avaliadores';

    protected $fillable = [
        'avaliacao_id',
        'avaliador_id',
        'papel',
        'anonimo',
        'status',
        'prazo_resposta',
        'respondido_em',
    ];

    protected function casts(): array
    {
        return [
            'anonimo' => 'boolean',
            'prazo_resposta' => 'datetime',
            'respondido_em' => 'datetime',
        ];
    }

    public function avaliacao(): BelongsTo
    {
        return $this->belongsTo(AvaliacaoDesempenhoAvaliacao::class, 'avaliacao_id');
    }

    public function avaliador(): BelongsTo
    {
        return $this->belongsTo(Colaborador::class, 'avaliador_id');
    }

    public function respostas(): HasMany
    {
        return $this->hasMany(AvaliacaoDesempenhoResposta::class, 'avaliador_vinculo_id');
    }
}
