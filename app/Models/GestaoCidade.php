<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GestaoCidade extends Model
{
    use SoftDeletes;

    protected $table = 'gestao_cidade';

    protected $fillable = [
        'nome',
        'estado_id',
        'codigo_ibge',
    ];

    protected function casts(): array
    {
        return [
            'estado_id' => 'integer',
            'deleted_at' => 'datetime',
        ];
    }

    public function estado(): BelongsTo
    {
        return $this->belongsTo(GestaoEstado::class, 'estado_id');
    }

    public function filiais(): HasMany
    {
        return $this->hasMany(EmpresaFilial::class, 'cidade_id');
    }
}
