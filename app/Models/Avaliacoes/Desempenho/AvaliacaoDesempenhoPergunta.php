<?php

namespace App\Models\Avaliacoes\Desempenho;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AvaliacaoDesempenhoPergunta extends Model
{
    use SoftDeletes;

    protected $table = 'avaliacao_desempenho_perguntas';

    protected $fillable = [
        'ciclo_id',
        'pilar_id',
        'grupo_id',
        'subgrupo_id',
        'enunciado',
        'descricao_apoio',
        'tipo_resposta',
        'obrigatoria',
        'peso',
        'ordem',
        'ativa',
        'permite_comentario',
        'comentario_obrigatorio',
    ];

    protected function casts(): array
    {
        return [
            'obrigatoria' => 'boolean',
            'peso' => 'decimal:2',
            'ativa' => 'boolean',
            'permite_comentario' => 'boolean',
            'comentario_obrigatorio' => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }

    public function ciclo(): BelongsTo
    {
        return $this->belongsTo(AvaliacaoDesempenhoCiclo::class, 'ciclo_id');
    }

    public function pilar(): BelongsTo
    {
        return $this->belongsTo(AvaliacaoDesempenhoPilar::class, 'pilar_id');
    }

    public function grupo(): BelongsTo
    {
        return $this->belongsTo(AvaliacaoDesempenhoGrupo::class, 'grupo_id');
    }

    public function subgrupo(): BelongsTo
    {
        return $this->belongsTo(AvaliacaoDesempenhoSubgrupo::class, 'subgrupo_id');
    }

    public function opcoes(): HasMany
    {
        return $this->hasMany(AvaliacaoDesempenhoPerguntaOpcao::class, 'pergunta_id')->orderBy('ordem');
    }

    public function regrasAplicacao(): HasMany
    {
        return $this->hasMany(AvaliacaoDesempenhoRegraAplicacao::class, 'escopo_id')
            ->where('escopo_tipo', 'pergunta');
    }
}
