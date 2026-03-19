<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmpresaNaturezaJuridica extends Model
{
    use SoftDeletes;

    protected $table = 'empresa_natureza_juridica';

    protected $fillable = [
        'descricao',
    ];

    public function filiais()
    {
        return $this->hasMany(EmpresaFilial::class, 'natureza_juridica_id');
    }
}
