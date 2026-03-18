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
        'cnpj',
        'nome_fantasia',
    ];

    public function getNomeAttribute()
    {
        return $this->nome_fantasia;
    }
}
