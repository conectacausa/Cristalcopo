<?php

namespace App\Models\Avaliacoes\Desempenho;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AvaliacaoDesempenhoPublicoAlvo extends Model
{
    protected $table = 'avaliacao_desempenho_publico_alvo';

    protected $fillable = ['ciclo_id', 'tipo', 'referencia_id'];

    public function ciclo(): BelongsTo
    {
        return $this->belongsTo(AvaliacaoDesempenhoCiclo::class, 'ciclo_id');
    }
}
