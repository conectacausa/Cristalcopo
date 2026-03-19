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
        'data_abertura',
        'porte_id',
        'nat_juridica_id',
        'tipo',
        'situacao',
        'telefone1',
        'telefone2',
        'email',
        'logradouro',
        'numero',
        'bairro',
        'cidade_id',
        'estado_id',
        'pais_id',
        'complemento',
    ];

    protected function casts(): array
    {
        return [
            'data_abertura' => 'date',
            'situacao' => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }

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
        return $this->belongsTo(EmpresaNaturezaJuridica::class, 'nat_juridica_id');
    }

    public function pais()
    {
        return $this->belongsTo(GestaoPais::class, 'pais_id');
    }

    public function estado()
    {
        return $this->belongsTo(GestaoEstado::class, 'estado_id');
    }

    public function cidade()
    {
        return $this->belongsTo(GestaoCidade::class, 'cidade_id');
    }

    public function setores()
    {
        return $this->belongsToMany(
            EmpresaSetor::class,
            'vinculo_filial_x_setor',
            'filial_id',
            'setor_id'
        );
    }
}
