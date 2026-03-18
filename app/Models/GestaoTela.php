<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GestaoTela extends Model
{
    protected $table = 'gestao_tela';

    protected $fillable = [
        'modulo_id',
        'nome_tela',
        'icone',
        'slug',
    ];
}
