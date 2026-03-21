<?php

namespace App\Models\Cargos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Responsabilidade extends Model
{
    use SoftDeletes;

    protected $table = 'cargos_responsabilidades';

    protected $fillable = [
        'descricao',
    ];
}
