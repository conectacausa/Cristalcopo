<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Colaborador extends Authenticatable
{
    use SoftDeletes;

    protected $table = 'colaboradores';

    protected $hidden = [
        'senha',
    ];

    protected $fillable = [
        'nome_completo',
        'matricula',
        'codigo_importacao',
        'nome_social',
        'cpf',
        'data_nascimento',
        'senha',
        'situacao',
        'permissao_id',
        'foto',
        'regime',
        'forma_trabalho',
        'cargo_id',
        'filial_id',
        'setor_id',
        'superior_imediato_id',
        'admissao',
        'desligamento',
        'pcd',
        'afastado',
        'menor_aprendiz',
        'raca_cor',
        'nacionalidade',
        'naturalidade',
        'genero',
        'calcula_headcount',
        'estabilidade',
        'email',
        'telefone',
        'celular',
        'logradouro',
        'numero_casa',
        'complemento',
        'bairro',
        'cidade_id',
        'estado_id',
        'pais_id',
        'cep',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'admissao' => 'date',
        'desligamento' => 'date',
        'situacao' => 'boolean',
        'pcd' => 'boolean',
        'afastado' => 'boolean',
        'menor_aprendiz' => 'boolean',
        'calcula_headcount' => 'boolean',
    ];

    public function getAuthPassword()
    {
        return $this->senha;
    }

    public function permissao()
    {
        return $this->belongsTo(GestaoPermissao::class, 'permissao_id');
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'cargo_id');
    }

    public function filial()
    {
        return $this->belongsTo(EmpresaFilial::class, 'filial_id');
    }

    public function setor()
    {
        return $this->belongsTo(EmpresaSetor::class, 'setor_id');
    }

    public function superiorImediato()
    {
        return $this->belongsTo(self::class, 'superior_imediato_id');
    }
}
