<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VinculoPermissaoXTela extends Model
{
    protected $table = 'vinculo_permissao_x_tela';

    protected $fillable = [
        'permissao_id',
        'tela_id',
    ];

    public function permissao(): BelongsTo
    {
        return $this->belongsTo(GestaoPermissao::class, 'permissao_id');
    }

    public function tela(): BelongsTo
    {
        return $this->belongsTo(GestaoTela::class, 'tela_id');
    }
}
