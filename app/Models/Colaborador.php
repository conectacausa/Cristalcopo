<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Colaborador extends Authenticatable
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
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'data_nascimento' => 'date',
            'situacao' => 'boolean',
        ];
    }

    public function getAuthPassword(): string
    {
        return $this->senha;
    }
}
