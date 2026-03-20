<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AprovacaoSolicitacao extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'aprovacao_solicitacao';

    protected $fillable = [
        'fluxo_id',
        'tipo_referencia',
        'referencia_id',
        'titulo',
        'descricao',
        'status',
        'modo_aprovacao_snapshot',
        'solicitante_colaborador_id',
        'etapa_atual',
        'aberto_em',
        'finalizado_em',
    ];

    protected $casts = [
        'aberto_em'     => 'datetime',
        'finalizado_em' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONAMENTOS
    |--------------------------------------------------------------------------
    */

    public function fluxo()
    {
        return $this->belongsTo(AprovacaoFluxo::class, 'fluxo_id', 'id');
    }

    public function solicitante()
    {
        return $this->belongsTo(Colaborador::class, 'solicitante_colaborador_id', 'id');
    }

    public function etapas()
    {
        return $this->hasMany(AprovacaoSolicitacaoEtapa::class, 'solicitacao_id', 'id')
            ->orderBy('ordem');
    }

    public function logs()
    {
        return $this->hasMany(AprovacaoLog::class, 'solicitacao_id', 'id')
            ->orderBy('created_at');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isPendente()
    {
        return $this->status === 'pendente';
    }

    public function isEmAprovacao()
    {
        return $this->status === 'em_aprovacao';
    }

    public function isAprovado()
    {
        return $this->status === 'aprovado';
    }

    public function isReprovado()
    {
        return $this->status === 'reprovado';
    }

    public function isCancelado()
    {
        return $this->status === 'cancelado';
    }
}
