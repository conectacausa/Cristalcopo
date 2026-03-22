<?php

namespace App\Http\Controllers\Avaliacoes\Desempenho;

use App\Http\Controllers\Avaliacoes\Desempenho\Concerns\InteractsWithAvaliacaoDesempenho;
use App\Http\Controllers\Controller;
use App\Http\Requests\Avaliacoes\Desempenho\StoreAvaliacaoDesempenhoSubgrupoRequest;
use App\Http\Requests\Avaliacoes\Desempenho\UpdateAvaliacaoDesempenhoSubgrupoRequest;
use App\Models\Avaliacoes\Desempenho\AvaliacaoDesempenhoGrupo;
use App\Models\Avaliacoes\Desempenho\AvaliacaoDesempenhoSubgrupo;
use App\Models\Cargos\Cargo;
use App\Models\EmpresaFilial;
use App\Models\EmpresaSetor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SubgrupoAvaliacaoController extends Controller
{
    use InteractsWithAvaliacaoDesempenho;

    public function create(int $grupo): View
    {
        return view('avaliacoes.desempenho.subgrupos.form', [
            'grupo' => AvaliacaoDesempenhoGrupo::with('pilar.ciclo')->findOrFail($grupo),
            'subgrupo' => new AvaliacaoDesempenhoSubgrupo(['ativo' => true, 'ordem' => 1]),
            'regras' => [],
        ] + $this->combos());
    }

    public function store(StoreAvaliacaoDesempenhoSubgrupoRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $subgrupo = DB::transaction(function () use ($validated) {
            $subgrupo = AvaliacaoDesempenhoSubgrupo::create(collect($validated)->except('regras')->all());
            $this->syncRegras('subgrupo', $subgrupo->id, $validated['regras'] ?? []);
            return $subgrupo;
        });

        return redirect()->route('avaliacoes.desempenho.ciclos.edit', $subgrupo->grupo->pilar->ciclo_id)
            ->with('success', 'Subgrupo cadastrado com sucesso.');
    }

    public function edit(int $subgrupo): View
    {
        $model = AvaliacaoDesempenhoSubgrupo::with(['grupo.pilar.ciclo', 'regrasAplicacao'])->findOrFail($subgrupo);

        return view('avaliacoes.desempenho.subgrupos.form', [
            'grupo' => $model->grupo,
            'subgrupo' => $model,
            'regras' => $model->regrasAplicacao->map(fn ($item) => [
                'regra_tipo' => $item->regra_tipo,
                'referencia_id' => $item->referencia_id,
            ])->all(),
        ] + $this->combos());
    }

    public function update(UpdateAvaliacaoDesempenhoSubgrupoRequest $request, int $subgrupo): RedirectResponse
    {
        $model = AvaliacaoDesempenhoSubgrupo::with('grupo.pilar')->findOrFail($subgrupo);
        $validated = $request->validated();

        DB::transaction(function () use ($model, $validated) {
            $model->update(collect($validated)->except('regras')->all());
            $this->syncRegras('subgrupo', $model->id, $validated['regras'] ?? []);
        });

        return redirect()->route('avaliacoes.desempenho.ciclos.edit', $model->grupo->pilar->ciclo_id)
            ->with('success', 'Subgrupo atualizado com sucesso.');
    }

    public function destroy(int $subgrupo): RedirectResponse
    {
        $model = AvaliacaoDesempenhoSubgrupo::with('grupo.pilar')->findOrFail($subgrupo);
        $cicloId = $model->grupo->pilar->ciclo_id;
        $model->delete();

        return redirect()->route('avaliacoes.desempenho.ciclos.edit', $cicloId)
            ->with('success', 'Subgrupo removido com sucesso.');
    }

    private function combos(): array
    {
        return [
            'filiais' => EmpresaFilial::query()->orderBy('nome_fantasia')->get(['id', 'nome_fantasia']),
            'setores' => EmpresaSetor::query()->orderBy('descricao')->get(['id', 'descricao']),
            'cargos' => Cargo::query()->orderBy('titulo_cargo')->get(['id', 'titulo_cargo']),
        ];
    }
}
