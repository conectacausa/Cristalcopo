<?php

namespace App\Models\Cargos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Escolaridade extends Model
{
    use SoftDeletes;

    protected $table = 'cargos_escolaridades';

    protected $fillable = [
        'descricao',
        'ordem',
    ];
}
