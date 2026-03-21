<?php

namespace App\Http\Controllers\Aprovacao;

use App\Http\Controllers\Controller;
use App\Models\AprovacaoConfiguracao;
use App\Models\AprovacaoFluxo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfiguracaoFluxoController extends Controller
{
    public function index()
    {
        $configuracoes = AprovacaoConfiguracao::with('fluxo')
            ->orderBy('tipo_referencia')
            ->get();

        $fluxos = AprovacaoFluxo::where('situacao', 'ativo')
            ->orderBy('nome_fluxo')
            ->get();

        return view('aprovacao.configuracao.index', compact('configuracoes', 'fluxos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_referencia' => 'required|string|max:100',
            'fluxo_id' => 'required|integer|exists:aprovacao_fluxo,id',
            'ativo' => 'required|boolean',
        ]);

        DB::beginTransaction();

        try {
            AprovacaoConfiguracao::updateOrCreate(
                ['tipo_referencia' => $request->tipo_referencia],
                [
                    'fluxo_id' => $request->fluxo_id,
                    'ativo' => $request->ativo,
                ]
            );

            DB::commit();

            return redirect()
                ->route('aprovacao.configuracao.index')
                ->with('success', 'Configuração salva com sucesso.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao salvar configuração: ' . $e->getMessage());
        }
    }
}
