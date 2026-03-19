<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\EmpresaSetor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class SetorController extends Controller
{
    public function index()
    {
        $filiaisModel = $this->filialModelClass();
        $filiais = $filiaisModel::orderBy('nome_fantasia')->get();

        return view('gestao.empresa.setor.index', compact('filiais'));
    }

    public function list(Request $request)
    {
        $query = EmpresaSetor::with(['filiais' => function ($q) {
            $q->orderBy('nome_fantasia');
        }]);

        if ($request->filled('nome')) {
            $query->where('descricao', 'ilike', '%' . trim($request->nome) . '%');
        }

        if ($request->filled('filial_id')) {
            $filialId = (int) $request->filial_id;

            $query->whereHas('filiais', function ($q) use ($filialId) {
                $q->where('empresa_filial.id', $filialId);
            });
        }

        $dados = $query
            ->orderBy('descricao')
            ->paginate(25);

        return view('gestao.empresa.setor.partials.table', compact('dados'))->render();
    }

    public function store(Request $request)
    {
        $filiaisTable = $this->filialTableName();

        $request->validate([
            'descricao' => ['required', 'string', 'max:255'],
            'filiais' => ['nullable', 'array'],
            'filiais.*' => ['integer', 'exists:' . $filiaisTable . ',id'],
        ]);

        try {
            DB::beginTransaction();

            $setor = EmpresaSetor::create([
                'descricao' => trim($request->descricao),
            ]);

            $setor->filiais()->sync($request->filiais ?? []);

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
        $filiaisTable = $this->filialTableName();

        $request->validate([
            'descricao' => ['required', 'string', 'max:255'],
            'filiais' => ['nullable', 'array'],
            'filiais.*' => ['integer', 'exists:' . $filiaisTable . ',id'],
        ]);

        try {
            DB::beginTransaction();

            $setor = EmpresaSetor::findOrFail($id);

            $setor->update([
                'descricao' => trim($request->descricao),
            ]);

            $setor->filiais()->sync($request->filiais ?? []);

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

            $setor = EmpresaSetor::findOrFail($id);
            $setor->filiais()->detach();
            $setor->delete();

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

    private function filialModelClass(): string
    {
        if (class_exists(\App\Models\EmpresaFilial::class)) {
            return \App\Models\EmpresaFilial::class;
        }

        if (class_exists(\App\Models\Gestao\EmpresaFilial::class)) {
            return \App\Models\Gestao\EmpresaFilial::class;
        }

        abort(500, 'Model de filial não encontrado.');
    }

    private function filialTableName(): string
    {
        $modelClass = $this->filialModelClass();
        return (new $modelClass)->getTable();
    }
}
