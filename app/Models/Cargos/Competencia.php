<?php

namespace App\Models\Cargos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Competencia extends Model
{
    use SoftDeletes;

    protected $table = 'cargos_competencias';

    protected $fillable = [
        'descricao',
    ];
}
