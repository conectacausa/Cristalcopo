<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmpresaNatJuridica extends Model
{
    use SoftDeletes;

    protected $table = 'empresa_nat_juridica';

    protected $fillable = [
        'codigo',
        'descricao',
    ];

    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }

    public function filiais(): HasMany
    {
        return $this->hasMany(EmpresaFilial::class, 'natureza_juridica_id');
    }
}
