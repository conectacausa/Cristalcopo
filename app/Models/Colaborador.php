<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Colaborador extends Model
{
    use SoftDeletes;

    protected $table = 'colaboradores';

    protected $fillable = [
        'nome_completo',
        'cpf',
        'data_nascimento',
        'senha',
        'situacao',
        'permissao_id',
    ];

    protected $hidden = [
        'senha',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'situacao' => 'boolean',
    ];

    public function permissao()
    {
        return $this->belongsTo(GestaoPermissao::class, 'permissao_id');
    }
}
