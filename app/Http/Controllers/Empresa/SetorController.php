<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class SetorController extends Controller
{
    public function index()
    {
        $filiais = DB::table('empresa_filial')
            ->whereNull('deleted_at')
            ->orderBy('nome_fantasia')
            ->select('id', 'nome_fantasia')
            ->get();

        return view('gestao.empresa.setor.index', compact('filiais'));
    }

    public function list(Request $request)
    {
        try {
            $query = DB::table('empresa_setores')
                ->whereNull('deleted_at');

            if ($request->filled('nome')) {
                $query->where('descricao', 'ilike', '%' . trim($request->nome) . '%');
            }

            if ($request->filled('filial_id')) {
                $filialId = (int) $request->filial_id;

                $query->whereExists(function ($sub) use ($filialId) {
                    $sub->select(DB::raw(1))
                        ->from('vinculo_filial_x_setor')
                        ->whereColumn('vinculo_filial_x_setor.setor_id', 'empresa_setores.id')
                        ->where('vinculo_filial_x_setor.filial_id', $filialId);
                });
            }

            $dados = $query
                ->orderBy('descricao')
                ->paginate(25);

            $setorIds = collect($dados->items())->pluck('id')->all();

            $filiaisPorSetor = [];

            if (!empty($setorIds)) {
                $rows = DB::table('vinculo_filial_x_setor as v')
                    ->join('empresa_filial as f', 'f.id', '=', 'v.filial_id')
                    ->whereIn('v.setor_id', $setorIds)
                    ->whereNull('f.deleted_at')
                    ->orderBy('f.nome_fantasia')
                    ->select('v.setor_id', 'f.id as filial_id', 'f.nome_fantasia')
                    ->get();

                foreach ($rows as $row) {
                    $filiaisPorSetor[$row->setor_id][] = [
                        'id' => $row->filial_id,
                        'nome' => $row->nome_fantasia,
                    ];
                }
            }

            foreach ($dados->items() as $item) {
                $lista = $filiaisPorSetor[$item->id] ?? [];
                $item->filiais_lista = $lista;
                $item->filiais_ids = array_column($lista, 'id');
            }

            return view('gestao.empresa.setor.partials.table', compact('dados'))->render();
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Erro ao carregar setores.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'descricao' => ['required', 'string', 'max:255'],
            'filiais' => ['nullable', 'array'],
            'filiais.*' => ['integer', 'exists:empresa_filial,id'],
        ]);

        try {
            DB::beginTransaction();

            $setorId = DB::table('empresa_setores')->insertGetId([
                'descricao' => trim($request->descricao),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $filiais = $request->filiais ?? [];

            foreach ($filiais as $filialId) {
                DB::table('vinculo_filial_x_setor')->insert([
                    'setor_id' => $setorId,
                    'filial_id' => $filialId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Setor salvo com sucesso.',
            ]);
        } catch (Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar setor.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'descricao' => ['required', 'string', 'max:255'],
            'filiais' => ['nullable', 'array'],
            'filiais.*' => ['integer', 'exists:empresa_filial,id'],
        ]);

        try {
            DB::beginTransaction();

            DB::table('empresa_setores')
                ->where('id', $id)
                ->update([
                    'descricao' => trim($request->descricao),
                    'updated_at' => now(),
                ]);

            DB::table('vinculo_filial_x_setor')
                ->where('setor_id', $id)
                ->delete();

            $filiais = $request->filiais ?? [];

            foreach ($filiais as $filialId) {
                DB::table('vinculo_filial_x_setor')->insert([
                    'setor_id' => $id,
                    'filial_id' => $filialId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Setor atualizado com sucesso.',
            ]);
        } catch (Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar setor.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();

            DB::table('vinculo_filial_x_setor')
                ->where('setor_id', $id)
                ->delete();

            DB::table('empresa_setores')
                ->where('id', $id)
                ->update([
                    'deleted_at' => now(),
                    'updated_at' => now(),
                ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Setor excluído com sucesso.',
            ]);
        } catch (Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir setor.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
