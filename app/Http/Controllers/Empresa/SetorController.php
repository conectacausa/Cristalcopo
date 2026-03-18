<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\EmpresaFilial;
use App\Models\EmpresaSetor;
use App\Models\VinculoFilialSetor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetorController extends Controller
{
    protected $idTela = 6;
    protected $slug = 'empresa/setor';

    public function index()
    {
        $filiais = EmpresaFilial::orderBy('nome')->get();

        return view('empresa.setor.index', compact('filiais'));
    }

    public function list(Request $request)
    {
        $query = VinculoFilialSetor::query()
            ->with(['filial', 'setor']);

        if (!empty($request->nome)) {
            $nome = trim($request->nome);

            $query->whereHas('setor', function ($q) use ($nome) {
                $q->where('descricao', 'like', '%' . $nome . '%');
            });
        }

        if (!empty($request->filial)) {
            $query->where('id_filial', $request->filial);
        }

        $registros = $query->orderByDesc('id')->get();

        $data = $registros->map(function ($item) {
            return [
                'setor' => optional($item->setor)->descricao ?? '-',
                'filial' => optional($item->filial)->nome ?? '-',
                'acoes' => '
                    <div class="clearfix text-center">
                        <button class="waves-effect waves-light btn btn-sm bg-gradient-primary me-1"
                                onclick="editarSetor(' . $item->id . ')">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="waves-effect waves-light btn btn-sm bg-gradient-danger"
                                onclick="deletarSetor(' . $item->id . ')">
                            <i class="fa fa-trash-o"></i>
                        </button>
                    </div>
                ',
            ];
        });

        return response()->json([
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'descricao' => 'required|string|max:255',
            'id_filial' => 'required|exists:filiais,id',
        ], [
            'descricao.required' => 'A descrição do setor é obrigatória.',
            'id_filial.required' => 'A filial é obrigatória.',
            'id_filial.exists' => 'A filial informada não existe.',
        ]);

        DB::beginTransaction();

        try {
            $descricao = trim($request->descricao);

            $setor = EmpresaSetor::firstOrCreate(
                ['descricao' => $descricao],
                ['descricao' => $descricao]
            );

            $vinculoExistente = VinculoFilialSetor::withTrashed()
                ->where('id_filial', $request->id_filial)
                ->where('id_setor', $setor->id)
                ->first();

            if ($vinculoExistente) {
                if ($vinculoExistente->trashed()) {
                    $vinculoExistente->restore();
                    $vinculoExistente->updated_at = now();
                    $vinculoExistente->save();
                }

                DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => 'Vínculo já existia e foi reativado com sucesso.',
                ]);
            }

            VinculoFilialSetor::create([
                'id_filial' => $request->id_filial,
                'id_setor' => $setor->id,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Setor cadastrado com sucesso.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Erro ao cadastrar setor.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        $registro = VinculoFilialSetor::with(['filial', 'setor'])->findOrFail($id);

        return response()->json([
            'id' => $registro->id,
            'id_filial' => $registro->id_filial,
            'id_setor' => $registro->id_setor,
            'descricao' => optional($registro->setor)->descricao ?? '',
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'descricao' => 'required|string|max:255',
            'id_filial' => 'required|exists:filiais,id',
        ], [
            'descricao.required' => 'A descrição do setor é obrigatória.',
            'id_filial.required' => 'A filial é obrigatória.',
            'id_filial.exists' => 'A filial informada não existe.',
        ]);

        DB::beginTransaction();

        try {
            $vinculo = VinculoFilialSetor::findOrFail($id);
            $descricao = trim($request->descricao);

            $setor = EmpresaSetor::firstOrCreate(
                ['descricao' => $descricao],
                ['descricao' => $descricao]
            );

            $duplicado = VinculoFilialSetor::where('id_filial', $request->id_filial)
                ->where('id_setor', $setor->id)
                ->where('id', '<>', $vinculo->id)
                ->first();

            if ($duplicado) {
                DB::rollBack();

                return response()->json([
                    'status' => false,
                    'message' => 'Já existe um vínculo com esta filial e este setor.',
                ], 422);
            }

            $vinculo->update([
                'id_filial' => $request->id_filial,
                'id_setor' => $setor->id,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Setor atualizado com sucesso.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Erro ao atualizar setor.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $registro = VinculoFilialSetor::findOrFail($id);
            $registro->delete();

            return response()->json([
                'status' => true,
                'message' => 'Setor excluído com sucesso.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao excluir setor.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
