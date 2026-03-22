<?php

namespace App\Http\Controllers\Avaliacoes\Desempenho;

use App\Http\Controllers\Avaliacoes\Desempenho\Concerns\InteractsWithAvaliacaoDesempenho;
use App\Http\Controllers\Controller;
use App\Http\Requests\Avaliacoes\Desempenho\StoreAvaliacaoDesempenhoPerguntaRequest;
use App\Http\Requests\Avaliacoes\Desempenho\UpdateAvaliacaoDesempenhoPerguntaRequest;
use App\Models\Avaliacoes\Desempenho\AvaliacaoDesempenhoCiclo;
use App\Models\Avaliacoes\Desempenho\AvaliacaoDesempenhoPergunta;
use App\Models\Cargos\Cargo;
use App\Models\EmpresaFilial;
use App\Models\EmpresaSetor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PerguntaAvaliacaoController extends Controller
{
    use InteractsWithAvaliacaoDesempenho;

    public function create(int $ciclo): View
    {
        $cicloModel = AvaliacaoDesempenhoCiclo::with('pilares.grupos.subgrupos')->findOrFail($ciclo);

        return view('avaliacoes.desempenho.perguntas.form', [
            'ciclo' => $cicloModel,
            'pergunta' => new AvaliacaoDesempenhoPergunta([
                'obrigatoria' => true,
                'ordem' => 1,
                'ativa' => true,
                'permite_comentario' => false,
                'comentario_obrigatorio' => false,
                'tipo_resposta' => 'escala_1_5',
            ]),
            'opcoes' => [],
            'regras' => [],
        ] + $this->combos());
    }

    public function store(StoreAvaliacaoDesempenhoPerguntaRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $pergunta = DB::transaction(function () use ($validated) {
            $pergunta = AvaliacaoDesempenhoPergunta::create(collect($validated)->except('opcoes', 'regras')->all());
            $this->syncOpcoesPergunta($pergunta->id, $validated['opcoes'] ?? []);
            $this->syncRegras('pergunta', $pergunta->id, $validated['regras'] ?? []);
            return $pergunta;
        });

        return redirect()->route('avaliacoes.desempenho.ciclos.edit', $pergunta->ciclo_id)
            ->with('success', 'Pergunta cadastrada com sucesso.');
    }

    public function edit(int $pergunta): View
    {
        $model = AvaliacaoDesempenhoPergunta::with([
            'ciclo.pilares.grupos.subgrupos',
            'opcoes',
            'regrasAplicacao',
        ])->findOrFail($pergunta);

        return view('avaliacoes.desempenho.perguntas.form', [
            'ciclo' => $model->ciclo,
            'pergunta' => $model,
            'opcoes' => $model->opcoes->map(fn ($item) => $item->only(['texto', 'valor', 'ordem', 'ativa']))->all(),
            'regras' => $model->regrasAplicacao->map(fn ($item) => [
                'regra_tipo' => $item->regra_tipo,
                'referencia_id' => $item->referencia_id,
            ])->all(),
        ] + $this->combos());
    }

    public function update(UpdateAvaliacaoDesempenhoPerguntaRequest $request, int $pergunta): RedirectResponse
    {
        $model = AvaliacaoDesempenhoPergunta::findOrFail($pergunta);
        $validated = $request->validated();

        DB::transaction(function () use ($model, $validated) {
            $model->update(collect($validated)->except('opcoes', 'regras')->all());
            $this->syncOpcoesPergunta($model->id, $validated['opcoes'] ?? []);
            $this->syncRegras('pergunta', $model->id, $validated['regras'] ?? []);
        });

        return redirect()->route('avaliacoes.desempenho.ciclos.edit', $model->ciclo_id)
            ->with('success', 'Pergunta atualizada com sucesso.');
    }

    public function destroy(int $pergunta): RedirectResponse
    {
        $model = AvaliacaoDesempenhoPergunta::findOrFail($pergunta);
        $cicloId = $model->ciclo_id;
        $model->delete();

        return redirect()->route('avaliacoes.desempenho.ciclos.edit', $cicloId)
            ->with('success', 'Pergunta removida com sucesso.');
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
