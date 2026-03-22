<?php

namespace App\Models\Avaliacoes\Desempenho;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AvaliacaoDesempenhoGrupo extends Model
{
    use SoftDeletes;

    protected $table = 'avaliacao_desempenho_grupos';

    protected $fillable = ['pilar_id', 'nome', 'descricao', 'ordem', 'ativo'];

    protected function casts(): array
    {
        return [
            'ativo' => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }

    public function pilar(): BelongsTo
    {
        return $this->belongsTo(AvaliacaoDesempenhoPilar::class, 'pilar_id');
    }

    public function subgrupos(): HasMany
    {
        return $this->hasMany(AvaliacaoDesempenhoSubgrupo::class, 'grupo_id')->orderBy('ordem');
    }

    public function perguntas(): HasMany
    {
        return $this->hasMany(AvaliacaoDesempenhoPergunta::class, 'grupo_id')->orderBy('ordem');
    }

    public function regrasAplicacao(): HasMany
    {
        return $this->hasMany(AvaliacaoDesempenhoRegraAplicacao::class, 'escopo_id')
            ->where('escopo_tipo', 'grupo');
    }
}
