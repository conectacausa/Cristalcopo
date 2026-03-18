<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmpresaSetor extends Model
{
    use SoftDeletes;

    protected $table = 'empresa_setores';

    protected $fillable = [
        'descricao'
    ];
}
