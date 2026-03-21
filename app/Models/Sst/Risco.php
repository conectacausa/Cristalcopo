<?php

namespace App\Models\Sst;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Risco extends Model
{
    use SoftDeletes;

    protected $table = 'sst_riscos';

    protected $fillable = [
        'descricao',
        'grupo_risco',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];
}
