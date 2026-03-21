<?php

namespace App\Http\Controllers\Cargos;

use App\Http\Controllers\Controller;
use App\Models\Cargos\Cargo;
use App\Models\EmpresaFilial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class CargosController extends Controller
{
    public function index()
    {
        $permissoes = $this->getPermissoes();

        abort_unless($permissoes['pode_ler'], 403);

        $filiais = EmpresaFilial::query()
            ->whereNull('deleted_at')
            ->orderBy('nome_fantasia')
            ->select('id', 'nome_fantasia')
            ->get();

        return view('cargos.cargos.index', compact('filiais', 'permissoes'));
    }

    public function list(Request $request)
    {
        try {
            $permissoes = $this->getPermissoes();

            abort_unless($permissoes['pode_ler'], 403);

            $query = Cargo::query()
                ->with(['cbo', 'filiais', 'setores'])
                ->whereNull('deleted_at');

            if ($request->filled('busca')) {
                $busca = trim($request->busca);

                $query->where(function ($q) use ($busca) {
                    $q->where('titulo_cargo', 'ilike', "%{$busca}%")
                        ->orWhereHas('cbo', function ($sub) use ($busca) {
                            $sub->where('codigo_cbo', 'ilike', "%{$busca}%")
                                ->orWhere('descricao_cbo', 'ilike', "%{$busca}%");
                        });
                });
            }

            if ($request->filled('filiais')) {
                $filiais = array_filter((array) $request->filiais);

                if (!empty($filiais)) {
                    $query->whereHas('filiais', function ($sub) use ($filiais) {
                        $sub->whereIn('empresa_filial.id', $filiais);
                    });
                }
            }

            $dados = $query
                ->orderBy('titulo_cargo')
                ->paginate(25);

            return view('cargos.cargos.partials.table', compact('dados', 'permissoes'))->render();
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Erro ao carregar cargos.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $permissoes = $this->getPermissoes();

            abort_unless($permissoes['pode_excluir'], 403);

            DB::beginTransaction();

            $cargo = Cargo::findOrFail($id);

            DB::table('vinculo_cargo_x_filial')
                ->where('cargo_id', $id)
                ->delete();

            DB::table('vinculo_cargo_x_setor')
                ->where('cargo_id', $id)
                ->delete();

            $cargo->update([
                'deleted_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cargo excluído com sucesso.',
            ]);
        } catch (Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir cargo.',
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
            ->where('gestao_tela.slug', 'cargos')
            ->select(
                'vinculo_permissao_x_tela.pode_ler',
                'vinculo_permissao_x_tela.pode_gravar',
                'vinculo_permissao_x_tela.pode_editar',
                'vinculo_permissao_x_tela.pode_excluir'
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
