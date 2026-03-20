<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AprovacaoSolicitacaoEtapa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'aprovacao_solicitacao_etapa';

    protected $fillable = [
        'solicitacao_id',
        'fluxo_etapa_id',
        'nome_etapa_snapshot',
        'ordem',
        'tipo_aprovacao_snapshot',
        'quantidade_minima_aprovacao_snapshot',
        'status',
        'liberada_em',
        'finalizada_em',
    ];

    protected $casts = [
        'liberada_em'   => 'datetime',
        'finalizada_em' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONAMENTOS
    |--------------------------------------------------------------------------
    */

    public function solicitacao()
    {
        return $this->belongsTo(AprovacaoSolicitacao::class, 'solicitacao_id', 'id');
    }

    public function fluxoEtapa()
    {
        return $this->belongsTo(AprovacaoFluxoEtapa::class, 'fluxo_etapa_id', 'id');
    }

    public function aprovadores()
    {
        return $this->hasMany(AprovacaoSolicitacaoEtapaAprovador::class, 'solicitacao_etapa_id', 'id')
            ->orderBy('id');
    }

    public function logs()
    {
        return $this->hasMany(AprovacaoLog::class, 'solicitacao_etapa_id', 'id')
            ->orderBy('created_at');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isAguardando()
    {
        return $this->status === 'aguardando';
    }

    public function isLiberada()
    {
        return $this->status === 'liberada';
    }

    public function isAprovada()
    {
        return $this->status === 'aprovada';
    }

    public function isReprovada()
    {
        return $this->status === 'reprovada';
    }

    public function isCancelada()
    {
        return $this->status === 'cancelada';
    }
}
