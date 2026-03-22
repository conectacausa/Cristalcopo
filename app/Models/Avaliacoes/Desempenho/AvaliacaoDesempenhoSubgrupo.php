<?php

namespace App\Models\Avaliacoes\Desempenho;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AvaliacaoDesempenhoSubgrupo extends Model
{
    use SoftDeletes;

    protected $table = 'avaliacao_desempenho_subgrupos';

    protected $fillable = ['grupo_id', 'nome', 'descricao', 'ordem', 'ativo'];

    protected function casts(): array
    {
        return [
            'ativo' => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }

    public function grupo(): BelongsTo
    {
        return $this->belongsTo(AvaliacaoDesempenhoGrupo::class, 'grupo_id');
    }

    public function perguntas(): HasMany
    {
        return $this->hasMany(AvaliacaoDesempenhoPergunta::class, 'subgrupo_id')->orderBy('ordem');
    }

    public function regrasAplicacao(): HasMany
    {
        return $this->hasMany(AvaliacaoDesempenhoRegraAplicacao::class, 'escopo_id')
            ->where('escopo_tipo', 'subgrupo');
    }
}
