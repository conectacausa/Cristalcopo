<?php

namespace App\Http\Controllers\Sst;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class RiscosController extends Controller
{
    public function index()
    {
        $permissoes = $this->getPermissoes();

        abort_unless($permissoes['pode_ler'], 403);

        return view('sst.riscos.index', compact('permissoes'));
    }

    public function list(Request $request)
    {
        try {
            $permissoes = $this->getPermissoes();

            abort_unless($permissoes['pode_ler'], 403);

            $query = DB::table('sst_riscos')
                ->whereNull('deleted_at');

            if ($request->filled('busca')) {
                $busca = trim($request->busca);

                $query->where(function ($q) use ($busca) {
                    $q->where('descricao', 'ilike', '%' . $busca . '%')
                      ->orWhere('grupo_risco', 'ilike', '%' . $busca . '%');
                });
            }

            if ($request->filled('ativo')) {
                $query->where('ativo', filter_var($request->ativo, FILTER_VALIDATE_BOOLEAN));
            }

            $dados = $query
                ->orderBy('descricao')
                ->paginate(25);

            return view('sst.riscos.partials.table', compact('dados', 'permissoes'))->render();
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Erro ao carregar riscos.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $permissoes = $this->getPermissoes();

        abort_unless($permissoes['pode_gravar'], 403);

        $request->validate([
            'descricao' => ['required', 'string', 'max:150'],
            'grupo_risco' => ['nullable', 'string', 'max:50'],
            'ativo' => ['nullable'],
        ]);

        try {
            DB::table('sst_riscos')->insert([
                'descricao' => trim($request->descricao),
                'grupo_risco' => filled($request->grupo_risco) ? trim($request->grupo_risco) : null,
                'ativo' => $request->boolean('ativo', true),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Risco ocupacional salvo com sucesso.',
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar risco ocupacional.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $permissoes = $this->getPermissoes();

            abort_unless($permissoes['pode_editar'], 403);

            $risco = DB::table('sst_riscos')
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->first();

            abort_if(!$risco, 404);

            return response()->json([
                'success' => true,
                'data' => $risco,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar risco ocupacional.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $permissoes = $this->getPermissoes();

        abort_unless($permissoes['pode_editar'], 403);

        $request->validate([
            'descricao' => ['required', 'string', 'max:150'],
            'grupo_risco' => ['nullable', 'string', 'max:50'],
            'ativo' => ['nullable'],
        ]);

        try {
            DB::table('sst_riscos')
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->update([
                    'descricao' => trim($request->descricao),
                    'grupo_risco' => filled($request->grupo_risco) ? trim($request->grupo_risco) : null,
                    'ativo' => $request->boolean('ativo'),
                    'updated_at' => now(),
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Risco ocupacional atualizado com sucesso.',
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar risco ocupacional.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $permissoes = $this->getPermissoes();

            abort_unless($permissoes['pode_excluir'], 403);

            DB::table('sst_riscos')
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->update([
                    'deleted_at' => now(),
                    'updated_at' => now(),
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Risco ocupacional excluído com sucesso.',
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir risco ocupacional.',
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
            ->where('gestao_tela.slug', 'sst/riscos')
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
