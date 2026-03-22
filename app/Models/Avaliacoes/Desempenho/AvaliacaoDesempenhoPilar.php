<?php

namespace App\Models\Avaliacoes\Desempenho;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AvaliacaoDesempenhoPilar extends Model
{
    use SoftDeletes;

    protected $table = 'avaliacao_desempenho_pilares';

    protected $fillable = ['ciclo_id', 'nome', 'descricao', 'peso', 'ordem', 'ativo'];

    protected function casts(): array
    {
        return [
            'peso' => 'decimal:2',
            'ativo' => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }

    public function ciclo(): BelongsTo
    {
        return $this->belongsTo(AvaliacaoDesempenhoCiclo::class, 'ciclo_id');
    }

    public function grupos(): HasMany
    {
        return $this->hasMany(AvaliacaoDesempenhoGrupo::class, 'pilar_id')->orderBy('ordem');
    }

    public function regrasAplicacao(): HasMany
    {
        return $this->hasMany(AvaliacaoDesempenhoRegraAplicacao::class, 'escopo_id')
            ->where('escopo_tipo', 'pilar');
    }
}
