<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AprovacaoFluxoEtapaAprovador extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'aprovacao_fluxo_etapa_aprovador';

    protected $fillable = [
        'fluxo_etapa_id',
        'colaborador_id',
        'obrigatorio',
        'ordem_interna',
    ];

    protected $casts = [
        'obrigatorio' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONAMENTOS
    |--------------------------------------------------------------------------
    */

    public function etapa()
    {
        return $this->belongsTo(AprovacaoFluxoEtapa::class, 'fluxo_etapa_id', 'id');
    }

    public function colaborador()
    {
        return $this->belongsTo(Colaborador::class, 'colaborador_id', 'id');
    }
}
