<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GestaoEstado extends Model
{
    use SoftDeletes;

    protected $table = 'gestao_estado';

    protected $fillable = [
        'nome',
        'uf',
        'pais_id',
        'codigo_ibge',
    ];

    protected function casts(): array
    {
        return [
            'pais_id' => 'integer',
            'deleted_at' => 'datetime',
        ];
    }

    public function pais(): BelongsTo
    {
        return $this->belongsTo(GestaoPais::class, 'pais_id');
    }

    public function cidades(): HasMany
    {
        return $this->hasMany(GestaoCidade::class, 'estado_id');
    }

    public function filiais(): HasMany
    {
        return $this->hasMany(EmpresaFilial::class, 'estado_id');
    }
}
