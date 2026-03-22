<?php

namespace App\Http\Controllers\Pessoas\Colaboradores;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class ColaboradoresController extends Controller
{
    public function index(Request $request)
    {
        $permissoes = $this->getPermissoes();

        abort_unless($permissoes['pode_ler'], 403);

        if ($request->ajax()) {
            return $this->list($request);
        }

        $filtros = $this->extrairFiltros($request);
        $filiaisLista = $this->buscarFiliais();
        $setoresLista = $this->buscarSetores($filtros['filiais']);
        $cargosLista = $this->buscarCargos($filtros['setores']);
        $dados = $this->montarQueryColaboradores($filtros)
            ->orderBy('c.nome_completo')
            ->paginate(25)
            ->appends($request->query());

        return view('pessoas.colaboradores.index', compact(
            'permissoes',
            'filtros',
            'filiaisLista',
            'setoresLista',
            'cargosLista',
            'dados'
        ));
    }

    public function list(Request $request)
    {
        try {
            $permissoes = $this->getPermissoes();

            abort_unless($permissoes['pode_ler'], 403);

            $filtros = $this->extrairFiltros($request);
            $dados = $this->montarQueryColaboradores($filtros)
                ->orderBy('c.nome_completo')
                ->paginate(25)
                ->appends($request->query());

            return view('pessoas.colaboradores.partials.tabela', compact('dados', 'permissoes'))->render();
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Erro ao carregar colaboradores.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getSetores(Request $request)
    {
        $permissoes = $this->getPermissoes();

        abort_unless($permissoes['pode_ler'], 403);

        return response()->json($this->buscarSetores(
            collect((array) $request->input('filiais', []))
                ->filter()
                ->map(fn ($item) => (int) $item)
                ->values()
                ->all()
        ));
    }

    public function getCargos(Request $request)
    {
        $permissoes = $this->getPermissoes();

        abort_unless($permissoes['pode_ler'], 403);

        return response()->json($this->buscarCargos(
            collect((array) $request->input('setores', []))
                ->filter()
                ->map(fn ($item) => (int) $item)
                ->values()
                ->all()
        ));
    }

    public function destroy($id)
    {
        return $this->delete($id);
    }

    public function delete($id)
    {
        try {
            $permissoes = $this->getPermissoes();

            abort_unless($permissoes['pode_excluir'], 403);

            DB::table('colaboradores')
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->update([
                    'deleted_at' => now(),
                    'updated_at' => now(),
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Colaborador excluído com sucesso.',
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir colaborador.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function montarQueryColaboradores(array $filtros)
    {
        $query = DB::table('colaboradores as c')
            ->leftJoin('cargos as ca', 'ca.id', '=', 'c.cargo_id')
            ->leftJoin('empresa_setores as s', 's.id', '=', 'c.setor_id')
            ->leftJoin('empresa_filial as f', 'f.id', '=', 'c.filial_id')
            ->whereNull('c.deleted_at')
            ->select(
                'c.id',
                'c.nome_completo',
                'c.matricula',
                'c.cpf',
                'c.data_nascimento',
                'c.admissao as data_admissao',
                'c.desligamento as data_desligamento',
                'c.situacao',
                'ca.titulo_cargo as cargo_nome',
                's.descricao as setor_nome',
                'f.nome_fantasia as filial_nome'
            );

        if ($filtros['texto'] !== '') {
            $busca = $filtros['texto'];
            $buscaNumerica = preg_replace('/\D/', '', $busca);

            $query->where(function ($q) use ($busca, $buscaNumerica) {
                $q->where('c.nome_completo', 'ilike', "%{$busca}%")
                    ->orWhere('c.matricula', 'ilike', "%{$busca}%")
                    ->orWhere('c.cpf', 'ilike', "%{$busca}%");

                if ($buscaNumerica !== '') {
                    $q->orWhere('c.cpf', 'like', "%{$buscaNumerica}%");
                }
            });
        }

        if ($filtros['situacao'] === 'ativo') {
            $query->where('c.situacao', true);
        } elseif ($filtros['situacao'] === 'inativo') {
            $query->where('c.situacao', false);
        }

        if ($filtros['filiais'] !== []) {
            $query->whereIn('c.filial_id', $filtros['filiais']);
        }

        if ($filtros['setores'] !== []) {
            $query->whereIn('c.setor_id', $filtros['setores']);
        }

        if ($filtros['cargos'] !== []) {
            $query->whereIn('c.cargo_id', $filtros['cargos']);
        }

        return $query;
    }

    private function extrairFiltros(Request $request): array
    {
        return [
            'texto' => trim((string) $request->input('texto', $request->input('busca', ''))),
            'situacao' => (string) $request->input('situacao', 'ativo'),
            'filiais' => collect((array) $request->input('filiais', []))
                ->filter()
                ->map(fn ($item) => (int) $item)
                ->values()
                ->all(),
            'setores' => collect((array) $request->input('setores', []))
                ->filter()
                ->map(fn ($item) => (int) $item)
                ->values()
                ->all(),
            'cargos' => collect((array) $request->input('cargos', []))
                ->filter()
                ->map(fn ($item) => (int) $item)
                ->values()
                ->all(),
        ];
    }

    private function buscarFiliais()
    {
        return DB::table('empresa_filial')
            ->whereNull('deleted_at')
            ->orderBy('nome_fantasia')
            ->select('id', 'nome_fantasia')
            ->get();
    }

    private function buscarSetores(array $filiais)
    {
        if ($filiais === []) {
            return collect();
        }

        return DB::table('vinculo_filial_x_setor as v')
            ->join('empresa_setores as s', 's.id', '=', 'v.setor_id')
            ->whereIn('v.filial_id', $filiais)
            ->whereNull('s.deleted_at')
            ->select('s.id', 's.descricao')
            ->distinct()
            ->orderBy('s.descricao')
            ->get();
    }

    private function buscarCargos(array $setores)
    {
        if ($setores === []) {
            return collect();
        }

        return DB::table('vinculo_cargo_x_setor as v')
            ->join('cargos as c', 'c.id', '=', 'v.cargo_id')
            ->whereIn('v.setor_id', $setores)
            ->whereNull('c.deleted_at')
            ->select('c.id', 'c.titulo_cargo')
            ->distinct()
            ->orderBy('c.titulo_cargo')
            ->get();
    }

    private function getPermissoes(): array
    {
        $user = Auth::user();

        $registro = DB::table('vinculo_permissao_x_tela')
            ->join('gestao_tela', 'gestao_tela.id', '=', 'vinculo_permissao_x_tela.tela_id')
            ->where('vinculo_permissao_x_tela.permissao_id', $user->permissao_id)
            ->where('gestao_tela.slug', 'pessoas/colaboradores')
            ->select(
                DB::raw('COALESCE(vinculo_permissao_x_tela.pode_ler, false) as pode_ler'),
                DB::raw('COALESCE(vinculo_permissao_x_tela.pode_gravar, false) as pode_gravar'),
                DB::raw('COALESCE(vinculo_permissao_x_tela.pode_editar, false) as pode_editar'),
                DB::raw('COALESCE(vinculo_permissao_x_tela.pode_excluir, false) as pode_excluir')
            )
            ->first();

        return [
            'pode_ler' => (bool) ($registro->pode_ler ?? false),
            'pode_gravar' => (bool) ($registro->pode_gravar ?? false),
            'pode_editar' => (bool) ($registro->pode_editar ?? false),
            'pode_excluir' => (bool) ($registro->pode_excluir ?? false),
        ];
    }
}
