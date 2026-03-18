<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GestaoPermissao extends Model
{
    protected $table = 'gestao_permissao';

    protected $fillable = [
        'nome_grupo',
        'situacao',
        'login_tela_id',
    ];

    protected $casts = [
        'situacao' => 'boolean',
    ];

    public function loginTela()
    {
        return $this->belongsTo(GestaoTela::class, 'login_tela_id');
    }

    public function colaboradores()
    {
        return $this->hasMany(Colaborador::class, 'permissao_id');
    }
}
