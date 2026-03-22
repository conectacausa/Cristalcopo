<?php

namespace App\Http\Controllers\Avaliacoes\Desempenho;

use App\Http\Controllers\Avaliacoes\Desempenho\Concerns\InteractsWithAvaliacaoDesempenho;
use App\Http\Controllers\Controller;
use App\Http\Requests\Avaliacoes\Desempenho\StoreAvaliacaoDesempenhoCicloRequest;
use App\Http\Requests\Avaliacoes\Desempenho\UpdateAvaliacaoDesempenhoCicloRequest;
use App\Models\Avaliacoes\Desempenho\AvaliacaoDesempenhoCiclo;
use App\Models\Colaborador;
use App\Models\EmpresaFilial;
use App\Models\EmpresaSetor;
use App\Models\Cargos\Cargo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CicloAvaliacaoController extends Controller
{
    use InteractsWithAvaliacaoDesempenho;

    public function index(Request $request): View
    {
        $query = AvaliacaoDesempenhoCiclo::query()
            ->withCount(['pilares', 'perguntas', 'publicoAlvo', 'avaliacoes'])
            ->orderByDesc('id');

        if ($request->filled('busca')) {
            $busca = trim((string) $request->input('busca'));
            $query->where('nome', 'ilike', "%{$busca}%");
        }

        $ciclos = $query->paginate(15)->withQueryString();

        return view('avaliacoes.desempenho.ciclos.index', compact('ciclos'));
    }

    public function create(): View
    {
        return view('avaliacoes.desempenho.ciclos.form', [
            'ciclo' => new AvaliacaoDesempenhoCiclo([
                'status' => 'rascunho',
                'tipo_avaliacao' => '90',
                'forma_liberacao' => 'manual',
                'publico_tipo' => 'todos',
                'permite_autoavaliacao' => true,
                'permite_avaliacao_gestor' => true,
                'permite_avaliacao_pares' => false,
                'permite_avaliacao_subordinados' => false,
                'anonimato' => false,
                'permite_edicao_ate_prazo_final' => false,
                'permite_resposta_parcial' => false,
                'lembrete_ativo' => false,
                'lembrete_parar_ao_responder' => true,
                'lembrete_final_antes_encerramento' => false,
                'lembrete_canais' => [],
            ]),
            'publicoSelecionado' => [],
        ] + $this->carregarCombos());
    }

    public function store(StoreAvaliacaoDesempenhoCicloRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $ciclo = DB::transaction(function () use ($validated) {
            $ciclo = AvaliacaoDesempenhoCiclo::create($this->extrairDadosCiclo($validated));
            $this->syncPublicoAlvo($ciclo->id, $validated['publico_alvo'] ?? []);

            return $ciclo;
        });

        return redirect()
            ->route('avaliacoes.desempenho.ciclos.edit', $ciclo->id)
            ->with('success', 'Ciclo de avaliação cadastrado com sucesso.');
    }

    public function edit(int $id): View
    {
        $ciclo = AvaliacaoDesempenhoCiclo::query()
            ->with([
                'publicoAlvo',
                'pilares.grupos.subgrupos',
                'perguntas.grupo',
                'avaliacoes.colaborador',
            ])
            ->findOrFail($id);

        return view('avaliacoes.desempenho.ciclos.form', [
            'ciclo' => $ciclo,
            'publicoSelecionado' => $ciclo->publicoAlvo
                ->map(fn ($item) => ['tipo' => $item->tipo, 'referencia_id' => $item->referencia_id])
                ->all(),
        ] + $this->carregarCombos());
    }

    public function update(UpdateAvaliacaoDesempenhoCicloRequest $request, int $ciclo): RedirectResponse
    {
        $model = AvaliacaoDesempenhoCiclo::query()->findOrFail($ciclo);
        $validated = $request->validated();

        DB::transaction(function () use ($model, $validated) {
            $model->update($this->extrairDadosCiclo($validated));
            $this->syncPublicoAlvo($model->id, $validated['publico_alvo'] ?? []);
        });

        return redirect()
            ->route('avaliacoes.desempenho.ciclos.edit', $model->id)
            ->with('success', 'Ciclo de avaliação atualizado com sucesso.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $ciclo = AvaliacaoDesempenhoCiclo::query()->findOrFail($id);
        $ciclo->delete();

        return redirect()
            ->route('avaliacoes.desempenho.ciclos.index')
            ->with('success', 'Ciclo removido com sucesso.');
    }

    private function extrairDadosCiclo(array $validated): array
    {
        return collect($validated)
            ->except('publico_alvo')
            ->all();
    }

    private function carregarCombos(): array
    {
        return [
            'filiais' => EmpresaFilial::query()->orderBy('nome_fantasia')->get(['id', 'nome_fantasia']),
            'setores' => EmpresaSetor::query()->orderBy('descricao')->get(['id', 'descricao']),
            'cargos' => Cargo::query()->orderBy('titulo_cargo')->get(['id', 'titulo_cargo']),
            'colaboradores' => Colaborador::query()->orderBy('nome_completo')->get(['id', 'nome_completo']),
        ];
    }
}
