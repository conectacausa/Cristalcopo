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
                'fluxo_id'                    => $request->input('fluxo_id'),
                'tipo_referencia'             => $request->input('tipo_referencia'),
                'referencia_id'               => $request->input('referencia_id'),
                'titulo'                      => $request->input('titulo'),
                'descricao'                   => $request->input('descricao'),
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
            ], 500);
        }
    }
}
