<?php

namespace App\Models\Avaliacoes\Desempenho;

use App\Models\Colaborador;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AvaliacaoDesempenhoCiclo extends Model
{
    use SoftDeletes;

    protected $table = 'avaliacao_desempenho_ciclos';

    protected $fillable = [
        'nome',
        'descricao',
        'tipo_avaliacao',
        'data_inicio',
        'data_fim',
        'status',
        'forma_liberacao',
        'permite_autoavaliacao',
        'permite_avaliacao_gestor',
        'permite_avaliacao_pares',
        'permite_avaliacao_subordinados',
        'anonimato',
        'permite_edicao_ate_prazo_final',
        'permite_resposta_parcial',
        'lembrete_ativo',
        'lembrete_frequencia',
        'lembrete_intervalo_dias',
        'lembrete_horario',
        'lembrete_canais',
        'lembrete_parar_ao_responder',
        'lembrete_final_antes_encerramento',
        'publico_tipo',
    ];

    protected function casts(): array
    {
        return [
            'data_inicio' => 'date',
            'data_fim' => 'date',
            'permite_autoavaliacao' => 'boolean',
            'permite_avaliacao_gestor' => 'boolean',
            'permite_avaliacao_pares' => 'boolean',
            'permite_avaliacao_subordinados' => 'boolean',
            'anonimato' => 'boolean',
            'permite_edicao_ate_prazo_final' => 'boolean',
            'permite_resposta_parcial' => 'boolean',
            'lembrete_ativo' => 'boolean',
            'lembrete_canais' => 'array',
            'lembrete_parar_ao_responder' => 'boolean',
            'lembrete_final_antes_encerramento' => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }

    public function pilares(): HasMany
    {
        return $this->hasMany(AvaliacaoDesempenhoPilar::class, 'ciclo_id')->orderBy('ordem');
    }

    public function perguntas(): HasMany
    {
        return $this->hasMany(AvaliacaoDesempenhoPergunta::class, 'ciclo_id')->orderBy('ordem');
    }

    public function publicoAlvo(): HasMany
    {
        return $this->hasMany(AvaliacaoDesempenhoPublicoAlvo::class, 'ciclo_id');
    }

    public function avaliacoes(): HasMany
    {
        return $this->hasMany(AvaliacaoDesempenhoAvaliacao::class, 'ciclo_id');
    }

    public function colaboradoresSelecionados(): BelongsToMany
    {
        return $this->belongsToMany(
            Colaborador::class,
            'avaliacao_desempenho_publico_alvo',
            'ciclo_id',
            'referencia_id'
        )->wherePivot('tipo', 'colaborador');
    }

    public function getResumoPublicoAttribute(): string
    {
        return match ($this->publico_tipo) {
            'todos' => 'Todos os colaboradores ativos',
            'filial' => 'Filiais selecionadas',
            'setor' => 'Setores selecionados',
            'cargo' => 'Cargos selecionados',
            'manual' => 'Seleção manual de colaboradores',
            default => ucfirst($this->publico_tipo),
        };
    }
}
