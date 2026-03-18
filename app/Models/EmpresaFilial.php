<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmpresaFilial extends Model
{
    use SoftDeletes;

    protected $table = 'empresa_filial';

    protected $fillable = [
        'razao_social',
        'cnpj',
        'nome_fantasia',
        'data_abertura',
        'porte_id',
        'natureza_juridica_id',
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
        'cep',
    ];

    protected function casts(): array
    {
        return [
            'data_abertura' => 'date',
            'porte_id' => 'integer',
            'natureza_juridica_id' => 'integer',
            'cidade_id' => 'integer',
            'estado_id' => 'integer',
            'pais_id' => 'integer',
            'situacao' => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }

    public function porte(): BelongsTo
    {
        return $this->belongsTo(EmpresaPorte::class, 'porte_id');
    }

    public function naturezaJuridica(): BelongsTo
    {
        return $this->belongsTo(EmpresaNatJuridica::class, 'natureza_juridica_id');
    }

    public function pais(): BelongsTo
    {
        return $this->belongsTo(GestaoPais::class, 'pais_id');
    }

    public function estado(): BelongsTo
    {
        return $this->belongsTo(GestaoEstado::class, 'estado_id');
    }

    public function cidade(): BelongsTo
    {
        return $this->belongsTo(GestaoCidade::class, 'cidade_id');
    }
}
