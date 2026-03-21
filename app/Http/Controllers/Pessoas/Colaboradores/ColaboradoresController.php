<?php

namespace App\Http\Controllers\Pessoas\Colaboradores;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class ColaboradoresController extends Controller
{
    public function index()
    {
        $permissoes = $this->getPermissoes();

        abort_unless($permissoes['pode_ler'], 403);

        return view('pessoas.colaboradores.index', compact('permissoes'));
    }

    public function list(Request $request)
    {
        try {
            $permissoes = $this->getPermissoes();

            abort_unless($permissoes['pode_ler'], 403);

            $query = DB::table('colaboradores as c')
                ->leftJoin('cargos as ca', 'ca.id', '=', 'c.cargo_id')
                ->leftJoin('empresa_setores as s', 's.id', '=', 'c.setor_id')
                ->leftJoin('empresa_filial as f', 'f.id', '=', 'c.filial_id')
                ->whereNull('c.deleted_at')
                ->select(
                    'c.id',
                    'c.nome_completo',
                    'c.cpf',
                    'c.data_nascimento',
                    'c.data_admissao',
                    'c.data_desligamento',
                    'ca.titulo_cargo as cargo_nome',
                    's.descricao as setor_nome',
                    'f.nome_fantasia as filial_nome'
                );

            // Filtro
            if ($request->filled('busca')) {
                $busca = trim($request->busca);

                $query->where(function ($q) use ($busca) {
                    $q->where('c.nome_completo', 'ilike', "%{$busca}%")
                      ->orWhere('c.cpf', 'ilike', "%{$busca}%");
                });
            }

            $dados = $query
                ->orderBy('c.nome_completo')
                ->paginate(25);

            return view('pessoas.colaboradores.partials.tabela', compact('dados', 'permissoes'))->render();

        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Erro ao carregar colaboradores.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $permissoes = $this->getPermissoes();

            abort_unless($permissoes['pode_excluir'], 403);

            DB::table('colaboradores')
                ->where('id', $id)
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
