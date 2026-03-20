<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AprovacaoLog extends Model
{
    use HasFactory;

    protected $table = 'aprovacao_log';

    public $timestamps = false; // usamos created_at manual

    protected $fillable = [
        'solicitacao_id',
        'solicitacao_etapa_id',
        'colaborador_id',
        'evento',
        'descricao',
        'ip',
        'user_agent',
        'session_id',
        'cookie_hash',
        'payload_json',
        'created_at',
    ];

    protected $casts = [
        'payload_json' => 'array',
        'created_at'   => 'datetime',
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

    public function etapa()
    {
        return $this->belongsTo(AprovacaoSolicitacaoEtapa::class, 'solicitacao_etapa_id', 'id');
    }

    public function colaborador()
    {
        return $this->belongsTo(Colaborador::class, 'colaborador_id', 'id');
    }
}
