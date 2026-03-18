<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GestaoPermissao extends Model
{
    protected $table = 'gestao_permissao';

    protected $fillable = [
        'nome_grupo',
        'situacao',
        'login_tela_id',
    ];

    protected function casts(): array
    {
        return [
            'situacao' => 'boolean',
        ];
    }

    public function telaLogin(): BelongsTo
    {
        return $this->belongsTo(GestaoTela::class, 'login_tela_id');
    }

    public function colaboradores(): HasMany
    {
        return $this->hasMany(Colaborador::class, 'permissao_id');
    }
}
