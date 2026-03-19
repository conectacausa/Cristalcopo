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

    public function getNomeAttribute()
    {
        return $this->descricao;
    }

    public function filiais()
    {
        $filialClass = class_exists(\App\Models\EmpresaFilial::class)
            ? \App\Models\EmpresaFilial::class
            : \App\Models\Gestao\EmpresaFilial::class;

        return $this->belongsToMany(
            $filialClass,
            'vinculo_filial_x_setor',
            'setor_id',
            'filial_id'
        );
    }
}
