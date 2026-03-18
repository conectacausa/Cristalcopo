<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VinculoFilialSetor extends Model
{
    use SoftDeletes;

    protected $table = 'vinculo_filial_x_setor';

    protected $fillable = [
        'id_filial',
        'id_setor'
    ];

    // 🔗 Relacionamentos (já deixa pronto)
    public function filial()
    {
        return $this->belongsTo(Filial::class, 'id_filial');
    }

    public function setor()
    {
        return $this->belongsTo(EmpresaSetor::class, 'id_setor');
    }
}
