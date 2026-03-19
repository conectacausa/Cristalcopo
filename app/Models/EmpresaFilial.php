<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmpresaFilial extends Model
{
    use SoftDeletes;

    protected $table = 'empresa_filial';

    protected $fillable = [
        'razao_social',
        'nome_fantasia',
        'cnpj',
        'inscricao_estadual',
        'email',
        'telefone',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'uf',
        'pais',
        'codigo_ibge',
        'porte_id',
        'natureza_juridica_id',
        'situacao',
    ];

    public function getNomeAttribute()
    {
        return $this->nome_fantasia;
    }

    public function porte()
    {
        return $this->belongsTo(EmpresaPorte::class, 'porte_id');
    }

    public function naturezaJuridica()
    {
        return $this->belongsTo(EmpresaNaturezaJuridica::class, 'natureza_juridica_id');
    }

    public function setores()
    {
        return $this->belongsToMany(
            Setor::class,
            'setor_filial',
            'filial_id',
            'setor_id'
        );
    }
}
