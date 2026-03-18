<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmpresaCnae extends Model
{
    use SoftDeletes;

    protected $table = 'empresa_cnae';

    protected $fillable = [
        'sessao',
        'divisao',
        'grupo',
        'classe',
        'subclasse',
        'descricao',
        'nota_explicativa',
    ];

    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }

    public function vinculosFiliais(): HasMany
    {
        return $this->hasMany(VinculoFilialXCnae::class, 'cnae_id');
    }
}
