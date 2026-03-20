<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AprovacaoSolicitacaoEtapaAprovador extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'aprovacao_solicitacao_etapa_aprovador';

    protected $fillable = [
        'solicitacao_etapa_id',
        'colaborador_id',
        'nome_aprovador_snapshot',
        'status',
        'decisao_em',
        'comentario',
        'ip',
        'user_agent',
        'session_id',
        'cookie_hash',
        'permitiu_reversao_ate',
    ];

    protected $casts = [
        'decisao_em'             => 'datetime',
        'permitiu_reversao_ate'  => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONAMENTOS
    |--------------------------------------------------------------------------
    */

    public function etapa()
    {
        return $this->belongsTo(AprovacaoSolicitacaoEtapa::class, 'solicitacao_etapa_id', 'id');
    }

    public function colaborador()
    {
        return $this->belongsTo(Colaborador::class, 'colaborador_id', 'id');
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
