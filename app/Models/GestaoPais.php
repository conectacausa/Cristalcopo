<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GestaoPais extends Model
{
    use SoftDeletes;

    protected $table = 'gestao_pais';

    protected $fillable = [
        'nome',
        'iso2',
        'iso3',
    ];

    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }

    public function estados(): HasMany
    {
        return $this->hasMany(GestaoEstado::class, 'pais_id');
    }

    public function filiais(): HasMany
    {
        return $this->hasMany(EmpresaFilial::class, 'pais_id');
    }
}
