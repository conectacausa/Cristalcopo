<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CargoCbo extends Model
{
    use SoftDeletes;

    protected $table = 'cargos_cbo';

    protected $fillable = [
        'codigo_cbo',
        'descricao_cbo',
    ];

    protected $dates = [
        'deleted_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONAMENTOS (FUTURO)
    |--------------------------------------------------------------------------
    */

    // Exemplo futuro:
    // public function colaboradores()
    // {
    //     return $this->hasMany(Colaborador::class, 'cargo_cbo_id');
    // }
}
