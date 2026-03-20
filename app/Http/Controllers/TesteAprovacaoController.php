<?php

namespace App\Http\Controllers;

use App\Services\Aprovacao\CriarSolicitacaoAprovacaoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TesteAprovacaoController extends Controller
{
    public function criar(Request $request, CriarSolicitacaoAprovacaoService $service): JsonResponse
    {
        try {
            $dados = [
                // valores padrão para facilitar teste via navegador
                'fluxo_id'                    => $request->input('fluxo_id', 1),
                'tipo_referencia'             => $request->input('tipo_referencia', 'cargo'),
                'referencia_id'               => $request->input('referencia_id', rand(100, 999)),
                'titulo'                      => $request->input('titulo', 'Teste de aprovação'),
                'descricao'                   => $request->input('descricao', 'Teste via navegador'),
                'solicitante_colaborador_id'  => auth()->id(),
            ];

            $solicitacao = $service->executar($dados);

            return response()->json([
                'success' => true,
                'message' => 'Solicitação de aprovação criada com sucesso.',
                'data'    => [
                    'id'              => $solicitacao->id,
                    'fluxo_id'        => $solicitacao->fluxo_id,
                    'tipo_referencia' => $solicitacao->tipo_referencia,
                    'referencia_id'   => $solicitacao->referencia_id,
                    'status'          => $solicitacao->status,
                    'etapa_atual'     => $solicitacao->etapa_atual,
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ], 500);
        }
    }
}
