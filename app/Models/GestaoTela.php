<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GestaoTela extends Model
{
    protected $table = 'gestao_tela';

    protected $fillable = [
        'modulo_id',
        'nome_tela',
        'icone',
        'slug',
    ];

    public function permissoesLogin(): HasMany
    {
        return $this->hasMany(GestaoPermissao::class, 'login_tela_id');
    }
}
