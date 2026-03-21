<?php

namespace App\Models\Cargos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Curso extends Model
{
    use SoftDeletes;

    protected $table = 'cargos_cursos';

    protected $fillable = [
        'descricao',
    ];
}
