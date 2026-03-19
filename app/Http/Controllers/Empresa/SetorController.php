<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmpresaSetor;
use App\Models\EmpresaFilial;

class SetorController extends Controller
{
    public function index()
    {
        $filiais = EmpresaFilial::orderBy('nome_fantasia')->get();

        return view('gestao.empresa.setor.index', compact('filiais'));
    }

    public function list(Request $request)
    {
        $query = EmpresaSetor::with('filiais');

        if ($request->filled('nome')) {
            $query->where('descricao', 'ilike', '%' . $request->nome . '%');
        }

        if ($request->filled('filial_id')) {
            $filialId = $request->filial_id;

            $query->whereHas('filiais', function ($q) use ($filialId) {
                $q->where('empresa_filial.id', $filialId);
            });
        }

        $dados = $query->orderBy('descricao')->paginate(25);

        return view('gestao.empresa.setor.partials.table', compact('dados'))->render();
    }

    public function store(Request $request)
    {
        $request->validate([
            'descricao' => 'required|string|max:255',
            'filiais' => 'nullable|array',
            'filiais.*' => 'integer',
        ]);

        $setor = EmpresaSetor::create([
            'descricao' => $request->descricao,
        ]);

        $setor->filiais()->sync($request->filiais ?? []);

        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'descricao' => 'required|string|max:255',
            'filiais' => 'nullable|array',
            'filiais.*' => 'integer',
        ]);

        $setor = EmpresaSetor::findOrFail($id);

        $setor->update([
            'descricao' => $request->descricao,
        ]);

        $setor->filiais()->sync($request->filiais ?? []);

        return response()->json(['success' => true]);
    }

    public function delete($id)
    {
        $setor = EmpresaSetor::findOrFail($id);
        $setor->delete();

        return response()->json(['success' => true]);
    }
}
