<?php

namespace App\Http\Controllers\Cargos;

use App\Http\Controllers\Controller;
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

        $filiais = DB::table('empresa_filial')
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

            $query = DB::table('cargos as c')
                ->leftJoin('cargos_cbo as cbo', 'cbo.id', '=', 'c.cargo_cbo_id')
                ->whereNull('c.deleted_at')
                ->select(
                    'c.id',
                    'c.titulo_cargo',
                    'c.codigo_importacao',
                    'c.status_aprovacao',
                    'c.conta_base_jovem_aprendiz',
                    'cbo.codigo_cbo',
                    'cbo.descricao_cbo'
                );

            if ($request->filled('busca')) {
                $busca = trim($request->busca);

                $query->where(function ($q) use ($busca) {
                    $q->where('c.titulo_cargo', 'ilike', '%' . $busca . '%')
                        ->orWhere('cbo.codigo_cbo', 'ilike', '%' . $busca . '%')
                        ->orWhere('cbo.descricao_cbo', 'ilike', '%' . $busca . '%');
                });
            }

            if ($request->filled('filiais')) {
                $filiais = array_filter((array) $request->filiais);

                if (!empty($filiais)) {
                    $query->whereExists(function ($sub) use ($filiais) {
                        $sub->select(DB::raw(1))
                            ->from('vinculo_cargo_x_filial as vcf')
                            ->whereColumn('vcf.cargo_id', 'c.id')
                            ->whereIn('vcf.filial_id', $filiais);
                    });
                }
            }

            $dados = $query
                ->orderBy('c.titulo_cargo')
                ->paginate(25);

            $cargoIds = collect($dados->items())->pluck('id')->all();

            $filiaisPorCargo = [];
            $setoresPorCargo = [];

            if (!empty($cargoIds)) {
                $rowsFiliais = DB::table('vinculo_cargo_x_filial as v')
                    ->join('empresa_filial as f', 'f.id', '=', 'v.filial_id')
                    ->whereIn('v.cargo_id', $cargoIds)
                    ->whereNull('f.deleted_at')
                    ->orderBy('f.nome_fantasia')
                    ->select('v.cargo_id', 'f.id as filial_id', 'f.nome_fantasia')
                    ->get();

                foreach ($rowsFiliais as $row) {
                    $filiaisPorCargo[$row->cargo_id][] = [
                        'id' => $row->filial_id,
                        'nome' => $row->nome_fantasia,
                    ];
                }

                $rowsSetores = DB::table('vinculo_cargo_x_setor as v')
                    ->join('empresa_setores as s', 's.id', '=', 'v.setor_id')
                    ->whereIn('v.cargo_id', $cargoIds)
                    ->whereNull('s.deleted_at')
                    ->orderBy('s.descricao')
                    ->select('v.cargo_id', 's.id as setor_id', 's.descricao')
                    ->get();

                foreach ($rowsSetores as $row) {
                    $setoresPorCargo[$row->cargo_id][] = [
                        'id' => $row->setor_id,
                        'nome' => $row->descricao,
                    ];
                }
            }

            foreach ($dados->items() as $item) {
                $item->filiais_lista = $filiaisPorCargo[$item->id] ?? [];
                $item->setores_lista = $setoresPorCargo[$item->id] ?? [];
            }

            return view('cargos.cargos.partials.table', compact('dados', 'permissoes'))->render();
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Erro ao carregar cargos.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function create()
    {
        $permissoes = $this->getPermissoes();

        abort_unless($permissoes['pode_gravar'], 403);

        $filiais = DB::table('empresa_filial')
            ->whereNull('deleted_at')
            ->orderBy('nome_fantasia')
            ->select('id', 'nome_fantasia')
            ->get();

        $setores = DB::table('empresa_setores')
            ->whereNull('deleted_at')
            ->orderBy('descricao')
            ->select('id', 'descricao')
            ->get();

        $cbos = DB::table('cargos_cbo')
            ->whereNull('deleted_at')
            ->orderBy('codigo_cbo')
            ->select('id', 'codigo_cbo', 'descricao_cbo')
            ->get();

        $cargo = null;

        return view('cargos.cargos.form', compact(
            'cargo',
            'filiais',
            'setores',
            'cbos',
            'permissoes'
        ));
    }

    public function editPage($id)
    {
        $permissoes = $this->getPermissoes();

        abort_unless($permissoes['pode_editar'], 403);

        $cargo = DB::table('cargos')
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->first();

        abort_if(!$cargo, 404);

        $filiais = DB::table('empresa_filial')
            ->whereNull('deleted_at')
            ->orderBy('nome_fantasia')
            ->select('id', 'nome_fantasia')
            ->get();

        $setores = DB::table('empresa_setores')
            ->whereNull('deleted_at')
            ->orderBy('descricao')
            ->select('id', 'descricao')
            ->get();

        $cbos = DB::table('cargos_cbo')
            ->whereNull('deleted_at')
            ->orderBy('codigo_cbo')
            ->select('id', 'codigo_cbo', 'descricao_cbo')
            ->get();

        $cargo->filiais = DB::table('vinculo_cargo_x_filial')
            ->where('cargo_id', $id)
            ->pluck('filial_id')
            ->toArray();

        $cargo->setores = DB::table('vinculo_cargo_x_setor')
            ->where('cargo_id', $id)
            ->pluck('setor_id')
            ->toArray();

        return view('cargos.cargos.form', compact(
            'cargo',
            'filiais',
            'setores',
            'cbos',
            'permissoes'
        ));
    }

    public function show($id)
    {
        try {
            $permissoes = $this->getPermissoes();

            abort_unless($permissoes['pode_ler'], 403);

            $cargo = DB::table('cargos as c')
                ->leftJoin('cargos_cbo as cbo', 'cbo.id', '=', 'c.cargo_cbo_id')
                ->where('c.id', $id)
                ->whereNull('c.deleted_at')
                ->select(
                    'c.id',
                    'c.titulo_cargo',
                    'c.codigo_importacao',
                    'c.cargo_cbo_id',
                    'c.status_aprovacao',
                    'c.conta_base_jovem_aprendiz',
                    'c.aprovacao_solicitacao_id',
                    'cbo.codigo_cbo',
                    'cbo.descricao_cbo'
                )
                ->first();

            abort_if(!$cargo, 404);

            $cargo->filiais = DB::table('vinculo_cargo_x_filial as v')
                ->join('empresa_filial as f', 'f.id', '=', 'v.filial_id')
                ->where('v.cargo_id', $id)
                ->whereNull('f.deleted_at')
                ->orderBy('f.nome_fantasia')
                ->pluck('f.nome_fantasia')
                ->toArray();

            $cargo->setores = DB::table('vinculo_cargo_x_setor as v')
                ->join('empresa_setores as s', 's.id', '=', 'v.setor_id')
                ->where('v.cargo_id', $id)
                ->whereNull('s.deleted_at')
                ->orderBy('s.descricao')
                ->pluck('s.descricao')
                ->toArray();

            return response()->json([
                'success' => true,
                'data' => $cargo,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar cargo.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $permissoes = $this->getPermissoes();

        abort_unless($permissoes['pode_gravar'], 403);

        $request->validate([
            'titulo_cargo' => ['required', 'string', 'max:255'],
            'codigo_importacao' => ['nullable', 'string', 'max:100'],
            'cargo_cbo_id' => ['required', 'integer', 'exists:cargos_cbo,id'],
            'filiais' => ['required', 'array', 'min:1'],
            'filiais.*' => ['integer', 'exists:empresa_filial,id'],
            'setores' => ['required', 'array', 'min:1'],
            'setores.*' => ['integer', 'exists:empresa_setores,id'],
            'conta_base_jovem_aprendiz' => ['nullable'],
        ]);

        try {
            DB::beginTransaction();

            $statusAprovacao = 'rascunho';
            $aprovacaoSolicitacaoId = null;

            $configuracaoFluxo = DB::table('aprovacao_configuracao')
                ->where('tipo_referencia', 'cargo')
                ->where('situacao', true)
                ->first();

            if ($configuracaoFluxo) {
                $statusAprovacao = 'pendente_aprovacao';
            }

            $cargoId = DB::table('cargos')->insertGetId([
                'titulo_cargo' => trim($request->titulo_cargo),
                'codigo_importacao' => filled($request->codigo_importacao) ? trim($request->codigo_importacao) : null,
                'cargo_cbo_id' => (int) $request->cargo_cbo_id,
                'aprovacao_solicitacao_id' => $aprovacaoSolicitacaoId,
                'status_aprovacao' => $statusAprovacao,
                'conta_base_jovem_aprendiz' => $request->boolean('conta_base_jovem_aprendiz'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($request->filiais as $filialId) {
                DB::table('vinculo_cargo_x_filial')->insert([
                    'cargo_id' => $cargoId,
                    'filial_id' => $filialId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            foreach ($request->setores as $setorId) {
                DB::table('vinculo_cargo_x_setor')->insert([
                    'cargo_id' => $cargoId,
                    'setor_id' => $setorId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            return redirect()
                ->route('cargos.cargos.edit', $cargoId)
                ->with('success', $configuracaoFluxo
                    ? 'Cargo salvo com sucesso e enviado para aprovação.'
                    : 'Cargo salvo com sucesso.');
        } catch (Throwable $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao salvar cargo: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $permissoes = $this->getPermissoes();

        abort_unless($permissoes['pode_editar'], 403);

        $request->validate([
            'titulo_cargo' => ['required', 'string', 'max:255'],
            'codigo_importacao' => ['nullable', 'string', 'max:100'],
            'cargo_cbo_id' => ['required', 'integer', 'exists:cargos_cbo,id'],
            'filiais' => ['required', 'array', 'min:1'],
            'filiais.*' => ['integer', 'exists:empresa_filial,id'],
            'setores' => ['required', 'array', 'min:1'],
            'setores.*' => ['integer', 'exists:empresa_setores,id'],
            'conta_base_jovem_aprendiz' => ['nullable'],
        ]);

        try {
            DB::beginTransaction();

            DB::table('cargos')
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->update([
                    'titulo_cargo' => trim($request->titulo_cargo),
                    'codigo_importacao' => filled($request->codigo_importacao) ? trim($request->codigo_importacao) : null,
                    'cargo_cbo_id' => (int) $request->cargo_cbo_id,
                    'conta_base_jovem_aprendiz' => $request->boolean('conta_base_jovem_aprendiz'),
                    'updated_at' => now(),
                ]);

            DB::table('vinculo_cargo_x_filial')->where('cargo_id', $id)->delete();
            foreach ($request->filiais as $filialId) {
                DB::table('vinculo_cargo_x_filial')->insert([
                    'cargo_id' => $id,
                    'filial_id' => $filialId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('vinculo_cargo_x_setor')->where('cargo_id', $id)->delete();
            foreach ($request->setores as $setorId) {
                DB::table('vinculo_cargo_x_setor')->insert([
                    'cargo_id' => $id,
                    'setor_id' => $setorId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            return redirect()
                ->route('cargos.cargos.edit', $id)
                ->with('success', 'Cargo atualizado com sucesso.');
        } catch (Throwable $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar cargo: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $permissoes = $this->getPermissoes();

            abort_unless($permissoes['pode_excluir'], 403);

            DB::beginTransaction();

            DB::table('vinculo_cargo_x_filial')->where('cargo_id', $id)->delete();
            DB::table('vinculo_cargo_x_setor')->where('cargo_id', $id)->delete();

            DB::table('cargos')
                ->where('id', $id)
                ->update([
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
