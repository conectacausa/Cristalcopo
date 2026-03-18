<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class VinculoFilialXCnae extends Model
{
    use SoftDeletes;

    protected $table = 'vinculo_filial_x_cnae';

    protected $fillable = [
        'filial_id',
        'cnae_id',
        'principal',
    ];

    protected function casts(): array
    {
        return [
            'filial_id' => 'integer',
            'cnae_id' => 'integer',
            'principal' => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }

    public function filial(): BelongsTo
    {
        return $this->belongsTo(EmpresaFilial::class, 'filial_id');
    }

    public function cnae(): BelongsTo
    {
        return $this->belongsTo(EmpresaCnae::class, 'cnae_id');
    }
}
