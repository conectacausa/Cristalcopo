<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CargoCbo;

class CargoCboController extends Controller
{
    public function index()
    {
        return view('gestao.cargos.cbo.index');
    }

    public function list(Request $request)
    {
        $query = CargoCbo::query();

        if ($request->filtro) {
            $query->where(function ($q) use ($request) {
                $q->where('descricao_cbo', 'ilike', '%' . $request->filtro . '%')
                  ->orWhere('codigo_cbo', 'ilike', '%' . $request->filtro . '%');
            });
        }

        $dados = $query->orderBy('descricao_cbo')
            ->paginate(25);

        return view('gestao.cargos.cbo.partials.table', compact('dados'))->render();
    }

    public function store(Request $request)
    {
        CargoCbo::create($request->all());

        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $cbo = CargoCbo::findOrFail($id);
        $cbo->update($request->all());

        return response()->json(['success' => true]);
    }

    public function delete($id)
    {
        CargoCbo::findOrFail($id)->delete();

        return response()->json(['success' => true]);
    }
}
