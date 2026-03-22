<?php

namespace App\Http\Controllers\Avaliacoes\Desempenho;

use App\Http\Controllers\Avaliacoes\Desempenho\Concerns\InteractsWithAvaliacaoDesempenho;
use App\Http\Controllers\Controller;
use App\Http\Requests\Avaliacoes\Desempenho\StoreAvaliacaoDesempenhoGrupoRequest;
use App\Http\Requests\Avaliacoes\Desempenho\UpdateAvaliacaoDesempenhoGrupoRequest;
use App\Models\Avaliacoes\Desempenho\AvaliacaoDesempenhoGrupo;
use App\Models\Avaliacoes\Desempenho\AvaliacaoDesempenhoPilar;
use App\Models\Cargos\Cargo;
use App\Models\EmpresaFilial;
use App\Models\EmpresaSetor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class GrupoAvaliacaoController extends Controller
{
    use InteractsWithAvaliacaoDesempenho;

    public function create(int $pilar): View
    {
        return view('avaliacoes.desempenho.grupos.form', [
            'pilar' => AvaliacaoDesempenhoPilar::with('ciclo')->findOrFail($pilar),
            'grupo' => new AvaliacaoDesempenhoGrupo(['ativo' => true, 'ordem' => 1]),
            'regras' => [],
        ] + $this->combos());
    }

    public function store(StoreAvaliacaoDesempenhoGrupoRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $grupo = DB::transaction(function () use ($validated) {
            $grupo = AvaliacaoDesempenhoGrupo::create(collect($validated)->except('regras')->all());
            $this->syncRegras('grupo', $grupo->id, $validated['regras'] ?? []);
            return $grupo;
        });

        return redirect()->route('avaliacoes.desempenho.ciclos.edit', $grupo->pilar->ciclo_id)
            ->with('success', 'Grupo cadastrado com sucesso.');
    }

    public function edit(int $grupo): View
    {
        $grupoModel = AvaliacaoDesempenhoGrupo::with(['pilar.ciclo', 'regrasAplicacao'])->findOrFail($grupo);

        return view('avaliacoes.desempenho.grupos.form', [
            'pilar' => $grupoModel->pilar,
            'grupo' => $grupoModel,
            'regras' => $grupoModel->regrasAplicacao->map(fn ($item) => [
                'regra_tipo' => $item->regra_tipo,
                'referencia_id' => $item->referencia_id,
            ])->all(),
        ] + $this->combos());
    }

    public function update(UpdateAvaliacaoDesempenhoGrupoRequest $request, int $grupo): RedirectResponse
    {
        $model = AvaliacaoDesempenhoGrupo::with('pilar')->findOrFail($grupo);
        $validated = $request->validated();

        DB::transaction(function () use ($model, $validated) {
            $model->update(collect($validated)->except('regras')->all());
            $this->syncRegras('grupo', $model->id, $validated['regras'] ?? []);
        });

        return redirect()->route('avaliacoes.desempenho.ciclos.edit', $model->pilar->ciclo_id)
            ->with('success', 'Grupo atualizado com sucesso.');
    }

    public function destroy(int $grupo): RedirectResponse
    {
        $model = AvaliacaoDesempenhoGrupo::with('pilar')->findOrFail($grupo);
        $cicloId = $model->pilar->ciclo_id;
        $model->delete();

        return redirect()->route('avaliacoes.desempenho.ciclos.edit', $cicloId)
            ->with('success', 'Grupo removido com sucesso.');
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
