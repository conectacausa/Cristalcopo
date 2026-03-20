<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AprovacaoFluxoEtapa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'aprovacao_fluxo_etapa';

    protected $fillable = [
        'fluxo_id',
        'nome_etapa',
        'ordem',
        'tipo_aprovacao_etapa',
        'quantidade_minima_aprovacao',
        'situacao',
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

    public function aprovadores()
    {
        return $this->hasMany(AprovacaoFluxoEtapaAprovador::class, 'fluxo_etapa_id', 'id')
            ->orderBy('ordem_interna');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isUnanimidade()
    {
        return $this->tipo_aprovacao_etapa === 'unanimidade';
    }

    public function isQualquerUm()
    {
        return $this->tipo_aprovacao_etapa === 'qualquer_um';
    }

    public function isMaioria()
    {
        return $this->tipo_aprovacao_etapa === 'maioria';
    }
}
