<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmpresaNaturezaJuridica extends Model
{
    use SoftDeletes;

    protected $table = 'empresa_nat_juridica'; // ✅ NOME REAL DO BANCO

    protected $fillable = [
        'codigo',
        'descricao',
    ];

    public function filiais()
    {
        return $this->hasMany(EmpresaFilial::class, 'nat_juridica_id');
    }
}
