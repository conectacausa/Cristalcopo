<?php

namespace App\Models\Cargos;

use App\Models\CargoCbo;
use App\Models\EmpresaFilial;
use App\Models\EmpresaSetor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cargo extends Model
{
    use SoftDeletes;

    protected $table = 'cargos';

    protected $fillable = [
        'titulo_cargo',
        'codigo_importacao',
        'cargo_cbo_id',
        'aprovacao_solicitacao_id',
        'status_aprovacao',
        'conta_base_jovem_aprendiz',
    ];

    protected $casts = [
        'conta_base_jovem_aprendiz' => 'boolean',
    ];

    public function cbo()
    {
        return $this->belongsTo(CargoCbo::class, 'cargo_cbo_id');
    }

    public function filiais()
    {
        return $this->belongsToMany(
            EmpresaFilial::class,
            'vinculo_cargo_x_filial',
            'cargo_id',
            'filial_id'
        );
    }

    public function setores()
    {
        return $this->belongsToMany(
            EmpresaSetor::class,
            'vinculo_cargo_x_setor',
            'cargo_id',
            'setor_id'
        );
    }

    public function getStatusFormatadoAttribute(): string
    {
        return match ($this->status_aprovacao) {
            'rascunho' => 'Rascunho',
            'pendente_aprovacao' => 'Pendente Aprovação',
            'em_aprovacao' => 'Em Aprovação',
            'aprovado' => 'Aprovado',
            'reprovado' => 'Reprovado',
            'cancelado' => 'Cancelado',
            default => ucfirst(str_replace('_', ' ', $this->status_aprovacao)),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status_aprovacao) {
            'aprovado' => 'badge badge-success',
            'reprovado' => 'badge badge-danger',
            'cancelado' => 'badge badge-dark',
            'pendente_aprovacao', 'em_aprovacao' => 'badge badge-warning',
            default => 'badge badge-secondary',
        };
    }
}
