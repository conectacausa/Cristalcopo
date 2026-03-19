<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmpresaSetor extends Model
{
    use SoftDeletes;

    protected $table = 'empresa_setores';

    protected $fillable = [
        'descricao',
    ];

    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }

    // Alias padrão do sistema
    public function getNomeAttribute()
    {
        return $this->descricao;
    }

    public function filiais()
    {
        return $this->belongsToMany(
            EmpresaFilial::class,
            'vinculo_filial_x_setor',
            'setor_id',
            'filial_id'
        );
    }
}
