<?php

namespace App\Models\Cargos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Formacao extends Model
{
    use SoftDeletes;

    protected $table = 'cargos_formacoes';

    protected $fillable = [
        'descricao',
    ];
}
