<?php

namespace App\Http\Controllers\Avaliacoes\Desempenho;

use App\Http\Controllers\Avaliacoes\Desempenho\Concerns\InteractsWithAvaliacaoDesempenho;
use App\Http\Controllers\Controller;
use App\Http\Requests\Avaliacoes\Desempenho\StoreAvaliacaoDesempenhoPilarRequest;
use App\Http\Requests\Avaliacoes\Desempenho\UpdateAvaliacaoDesempenhoPilarRequest;
use App\Models\Avaliacoes\Desempenho\AvaliacaoDesempenhoCiclo;
use App\Models\Avaliacoes\Desempenho\AvaliacaoDesempenhoPilar;
use App\Models\Cargos\Cargo;
use App\Models\EmpresaFilial;
use App\Models\EmpresaSetor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PilarAvaliacaoController extends Controller
{
    use InteractsWithAvaliacaoDesempenho;

    public function create(int $ciclo): View
    {
        return view('avaliacoes.desempenho.pilares.form', [
            'ciclo' => AvaliacaoDesempenhoCiclo::findOrFail($ciclo),
            'pilar' => new AvaliacaoDesempenhoPilar(['ativo' => true, 'ordem' => 1, 'peso' => 0]),
            'regras' => [],
        ] + $this->combos());
    }

    public function store(StoreAvaliacaoDesempenhoPilarRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $pilar = DB::transaction(function () use ($validated) {
            $pilar = AvaliacaoDesempenhoPilar::create(collect($validated)->except('regras')->all());
            $this->syncRegras('pilar', $pilar->id, $validated['regras'] ?? []);
            return $pilar;
        });

        return redirect()->route('avaliacoes.desempenho.ciclos.edit', $pilar->ciclo_id)
            ->with('success', 'Pilar cadastrado com sucesso.');
    }

    public function edit(int $pilar): View
    {
        $pilarModel = AvaliacaoDesempenhoPilar::with(['ciclo', 'regrasAplicacao'])->findOrFail($pilar);

        return view('avaliacoes.desempenho.pilares.form', [
            'ciclo' => $pilarModel->ciclo,
            'pilar' => $pilarModel,
            'regras' => $pilarModel->regrasAplicacao->map(fn ($item) => [
                'regra_tipo' => $item->regra_tipo,
                'referencia_id' => $item->referencia_id,
            ])->all(),
        ] + $this->combos());
    }

    public function update(UpdateAvaliacaoDesempenhoPilarRequest $request, int $pilar): RedirectResponse
    {
        $model = AvaliacaoDesempenhoPilar::findOrFail($pilar);
        $validated = $request->validated();

        DB::transaction(function () use ($model, $validated) {
            $model->update(collect($validated)->except('regras')->all());
            $this->syncRegras('pilar', $model->id, $validated['regras'] ?? []);
        });

        return redirect()->route('avaliacoes.desempenho.ciclos.edit', $model->ciclo_id)
            ->with('success', 'Pilar atualizado com sucesso.');
    }

    public function destroy(int $pilar): RedirectResponse
    {
        $model = AvaliacaoDesempenhoPilar::findOrFail($pilar);
        $cicloId = $model->ciclo_id;
        $model->delete();

        return redirect()->route('avaliacoes.desempenho.ciclos.edit', $cicloId)
            ->with('success', 'Pilar removido com sucesso.');
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
