<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AprovacaoConfiguracao extends Model
{
    protected $table = 'aprovacao_configuracoes';

    protected $fillable = [
        'tipo_referencia',
        'fluxo_id',
        'ativo'
    ];

    public function fluxo()
    {
        return $this->belongsTo(AprovacaoFluxo::class, 'fluxo_id');
    }
}
