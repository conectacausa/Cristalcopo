<?php

namespace App\Models\Avaliacoes\Desempenho;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AvaliacaoDesempenhoPerguntaOpcao extends Model
{
    protected $table = 'avaliacao_desempenho_pergunta_opcoes';

    protected $fillable = ['pergunta_id', 'texto', 'valor', 'ordem', 'ativa'];

    protected function casts(): array
    {
        return [
            'ativa' => 'boolean',
        ];
    }

    public function pergunta(): BelongsTo
    {
        return $this->belongsTo(AvaliacaoDesempenhoPergunta::class, 'pergunta_id');
    }
}
