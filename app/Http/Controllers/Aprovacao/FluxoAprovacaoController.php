<?php

namespace App\Http\Controllers\Aprovacao;

use App\Http\Controllers\Controller;
use App\Models\AprovacaoFluxo;
use App\Models\Colaborador;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FluxoAprovacaoController extends Controller
{
    public function index(Request $request)
{
    $query = AprovacaoFluxo::query()->orderBy('id', 'desc');

    if ($request->filled('descricao')) {
        $query->where('nome_fluxo', 'ilike', '%' . $request->descricao . '%');
    }

    $fluxos = $query->get();

    return view('aprovacao.fluxo.index', compact('fluxos'));
}

    public function create()
    {
        $colaboradores = Colaborador::orderBy('nome')->get();

        return view('aprovacao.fluxo.create', compact('colaboradores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome_fluxo' => 'required|string|max:150',
            'descricao' => 'nullable|string',
            'tipo_referencia' => 'required|string|max:100',
            'modo_aprovacao' => 'required|in:sequencial,paralelo',
            'permite_reprovacao' => 'required|boolean',
            'permite_retorno' => 'required|boolean',
            'situacao' => 'required|in:ativo,inativo',

            'etapas' => 'required|array|min:1',
            'etapas.*.nome_etapa' => 'required|string|max:150',
            'etapas.*.ordem' => 'required|integer|min:1',
            'etapas.*.tipo_aprovacao_etapa' => 'required|in:unanimidade,qualquer_um,maioria',
            'etapas.*.quantidade_minima_aprovacao' => 'nullable|integer|min:1',
            'etapas.*.aprovadores' => 'required|array|min:1',
            'etapas.*.aprovadores.*' => 'required|integer|exists:colaboradores,id',
        ]);

        $fluxo = AprovacaoFluxo::create([
            'nome_fluxo' => $request->nome_fluxo,
            'slug' => Str::slug($request->nome_fluxo),
            'descricao' => $request->descricao,
            'tipo_referencia' => $request->tipo_referencia,
            'modo_aprovacao' => $request->modo_aprovacao,
            'permite_reprovacao' => $request->permite_reprovacao,
            'permite_retorno' => $request->permite_retorno,
            'situacao' => $request->situacao,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        foreach ($request->etapas as $etapaData) {
            $etapa = $fluxo->etapas()->create([
                'nome_etapa' => $etapaData['nome_etapa'],
                'ordem' => $etapaData['ordem'],
                'tipo_aprovacao_etapa' => $etapaData['tipo_aprovacao_etapa'],
                'quantidade_minima_aprovacao' => $etapaData['quantidade_minima_aprovacao'] ?? null,
                'situacao' => 'ativo',
            ]);

            foreach ($etapaData['aprovadores'] as $index => $colaboradorId) {
                $etapa->aprovadores()->create([
                    'colaborador_id' => $colaboradorId,
                    'obrigatorio' => true,
                    'ordem_interna' => $index + 1,
                ]);
            }
        }

        return redirect()
            ->route('aprovacao.fluxo.index')
            ->with('success', 'Fluxo de aprovação cadastrado com sucesso.');
    }
}
