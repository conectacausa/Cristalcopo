<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AprovacaoFluxo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'aprovacao_fluxo';

    protected $fillable = [
        'nome_fluxo',
        'slug',
        'descricao',
        'tipo_referencia',
        'modo_aprovacao',
        'permite_reprovacao',
        'permite_retorno',
        'situacao',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'permite_reprovacao' => 'boolean',
        'permite_retorno'    => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONAMENTOS
    |--------------------------------------------------------------------------
    */

    public function etapas()
    {
        return $this->hasMany(AprovacaoFluxoEtapa::class, 'fluxo_id', 'id')
            ->orderBy('ordem');
    }

    public function solicitacoes()
    {
        return $this->hasMany(AprovacaoSolicitacao::class, 'fluxo_id', 'id');
    }

    public function criador()
    {
        return $this->belongsTo(Colaborador::class, 'created_by', 'id');
    }

    public function editor()
    {
        return $this->belongsTo(Colaborador::class, 'updated_by', 'id');
    }
}
