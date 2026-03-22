<?php

namespace App\Models\Avaliacoes\Desempenho;

use Illuminate\Database\Eloquent\Model;

class AvaliacaoDesempenhoRegraAplicacao extends Model
{
    protected $table = 'avaliacao_desempenho_regras_aplicacao';

    protected $fillable = ['escopo_tipo', 'escopo_id', 'regra_tipo', 'referencia_id'];

    public const ESCOPOS = ['pilar', 'grupo', 'subgrupo', 'pergunta'];
    public const TIPOS = ['cargo', 'filial', 'setor'];
}
