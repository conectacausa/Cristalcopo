<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VinculoPermissaoXTela extends Model
{
    protected $table = 'vinculo_permissao_x_tela';

    protected $fillable = [
        'permissao_id',
        'tela_id',
    ];
}
