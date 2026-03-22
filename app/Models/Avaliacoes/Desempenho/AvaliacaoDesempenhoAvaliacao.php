<?php

namespace App\Models\Avaliacoes\Desempenho;

use App\Models\Colaborador;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AvaliacaoDesempenhoAvaliacao extends Model
{
    protected $table = 'avaliacao_desempenho_avaliacoes';

    protected $fillable = [
        'ciclo_id',
        'colaborador_id',
        'tipo_avaliacao',
        'status',
        'data_liberacao',
        'data_conclusao',
        'nota_final',
    ];

    protected function casts(): array
    {
        return [
            'data_liberacao' => 'datetime',
            'data_conclusao' => 'datetime',
            'nota_final' => 'decimal:2',
        ];
    }

    public function ciclo(): BelongsTo
    {
        return $this->belongsTo(AvaliacaoDesempenhoCiclo::class, 'ciclo_id');
    }

    public function colaborador(): BelongsTo
    {
        return $this->belongsTo(Colaborador::class, 'colaborador_id');
    }

    public function avaliadores(): HasMany
    {
        return $this->hasMany(AvaliacaoDesempenhoAvaliador::class, 'avaliacao_id');
    }

    public function respostas(): HasMany
    {
        return $this->hasMany(AvaliacaoDesempenhoResposta::class, 'avaliacao_id');
    }
}
